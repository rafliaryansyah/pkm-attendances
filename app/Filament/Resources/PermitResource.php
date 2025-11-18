<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermitResource\Pages;
use App\Filament\Resources\PermitResource\RelationManagers;
use App\Models\Permit;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Carbon\Carbon;

class PermitResource extends Resource
{
    protected static ?string $model = Permit::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Approvals';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->disabled(fn (string $operation): bool => $operation === 'edit'),
                Forms\Components\Select::make('type')
                    ->options([
                        'sick' => 'Sick Leave',
                        'permit' => 'Permit/Other',
                    ])
                    ->required()
                    ->disabled(fn (string $operation): bool => $operation === 'edit'),
                Forms\Components\DatePicker::make('start_date')
                    ->required()
                    ->disabled(fn (string $operation): bool => $operation === 'edit'),
                Forms\Components\DatePicker::make('end_date')
                    ->required()
                    ->disabled(fn (string $operation): bool => $operation === 'edit'),
                Forms\Components\Textarea::make('reason')
                    ->required()
                    ->rows(3)
                    ->disabled(fn (string $operation): bool => $operation === 'edit'),
                Forms\Components\FileUpload::make('attachment')
                    ->directory('permits')
                    ->image()
                    ->maxSize(5120)
                    ->disabled(fn (string $operation): bool => $operation === 'edit'),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->required()
                    ->disabled(),
                Forms\Components\Textarea::make('admin_note')
                    ->rows(2)
                    ->placeholder('Add notes for approval/rejection'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'danger' => 'sick',
                        'warning' => 'permit',
                    ])
                    ->formatStateUsing(fn (string $state): string => $state === 'sick' ? 'Sick Leave' : 'Permit'),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reason')
                    ->limit(50)
                    ->wrap(),
                Tables\Columns\ImageColumn::make('attachment')
                    ->circular(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'sick' => 'Sick Leave',
                        'permit' => 'Permit',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Permit $record): bool => $record->status === 'pending')
                    ->form([
                        Forms\Components\Textarea::make('admin_note')
                            ->label('Approval Note (Optional)')
                            ->rows(2),
                    ])
                    ->action(function (Permit $record, array $data): void {
                        $record->update([
                            'status' => 'approved',
                            'admin_note' => $data['admin_note'] ?? null,
                        ]);

                        // Create attendance records for approved permits
                        $startDate = Carbon::parse($record->start_date);
                        $endDate = Carbon::parse($record->end_date);

                        while ($startDate->lte($endDate)) {
                            Attendance::updateOrCreate(
                                [
                                    'user_id' => $record->user_id,
                                    'date' => $startDate->format('Y-m-d'),
                                ],
                                [
                                    'status' => $record->type,
                                    'clock_in' => $startDate->copy()->setTime(6, 30),
                                    'clock_out' => $startDate->copy()->setTime(15, 0),
                                    'lat_in' => 0,
                                    'long_in' => 0,
                                    'lat_out' => 0,
                                    'long_out' => 0,
                                    'note' => "Auto-created from {$record->type} permit",
                                ]
                            );
                            $startDate->addDay();
                        }

                        Notification::make()
                            ->success()
                            ->title('Permit Approved')
                            ->body('Attendance records have been created automatically.')
                            ->send();
                    }),
                Tables\Actions\Action::make('reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Permit $record): bool => $record->status === 'pending')
                    ->form([
                        Forms\Components\Textarea::make('admin_note')
                            ->label('Rejection Reason')
                            ->required()
                            ->rows(2),
                    ])
                    ->action(function (Permit $record, array $data): void {
                        $record->update([
                            'status' => 'rejected',
                            'admin_note' => $data['admin_note'],
                        ]);

                        Notification::make()
                            ->danger()
                            ->title('Permit Rejected')
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermits::route('/'),
            'create' => Pages\CreatePermit::route('/create'),
            'edit' => Pages\EditPermit::route('/{record}/edit'),
        ];
    }
}
