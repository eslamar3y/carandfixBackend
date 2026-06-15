<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ManageUsers;
use App\Models\User;
use BackedEnum;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $slug = 'users';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;
    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('nav.Users');
    }

    public static function getModelLabel(): string
    {
        return __('User');
    }

    public static function getPluralModelLabel(): string
    {
        return __('nav.Users');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('nav.System');
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) User::where('role', 'customer')->where('is_active', true)->where('email', 'not like', 'guest_%')->count();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->label(__('Name'))->required(),
                TextInput::make('email')->label(__('Email'))->required()->email()->unique(ignoreRecord: true),
                TextInput::make('phone')->label(__('Phone'))->required(fn($record) => $record === null),
                Select::make('role')->label(__('Role'))->options(['customer' => __('Customer'), 'technician' => __('Technician'), 'admin' => __('Admin')])->required(),
                TextInput::make('password')->label(__('Password'))->password()->required(fn($record) => $record === null),
                Toggle::make('is_verified')->label(__('Verified')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->searchOnBlur()
            ->columns([
                TextColumn::make('id')->label(__('ID'))->sortable(),
                TextColumn::make('name')->label(__('Name'))->searchable(),
                TextColumn::make('email')->label(__('Email'))->searchable(),
                TextColumn::make('phone')->label(__('Phone')),
                TextColumn::make('role')->label(__('Role'))->badge()->color(fn(string $state) => match ($state) { 'admin' => 'danger', 'technician' => 'warning', 'customer' => 'success' })->formatStateUsing(fn(string $state): string => __(ucfirst($state))),
                IconColumn::make('is_active')->label(__('Active'))->boolean(),
                IconColumn::make('is_verified')->label(__('Is verified'))->boolean(),
                TextColumn::make('created_at')->label(__('Created'))->dateTime(),
            ])
            ->filters([
                SelectFilter::make('role')->options(['customer' => __('Customer'), 'technician' => __('Technician'), 'admin' => __('Admin')]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageUsers::route('/'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
