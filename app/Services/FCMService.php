<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FCMService
{
    protected string $projectId;
    protected string $clientEmail;
    protected string $privateKey;

    public function __construct()
    {
        $this->projectId = config('services.firebase.project_id');
        $this->clientEmail = config('services.firebase.client_email');
        $this->privateKey = config('services.firebase.private_key');
    }

    public function send(
        User $user,
        string $title,
        string $body,
        ?int $orderId = null,
        ?string $type = null,
        ?string $locale = null,
        ?string $titleAr = null,
        ?string $bodyAr = null,
    ): void {
        if (empty($this->clientEmail) || empty($this->privateKey)) {
            Log::warning('FCM service account not configured. Skipping push notification.');
            return;
        }

        $tokens = $user->deviceTokens()->pluck('fcm_token')->filter()->unique()->values();

        if ($tokens->isEmpty()) {
            Log::info("No device tokens for user {$user->id}. Skipping push.");
            return;
        }

        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            Log::error('Failed to obtain FCM access token.');
            return;
        }

        $data = [];
        if ($orderId !== null) {
            $data['orderId'] = (string) $orderId;
        }
        if ($type !== null) {
            $data['type'] = $type;
        }

        $userLocale = $locale ?? $user->locale;
        if ($userLocale === 'ar' && $titleAr !== null && $bodyAr !== null) {
            $title = $titleAr;
            $body = $bodyAr;
        }

        foreach ($tokens as $token) {
            $message = [
                'message' => [
                    'token' => $token,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                ],
            ];

            if (!empty($data)) {
                $message['message']['data'] = $data;
            }

            try {
                $response = Http::withoutVerifying()->withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ])->post(
                    "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send",
                    $message
                );

                if ($response->failed()) {
                    Log::error('FCM v1 send failed', [
                        'user_id' => $user->id,
                        'token' => substr($token, 0, 20) . '...',
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);
                } else {
                    Log::info('FCM v1 send success', [
                        'user_id' => $user->id,
                        'status' => $response->status(),
                        'body' => substr($response->body(), 0, 200),
                    ]);
                }
            } catch (\Throwable $e) {
                Log::error('FCM exception: ' . $e->getMessage(), [
                    'user_id' => $user->id,
                    'token' => substr($token, 0, 20) . '...',
                ]);
            }
        }
    }

    protected function getAccessToken(): ?string
    {
        $now = time();
        $payload = [
            'iss' => $this->clientEmail,
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now,
        ];

        $header = [
            'alg' => 'RS256',
            'typ' => 'JWT',
        ];

        $base64UrlHeader = $this->base64UrlEncode(json_encode($header));
        $base64UrlPayload = $this->base64UrlEncode(json_encode($payload));

        $privateKeyResource = openssl_pkey_get_private($this->privateKey);
        if (!$privateKeyResource) {
            Log::error('FCM: Failed to parse private key: ' . openssl_error_string());
            return null;
        }

        $signature = '';
        openssl_sign(
            "$base64UrlHeader.$base64UrlPayload",
            $signature,
            $privateKeyResource,
            'sha256WithRSAEncryption'
        );
        openssl_free_key($privateKeyResource);

        $base64UrlSignature = $this->base64UrlEncode($signature);
        $jwt = "$base64UrlHeader.$base64UrlPayload.$base64UrlSignature";

        $response = Http::withoutVerifying()->asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]);

        if ($response->failed()) {
            Log::error('FCM OAuth2 token exchange failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return null;
        }

        return $response->json('access_token');
    }

    protected function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
