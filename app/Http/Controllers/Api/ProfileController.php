<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use App\Models\Notification as NotificationModel;
use App\Models\User;
use App\Services\FCMService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'error' => false,
            'message' => 'success',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'image' => $user->image ? request()->getSchemeAndHttpHost() . '/storage/' . $user->image : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random&size=200',
                'isVerified' => $user->is_verified ? 1 : 0,
                'isActive' => $user->is_active ? 1 : 0,
                'locale' => $user->locale,
                'role' => $user->role,
                'token' => $request->bearerToken(),
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
        ]);
    }

    public function updateLocale(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'locale' => 'required|string|in:en,ar',
        ]);

        $request->user()->update(['locale' => $validated['locale']]);

        return response()->json([
            'error' => false,
            'message' => 'Locale updated',
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = $request->user();
        $user->name = $validated['name'];
        $user->phone = $validated['phone'];

        if ($request->hasFile('image')) {
            $user->image = $request->file('image')->store('users', 'public');
        }

        $user->save();

        return response()->json([
            'error' => false,
            'message' => __('profile.updated'),
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'image' => $user->image ? request()->getSchemeAndHttpHost() . '/storage/' . $user->image : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random&size=200',
                'isVerified' => $user->is_verified ? 1 : 0,
                'isActive' => $user->is_active ? 1 : 0,
                'locale' => $user->locale,
                'role' => $user->role,
                'token' => $request->bearerToken(),
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
        ]);
    }

    public function reportIssue(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'issueType' => 'required|string',
            'description' => 'required|string',
        ]);

        $user = $request->user();

        Issue::create([
            'user_id' => $user->id,
            'issue_type' => $validated['issueType'],
            'description' => $validated['description'],
        ]);

        NotificationModel::create([
            'user_id' => $user->id,
            'title' => 'We received your issue',
            'body' => 'Thank you for contacting us. We have received your issue and will get back to you soon.',
            'title_ar' => 'تم استلام مشكلتك',
            'body_ar' => 'شكراً لتواصلك معنا. تم استلام مشكلتك وسنتواصل معك قريباً.',
            'date' => now()->format('Y-m-d H:i'),
            'admin_sent' => false,
        ]);

        $locale = $user->locale;
        app(FCMService::class)->send(
            $user,
            $locale === 'ar' ? 'تم استلام مشكلتك' : 'We received your issue',
            $locale === 'ar' ? 'شكراً لتواصلك معنا. تم استلام مشكلتك وسنتواصل معك قريباً.' : 'Thank you for contacting us. We have received your issue and will get back to you soon.',
        );

        return response()->json([
            'error' => false,
            'message' => __('profile.issue_reported'),
        ]);
    }
}
