<?php

namespace App\Filament\Resources\Issue;

use App\Filament\Resources\Issue\Pages\ManageIssues;
use App\Filament\Resources\Users\UserResource;
use App\Models\Issue;
use App\Models\Notification as NotificationModel;
use App\Services\FCMService;
use BackedEnum;
use UnitEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class IssueResource extends Resource
{
    protected static ?string $model = Issue::class;
    protected static ?string $slug = 'issues';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;
    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return __('nav.Issues');
    }

    public static function getModelLabel(): string
    {
        return __('Issue');
    }

    public static function getPluralModelLabel(): string
    {
        return __('nav.Issues');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('nav.System');
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Issue::where('status', 'new')->count();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->label(__('ID')),
                TextColumn::make('user.name')
                    ->searchable()
                    ->label(__('User'))
                    ->url(fn(Issue $record) => UserResource::getUrl('index') . '?search=' . urlencode($record->user?->name ?? '')),
                TextColumn::make('user.email')
                    ->label(__('Email'))
                    ->copyable()
                    ->copyMessage(__('Email copied')),
                TextColumn::make('user.phone')
                    ->label(__('Phone'))
                    ->copyable()
                    ->copyMessage(__('Phone copied')),
                BadgeColumn::make('issue_type')->searchable()->label(__('Issue Type')),
                TextColumn::make('description')->limit(80)->label(__('Description')),
                BadgeColumn::make('status')->label(__('Status'))->color(fn(string $state): string => match ($state) { 'new' => 'warning', 'solved' => 'success', default => 'gray' }),
                TextColumn::make('created_at')->dateTime()->label(__('Reported At')),
            ])
            ->filters([
                SelectFilter::make('issue_type')->options(Issue::TYPES),
            ])
            ->recordActions([
                ViewAction::make()
                    ->mutateRecordDataUsing(fn(array $data, Issue $record): array => array_merge($data, [
                        'user_name' => $record->user?->name ?? '-',
                        'user_email' => $record->user?->email ?? '-',
                        'user_phone' => $record->user?->phone ?? '-',
                    ]))
                    ->form([
                        TextInput::make('id')->label(__('ID')),
                        TextInput::make('issue_type')->label(__('Issue Type')),
                        TextInput::make('user_name')->label(__('User')),
                        TextInput::make('user_email')->label(__('Email')),
                        TextInput::make('user_phone')->label(__('Phone')),
                        TextInput::make('description')->label(__('Description')),
                        TextInput::make('status')->label(__('Status')),
                        TextInput::make('created_at')->label(__('Reported At')),
                    ]),
                Action::make('markSolved')
                    ->label(__('Mark Solved'))
                    ->color('success')
                    ->icon(Heroicon::OutlinedCheck)
                    ->visible(fn(Issue $record) => $record->status !== 'solved')
                    ->action(function (Issue $record) {
                        $record->update(['status' => 'solved']);
                        $user = $record->user;
                        if ($user) {
                            NotificationModel::create([
                                'user_id' => $user->id,
                                'title' => 'Your issue has been solved',
                                'body' => 'Your issue has been reviewed and solved. Thank you for your patience.',
                                'title_ar' => 'تم حل مشكلتك',
                                'body_ar' => 'تم مراجعة مشكلتك وحلها، شكراً لصبرك.',
                                'date' => now()->format('Y-m-d H:i'),
                                'admin_sent' => false,
                            ]);
                            app(FCMService::class)->send(
                                $user,
                                'Your issue has been solved',
                                'Your issue has been reviewed and solved. Thank you for your patience.',
                                null,
                                null,
                                null,
                                'تم حل مشكلتك',
                                'تم مراجعة مشكلتك وحلها، شكراً لصبرك.',
                            );
                        }
                    })
                    ->requiresConfirmation(),
                DeleteAction::make(),
            ])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return ['index' => ManageIssues::route('/')];
    }
}
