<?php

namespace App\Filament\Resources\CikkResource\Pages;

use App\Filament\Resources\CikkResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCikk extends EditRecord
{
    protected static string $resource = CikkResource::class;

    public function getTitle(): string
    {
        return 'Cikk szerkesztése';
    }

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
