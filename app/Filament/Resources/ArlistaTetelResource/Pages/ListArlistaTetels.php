<?php

namespace App\Filament\Resources\ArlistaTetelResource\Pages;

use App\Filament\Exports\ArlistaTetelExporter;
use App\Filament\Imports\ArlistaTetelImporter;
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
            Actions\ImportAction::make()
                ->importer(ArlistaTetelImporter::class),
            Actions\ExportAction::make()
                ->exporter(ArlistaTetelExporter::class),
        ];
    }
}
