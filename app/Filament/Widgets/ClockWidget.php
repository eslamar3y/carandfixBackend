<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class ClockWidget extends Widget
{
    protected string $view = 'filament.widgets.clock';

    protected static ?int $sort = -1;

    public static function canView(): bool
    {
        return auth()->user()?->can('View:ClockWidget');
    }

    public string $time = '';

    public function mount(): void
    {
        $this->refresh();
    }

    public function refresh(): void
    {
        $this->time = now()->translatedFormat('h:i:s A');
    }
}
