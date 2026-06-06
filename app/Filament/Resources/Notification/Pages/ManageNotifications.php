<?php

namespace App\Filament\Resources\Notification\Pages;

use App\Filament\Resources\Notification\NotificationResource;
use App\Models\Notification as NotificationModel;
use App\Models\User;
use App\Services\FCMService;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class ManageNotifications extends ManageRecords
{
    protected static string $resource = NotificationResource::class;

    public function getTabs(): array
    {
        return [
            __('All') => Tab::make()
                ->badge(NotificationModel::count()),
            __('System') => Tab::make()
                ->badge(NotificationModel::where('admin_sent', false)->count())
                ->query(fn($query) => $query->where('admin_sent', false)),
            __('Custom') => Tab::make()
                ->badge(NotificationModel::where('admin_sent', true)->count())
                ->query(fn($query) => $query->where('admin_sent', true)),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->form([
                    Toggle::make('send_to_all')
                        ->label(__('Send to All Users'))
                        ->live(),
                    \Filament\Forms\Components\Select::make('user_ids')
                        ->label(__('Users'))
                        ->multiple()
                        ->searchable()
                        ->options(fn() => User::pluck('name', 'id'))
                        ->visible(fn($get) => !$get('send_to_all')),
                    TextInput::make('title')->label(__('Title (English)'))->required(),
                    TextInput::make('body')->label(__('Body (English)'))->required(),
                    TextInput::make('title_ar')->label(__('Title (Arabic)'))->required(),
                    TextInput::make('body_ar')->label(__('Body (Arabic)'))->required(),
                ])
                ->using(function (array $data): NotificationModel {
                    $users = ($data['send_to_all'] ?? false)
                        ? User::all()
                        : User::whereIn('id', $data['user_ids'] ?? [])->get();
                    $date = now()->format('Y-m-d H:i');
                    $firstNotification = null;
                    foreach ($users as $user) {
                        $n = NotificationModel::create([
                            'user_id' => $user->id,
                            'title' => $data['title'],
                            'body' => $data['body'],
                            'title_ar' => $data['title_ar'],
                            'body_ar' => $data['body_ar'],
                            'date' => $date,
                            'admin_sent' => true,
                        ]);
                        app(FCMService::class)->send($user, $n->title, $n->body, null, 'admin');
                        $firstNotification ??= $n;
                    }
                    return $firstNotification ?? NotificationModel::latest()->first();
                })
                ->createAnother(),
        ];
    }
}
