<?php

namespace App\Filament\Resources\AboutAndContact;

use App\Filament\Resources\AboutAndContact\Pages\ManageAboutAndContacts;
use App\Models\AboutAndContact;
use BackedEnum;
use UnitEnum;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class AboutAndContactResource extends Resource
{
    protected static ?string $model = AboutAndContact::class;
    protected static ?string $slug = 'about-and-contacts';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInformationCircle;
    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('nav.AboutAndContacts');
    }

    public static function getModelLabel(): string
    {
        return __('About And Contact');
    }

    public static function getPluralModelLabel(): string
    {
        return __('nav.AboutAndContacts');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('nav.Content');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Textarea::make('description_en')->label(__('About (English)')),
            Textarea::make('description_ar')->label(__('About (Arabic)')),
            TextInput::make('email')->label(__('Contact Email')),
            TextInput::make('phone')->label(__('Contact Phone')),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label(__('ID'))->sortable(),
                TextColumn::make('email')->label(__('Contact Email')),
                TextColumn::make('phone')->label(__('Contact Phone')),
                TextColumn::make('updated_at')->label(__('Updated'))->since(),
            ])
            ->recordActions([EditAction::make()])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return ['index' => ManageAboutAndContacts::route('/')];
    }
}
