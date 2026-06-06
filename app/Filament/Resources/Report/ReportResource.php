<?php

namespace App\Filament\Resources\Report;

use App\Filament\Resources\Report\Pages\ManageReports;
use App\Filament\Resources\Report\Pages\ViewReport;
use App\Models\Report as ReportModel;
use BackedEnum;
use UnitEnum;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Html;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use App\Models\Car;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ReportResource extends Resource
{
    protected static ?string $model = ReportModel::class;
    protected static ?string $slug = 'reports';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocument;
    protected static ?int $navigationSort = 5;

    public static function getNavigationLabel(): string
    {
        return __('nav.Reports');
    }

    public static function getModelLabel(): string
    {
        return __('Report');
    }

    public static function getPluralModelLabel(): string
    {
        return __('nav.Reports');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('nav.ServicesRepairs');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Basic Info'))
                    ->schema([
                        Select::make('car_id')
                            ->label(__('Car (VIN)'))
                            ->relationship('car', 'vin_number')
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                $car = Car::with('user')->find($state);
                                $set('_owner_name', $car?->user?->name ?? '');
                            }),
                        TextInput::make('_owner_name')
                            ->label(__('Owner'))
                            ->disabled()
                            ->dehydrated(false)
                            ->formatStateUsing(fn($record) => $record?->car?->user?->name ?? ''),
                        Select::make('technician_id')
                            ->label(__('Technician'))
                            ->options(fn() => \App\Models\User::where('role', 'technician')->pluck('name', 'id'))
                            ->searchable()
                            ->hidden(fn() => Auth::user()?->hasRole('technician')),
                        TextInput::make('serial')->label(__('Serial'))->numeric(),
                        Select::make('final_decision')
                            ->options([
                                'excellent' => __('Excellent'),
                                'good' => __('Good'),
                                'fair' => __('Fair'),
                                'poor' => __('Poor'),
                            ]),
                        TextInput::make('current_mileage')->label(__('Mileage')),
                        TextInput::make('report_date')->label(__('Date')),
                        TextInput::make('car_options')->label(__('Car Options')),
                    ]),

                Section::make(__('Percentages'))
                    ->schema([
                        TextInput::make('exterior_percent')->label(__('Exterior %'))->numeric()->minValue(0)->maxValue(100)->suffix('%'),
                        TextInput::make('chassis_percent')->label(__('Chassis %'))->numeric()->minValue(0)->maxValue(100)->suffix('%'),
                        TextInput::make('road_test_percent')->label(__('Road Test %'))->numeric()->minValue(0)->maxValue(100)->suffix('%'),
                        TextInput::make('power_train_percent')->label(__('Power Train %'))->numeric()->minValue(0)->maxValue(100)->suffix('%'),
                        TextInput::make('electrical_percent')->label(__('Electrical %'))->numeric()->minValue(0)->maxValue(100)->suffix('%'),
                        TextInput::make('braking_percent')->label(__('Braking %'))->numeric()->minValue(0)->maxValue(100)->suffix('%'),
                        TextInput::make('suspension_percent')->label(__('Suspension %'))->numeric()->minValue(0)->maxValue(100)->suffix('%'),
                        TextInput::make('ac_cooling_percent')->label(__('AC & Cooling %'))->numeric()->minValue(0)->maxValue(100)->suffix('%'),
                    ]),

                Tabs::make(__('Sections'))
                    ->tabs([
                        self::sectionRepeaterTab(__('Exterior'), 'exterior'),
                        self::sectionRepeaterTab(__('Chassis & Frame'), 'chassis_frame'),
                        self::sectionRepeaterTab(__('Road Test'), 'road_test'),
                        self::sectionRepeaterTab(__('Power Train'), 'power_train'),
                        self::sectionRepeaterTab(__('Electrical System'), 'electrical_system'),
                        self::sectionRepeaterTab(__('Braking & Safety'), 'braking_safety'),
                        self::sectionRepeaterTab(__('Suspension'), 'suspension'),
                        self::sectionRepeaterTab(__('AC & Cooling'), 'ac_cooling'),
                    ]),

                Section::make(__('Additional Data'))
                    ->schema([
                        Repeater::make('all_notes')
                            ->schema([
                                TextInput::make('note')->label(__('Note')),
                                TextInput::make('section')->label(__('Section')),
                            ])
                            ->collapsible()
                            ->defaultItems(0),
                        Repeater::make('inspection_systems')
                            ->schema([
                                TextInput::make('name')->label(__('Name')),
                                Select::make('status')
                                    ->options([
                                        'good' => __('Good'),
                                        'fair' => __('Fair'),
                                        'poor' => __('Poor'),
                                    ]),
                            ])
                            ->collapsible()
                            ->defaultItems(0),
                    ]),
                Section::make(__('Report Images'))
                    ->description(__('Upload images related to the inspection notes'))
                    ->schema([
                        FileUpload::make('note_images')
                            ->multiple()
                            ->image()
                            ->directory('note-images')
                            ->disk('public')
                            ->reorderable()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    private static function sectionRepeaterTab(string $label, string $field): Tab
    {
        return Tab::make($label)
            ->schema([
                Repeater::make($field)
                    ->schema([
                        TextInput::make('name')->label(__('Name'))->required(),
                        Select::make('status')
                            ->options([
                                'excellent' => __('Excellent'),
                                'good' => __('Good'),
                                'fair' => __('Fair'),
                                'poor' => __('Poor'),
                                'not_checked' => __('Not Checked'),
                            ]),
                        Textarea::make('notes')->label(__('Notes')),
                    ])
                    ->collapsible()
                    ->defaultItems(0),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (Auth::user()?->hasRole('technician')) {
            $query->where('technician_id', Auth::id());
        }

        return $query;
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        if (Auth::user()?->hasRole('technician')) {
            $data['technician_id'] = Auth::id();
        }

        return $data;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label(__('ID'))->sortable(),
                TextColumn::make('car.vin_number')->label(__('Car (VIN)'))->searchable(),
                TextColumn::make('technician.name')->label(__('Technician')),
                TextColumn::make('final_decision')->label(__('Decision')),
                TextColumn::make('current_mileage')->label(__('Mileage')),
                TextColumn::make('exterior_percent')->label(__('Exterior %')),
                TextColumn::make('road_test_percent')->label(__('Road Test %')),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->recordActions([
                ViewAction::make()->url(fn(ReportModel $record): string => ReportResource::getUrl('view', ['record' => $record])),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageReports::route('/'),
            'view' => ViewReport::route('/{record}'),
        ];
    }
}
