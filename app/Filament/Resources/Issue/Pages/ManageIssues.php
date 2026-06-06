<?php

namespace App\Filament\Resources\Issue\Pages;

use App\Filament\Resources\Issue\IssueResource;
use App\Models\Issue;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ManageIssues extends ManageRecords
{
    protected static string $resource = IssueResource::class;

    public function getTabs(): array
    {
        return [
            __('All') => Tab::make()
                ->badge(Issue::count()),
            __('New') => Tab::make()
                ->badge(Issue::where('status', 'new')->count())
                ->badgeColor('warning')
                ->query(fn($query) => $query->where('status', 'new')),
            __('Solved') => Tab::make()
                ->badge(Issue::where('status', 'solved')->count())
                ->badgeColor('success')
                ->query(fn($query) => $query->where('status', 'solved')),
        ];
    }
}
