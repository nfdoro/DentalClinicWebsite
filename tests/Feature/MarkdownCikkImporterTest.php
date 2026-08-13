<?php

namespace Tests\Feature;

use App\Models\Cikk;
use App\Support\MarkdownCikkImporter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarkdownCikkImporterTest extends TestCase
{
    use RefreshDatabase;

    private function md(): string
    {
        return <<<'MD'
        ---
        title: "Miért egyék meg a gyerekek a kenyér héját is?"
        meta_description: "Fogorvosi tanácsok Miskolcról a kenyérhéj hatásáról."
        slug: "kenyerhej-hatasa-a-fogakra"
        focus_keyword: "kenyérhéj hatása a fogakra"
        excerpt: "A kenyérhéj rágása jót tesz a gyerekek fogainak és állkapcsának."
        ---

        # Miért egyék meg a gyerekek a kenyér héját is?

        Sok szülő ismerős helyzet: a gyerek megeszi a **puha belsejét**.

        ## Erősíti a rágóizmokat

        A keményebb héj intenzívebb rágást igényel.
        MD;
    }

    public function test_importalja_a_fejlecet_es_a_torzset(): void
    {
        $cikk = app(MarkdownCikkImporter::class)->import($this->md());

        $this->assertSame('Miért egyék meg a gyerekek a kenyér héját is?', $cikk->cim);
        $this->assertSame('kenyerhej-hatasa-a-fogakra', $cikk->slug);
        $this->assertSame('Fogorvosi tanácsok Miskolcról a kenyérhéj hatásáról.', $cikk->meta_leiras);
        $this->assertSame('kenyérhéj hatása a fogakra', $cikk->kulcsszavak);
        $this->assertStringContainsString('A kenyérhéj rágása', $cikk->bevezeto);
        $this->assertDatabaseHas('cikkek', ['slug' => 'kenyerhej-hatasa-a-fogakra']);
    }

    public function test_a_torzs_htmle_alakul_es_a_vezeto_h1_elmarad(): void
    {
        $cikk = app(MarkdownCikkImporter::class)->import($this->md());

        // A cím a törzsből eltűnik (nincs dupla főcím), az alcímek megmaradnak.
        $this->assertStringNotContainsString('<h1', $cikk->tartalom);
        $this->assertStringContainsString('<h2>Erősíti a rágóizmokat</h2>', $cikk->tartalom);
        $this->assertStringContainsString('<strong>puha belsejét</strong>', $cikk->tartalom);
    }

    public function test_datum_nelkul_vazlat_marad(): void
    {
        $cikk = app(MarkdownCikkImporter::class)->import($this->md());

        $this->assertNull($cikk->published_at);
        $this->assertSame('Vázlat', $cikk->allapot);
    }

    public function test_utkozo_slug_eseten_egyedive_teszi(): void
    {
        Cikk::create([
            'cim' => 'Meglévő', 'slug' => 'kenyerhej-hatasa-a-fogakra',
            'bevezeto' => 'x', 'tartalom' => '<p>x</p>',
        ]);

        $cikk = app(MarkdownCikkImporter::class)->import($this->md());

        $this->assertSame('kenyerhej-hatasa-a-fogakra-2', $cikk->slug);
    }

    public function test_cim_nelkul_hibat_dob(): void
    {
        $this->expectException(\RuntimeException::class);

        app(MarkdownCikkImporter::class)->import("---\nexcerpt: nincs cim\n---\n\nTörzs.");
    }
}
