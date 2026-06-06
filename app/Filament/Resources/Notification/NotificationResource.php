<?php

namespace App\Filament\Resources\Notification;

use App\Filament\Resources\Notification\Pages\ManageNotifications;
use App\Models\Notification as NotificationModel;
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
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class NotificationResource extends Resource
{
    protected static ?string $model = NotificationModel::class;
    protected static ?string $slug = 'notifications';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBell;
    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('nav.Notifications');
    }

    public static function getModelLabel(): string
    {
        return __('Notification');
    }

    public static function getPluralModelLabel(): string
    {
        return __('nav.Notifications');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('nav.System');
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) NotificationModel::count();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('user_id')->label(__('User'))->relationship('user', 'name')->required(),
            TextInput::make('title')->label(__('Title (English)'))->required(),
            TextInput::make('body')->label(__('Body (English)'))->required(),
            TextInput::make('title_ar')->label(__('Title (Arabic)'))->required(),
            TextInput::make('body_ar')->label(__('Body (Arabic)'))->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('user.name')->searchable(),
                TextColumn::make('title')->label(__('Title (English)'))->limit(30)->searchable(),
                TextColumn::make('title_ar')->label(__('Title (Arabic)'))->limit(30),
                TextColumn::make('body')->label(__('Body (English)'))->limit(40),
                TextColumn::make('body_ar')->label(__('Body (Arabic)'))->limit(40),
                ToggleColumn::make('is_order'),
                TextColumn::make('date'),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageNotifications::route('/')];
    }
}
