<?php

namespace App\Filament\Resources\Report\Pages;

use App\Filament\Resources\Report\ReportResource;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Html;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class ViewReport extends ViewRecord
{
    protected static string $resource = ReportResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        Section::make(__('Report Details'))
                            ->schema([
                                TextEntry::make('id')->label(__('ID')),
                                TextEntry::make('serial')->label(__('Serial')),
                                TextEntry::make('car.vin_number')->label(__('Car')),
                                TextEntry::make('technician.name')->label(__('Technician')),
                                TextEntry::make('report_date')->label(__('Date')),
                                TextEntry::make('final_decision')
                                    ->badge()
                                    ->color(fn(?string $state): string => match ($state) {
                                        'approved', 'good', 'excellent' => 'success',
                                        'rejected', 'poor' => 'danger',
                                        'moderate', 'fair' => 'warning',
                                        default => 'gray',
                                    }),
                                TextEntry::make('current_mileage')->label(__('Mileage')),
                                TextEntry::make('car_options')->label(__('Car Options')),
                            ]),

                        Html::make(fn($record) => view('filament.report.gauge', ['record' => $record])),
                    ]),

                Tabs::make(__('Sections'))
                    ->tabs([
                        self::sectionTab(__('Exterior'), 'exterior', 'exterior_percent'),
                        self::sectionTab(__('Chassis & Frame'), 'chassis_frame', 'chassis_percent'),
                        self::sectionTab(__('Road Test'), 'road_test', 'road_test_percent'),
                        self::sectionTab(__('Power Train'), 'power_train', 'power_train_percent'),
                        self::sectionTab(__('Electrical System'), 'electrical_system', 'electrical_percent'),
                        self::sectionTab(__('Braking & Safety'), 'braking_safety', 'braking_percent'),
                        self::sectionTab(__('Suspension'), 'suspension', 'suspension_percent'),
                        self::sectionTab(__('AC & Cooling'), 'ac_cooling', 'ac_cooling_percent'),

                        Tab::make(__('Notes'))
                            ->schema([
                                RepeatableEntry::make('all_notes')
                                    ->schema([
                                        TextEntry::make('note'),
                                        TextEntry::make('section'),
                                    ]),
                            ]),

                        Tab::make(__('Images'))
                            ->schema([
                                Html::make(fn($record) => $record->note_images
                                    ? '<div style="display: flex; flex-wrap: wrap; gap: 8px;">' .
                                      collect($record->note_images)->map(fn($img) =>
                                          '<img src="' . asset('storage/' . $img) . '" style="max-width: 200px; max-height: 200px; border-radius: 4px; object-fit: cover;" />'
                                      )->implode('') .
                                      '</div>'
                                    : '<p>' . __('No images uploaded.') . '</p>'),
                            ]),

                        Tab::make(__('Inspection Systems'))
                            ->schema([
                                RepeatableEntry::make('inspection_systems')
                                    ->schema([
                                        TextEntry::make('name'),
                                        TextEntry::make('status')
                                            ->badge()
                                            ->color(fn(?string $state): string => match ($state) {
                                                'good', 'excellent' => 'success',
                                                'fair', 'moderate' => 'warning',
                                                'poor', 'bad' => 'danger',
                                                default => 'gray',
                                            }),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    private static function sectionTab(string $label, string $field, string $percentField): Tab
    {
        return Tab::make($label)
            ->badge(fn($record) => $record?->$percentField !== null ? "{$record->$percentField}%" : null)
            ->schema([
                Html::make(fn($record) => view('filament.report.section-items', ['items' => $record->$field])),
            ]);
    }
}
