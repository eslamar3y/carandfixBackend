<?php

namespace App\Filament\Resources\Order;

use App\Filament\Resources\Order\Pages\ManageOrders;
use App\Models\Order;
use App\Models\User;
use BackedEnum;
use UnitEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $slug = 'orders';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;
    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('nav.Orders');
    }

    public static function getModelLabel(): string
    {
        return __('Order');
    }

    public static function getPluralModelLabel(): string
    {
        return __('nav.Orders');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('nav.Orders');
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Order::where('status', 'pending')->count();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('status')->label(__('Status'))->options(['pending' => __('Pending'), 'completed' => __('Completed'), 'cancelled' => __('Cancelled')])->required(),
            Select::make('technician_id')->label(__('Technician'))->options(fn() => User::where('role', 'technician')->pluck('name', 'id')),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label(__('ID'))->sortable(),
                TextColumn::make('user.name')->label(__('User'))->searchable(),
                TextColumn::make('car.vin_number')->label(__('Car VIN')),
                TextColumn::make('type')->label(__('Type')),
                TextColumn::make('item_name')->label(__('Item')),
                TextColumn::make('price')->label(__('Price'))->money('usd'),
                BadgeColumn::make('status')->label(__('Status'))->color(fn(string $state) => match ($state) { 'pending' => 'warning', 'completed' => 'success', 'cancelled' => 'danger', default => 'gray' }),
                TextColumn::make('technician.name')->label(__('Technician')),
                TextColumn::make('pick_date')->label(__('Pick Date')),
                TextColumn::make('created_at')->label(__('Created'))->dateTime(),
            ])
            ->filters([
                SelectFilter::make('status')->label(__('Status'))->options(['pending' => __('Pending'), 'completed' => __('Completed'), 'cancelled' => __('Cancelled')]),
            ])
            ->recordActions([
                Action::make('complete')
                    ->label(__('Complete'))
                    ->color('success')
                    ->icon(Heroicon::OutlinedCheck)
                    ->visible(fn(Order $record) => $record->status === 'pending')
                    ->action(fn(Order $record) => $record->update(['status' => 'completed']))
                    ->requiresConfirmation(),
                Action::make('cancel')
                    ->label(__('Cancel'))
                    ->color('danger')
                    ->icon(Heroicon::OutlinedXMark)
                    ->visible(fn(Order $record) => $record->status === 'pending')
                    ->action(fn(Order $record) => $record->update(['status' => 'cancelled']))
                    ->requiresConfirmation(),
                ViewAction::make()
                    ->mutateRecordDataUsing(fn(array $data, Model $record): array => array_merge($data, [
                        'user_name' => $record->user?->name ?? '-',
                        'car_vin' => $record->car?->vin_number ?? '-',
                        'technician_name' => $record->technician?->name ?? '-',
                        'location' => ($record->lat && $record->long && $record->lat !== '-' && $record->long !== '-')
                            ? $record->lat . ',' . $record->long
                            : null,
                    ]))
                    ->form([
                        TextInput::make('id')->label(__('Order ID')),
                        TextInput::make('user_name')->label(__('Customer')),
                        TextInput::make('car_vin')->label(__('Car VIN')),
                        TextInput::make('type')->label(__('Type')),
                        TextInput::make('status')->label(__('Status')),
                        TextInput::make('location')->label(__('Location'))
                            ->url(fn(?string $state): ?string => $state ? "https://www.google.com/maps?q={$state}" : null),
                        TextInput::make('price')->label(__('Price')),
                        TextInput::make('payment_method')->label(__('Payment Method'))
                            ->formatStateUsing(fn($state) => match ($state) { '1' => 'Cash / كاش', '0' => 'Bank Transfer / تحويل بنكي', default => $state }),
                        TextInput::make('item_name')->label(__('Item')),
                        TextInput::make('phone')->label(__('Phone')),
                        FileUpload::make('car_license')->label(__('License Image'))->image()->disk('public')
                            ->visible(fn($record) => filled($record->car_license)),
                        TextInput::make('manufactory')->label(__('Manufactory')),
                        TextInput::make('battery_voltage_id')->label(__('Battery Voltage')),
                        TextInput::make('with_service')->label(__('With Service')),
                        TextInput::make('with_filter')->label(__('With Filter')),
                        TextInput::make('start_time')->label(__('Start Time')),
                        TextInput::make('end_time')->label(__('End Time')),
                        TextInput::make('pick_date')->label(__('Pick Date')),
                        TextInput::make('technician_name')->label(__('Technician')),
                        TextInput::make('note')->label(__('Notes')),
                        TextInput::make('created_at')->label(__('Created')),
                    ]),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return ['index' => ManageOrders::route('/')];
    }
}
