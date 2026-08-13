<?php

namespace App\Support;

use App\Models\Cikk;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;
use League\CommonMark\MarkdownConverter;
use RuntimeException;

/**
 * Markdown (.md) fájlból cikket készít: a fájl eleji YAML fejlécből olvassa a
 * metaadatokat, a törzset pedig HTML-lé alakítja a RichEditor mezőbe.
 *
 * Támogatott fejléc-kulcsok (angol és magyar megnevezés is elfogadott):
 *   title / cim            -> cím (kötelező)
 *   excerpt / bevezeto     -> bevezető
 *   meta_description / meta_leiras -> SEO meta leírás
 *   focus_keyword / kulcsszavak    -> kulcsszavak
 *   slug                   -> URL (üresen a címből generálva)
 *   published_at / date    -> közzététel időpontja (üresen Vázlat marad)
 */
class MarkdownCikkImporter
{
    /**
     * A .md fájl teljes tartalmából létrehoz és elment egy Cikk-et (vázlatként,
     * ha nincs published_at a fejlécben), majd visszaadja.
     */
    public function import(string $raw): Cikk
    {
        [$frontMatter, $html] = $this->parse($raw);

        $cim = trim((string) $this->pick($frontMatter, ['cim', 'cím', 'title']));
        if ($cim === '') {
            throw new RuntimeException('Hiányzik a cím. Adj meg egy "title:" (vagy "cim:") sort a fájl eleji fejlécben.');
        }

        if (trim(strip_tags($html)) === '') {
            throw new RuntimeException('A cikk törzse üres. Írj tartalmat a fejléc (---) alá.');
        }

        return Cikk::create([
            'cim' => Str::limit($cim, 255, ''),
            'slug' => $this->uniqueSlug($this->pick($frontMatter, ['slug']) ?: Str::slug($cim)),
            'bevezeto' => trim((string) $this->pick($frontMatter, ['bevezeto', 'bevezető', 'excerpt'])),
            'tartalom' => $html,
            'meta_leiras' => $this->clip($this->pick($frontMatter, ['meta_leiras', 'meta_description']), 320),
            'kulcsszavak' => $this->clip($this->pick($frontMatter, ['kulcsszavak', 'focus_keyword', 'keywords']), 500),
            'published_at' => $this->parseDatum($this->pick($frontMatter, ['published_at', 'date'])),
        ]);
    }

    /**
     * @return array{0: array<string, mixed>, 1: string} [fejléc, HTML törzs]
     */
    private function parse(string $raw): array
    {
        $environment = new Environment(['html_input' => 'strip']);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new FrontMatterExtension());

        $result = (new MarkdownConverter($environment))->convert($raw);

        $frontMatter = $result instanceof RenderedContentWithFrontMatter
            ? (array) $result->getFrontMatter()
            : [];

        return [$frontMatter, $this->stripLeadingH1($result->getContent())];
    }

    /**
     * A törzs elején lévő H1-et eltávolítja: a cikkoldal külön kiírja a címet,
     * így elkerüljük a dupla főcímet és a duplikált SEO-t.
     */
    private function stripLeadingH1(string $html): string
    {
        return trim(preg_replace('/^\s*<h1\b[^>]*>.*?<\/h1>\s*/is', '', $html, 1));
    }

    /**
     * Az első létező, nem üres kulcs értéke a fejlécből.
     */
    private function pick(array $frontMatter, array $keys): ?string
    {
        foreach ($keys as $key) {
            if (isset($frontMatter[$key]) && trim((string) $frontMatter[$key]) !== '') {
                return (string) $frontMatter[$key];
            }
        }

        return null;
    }

    private function clip(?string $value, int $max): ?string
    {
        $value = $value !== null ? trim($value) : null;

        return $value ? Str::limit($value, $max, '') : null;
    }

    private function parseDatum(?string $value): ?Carbon
    {
        if (! $value) {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable) {
            return null;
        }
    }

    private function uniqueSlug(string $base): string
    {
        $base = Str::slug($base) ?: 'cikk';
        $slug = $base;
        $i = 2;

        while (Cikk::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }
}
