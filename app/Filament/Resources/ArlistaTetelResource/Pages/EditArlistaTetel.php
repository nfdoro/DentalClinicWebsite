<?php

namespace App\Filament\Resources\ArlistaTetelResource\Pages;

use App\Filament\Resources\ArlistaTetelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArlistaTetel extends EditRecord
{
    protected static string $resource = ArlistaTetelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
