<?php

namespace App\Filament\Resources\Service;

use App\Filament\Resources\Service\Pages\ManageServices;
use App\Models\Service;
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
use Filament\Forms\Components\TextInput;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;
    protected static ?string $slug = 'services';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrench;
    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('nav.Categories');
    }

    public static function getModelLabel(): string
    {
        return __('nav.Category');
    }

    public static function getPluralModelLabel(): string
    {
        return __('nav.Categories');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('nav.ServicesRepairs');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label(__('Name (EN)'))->required(),
            TextInput::make('name_ar')->label(__('Name (AR)'))->required(),
            TextInput::make('price')->label(__('Price'))->numeric()->nullable(),
            FileUpload::make('image')->label(__('Image'))->image()->directory('services')->disk('public'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label(__('ID'))->sortable(),
                TextColumn::make('name')->label(__('Name'))->searchable(),
                TextColumn::make('price')->label(__('Price'))->money('usd'),
                TextColumn::make('created_at')->label(__('Created'))->dateTime(),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return ['index' => ManageServices::route('/')];
    }
}
