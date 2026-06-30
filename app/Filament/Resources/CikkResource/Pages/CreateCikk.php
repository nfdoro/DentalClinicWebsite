<?php

namespace App\Filament\Resources\CikkResource\Pages;

use App\Filament\Resources\CikkResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCikk extends CreateRecord
{
    protected static string $resource = CikkResource::class;

    public function getTitle(): string
    {
        return 'Új blog cikk';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
