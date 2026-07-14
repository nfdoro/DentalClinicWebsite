<?php

namespace App\Filament\Exports;

use App\Models\ArlistaTetel;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ArlistaTetelExporter extends Exporter
{
    protected static ?string $model = ArlistaTetel::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('kategoria.nev')
                ->label('Kategória'),
            ExportColumn::make('muveletnev')
                ->label('Beavatkozás'),
            ExportColumn::make('ar')
                ->label('Ár (Ft)'),
            ExportColumn::make('kiegeszites')
                ->label('Kiegészítés'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Az árlista exportálása befejeződött, ' . number_format($export->successful_rows) . ' ' . str('sor')->plural($export->successful_rows) . ' exportálva.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('sor')->plural($failedRowsCount) . ' exportálása sikertelen.';
        }

        return $body;
    }
}
