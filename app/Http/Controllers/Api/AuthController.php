<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'phone' => 'required|string|max:20',
                'fcm_token' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'locale' => 'nullable|string|in:en,ar',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => true,
                'message' => $e->validator->errors()->first(),
                'data' => $this->emptyUserData(),
            ], 200);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('users', 'public');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'phone' => $validated['phone'],
            'image' => $imagePath,
            'role' => 'customer',
            'is_verified' => false,
            'is_active' => false,
            'locale' => $validated['locale'] ?? null,
        ]);

        \Illuminate\Support\Facades\Log::info('Register fcm_token', [
            'raw' => $request->input('fcm_token'),
            'validated' => $validated['fcm_token'] ?? null,
            'all_keys' => $request->keys(),
        ]);

        if ($validated['fcm_token'] ?? null) {
            $user->deviceTokens()->delete();
            $user->deviceTokens()->create(['fcm_token' => $validated['fcm_token']]);
        }

        try {
            $user->sendEmailVerificationNotification();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send verification email: ' . $e->getMessage());
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'error' => false,
            'message' => __('auth.register_success'),
            'data' => $this->userData($user, $token),
        ], 200);
    }

    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'fcm_token' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()->first(),
                'data' => $this->emptyUserData(),
            ], 200);
        }

        $validated = $validator->validated();

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'error' => true,
                'message' => __('auth.failed'),
                'data' => $this->emptyUserData(),
            ], 200);
        }

        if (!$user->is_verified) {
            $tempToken = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'error' => true,
                'message' => 'Please Verify your Email First',
                'data' => $this->userData($user, $tempToken),
            ], 200);
        }

        \Illuminate\Support\Facades\Log::info('Login fcm_token', [
            'raw' => $request->input('fcm_token'),
            'validated' => $validated['fcm_token'] ?? null,
            'all_keys' => $request->keys(),
        ]);

        if ($validated['fcm_token'] ?? null) {
            DeviceToken::where('user_id', $user->id)->delete();
            $user->deviceTokens()->create(['fcm_token' => $validated['fcm_token']]);
        }

        if ($request->locale && in_array($request->locale, ['en', 'ar'])) {
            $user->update(['locale' => $request->locale]);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'error' => false,
            'message' => __('auth.login_success'),
            'data' => $this->userData($user, $token),
        ]);
    }

    public function showResetForm(Request $request, string $token)
    {
        $email = $request->query('email');
        $status = $request->query('status');
        $errors = [];

        if ($status === 'success') {
            return $this->resetSuccessPage();
        }

        $errorsRaw = $request->query('errors');
        if ($errorsRaw) {
            foreach (explode('|', $errorsRaw) as $err) {
                $errors[] = e(trim($err));
            }
        }

        $errorHtml = '';
        if (!empty($errors)) {
            $list = '';
            foreach ($errors as $err) {
                $list .= "<li>{$err}</li>";
            }
            $errorHtml = '<div class="error-msg"><ul style="margin:0;padding-left:18px;text-align:start;">' . $list . '</ul></div>';
        }

        $action = e(url('/api/reset-password'));
        $csrf = csrf_token();
        $escapedEmail = e($email);
        $escapedToken = e($token);

        return response(<<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Click & Fix</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #E8F4F8 0%, #50CADE 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(80, 202, 222, 0.2);
            padding: 48px;
            max-width: 440px;
            width: 100%;
            border: 2px solid #50CADE;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #50CADE;
            text-align: center;
            margin-bottom: 8px;
        }
        h2 {
            color: #2F4858;
            font-size: 22px;
            text-align: center;
            margin-bottom: 8px;
        }
        .subtitle {
            color: #707070;
            font-size: 14px;
            text-align: center;
            margin-bottom: 28px;
        }
        .error-msg {
            background: #FEE2E2;
            color: #dc2626;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            text-align: center;
            margin-bottom: 20px;
        }
        input {
            width: 100%;
            padding: 14px 16px;
            margin-bottom: 16px;
            border: 2px solid #E8F4F8;
            border-radius: 8px;
            font-size: 15px;
            outline: none;
            transition: border-color 0.3s;
        }
        input:focus { border-color: #50CADE; }
        input[readonly] { background: #F4F4F4; color: #707070; }
        button {
            width: 100%;
            padding: 14px;
            background: #50CADE;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover { background: #41A3B3; }
        .footer-text {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #707070;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">Click & Fix</div>
        <h2>Reset Password</h2>
        <p class="subtitle">Enter your new password below</p>
        {$errorHtml}
        <form method="POST" action="{$action}">
            <input type="hidden" name="_browser" value="1">
            <input type="hidden" name="_token" value="{$csrf}">
            <input type="hidden" name="token" value="{$escapedToken}">
            <input type="hidden" name="email" value="{$escapedEmail}">
            <input type="email" value="{$escapedEmail}" readonly required>
            <input type="password" name="password" placeholder="New Password" required minlength="8">
            <input type="password" name="password_confirmation" placeholder="Confirm Password" required minlength="8">
            <button type="submit">Reset Password</button>
        </form>
        <div class="footer-text">Click & Fix - Make a better for your car</div>
    </div>
</body>
</html>
HTML
        )->header('Content-Type', 'text/html');
    }

    private function resetSuccessPage()
    {
        return response(<<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset - Click & Fix</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #E8F4F8 0%, #50CADE 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(80, 202, 222, 0.2);
            padding: 48px;
            max-width: 440px;
            width: 100%;
            text-align: center;
            border: 2px solid #50CADE;
        }
        .icon {
            width: 80px; height: 80px;
            border-radius: 50%;
            background: #E8F8F5;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 24px;
        }
        .logo { font-size: 28px; font-weight: bold; color: #50CADE; margin-bottom: 32px; }
        h1 { color: #2F4858; font-size: 24px; margin-bottom: 12px; }
        p { color: #707070; font-size: 16px; line-height: 1.6; margin-bottom: 24px; }
        .btn {
            display: inline-block;
            background: #50CADE; color: white;
            text-decoration: none; padding: 12px 32px;
            border-radius: 8px; font-weight: 600; font-size: 16px;
            transition: background 0.3s;
        }
        .btn:hover { background: #41A3B3; }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">Click & Fix</div>
        <div class="icon"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg></div>
        <h1>Password Reset Successful</h1>
        <p>Your password has been reset successfully. You can now log in with your new password.</p>
        <!-- <a href="https://clickandfixqa.com" class="btn">Go to Click & Fix</a> -->
    </div>
</body>
</html>
HTML
        )->header('Content-Type', 'text/html');
    }



    public function forgetPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()->first(),
            ], 200);
        }

        $validated = $validator->validated();

        try {
            $status = Password::sendResetLink($validated);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send reset link: ' . $e->getMessage());
            $status = Password::RESET_LINK_SENT;
        }

        return response()->json([
            'error' => $status !== Password::RESET_LINK_SENT,
            'message' => __($status),
        ]);
    }

    public function resetPasswordWithToken(Request $request): JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
    {
        $email = $request->input('email');
        $token = $request->input('token');
        $password = $request->input('password');
        $passwordConfirmation = $request->input('password_confirmation');
        $isApi = !$request->input('_browser');

        $errors = [];

        if (!$email || !$token) {
            $errors[] = 'Invalid reset link. Please request a new one.';
        }
        if (!$password) {
            $errors[] = 'Password is required.';
        } elseif (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters.';
        }
        if ($password !== $passwordConfirmation) {
            $errors[] = 'Password confirmation does not match.';
        }

        if (!empty($errors)) {
            if ($isApi) {
                return response()->json([
                    'error' => true,
                    'message' => $errors[0],
                    'errors' => ['password' => $errors],
                ], 422);
            }
            return redirect('/api/reset-password/' . $token . '?email=' . urlencode($email) . '&errors=' . urlencode(implode('|', $errors)));
        }

        $status = Password::reset(
            compact('email', 'token', 'password'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => bcrypt($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        if ($isApi) {
            return response()->json([
                'error' => $status !== Password::PASSWORD_RESET,
                'message' => __($status),
            ]);
        }

        if ($status === Password::PASSWORD_RESET) {
            return redirect('/api/reset-password/' . $token . '?email=' . urlencode($email) . '&status=success');
        }

        return redirect('/api/reset-password/' . $token . '?email=' . urlencode($email) . '&errors=' . urlencode('Invalid or expired reset link. Please request a new one.'));
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'oldPassword' => 'required|string',
            'newPassword' => 'required|string|min:8',
            'confirmPassword' => 'required|string|same:newPassword',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()->first(),
            ], 200);
        }

        $validated = $validator->validated();

        $user = $request->user();

        if (!Hash::check($validated['oldPassword'], $user->password)) {
            return response()->json([
                'error' => true,
                'message' => __('auth.password_incorrect'),
            ], 422);
        }

        $user->update(['password' => $validated['newPassword']]);

        return response()->json([
            'error' => false,
            'message' => __('auth.password_reset_success'),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'error' => false,
            'message' => __('auth.logout_success'),
        ]);
    }

    public function sendVerificationEmail(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json(['error' => true, 'message' => 'Email already verified.'], 422);
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['error' => false, 'message' => 'Verification email sent.']);
    }

    public function verifyEmail(Request $request, $id, $hash): JsonResponse
    {
        $user = User::findOrFail($id);

        if (!URL::hasValidSignature($request)) {
            return response()->json(['error' => true, 'message' => 'Invalid or expired verification link.'], 400);
        }

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json(['error' => true, 'message' => 'Invalid verification link.'], 400);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['error' => false, 'message' => 'Email already verified.']);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
            $user->update(['is_verified' => true]);
        }

        return response()->json(['error' => false, 'message' => 'Email verified successfully.']);
    }

    public function verifyEmailRedirect(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);
        $title = 'Email Verified';
        $message = 'Your email has been verified successfully!';
        $icon = 'success';
        $color = '#50CADE';

        if (!URL::hasValidSignature($request)) {
            $title = 'Link Expired';
            $message = 'This verification link has expired or is invalid.';
            $icon = 'error';
            $color = '#dc2626';
        } elseif (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            $title = 'Invalid Link';
            $message = 'This verification link is invalid.';
            $icon = 'error';
            $color = '#dc2626';
        } elseif ($user->hasVerifiedEmail()) {
            $message = 'Your email is already verified!';
        } else {
            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
                $user->update(['is_verified' => true]);
            }
        }

        return response(<<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title} - Click & Fix</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #E8F4F8 0%, #50CADE 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(80, 202, 222, 0.2);
            padding: 48px;
            max-width: 440px;
            width: 100%;
            text-align: center;
            border: 2px solid #50CADE;
        }
        .icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 40px;
        }
        .icon.success { background: #E8F8F5; }
        .icon.error { background: #FEE2E2; }
        h1 { color: #2F4858; font-size: 24px; margin-bottom: 12px; }
        p { color: #707070; font-size: 16px; line-height: 1.6; margin-bottom: 24px; }
        .btn {
            display: inline-block;
            background: #50CADE;
            color: white;
            text-decoration: none;
            padding: 12px 32px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: background 0.3s;
        }
        .btn:hover { background: #41A3B3; }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #50CADE;
            margin-bottom: 32px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">Click & Fix</div>
        <div class="icon {$icon}">
            {$this->iconSvg($icon)}
        </div>
        <h1>{$title}</h1>
        <p>{$message}</p>
        <!-- <a href="https://clickandfixqa.com" class="btn">Go to Click & Fix</a> -->
    </div>
</body>
</html>
HTML
        )->header('Content-Type', 'text/html');
    }

    private function iconSvg(string $type): string
    {
        if ($type === 'success') {
            return '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>';
        }
        return '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>';
    }

    public function guestLogin(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'nullable|string',
            'locale' => 'nullable|string|in:en,ar',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => true, 'message' => $validator->errors()->first(), 'data' => null], 422);
        }

        $guestEmail = 'guest_' . Str::random(16) . '@clickandfix.com';

        $user = User::create([
            'name' => 'Guest',
            'email' => $guestEmail,
            'password' => bcrypt(Str::random(32)),
            'phone' => '0000000000',
            'role' => 'customer',
            'is_verified' => true,
            'is_active' => true,
            'locale' => $request->locale ?? null,
        ]);

        if ($request->fcm_token) {
            $user->deviceTokens()->delete();
            $user->deviceTokens()->create(['fcm_token' => $request->fcm_token]);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'error' => false,
            'message' => 'Guest login successful',
            'data' => $this->userData($user, $token),
        ]);
    }

    public function deleteAccount(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->tokens()->delete();
        $user->delete();

        return response()->json([
            'error' => false,
            'message' => 'Account deleted successfully',
        ]);
    }

    public function latestVersion(): JsonResponse
    {
        return response()->json([
            'error' => false,
            'message' => 'success',
            'data' => [
                'version' => '1.0.0',
                'versionCode' => 1,
                'updateUrl' => 'https://play.google.com/store/apps/details?id=com.clickandfix',
                'iosUpdateUrl' => 'https://apps.apple.com/app/clickandfix',
                'forceUpdate' => false,
            ],
        ]);
    }

    private function userData(User $user, string $token): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'image' => $user->image ? request()->getSchemeAndHttpHost() . '/storage/' . $user->image : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random&size=200',
            'isVerified' => $user->is_verified ? 1 : 0,
            'isActive' => $user->is_active ? 1 : 0,
            'role' => $user->role,
            'locale' => $user->locale,
            'token' => $token,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
    }

    private function emptyUserData(): array
    {
        return [
            'id' => 0,
            'name' => '',
            'email' => '',
            'phone' => '',
            'image' => 'https://ui-avatars.com/api/?name=User&background=random&size=200',
            'isVerified' => 0,
            'isActive' => 0,
            'role' => null,
            'locale' => null,
            'token' => '',
            'created_at' => null,
            'updated_at' => null,
        ];
    }
}
