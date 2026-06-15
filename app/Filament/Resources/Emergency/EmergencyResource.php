<?php

namespace App\Filament\Resources\Emergency;

use App\Filament\Resources\Emergency\Pages\ManageEmergencies;
use App\Models\Emergency;
use BackedEnum;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EmergencyResource extends Resource
{
    protected static ?string $model = Emergency::class;
    protected static ?string $slug = 'emergencies';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;
    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return __('nav.Emergencies');
    }

    public static function getModelLabel(): string
    {
        return __('Emergency');
    }

    public static function getPluralModelLabel(): string
    {
        return __('nav.Emergencies');
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
            FileUpload::make('image')->label(__('Image'))->image()->directory('emergencies')->disk('public'),
            Select::make('service_category_id')
                ->label('القسم الرئيسي')
                ->relationship('parent', 'name')
                ->nullable(),
            Section::make('الحقول المطلوبة في الطلب')
                ->description('حدد الحقول التي ستظهر للمستخدم عند تقديم الطلب')
                ->schema([
                    Grid::make(2)->schema([
                        Toggle::make('fields.selectCar')
                            ->label('اختيار سيارة')
                            ->mutateDehydratedStateUsing(fn ($state): int => $state ? 1 : 0),
                        Toggle::make('fields.pickLocation')
                            ->label('تحديد الموقع')
                            ->mutateDehydratedStateUsing(fn ($state): int => $state ? 1 : 0),
                        Toggle::make('fields.manufactory')
                            ->label('أصلي / تقليد')
                            ->mutateDehydratedStateUsing(fn ($state): int => $state ? 1 : 0),
                        Toggle::make('fields.batteryVoltage')
                            ->label('فولتية البطارية')
                            ->mutateDehydratedStateUsing(fn ($state): int => $state ? 1 : 0),
                        Toggle::make('fields.withService')
                            ->label('مع خدمة / بدون')
                            ->mutateDehydratedStateUsing(fn ($state): int => $state ? 1 : 0),
                        Toggle::make('fields.carLicense')
                            ->label('رخصة السيارة')
                            ->mutateDehydratedStateUsing(fn ($state): int => $state ? 1 : 0),
                        Toggle::make('fields.withFilter')
                            ->label('مع فلتر / بدون')
                            ->mutateDehydratedStateUsing(fn ($state): int => $state ? 1 : 0),
                        Toggle::make('fields.pickDate')
                            ->label('اختيار التاريخ')
                            ->mutateDehydratedStateUsing(fn ($state): int => $state ? 1 : 0),
                        Toggle::make('fields.startTime')
                            ->label('وقت البداية')
                            ->mutateDehydratedStateUsing(fn ($state): int => $state ? 1 : 0),
                        Toggle::make('fields.endTime')
                            ->label('وقت النهاية')
                            ->mutateDehydratedStateUsing(fn ($state): int => $state ? 1 : 0),
                        Toggle::make('fields.note')
                            ->label('ملاحظات')
                            ->mutateDehydratedStateUsing(fn ($state): int => $state ? 1 : 0),
                        Toggle::make('fields.phone')
                            ->label('رقم الهاتف')
                            ->mutateDehydratedStateUsing(fn ($state): int => $state ? 1 : 0),
                        Toggle::make('fields.PaymentMethod')
                            ->label('طريقة الدفع')
                            ->mutateDehydratedStateUsing(fn ($state): int => $state ? 1 : 0),
                    ]),
                ]),
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
        return ['index' => ManageEmergencies::route('/')];
    }
}
