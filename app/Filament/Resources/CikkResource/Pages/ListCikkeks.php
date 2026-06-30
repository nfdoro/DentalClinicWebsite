<?php

namespace App\Filament\Resources\CikkResource\Pages;

use App\Filament\Resources\CikkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCikkeks extends ListRecords
{
    protected static string $resource = CikkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
