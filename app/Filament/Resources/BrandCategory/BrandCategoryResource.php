<?php

namespace App\Filament\Resources\BrandCategory;

use App\Filament\Resources\BrandCategory\Pages\EditBrandCategory;
use App\Filament\Resources\BrandCategory\Pages\ManageBrandCategories;
use App\Models\BrandCategory;
use App\Models\Part;
use BackedEnum;
use UnitEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class BrandCategoryResource extends Resource
{
    protected static ?string $model = BrandCategory::class;
    protected static ?string $slug = 'brand-categories';
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('categorizable_id')->label(__('Part'))->options(fn() => Part::pluck('name', 'id'))->searchable()->required(),
            TextInput::make('name')->label(__('Name (EN)'))->required(),
            TextInput::make('name_ar')->label(__('Name (AR)'))->required(),
            FileUpload::make('image')->label(__('Image'))->image()->directory('brand-categories')->disk('public'),
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
        return [
            'index' => ManageBrandCategories::route('/'),
            'edit' => EditBrandCategory::route('/{record}/edit'),
        ];
    }
}
