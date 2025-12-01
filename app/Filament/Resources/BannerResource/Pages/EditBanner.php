<?php

namespace App\Filament\Resources\BannerResource\Pages;

use App\Filament\Resources\BannerResource;
use App\Models\Banner;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditBanner extends EditRecord
{
    protected static string $resource = BannerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->label('Hapus')
                ->successNotificationTitle('Banner berhasil dihapus'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function beforeValidate(): void
    {
        $activeBannersCount = Banner::active()
            ->where('id', '!=', $this->record->id)
            ->count();

        if ($activeBannersCount >= 10 && ($this->data['is_active'] ?? false) && !$this->record->is_active) {
            Notification::make()
                ->warning()
                ->title('Peringatan')
                ->body('Maksimal 10 banner aktif. Banner tidak dapat diaktifkan.')
                ->send();

            $this->data['is_active'] = false;
        }
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Banner berhasil diupdate';
    }
}
