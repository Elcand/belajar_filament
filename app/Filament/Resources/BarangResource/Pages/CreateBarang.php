<?php

namespace App\Filament\Resources\BarangResource\Pages;

use App\Filament\Resources\BarangResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateBarang extends CreateRecord
{
    protected static string $resource = BarangResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Sukses')
            ->icon('heroicon-o-document-check')
            ->color('success')
            ->duration(5000)
            ->body('Data Barang Berhasil Ditambahkan!');
    }
}
