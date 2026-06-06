<?php

namespace App\Filament\Resources\Part;

use App\Filament\Resources\Part\Pages\EditPart;
use App\Filament\Resources\Part\Pages\ManageParts;
use App\Models\Part;
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

class PartResource extends Resource
{
    protected static ?string $model = Part::class;
    protected static ?string $slug = 'parts';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;
    protected static ?int $navigationSort = 4;

    public static function getNavigationLabel(): string
    {
        return __('nav.Parts');
    }

    public static function getModelLabel(): string
    {
        return __('Part');
    }

    public static function getPluralModelLabel(): string
    {
        return __('nav.Parts');
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
            FileUpload::make('image')->label(__('Image'))->image()->directory('parts')->disk('public'),
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
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageParts::route('/'),
            'edit' => EditPart::route('/{record}/edit'),
        ];
    }
}
