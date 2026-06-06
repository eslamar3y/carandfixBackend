<?php

use App\Models\Notification;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Notification::where('title', 'Car Approved')->orWhere('title', 'Car Rejected')->each(function ($n) {
            preg_match('/Your car \(([^)]+)\)/', $n->body, $m);
            $vin = $m[1] ?? '';
            $n->update([
                'title' => $n->title === 'Car Approved' ? 'notifications.car_approved' : 'notifications.car_rejected',
                'body' => $vin,
            ]);
        });

        Notification::where('title', 'Order Status Updated')->each(function ($n) {
            preg_match('/order #(\d+).*status is now: (.+)/', $n->body, $m);
            $id = $m[1] ?? '';
            $status = $m[2] ?? '';
            $n->update([
                'title' => 'notifications.order_status_updated',
                'body' => $id . '|' . $status,
            ]);
        });
    }

    public function down(): void
    {
    }
};
