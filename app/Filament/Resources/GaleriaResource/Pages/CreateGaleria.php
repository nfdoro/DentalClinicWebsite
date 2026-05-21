<?php

namespace App\Filament\Resources\GaleriaResource\Pages;

use App\Filament\Resources\GaleriaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGaleria extends CreateRecord
{
    protected static string $resource = GaleriaResource::class;

    public function getTitle(): string
    {
        return 'Új kép hozzáadása';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
