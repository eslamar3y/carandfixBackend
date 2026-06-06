<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class DateWidget extends Widget
{
    protected string $view = 'filament.widgets.date';

    protected static ?int $sort = -2;

    public static function canView(): bool
    {
        return auth()->user()?->can('View:DateWidget');
    }

    public string $date = '';

    public function mount(): void
    {
        $this->date = now()->translatedFormat('l, j F Y');
    }
}
