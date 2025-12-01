<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceRevisionResource\Pages;
use App\Models\AttendanceRevision;
use App\Services\ApprovalService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AttendanceRevisionResource extends Resource
{
    protected static ?string $model = AttendanceRevision::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';

    protected static ?string $navigationLabel = 'Revisi Absensi';

    protected static ?string $modelLabel = 'Revisi Absensi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable(),
                Forms\Components\DatePicker::make('date')
                    ->required()
                    ->label('Tanggal'),
                Forms\Components\DateTimePicker::make('proposed_clock_in')
                    ->required()
                    ->label('Usulan Check In'),
                Forms\Components\DateTimePicker::make('proposed_clock_out')
                    ->required()
                    ->label('Usulan Check Out'),
                Forms\Components\Textarea::make('reason')
                    ->required()
                    ->label('Alasan Revisi')
                    ->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('proposed_clock_in')
                    ->label('Usulan Check In')
                    ->dateTime('H:i'),
                Tables\Columns\TextColumn::make('proposed_clock_out')
                    ->label('Usulan Check Out')
                    ->dateTime('H:i'),
                Tables\Columns\TextColumn::make('reason')
                    ->label('Alasan')
                    ->limit(50),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (AttendanceRevision $record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(function (AttendanceRevision $record) {
                        $service = app(ApprovalService::class);
                        $service->approveRevision($record);

                        Notification::make()
                            ->title('Revisi Absensi Disetujui')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (AttendanceRevision $record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(function (AttendanceRevision $record) {
                        $service = app(ApprovalService::class);
                        $service->rejectRevision($record);

                        Notification::make()
                            ->title('Revisi Absensi Ditolak')
                            ->warning()
                            ->send();
                    }),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
