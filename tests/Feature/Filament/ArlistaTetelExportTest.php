<?php

namespace Tests\Feature\Filament;

use App\Filament\Resources\ArlistaTetelResource\Pages\ListArlistaTetels;
use App\Models\ArlistaTetel;
use App\Models\User;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ArlistaTetelExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_exports_all_arlista_tetel_records(): void
    {
        $this->actingAs(User::factory()->create());

        ArlistaTetel::factory()->count(3)->create();

        Livewire::test(ListArlistaTetels::class)
            ->callAction('export')
            ->assertHasNoActionErrors();

        $export = Export::query()->latest('id')->first();

        $this->assertNotNull($export);
        $this->assertSame(3, $export->successful_rows);
        $this->assertSame(0, $export->getFailedRowsCount());
    }
}
