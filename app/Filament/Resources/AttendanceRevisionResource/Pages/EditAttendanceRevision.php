<?php

namespace App\Filament\Resources\AttendanceRevisionResource\Pages;

use App\Filament\Resources\AttendanceRevisionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAttendanceRevision extends EditRecord
{
    protected static string $resource = AttendanceRevisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
