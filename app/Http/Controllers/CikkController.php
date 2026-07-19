<?php

namespace App\Http\Controllers;

use App\Models\Cikk;

class CikkController extends Controller
{
    public function index()
    {
        $cikkek = Cikk::published()
            ->orderByDesc('published_at')
            ->paginate(9);

        return view('blog.index', compact('cikkek'));
    }

    public function show(string $slug)
    {
        $cikk = Cikk::published()->where('slug', $slug)->firstOrFail();

        $kapcsolodo = Cikk::published()
            ->where('id', '!=', $cikk->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        $ogKep = $this->ogKep($cikk);

        return view('blog.show', compact('cikk', 'kapcsolodo', 'ogKep'));
    }

    /**
     * A közösségi megosztáshoz (og:image) használt kép abszolút URL-je.
     *
     * A Facebook/LinkedIn fix, fekvő (kb. 1.91:1) kártyába teszi a képet, ezért az
     * álló borítóképeket levágná. Ezért:
     *  - ha nincs borítókép, a fekvő, márkás alapképet adjuk vissza;
     *  - ha van, a borítóból egy 1200x630-as, fehér kitöltésű változatot készítünk
     *    (a teljes kép belefér, nem vágódik le), és azt cache-eljük.
     */
    private function ogKep(Cikk $cikk): string
    {
        $alap = asset('images/og-share.jpg');

        if (! $cikk->boritekep) {
            return $alap;
        }

        $forras = public_path($cikk->boritekep);
        if (! is_file($forras) || ! function_exists('imagecreatetruecolor')) {
            return $alap;
        }

        $kulcs = md5($cikk->boritekep.'|'.filemtime($forras));
        $celMappa = public_path('images/blog/og');
        $celFajl = $celMappa.DIRECTORY_SEPARATOR.$kulcs.'.jpg';
        $celUrl = asset('images/blog/og/'.$kulcs.'.jpg');

        if (is_file($celFajl)) {
            return $celUrl;
        }

        if (! is_dir($celMappa) && ! @mkdir($celMappa, 0755, true) && ! is_dir($celMappa)) {
            return $alap;
        }

        return $this->keszitMegosztasiKep($forras, $celFajl) ? $celUrl : $alap;
    }

    /**
     * 1200x630-as megosztási kép a borítóból: fehér háttér, a kép teljes egészében
     * belekicsinyítve (contain), így a Facebook-kártya nem vág le belőle.
     */
    private function keszitMegosztasiKep(string $forras, string $celFajl): bool
    {
        $info = @getimagesize($forras);
        if (! $info) {
            return false;
        }

        [$sw, $sh] = $info;

        $src = match ($info[2]) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($forras),
            IMAGETYPE_PNG => @imagecreatefrompng($forras),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($forras) : false,
            default => false,
        };
        if (! $src) {
            return false;
        }

        $w = 1200;
        $h = 630;
        $canvas = imagecreatetruecolor($w, $h);
        imagefill($canvas, 0, 0, imagecolorallocate($canvas, 255, 255, 255));

        $scale = min($w / $sw, $h / $sh);
        $nw = (int) round($sw * $scale);
        $nh = (int) round($sh * $scale);
        $dx = (int) (($w - $nw) / 2);
        $dy = (int) (($h - $nh) / 2);
        imagecopyresampled($canvas, $src, $dx, $dy, 0, 0, $nw, $nh, $sw, $sh);

        $ok = imagejpeg($canvas, $celFajl, 88);
        imagedestroy($src);
        imagedestroy($canvas);

        return $ok;
    }
}
