<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $locale = $request->header('Accept-Language', 'en');

        $notifications = Notification::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'error' => false,
            'message' => 'success',
            'data' => $notifications->map(fn($n) => [
                'id' => $n->id,
                'title' => $locale === 'ar' && $n->title_ar ? $n->title_ar : ($n->title ?? ''),
                'body' => $this->safeBody(
                    $locale === 'ar' && $n->body_ar ? $n->body_ar : ($n->body ?? '')
                ),
                'isOrder' => (int) ($n->is_order ?? 0),
                'orderId' => $n->order_id ?? 0,
                'date' => $n->date ?? ($n->created_at ? $n->created_at->format('Y-m-d H:i') : ''),
            ]),
        ]);
    }

    private function safeBody(string $body): string
    {
        $len = mb_strlen($body);
        if ($len >= 50 && $len < 55) {
            return $body . str_repeat(' ', 55 - $len);
        }
        return $body;
    }

    public function approve(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id' => 'required|exists:notifications,id',
            'orderId' => 'required|exists:orders,id',
        ]);

        $notification = Notification::findOrFail($validated['id']);

        $order = \App\Models\Order::findOrFail($validated['orderId']);
        $order->update(['status' => 'approved']);

        return response()->json([
            'error' => false,
            'message' => __('notifications.approved'),
        ]);
    }

    public function cancel(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id' => 'required|exists:notifications,id',
            'orderId' => 'required|exists:orders,id',
        ]);

        $notification = Notification::findOrFail($validated['id']);
        $notification->update(['is_order' => false]);

        $order = \App\Models\Order::findOrFail($validated['orderId']);
        $order->update(['status' => 'cancelled']);

        return response()->json([
            'error' => false,
            'message' => __('notifications.cancelled'),
        ]);
    }
}
