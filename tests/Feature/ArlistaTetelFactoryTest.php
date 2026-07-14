<?php

namespace Tests\Feature;

use App\Models\ArlistaTetel;
use App\Models\Kategoria;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArlistaTetelFactoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_arlista_tetel_factory_creates_a_valid_record_with_a_kategoria(): void
    {
        $arlistaTetel = ArlistaTetel::factory()->create();

        $this->assertDatabaseCount('adatok', 1);
        $this->assertInstanceOf(Kategoria::class, $arlistaTetel->kategoria);
    }
}
