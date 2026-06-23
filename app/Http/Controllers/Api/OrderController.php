<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Emergency;
use App\Models\Notification;
use App\Models\Order;
use App\Models\ServiceCategory;
use App\Services\FCMService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'carId' => 'nullable|string',
            'lat' => 'nullable|string',
            'long' => 'nullable|string',
            'phone' => 'nullable|string',
            'manufactory' => 'nullable|string',
            'batteryVoltageId' => 'nullable|string',
            'withService' => 'nullable|string',
            'withFilter' => 'nullable|string',
            'startTime' => 'nullable|string',
            'endTime' => 'nullable|string',
            'pickDate' => 'nullable|string',
            'note' => 'nullable|string',
            'PaymentMethod' => 'nullable|string',
            'type' => 'nullable|string',
            'itemId' => 'nullable|string',
            'price' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('licenses', 'public');
            $fullPath = storage_path('app/public/' . $imagePath);
            if (file_exists($fullPath) && filesize($fullPath) < 5000) {
                unlink($fullPath);
                $imagePath = null;
            }
        }

        $itemId = $validated['itemId'] && $validated['itemId'] !== '-' ? (int) $validated['itemId'] : null;
        $carId = $validated['carId'] && $validated['carId'] !== '-' ? (int) $validated['carId'] : null;

        $itemName = null;
        $itemNameAr = null;
        if (isset($validated['type']) && $itemId) {
            $item = match ($validated['type']) {
                'Emergency' => Emergency::find($itemId),
                'Services' => ServiceCategory::find($itemId),
                'Parts' => Brand::find($itemId),
                default => null,
            };
            $itemName = $item?->getRawOriginal('name') ?? $item?->name;
            $itemNameAr = $item?->name_ar ?? null;
        }

        $order = Order::create([
            'user_id' => $request->user()->id,
            'car_id' => $carId,
            'lat' => $validated['lat'] ?? '-',
            'long' => $validated['long'] ?? '-',
            'phone' => $validated['phone'] ?? '-',
            'manufactory' => $validated['manufactory'] ?? '-',
            'battery_voltage_id' => in_array($validated['batteryVoltageId'] ?? '-', ['-', '-1']) ? null : $validated['batteryVoltageId'],
            'with_service' => $validated['withService'] ?? '-',
            'car_license' => $imagePath,
            'with_filter' => $validated['withFilter'] ?? '-',
            'start_time' => $validated['startTime'] ?? '-',
            'end_time' => $validated['endTime'] ?? '-',
            'pick_date' => $validated['pickDate'] ?? '-',
            'note' => $validated['note'] ?? '-',
            'payment_method' => $validated['PaymentMethod'] ?? '-',
            'type' => $validated['type'] ?? '-',
            'item_id' => $itemId,
            'item_name' => $itemName,
            'item_name_ar' => $itemNameAr,
            'price' => $validated['price'] ?? 0,
            'status' => 'pending',
        ]);

        $user = $request->user();
        Notification::create([
            'user_id' => $user->id,
            'title' => 'Order #' . $order->id,
            'body' => 'your ordered has been created successfully',
            'title_ar' => 'الطلب #' . $order->id,
            'body_ar' => 'تم إنشاء طلبك بنجاح',
            'date' => now()->format('Y-m-d H:i'),
            'is_order' => false,
            'order_id' => $order->id,
            'admin_sent' => false,
        ]);

        app(FCMService::class)->send(
            $user,
            $user->locale === 'ar' ? 'الطلب #' . $order->id : 'Order #' . $order->id,
            $user->locale === 'ar' ? 'تم إنشاء طلبك بنجاح' : 'your ordered has been created successfully',
            $order->id,
            'order',
        );

        return response()->json([
            'error' => false,
            'message' => __('orders.created'),
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'error' => false,
            'message' => 'success',
            'data' => $orders->map(fn($o) => $this->formatOrder($o)),
        ]);
    }

    public function approve(Request $request): JsonResponse
    {
        $validated = $request->validate(['id' => 'required|exists:orders,id']);
        $order = Order::findOrFail($validated['id']);

        if ($order->user_id !== $request->user()->id) {
            return response()->json(['error' => true, 'message' => 'Forbidden'], 403);
        }

        $order->update(['status' => 'approved']);

        return response()->json([
            'error' => false,
            'message' => __('orders.approved'),
        ]);
    }

    public function cancel(Request $request): JsonResponse
    {
        $validated = $request->validate(['id' => 'required|exists:orders,id']);
        $order = Order::findOrFail($validated['id']);

        if ($order->user_id !== $request->user()->id) {
            return response()->json(['error' => true, 'message' => 'Forbidden'], 403);
        }

        $order->update(['status' => 'cancelled']);

        return response()->json([
            'error' => false,
            'message' => __('orders.cancelled'),
        ]);
    }

    private function formatOrder(Order $order): array
    {
        $statusMap = [
            'pending' => '1',
            'cancelled' => '2',
            'completed' => '6',
        ];

        return [
            'id' => $order->id,
            'price' => $order->price ?? '0',
            'lat' => $order->lat,
            'long' => $order->long,
            'status' => $statusMap[$order->status] ?? $order->status,
            'manufactory' => $order->manufactory,
            'carLicense' => $order->car_license && file_exists(storage_path('app/public/' . $order->car_license)) ? request()->getSchemeAndHttpHost() . '/storage/' . $order->car_license : null,
            'withService' => $order->with_service,
            'withFilter' => $order->with_filter,
            'startTime' => $order->start_time,
            'endTime' => $order->end_time,
            'pickDate' => $order->pick_date,
            'note' => $order->note,
            'type' => $order->type,
            'itemId' => $order->item_id,
            'batteryVoltageId' => $order->battery_voltage_id,
            'userId' => $order->user_id,
            'carId' => $order->car_id !== null ? (string) $order->car_id : null,
            'created_at' => $order->created_at,
            'updated_at' => $order->updated_at,
            'itemName' => $order->item_name,
            'date' => $order->created_at?->format('Y-m-d'),
            'time' => $order->created_at?->format('H:i'),
        ];
    }
}
