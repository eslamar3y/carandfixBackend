<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Notification;
use App\Models\Order;
use App\Models\User;
use App\Services\FCMService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        return response()->json([
            'error' => false,
            'message' => 'success',
            'data' => [
                'total_users' => User::count(),
                'total_customers' => User::where('role', 'customer')->count(),
                'total_technicians' => User::where('role', 'technician')->count(),
                'total_cars' => Car::count(),
                'pending_cars' => Car::where('status', 'pending')->count(),
                'approved_cars' => Car::where('status', 'approved')->count(),
                'total_orders' => Order::count(),
                'pending_orders' => Order::where('status', 'pending')->count(),
                'approved_orders' => Order::where('status', 'approved')->count(),
                'cancelled_orders' => Order::where('status', 'cancelled')->count(),
            ],
        ]);
    }

    public function users(): JsonResponse
    {
        return response()->json([
            'error' => false,
            'message' => 'success',
            'data' => User::select('id', 'name', 'email', 'phone', 'role', 'is_active', 'is_verified')->get(),
        ]);
    }

    public function toggleUserStatus(User $user): JsonResponse
    {
        $user->update(['is_active' => !$user->is_active]);

        return response()->json([
            'error' => false,
            'message' => 'User status updated.',
        ]);
    }

    public function allOrders(): JsonResponse
    {
        $orders = Order::with('user')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'error' => false,
            'message' => 'success',
            'data' => $orders,
        ]);
    }

    public function updateOrderStatus(Request $request, Order $order): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,accepted,in_progress,completed,cancelled',
            'technician_id' => 'nullable|exists:users,id',
        ]);

        $order->update($validated);

        return response()->json([
            'error' => false,
            'message' => 'Order status updated.',
        ]);
    }

    public function createNotification(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string',
            'body' => 'required|string',
            'title_ar' => 'nullable|string',
            'body_ar' => 'nullable|string',
            'is_order' => 'boolean',
            'order_id' => 'nullable|exists:orders,id',
        ]);

        $notification = Notification::create($validated + ['date' => now()->format('Y-m-d H:i'), 'admin_sent' => true]);

        $user = User::find($validated['user_id']);
        if ($user) {
            app(FCMService::class)->send(
                $user,
                $notification->title,
                $notification->body,
                $validated['order_id'] ?? null,
                'admin',
                null,
                $notification->title_ar,
                $notification->body_ar,
            );
        }

        return response()->json([
            'error' => false,
            'message' => 'Notification sent.',
            'data' => $notification,
        ]);
    }
}
