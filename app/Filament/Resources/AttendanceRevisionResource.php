<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceRevisionResource\Pages;
use App\Filament\Resources\AttendanceRevisionResource\RelationManagers;
use App\Models\AttendanceRevision;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttendanceRevisionResource extends Resource
{
    protected static ?string $model = AttendanceRevision::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationGroup = 'Approvals';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Attendance Revisions';

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
                Forms\Components\DatePicker::make('date')
                    ->required()
                    ->disabled(fn (string $operation): bool => $operation === 'edit'),
                Forms\Components\DateTimePicker::make('proposed_clock_in')
                    ->required()
                    ->disabled(fn (string $operation): bool => $operation === 'edit'),
                Forms\Components\DateTimePicker::make('proposed_clock_out')
                    ->disabled(fn (string $operation): bool => $operation === 'edit'),
                Forms\Components\Textarea::make('reason')
                    ->required()
                    ->rows(3)
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
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('proposed_clock_in')
                    ->dateTime()
                    ->sortable()
                    ->label('Proposed Clock In'),
                Tables\Columns\TextColumn::make('proposed_clock_out')
                    ->dateTime()
                    ->sortable()
                    ->label('Proposed Clock Out'),
                Tables\Columns\TextColumn::make('reason')
                    ->limit(50)
                    ->wrap(),
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
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (AttendanceRevision $record): bool => $record->status === 'pending')
                    ->form([
                        Forms\Components\Textarea::make('admin_note')
                            ->label('Approval Note (Optional)')
                            ->rows(2),
                    ])
                    ->action(function (AttendanceRevision $record, array $data): void {
                        $record->update([
                            'status' => 'approved',
                            'admin_note' => $data['admin_note'] ?? null,
                        ]);

                        // Update or create the attendance record
                        Attendance::updateOrCreate(
                            [
                                'user_id' => $record->user_id,
                                'date' => $record->date,
                            ],
                            [
                                'clock_in' => $record->proposed_clock_in,
                                'clock_out' => $record->proposed_clock_out,
                                'is_revision' => true,
                                'lat_in' => 0,
                                'long_in' => 0,
                                'lat_out' => 0,
                                'long_out' => 0,
                                'note' => "Revised: {$record->reason}",
                                'status' => 'present',
                            ]
                        );

                        Notification::make()
                            ->success()
                            ->title('Revision Approved')
                            ->body('Attendance record has been updated.')
                            ->send();
                    }),
                Tables\Actions\Action::make('reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (AttendanceRevision $record): bool => $record->status === 'pending')
                    ->form([
                        Forms\Components\Textarea::make('admin_note')
                            ->label('Rejection Reason')
                            ->required()
                            ->rows(2),
                    ])
                    ->action(function (AttendanceRevision $record, array $data): void {
                        $record->update([
                            'status' => 'rejected',
                            'admin_note' => $data['admin_note'],
                        ]);

                        Notification::make()
                            ->danger()
                            ->title('Revision Rejected')
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
            'index' => Pages\ListAttendanceRevisions::route('/'),
            'create' => Pages\CreateAttendanceRevision::route('/create'),
            'edit' => Pages\EditAttendanceRevision::route('/{record}/edit'),
        ];
    }
}
