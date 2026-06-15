<?php

namespace App\Filament\Resources\EngineType;

use App\Filament\Resources\EngineType\Pages\ManageEngineTypes;
use App\Models\EngineType;
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

class EngineTypeResource extends Resource
{
    protected static ?string $model = EngineType::class;
    protected static ?string $slug = 'engine-types';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;
    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return __('nav.EngineTypes');
    }

    public static function getModelLabel(): string
    {
        return __('Engine Type');
    }

    public static function getPluralModelLabel(): string
    {
        return __('nav.EngineTypes');
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
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return ['index' => ManageEngineTypes::route('/')];
    }
}
