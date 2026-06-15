<?php

namespace App\Filament\Resources\Part\RelationManagers;

use App\Filament\Resources\BrandCategory\BrandCategoryResource;
use App\Models\BrandCategory;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;

class BrandCategoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'brandCategories';
    protected static ?string $title = '';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Brand Categories');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label(__('Name (EN)'))->required(),
            TextInput::make('name_ar')->label(__('Name (AR)'))->required(),
            FileUpload::make('image')->label(__('Image'))->image()->directory('brand-categories')->disk('public'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label(__('Name'))->searchable(),
                TextColumn::make('brands_count')->counts('brands')->label(__('Brands')),
                TextColumn::make('created_at')->label(__('Created'))->dateTime(),
            ])
            ->headerActions([CreateAction::make()])
            ->recordActions([
                EditAction::make()->url(fn(BrandCategory $record) => BrandCategoryResource::getUrl('edit', ['record' => $record])),
                DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
