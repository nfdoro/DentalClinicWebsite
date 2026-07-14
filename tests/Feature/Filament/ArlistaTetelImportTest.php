<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\ArlistaTetelResource\Pages\ListArlistaTetels;
use App\Models\ArlistaTetel;
use App\Models\Kategoria;
use App\Models\User;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use Tests\TestCase;

class ArlistaTetelImportTest extends TestCase
{
    use RefreshDatabase;

    private const COLUMN_MAP = [
        'id' => 'id',
        'kategoria' => 'kategoria',
        'muveletnev' => 'muveletnev',
        'ar' => 'ar',
        'kiegeszites' => 'kiegeszites',
    ];

    public function test_it_creates_new_records_for_rows_without_an_id(): void
    {
        $this->actingAs(User::factory()->create());
        $kategoria = Kategoria::factory()->create(['nev' => 'Fogszabályozás']);

        $csv = "id,kategoria,muveletnev,ar,kiegeszites\n"
            . ",Fogszabályozás,Konzultáció,15000,első alkalom\n";

        Livewire::test(ListArlistaTetels::class)
            ->callAction('import', data: [
                'file' => UploadedFile::fake()->createWithContent('arlista.csv', $csv),
                'columnMap' => self::COLUMN_MAP,
            ])
            ->assertHasNoActionErrors();

        $this->assertDatabaseHas('adatok', [
            'kategoria_id' => $kategoria->id,
            'muveletnev' => 'Konzultáció',
            'ar' => '15000',
            'kiegeszites' => 'első alkalom',
        ]);
    }

    public function test_it_updates_existing_records_by_id(): void
    {
        $this->actingAs(User::factory()->create());
        $kategoria = Kategoria::factory()->create(['nev' => 'Fogszabályozás']);
        $tetel = ArlistaTetel::factory()->create([
            'kategoria_id' => $kategoria->id,
            'muveletnev' => 'Régi név',
            'ar' => '10000',
        ]);

        $csv = "id,kategoria,muveletnev,ar,kiegeszites\n"
            . "{$tetel->id},Fogszabályozás,Régi név,20000,\n";

        Livewire::test(ListArlistaTetels::class)
            ->callAction('import', data: [
                'file' => UploadedFile::fake()->createWithContent('arlista.csv', $csv),
                'columnMap' => self::COLUMN_MAP,
            ])
            ->assertHasNoActionErrors();

        $this->assertDatabaseCount('adatok', 1);
        $this->assertDatabaseHas('adatok', [
            'id' => $tetel->id,
            'ar' => '20000',
        ]);
    }

    public function test_it_reports_failed_rows_for_unknown_kategoria_without_crashing(): void
    {
        $this->actingAs(User::factory()->create());
        Kategoria::factory()->create(['nev' => 'Fogszabályozás']);

        $csv = "id,kategoria,muveletnev,ar,kiegeszites\n"
            . ",Nemletezo Kategoria,Ismeretlen,5000,\n"
            . ",Fogszabályozás,Konzultáció,15000,\n";

        Livewire::test(ListArlistaTetels::class)
            ->callAction('import', data: [
                'file' => UploadedFile::fake()->createWithContent('arlista.csv', $csv),
                'columnMap' => self::COLUMN_MAP,
            ])
            ->assertHasNoActionErrors();

        $import = Import::query()->latest('id')->first();

        $this->assertNotNull($import);
        $this->assertSame(1, $import->successful_rows);
        $this->assertSame(1, $import->getFailedRowsCount());
        $this->assertDatabaseHas('adatok', ['muveletnev' => 'Konzultáció']);
        $this->assertDatabaseMissing('adatok', ['muveletnev' => 'Ismeretlen']);
    }
}
