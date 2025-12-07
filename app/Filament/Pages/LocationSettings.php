<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class LocationSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static string $view = 'filament.pages.location-settings';

    protected static ?string $navigationLabel = 'Pengaturan Lokasi';

    protected static ?string $title = 'Pengaturan Lokasi Absensi';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?int $navigationSort = 1;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'location_name' => Setting::get('attendance_location_name'),
            'location_lat' => Setting::get('attendance_location_lat'),
            'location_long' => Setting::get('attendance_location_long'),
            'radius' => Setting::get('attendance_radius', 100),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Lokasi')
                    ->description('Tentukan lokasi dan radius untuk sistem absensi')
                    ->schema([
                        TextInput::make('location_name')
                            ->label('Nama Lokasi')
                            ->placeholder('contoh: Kantor Pusat, Gedung A, dll')
                            ->maxLength(255),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('location_lat')
                                    ->label('Latitude')
                                    ->numeric()
                                    ->step(0.00000001)
                                    ->placeholder('-6.200000')
                                    ->helperText('Koordinat latitude lokasi absensi')
                                    ->required(),

                                TextInput::make('location_long')
                                    ->label('Longitude')
                                    ->numeric()
                                    ->step(0.00000001)
                                    ->placeholder('106.816666')
                                    ->helperText('Koordinat longitude lokasi absensi')
                                    ->required(),
                            ]),

                        TextInput::make('radius')
                            ->label('Radius (meter)')
                            ->numeric()
                            ->minValue(10)
                            ->maxValue(5000)
                            ->default(100)
                            ->required()
                            ->helperText('Jarak maksimal dari titik lokasi untuk bisa absen'),
                    ])
                    ->columns(1),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Pengaturan')
                ->action('save')
                ->color('primary'),
            
            Action::make('reset')
                ->label('Reset Lokasi')
                ->action('resetLocation')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Reset Lokasi Absensi')
                ->modalDescription('Apakah Anda yakin ingin mereset lokasi absensi? User tidak akan bisa absen sampai lokasi diatur kembali.')
                ->modalSubmitActionLabel('Ya, Reset'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Setting::set('attendance_location_name', $data['location_name']);
        Setting::set('attendance_location_lat', $data['location_lat']);
        Setting::set('attendance_location_long', $data['location_long']);
        Setting::set('attendance_radius', $data['radius']);

        Notification::make()
            ->title('Pengaturan Tersimpan')
            ->success()
            ->body('Lokasi absensi berhasil diperbarui.')
            ->send();
    }

    public function resetLocation(): void
    {
        Setting::set('attendance_location_name', null);
        Setting::set('attendance_location_lat', null);
        Setting::set('attendance_location_long', null);
        Setting::set('attendance_radius', 100);

        $this->form->fill([
            'location_name' => null,
            'location_lat' => null,
            'location_long' => null,
            'radius' => 100,
        ]);

        Notification::make()
            ->title('Lokasi Direset')
            ->warning()
            ->body('Lokasi absensi telah direset. User tidak dapat melakukan absensi sampai lokasi diatur kembali.')
            ->send();
    }

    public function getCurrentLocation(): ?array
    {
        return Setting::getAttendanceLocation();
    }
}
