<?php

namespace App\Filament\Imports;

use App\Models\ArlistaTetel;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Database\Eloquent\Model;

class ArlistaTetelImporter extends Importer
{
    protected static ?string $model = ArlistaTetel::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('id')
                ->label('ID')
                ->integer(),
            ImportColumn::make('kategoria')
                ->label('Kategória')
                ->relationship(resolveUsing: 'nev')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('muveletnev')
                ->label('Beavatkozás')
                ->requiredMapping()
                ->rules(['required', 'max:1000']),
            ImportColumn::make('ar')
                ->label('Ár')
                ->requiredMapping()
                ->rules(['required', 'max:100']),
            ImportColumn::make('kiegeszites')
                ->label('Kiegészítés')
                ->rules(['max:200']),
        ];
    }

    public function resolveRecord(): ?Model
    {
        if (filled($this->data['id'] ?? null)) {
            return ArlistaTetel::find($this->data['id']) ?? new ArlistaTetel();
        }

        return new ArlistaTetel();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Az árlista importálása befejeződött, ' . number_format($import->successful_rows) . ' ' . str('sor')->plural($import->successful_rows) . ' importálva.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('sor')->plural($failedRowsCount) . ' importálása sikertelen.';
        }

        return $body;
    }
}
