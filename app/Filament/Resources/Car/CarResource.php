<?php

namespace App\Filament\Resources\Car;

use App\Filament\Resources\Car\Pages\ManageCars;
use App\Models\Car;
use App\Models\CarType;
use App\Models\EngineType;
use BackedEnum;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class CarResource extends Resource
{
    protected static ?string $model = Car::class;
    protected static ?string $slug = 'cars';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;
    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('nav.Cars');
    }

    public static function getModelLabel(): string
    {
        return __('Car');
    }

    public static function getPluralModelLabel(): string
    {
        return __('nav.Cars');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('nav.Cars');
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Car::count();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('user_id')->label(__('Customer'))->relationship('user', 'name')->searchable()->required(),
            TextInput::make('vin_number')->label(__('VIN Number'))->unique(Car::class, 'vin_number'),
            TextInput::make('color')->label(__('Color')),
            TextInput::make('year_of_production')->label(__('Year')),
            TextInput::make('engine_power')->label(__('Engine Power')),
            TextInput::make('registration_number')->label(__('Plate Number')),
            TextInput::make('last_oil_change_date')->label(__('Last Oil Change')),
            Select::make('car_type_id')->label(__('Car Type'))->options(CarType::pluck('name', 'id')),
            Select::make('engine_type_id')->label(__('Engine Type'))->options(EngineType::pluck('name', 'id')),
            Select::make('status')->label(__('Status'))->options(['pending' => __('Pending'), 'approved' => __('Approved'), 'rejected' => __('Rejected')]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label(__('ID'))->sortable(),
                TextColumn::make('user.name')->label(__('User'))->searchable(),
                TextColumn::make('vin_number')->label(__('VIN Number'))->searchable(),
                TextColumn::make('carType.name')->label(__('Car Type')),
                TextColumn::make('carSubType.name')->label(__('Car Sub Type')),
                TextColumn::make('engineType.name')->label(__('Engine Type')),
                BadgeColumn::make('status')->label(__('Status'))->color(fn(string $state) => match ($state) { 'pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger' }),
                TextColumn::make('created_at')->label(__('Created'))->dateTime(),
            ])
            ->filters([
                SelectFilter::make('status')->label(__('Status'))->options(['pending' => __('Pending'), 'approved' => __('Approved'), 'rejected' => __('Rejected')]),
            ])
            ->recordActions([
                \Filament\Actions\Action::make('approve')
                    ->label(__('Approve'))
                    ->color('success')
                    ->icon(Heroicon::OutlinedCheck)
                    ->visible(fn(Car $record) => $record->status === 'pending')
                    ->action(fn(Car $record) => $record->update(['status' => 'approved']))
                    ->requiresConfirmation(),
                \Filament\Actions\Action::make('reject')
                    ->label(__('Reject'))
                    ->color('danger')
                    ->icon(Heroicon::OutlinedXMark)
                    ->visible(fn(Car $record) => $record->status === 'pending')
                    ->action(fn(Car $record) => $record->update(['status' => 'rejected']))
                    ->requiresConfirmation(),
                ViewAction::make()->form([
            TextInput::make('vin_number')->label(__('VIN Number')),
                    TextInput::make('color')->label(__('Color')),
                    TextInput::make('year_of_production')->label(__('Year')),
                    TextInput::make('engine_power')->label(__('Engine Power')),
                    TextInput::make('registration_number')->label(__('Plate Number')),
                    TextInput::make('last_oil_change_date')->label(__('Last Oil Change')),
                    TextInput::make('status')->label(__('Status')),
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
        return [
            'index' => ManageCars::route('/'),
        ];
    }
}
