<?php

namespace App\Filament\Resources\Order\Pages;

use App\Filament\Resources\Order\OrderResource;
use App\Models\BatteryVoltage;
use App\Models\Brand;
use App\Models\Car;
use App\Models\Emergency;
use App\Models\Order;
use App\Models\ServiceCategory;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ManageOrders extends ManageRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->form([
                    Select::make('user_id')->label(__('Customer'))->relationship('user', 'name')->searchable()->required()->live(),
                    Select::make('car_id')->label(__('Car'))->searchable()
                        ->options(fn($get) => Car::where('user_id', $get('user_id'))->pluck('vin_number', 'id')),
                    Select::make('type')->label(__('Type'))->options(['Emergency' => __('Emergency'), 'Services' => __('Services'), 'Parts' => __('Parts')])->required()->live(),
                    Select::make('item_id')->label(__('Item'))->searchable()
                        ->options(fn($get) => match ($get('type')) {
                            'Emergency' => Emergency::pluck('name', 'id'),
                            'Services' => ServiceCategory::pluck('name', 'id'),
                            'Parts' => Brand::pluck('name', 'id'),
                            default => [],
                        }),
                    Select::make('battery_voltage_id')->label(__('Battery Voltage'))
                        ->options(BatteryVoltage::pluck('name', 'id'))
                        ->visible(fn($get) => $get('type') === 'Emergency'),
                    Select::make('with_service')->label(__('With Service'))->options(['yes' => __('Yes'), 'no' => __('No')])
                        ->visible(fn($get) => $get('type') === 'Services'),
                    Select::make('with_filter')->label(__('With Filter'))->options(['yes' => __('Yes'), 'no' => __('No')])
                        ->visible(fn($get) => $get('type') === 'Services'),
                    TextInput::make('start_time')->label(__('Start Time'))
                        ->visible(fn($get) => $get('type') === 'Services'),
                    TextInput::make('end_time')->label(__('End Time'))
                        ->visible(fn($get) => $get('type') === 'Services'),
                    TextInput::make('price')->label(__('Price'))->numeric()->prefix('$')->nullable(),
                    TextInput::make('payment_method')->label(__('Payment Method')),
                    TextInput::make('phone')->label(__('Phone')),
                    FileUpload::make('car_license')->label(__('License Image'))->image()->directory('licenses')->disk('public'),
                    Select::make('status')->label(__('Status'))->options(['pending' => __('Pending'), 'completed' => __('Completed'), 'cancelled' => __('Cancelled')])->default('pending')->required(),
                    Select::make('technician_id')->label(__('Technician'))->options(fn() => User::where('role', 'technician')->pluck('name', 'id')),
                    TextInput::make('note')->label(__('Notes')),
                ])
                ->action(function (array $data) {
                    $itemId = $data['item_id'] ?? null;
                    if ($itemId && isset($data['type'])) {
                        $item = match ($data['type']) {
                            'Emergency' => Emergency::find($itemId),
                            'Services' => ServiceCategory::find($itemId),
                            'Parts' => Brand::find($itemId),
                            default => null,
                        };
                        $data['item_name'] = $item?->getRawOriginal('name') ?? $item?->name;
                        $data['item_name_ar'] = $item?->name_ar ?? null;
                    }
                    $data['price'] ??= 0;
                    $data['lat'] ??= '-';
                    $data['long'] ??= '-';
                    if (!empty($data['car_license'])) {
                        $path = storage_path('app/public/' . $data['car_license']);
                        if (file_exists($path) && filesize($path) < 5000) {
                            unlink($path);
                            $data['car_license'] = null;
                        }
                    } else {
                        $data['car_license'] = null;
                    }
                    Order::create($data);
                }),
        ];
    }

    public function getTabs(): array
    {
        return [
            __('All') => Tab::make()
                ->badge(Order::count()),
            __('New') => Tab::make()
                ->badge(Order::where('status', 'pending')->count())
                ->query(fn($query) => $query->where('status', 'pending')),
            __('Completed') => Tab::make()
                ->badge(Order::where('status', 'completed')->count())
                ->query(fn($query) => $query->where('status', 'completed')),
            __('Cancelled') => Tab::make()
                ->badge(Order::where('status', 'cancelled')->count())
                ->badgeColor('danger')
                ->query(fn($query) => $query->where('status', 'cancelled')),
        ];
    }
}
