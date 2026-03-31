<?php

namespace App\Filament\Resources\ArlistaTetelResource\Pages;

use App\Filament\Resources\ArlistaTetelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListArlistaTetels extends ListRecords
{
    protected static string $resource = ArlistaTetelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
