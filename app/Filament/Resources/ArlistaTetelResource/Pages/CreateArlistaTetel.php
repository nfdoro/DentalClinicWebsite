<?php

namespace App\Filament\Resources\ArlistaTetelResource\Pages;

use App\Filament\Resources\ArlistaTetelResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateArlistaTetel extends CreateRecord
{
    protected static string $resource = ArlistaTetelResource::class;

    public function getTitle(): string
    {
        return 'Új árlista tétel hozzáadása';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
