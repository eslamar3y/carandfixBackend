<?php

namespace App\Filament\Resources\TermsCondition;

use App\Filament\Resources\TermsCondition\Pages\ManageTermsConditions;
use App\Models\TermsCondition;
use BackedEnum;
use UnitEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TermsConditionResource extends Resource
{
    protected static ?string $model = TermsCondition::class;
    protected static ?string $slug = 'terms-conditions';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;
    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('nav.TermsConditions');
    }

    public static function getModelLabel(): string
    {
        return __('Terms Condition');
    }

    public static function getPluralModelLabel(): string
    {
        return __('nav.TermsConditions');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('nav.Content');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name_en')->label(__('Name (EN)'))->required(),
            TextInput::make('name_ar')->label(__('Name (AR)'))->required(),
            Textarea::make('description_en')->label(__('Description (EN)'))->required(),
            Textarea::make('description_ar')->label(__('Description (AR)'))->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name_en')->limit(30)->label(__('Name (EN)')),
                TextColumn::make('name_ar')->limit(30)->label(__('Name (AR)')),
                TextColumn::make('updated_at')->since(),
            ])
            ->recordActions([EditAction::make()])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return ['index' => ManageTermsConditions::route('/')];
    }
}
