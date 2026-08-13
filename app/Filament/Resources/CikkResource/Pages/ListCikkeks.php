<?php

namespace App\Filament\Resources\CikkResource\Pages;

use App\Filament\Resources\CikkResource;
use App\Support\MarkdownCikkImporter;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListCikkeks extends ListRecords
{
    protected static string $resource = CikkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('importMd')
                ->label('Importálás MD-ből')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('gray')
                ->modalHeading('Cikk importálása Markdown fájlból')
                ->modalSubmitActionLabel('Importálás')
                ->form([
                    Forms\Components\FileUpload::make('md_fajl')
                        ->label('Markdown fájl (.md)')
                        ->storeFiles(false)
                        ->required()
                        ->helperText('YAML fejléc (title, excerpt, meta_description, focus_keyword, slug) + Markdown törzs. A cikk vázlatként jön létre, utána a szerkesztőben állíthatod be a képet és a közzététel idejét.'),
                ])
                ->action(function (array $data) {
                    $upload = $data['md_fajl'];
                    if (is_array($upload)) {
                        $upload = reset($upload);
                    }

                    try {
                        $cikk = app(MarkdownCikkImporter::class)->import($upload->get());
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title('Nem sikerült importálni')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();

                        return;
                    }

                    Notification::make()
                        ->title('Cikk importálva vázlatként')
                        ->body('Nézd át, tölts fel borítóképet, és állítsd be a közzététel idejét.')
                        ->success()
                        ->send();

                    return redirect(CikkResource::getUrl('edit', ['record' => $cikk]));
                }),

            Actions\CreateAction::make(),
        ];
    }
}
