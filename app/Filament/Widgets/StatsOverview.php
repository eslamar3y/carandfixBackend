<?php

namespace App\Filament\Widgets;

use App\Models\Car;
use App\Models\Order;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    public static function canView(): bool
    {
        return auth()->user()?->can('View:StatsOverview');
    }

    protected function getColumns(): int
    {
        return 6;
    }

    protected function getStats(): array
    {
        $panelPath = Filament::getCurrentPanel()->getPath();

        return [
            Stat::make(__('Customers'), User::where('role', 'customer')->where('email', 'not like', 'guest_%')->count())
                ->description(__('All registered customers'))
                ->color('info')
                ->url("/$panelPath/users")
                ->columnSpan(2),

            Stat::make(__('Active Customers'), User::where('role', 'customer')->where('is_active', true)->where('email', 'not like', 'guest_%')->count())
                ->description(__('Customers with active accounts'))
                ->color('success')
                ->url("/$panelPath/users")
                ->columnSpan(2),

            Stat::make(__('Inactive Customers'), User::where('role', 'customer')->where('is_active', false)->where('email', 'not like', 'guest_%')->count())
                ->description(__('Customers with inactive accounts'))
                ->color('danger')
                ->url("/$panelPath/users")
                ->columnSpan(2),

            Stat::make(__('Total Cars'), Car::count())
                ->description(__('New') . ': ' . Car::where('status', 'pending')->count())
                ->color('warning')
                ->url("/$panelPath/cars")
                ->columnSpan(6),

            Stat::make(__('Total Orders'), Order::count())
                ->description(__('All orders'))
                ->color('primary')
                ->url("/$panelPath/orders")
                ->columnSpan(2),

            Stat::make(__('New Orders'), Order::where('status', 'pending')->count())
                ->description(__('Awaiting acceptance'))
                ->color('warning')
                ->url("/$panelPath/orders")
                ->columnSpan(2),

            Stat::make(__('Accepted Orders'), Order::where('status', 'accepted')->count())
                ->description(__('Accepted by admin'))
                ->color('info')
                ->url("/$panelPath/orders")
                ->columnSpan(2),

            Stat::make(__('Completed Orders'), Order::where('status', 'completed')->count())
                ->description(__('Successfully completed'))
                ->color('success')
                ->url("/$panelPath/orders")
                ->columnSpan(2),

            Stat::make(__('Cancelled Orders'), Order::where('status', 'cancelled')->count())
                ->description(__('Cancelled by user or admin'))
                ->color('danger')
                ->url("/$panelPath/orders")
                ->columnSpan(2),

            Stat::make(__('Orders Today'), Order::whereDate('created_at', today())->count())
                ->description(__('Orders placed today'))
                ->color('primary')
                ->url("/$panelPath/orders")
                ->columnSpan(2),
        ];
    }
}
