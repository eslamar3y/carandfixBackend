<?php

namespace App\Filament\Resources\Users\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class CarsRelationManager extends RelationManager
{
    protected static string $relationship = 'cars';
    protected static ?string $title = '';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Cars');
    }

    public static function getModelLabel(): string
    {
        return __('Car');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Cars');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('vin_number')->label(__('VIN Number'))->required(),
            TextInput::make('color')->label(__('Color')),
            TextInput::make('year_of_production')->label(__('Year')),
            TextInput::make('registration_number')->label(__('Plate Number')),
            Select::make('status')
                ->options(['pending' => __('Pending'), 'approved' => __('Approved'), 'rejected' => __('Rejected')])
                ->default('approved')
                ->required(),
            Select::make('car_type_id')
                ->label(__('Car Type'))
                ->relationship('carType', 'name')
                ->searchable(),
            Select::make('car_sub_type_id')
                ->label(__('Car Sub Type'))
                ->relationship('carSubType', 'name')
                ->searchable(),
            Select::make('engine_type_id')
                ->label(__('Engine Type'))
                ->relationship('engineType', 'name')
                ->searchable(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('vin_number')->label(__('VIN Number'))->searchable(),
                TextColumn::make('carType.name')->label(__('Type')),
                TextColumn::make('status')->label(__('Status'))
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    }),
                TextColumn::make('created_at')->label(__('Created'))->dateTime(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->after(fn() => $this->getOwnerRecord()->update(['is_active' => true])),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
