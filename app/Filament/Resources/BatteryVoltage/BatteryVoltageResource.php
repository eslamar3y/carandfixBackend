<?php

namespace App\Filament\Resources\BatteryVoltage;

use App\Filament\Resources\BatteryVoltage\Pages\ManageBatteryVoltages;
use App\Models\BatteryVoltage;
use BackedEnum;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;

class BatteryVoltageResource extends Resource
{
    protected static ?string $model = BatteryVoltage::class;
    protected static ?string $slug = 'battery-voltages';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBolt;
    protected static ?int $navigationSort = 4;

    public static function getNavigationLabel(): string
    {
        return __('nav.BatteryVoltages');
    }

    public static function getModelLabel(): string
    {
        return __('Battery Voltage');
    }

    public static function getPluralModelLabel(): string
    {
        return __('nav.BatteryVoltages');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('nav.Cars');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label(__('Name (EN)'))->required(),
            TextInput::make('name_ar')->label(__('Name (AR)'))->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label(__('ID'))->sortable(),
                TextColumn::make('name')->label(__('Name'))->searchable(),
                TextColumn::make('created_at')->label(__('Created'))->dateTime(),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageBatteryVoltages::route('/')];
    }
}
