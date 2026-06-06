<?php

namespace App\Filament\Resources\ServiceCategory;

use App\Filament\Resources\ServiceCategory\Pages\ManageServiceCategories;
use App\Models\Service;
use App\Models\ServiceCategory;
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
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;

class ServiceCategoryResource extends Resource
{
    protected static ?string $model = ServiceCategory::class;
    protected static ?string $slug = 'service-categories';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('nav.Services');
    }

    public static function getModelLabel(): string
    {
        return __('nav.Service');
    }

    public static function getPluralModelLabel(): string
    {
        return __('nav.Services');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('nav.ServicesRepairs');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('service_id')->label(__('Category'))->options(fn() => Service::pluck('name', 'id'))->searchable()->required(),
            TextInput::make('name')->label(__('Name (EN)'))->required(),
            TextInput::make('name_ar')->label(__('Name (AR)'))->required(),
            TextInput::make('price')->label(__('Price'))->numeric()->nullable(),
            FileUpload::make('image')->label(__('Image'))->image()->directory('service-categories')->disk('public'),
            Section::make(__('Form Fields'))
                ->description(__('Toggle which fields appear to the user when ordering this service'))
                ->columns(2)
                ->schema([
                    Toggle::make('fields.selectCar')->label(__('Select Car')),
                    Toggle::make('fields.pickLocation')->label(__('Pick Location')),
                    Toggle::make('fields.manufactory')->label(__('Manufactory')),
                    Toggle::make('fields.batteryVoltage')->label(__('Battery Voltage')),
                    Toggle::make('fields.withService')->label(__('With Service')),
                    Toggle::make('fields.carLicense')->label(__('Car License')),
                    Toggle::make('fields.withFilter')->label(__('With Filter')),
                    Toggle::make('fields.pickDate')->label(__('Pick Date')),
                    Toggle::make('fields.startTime')->label(__('Start Time')),
                    Toggle::make('fields.endTime')->label(__('End Time')),
                    Toggle::make('fields.note')->label(__('Note')),
                    Toggle::make('fields.phone')->label(__('Phone')),
                    Toggle::make('fields.PaymentMethod')->label(__('Payment Method')),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label(__('ID'))->sortable(),
                TextColumn::make('service.name')->label(__('Category')),
                TextColumn::make('name')->label(__('Name'))->searchable(),
                TextColumn::make('price')->label(__('Price'))->money('usd'),
                TextColumn::make('created_at')->label(__('Created'))->dateTime(),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageServiceCategories::route('/')];
    }
}
