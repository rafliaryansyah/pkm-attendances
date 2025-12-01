<?php

namespace App\Filament\Resources\BannerResource\Pages;

use App\Filament\Resources\BannerResource;
use App\Models\Banner;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateBanner extends CreateRecord
{
    protected static string $resource = BannerResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function beforeValidate(): void
    {
        $activeBannersCount = Banner::active()->count();

        if ($activeBannersCount >= 10 && ($this->data['is_active'] ?? false)) {
            Notification::make()
                ->warning()
                ->title('Peringatan')
                ->body('Maksimal 10 banner aktif. Banner akan dibuat sebagai tidak aktif.')
                ->send();

            $this->data['is_active'] = false;
        }
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Banner berhasil dibuat';
    }
}
