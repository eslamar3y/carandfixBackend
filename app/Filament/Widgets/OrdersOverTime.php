<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrdersOverTime extends ChartWidget
{
    public static function canView(): bool
    {
        return auth()->user()?->can('View:OrdersOverTime') ?? auth()->user()?->hasRole('admin');
    }

    protected ?string $heading = '';

    public function getHeading(): string
    {
        return __('Orders Over Time (Last 30 Days)');
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $data = collect(range(29, 0, -1))->map(function ($day) {
            $date = now()->subDays($day)->format('Y-m-d');
            return [
                'date' => now()->subDays($day)->format('M d'),
                'count' => Order::whereDate('created_at', $date)->count(),
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => __('Orders'),
                    'data' => $data->pluck('count')->toArray(),
                    'borderColor' => '#6366f1',
                    'backgroundColor' => 'rgba(99, 102, 241, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $data->pluck('date')->toArray(),
        ];
    }
}
