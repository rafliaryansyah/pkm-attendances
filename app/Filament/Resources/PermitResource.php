<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermitResource\Pages;
use App\Models\Permit;
use App\Services\ApprovalService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PermitResource extends Resource
{
    protected static ?string $model = Permit::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Izin/Sakit';

    protected static ?string $modelLabel = 'Izin/Sakit';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable(),
                Forms\Components\Select::make('type')
                    ->options([
                        'sick' => 'Sakit',
                        'permit' => 'Izin',
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('start_date')
                    ->required()
                    ->label('Tanggal Mulai'),
                Forms\Components\DatePicker::make('end_date')
                    ->required()
                    ->label('Tanggal Selesai'),
                Forms\Components\Textarea::make('reason')
                    ->required()
                    ->label('Alasan')
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('attachment')
                    ->label('Lampiran')
                    ->directory('permits'),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending')
                    ->required(),
                Forms\Components\Textarea::make('admin_note')
                    ->label('Catatan Admin')
                    ->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'sick' => 'danger',
                        'permit' => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'sick' => 'Sakit',
                        'permit' => 'Izin',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Tanggal Mulai')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Tanggal Selesai')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('reason')
                    ->label('Alasan')
                    ->limit(50)
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    })
                    ->sortable(),
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
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'sick' => 'Sakit',
                        'permit' => 'Izin',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Permit $record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Textarea::make('admin_note')
                            ->label('Catatan Admin (Opsional)')
                            ->rows(3),
                    ])
                    ->action(function (Permit $record, array $data) {
                        $service = app(ApprovalService::class);
                        $service->approvePermit($record, $data['admin_note'] ?? null);

                        Notification::make()
                            ->title('Izin/Sakit Disetujui')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Permit $record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Textarea::make('admin_note')
                            ->label('Alasan Penolakan')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (Permit $record, array $data) {
                        $service = app(ApprovalService::class);
                        $service->rejectPermit($record, $data['admin_note']);

                        Notification::make()
                            ->title('Izin/Sakit Ditolak')
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
            'index' => Pages\ListPermits::route('/'),
            'create' => Pages\CreatePermit::route('/create'),
            'edit' => Pages\EditPermit::route('/{record}/edit'),
        ];
    }
}
