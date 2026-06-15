<?php

namespace App\Filament\Resources\BrandCategory\RelationManagers;

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
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;

class BrandsRelationManager extends RelationManager
{
    protected static string $relationship = 'brands';
    protected static ?string $title = '';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Brands');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label(__('Name (EN)'))->required(),
            TextInput::make('name_ar')->label(__('Name (AR)'))->required(),
            TextInput::make('price')->label(__('Price'))->numeric()->nullable(),
            FileUpload::make('image')->label(__('Image'))->image()->directory('brands')->disk('public')->required(),
            Section::make(__('Order Fields'))
                ->description(__('Choose which fields appear on the order page'))
                ->columns(2)
                ->schema([
                    Toggle::make('fields.selectCar')->label('Select Car'),
                    Toggle::make('fields.pickLocation')->label('Pick Location'),
                    Toggle::make('fields.manufactory')->label('Manufactory'),
                    Toggle::make('fields.batteryVoltage')->label('Battery Voltage'),
                    Toggle::make('fields.withService')->label('With Service'),
                    Toggle::make('fields.carLicense')->label('Car License'),
                    Toggle::make('fields.withFilter')->label('With Filter'),
                    Toggle::make('fields.pickDate')->label('Pick Date'),
                    Toggle::make('fields.startTime')->label('Start Time'),
                    Toggle::make('fields.endTime')->label('End Time'),
                    Toggle::make('fields.note')->label('Note'),
                    Toggle::make('fields.phone')->label('Phone'),
                    Toggle::make('fields.PaymentMethod')->label('Payment Method'),
                ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label(__('Name'))->searchable(),
                TextColumn::make('price')->label(__('Price'))->money('usd'),
                TextColumn::make('created_at')->label(__('Created'))->dateTime(),
            ])
            ->headerActions([CreateAction::make()])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
