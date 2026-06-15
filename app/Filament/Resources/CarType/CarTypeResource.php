<?php

namespace App\Filament\Resources\CarType;

use App\Filament\Resources\CarType\Pages\ManageCarTypes;
use App\Models\CarType;
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
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;

class CarTypeResource extends Resource
{
    protected static ?string $model = CarType::class;
    protected static ?string $slug = 'car-types';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('nav.CarTypes');
    }

    public static function getModelLabel(): string
    {
        return __('Car Type');
    }

    public static function getPluralModelLabel(): string
    {
        return __('nav.CarTypes');
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
            FileUpload::make('image')->image()->directory('car_types')->disk('public')->required(),
            Repeater::make('carSubTypes')
                ->relationship()
                ->schema([
                    TextInput::make('name')->label(__('Name (EN)'))->required(),
                    TextInput::make('name_ar')->label(__('Name (AR)'))->required(),
                ])
                ->addActionLabel(__('Add Sub Type'))
                ->collapsible(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label(__('ID'))->sortable(),
                TextColumn::make('name')->label(__('Name'))->searchable(),
                TextColumn::make('carSubTypes.name')->label(__('Sub Types'))->badge()->limitList(5),
                TextColumn::make('created_at')->label(__('Created'))->dateTime(),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return ['index' => ManageCarTypes::route('/')];
    }
}
