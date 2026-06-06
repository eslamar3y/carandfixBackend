<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrdersByStatusChart extends ChartWidget
{
    public static function canView(): bool
    {
        return auth()->user()?->can('View:OrdersByStatusChart');
    }

    protected ?string $heading = '';

    public function getHeading(): string
    {
        return __('Orders by Status');
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $statuses = ['pending', 'accepted', 'in_progress', 'completed', 'cancelled'];
        $colors = [
            'pending' => '#f59e0b',
            'accepted' => '#3b82f6',
            'in_progress' => '#6366f1',
            'completed' => '#22c55e',
            'cancelled' => '#ef4444',
        ];

        $data = collect($statuses)->mapWithKeys(fn($s) => [$s => Order::where('status', $s)->count()]);

        return [
            'datasets' => [
                [
                    'data' => $data->values()->toArray(),
                    'backgroundColor' => collect($statuses)->map(fn($s) => $colors[$s])->toArray(),
                    'borderColor' => '#ffffff',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => collect($statuses)->map(fn($s) => __(ucfirst(str_replace('_', ' ', $s))))->toArray(),
        ];
    }
}
