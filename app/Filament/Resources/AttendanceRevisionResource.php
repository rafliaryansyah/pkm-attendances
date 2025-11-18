<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceRevisionResource\Pages;
use App\Filament\Resources\AttendanceRevisionResource\RelationManagers;
use App\Models\AttendanceRevision;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttendanceRevisionResource extends Resource
{
    protected static ?string $model = AttendanceRevision::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
