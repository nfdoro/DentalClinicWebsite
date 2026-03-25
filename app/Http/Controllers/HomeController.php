<?php

namespace App\Http\Controllers;

use App\Models\Kategoria;

class HomeController extends Controller
{
    public function index()
    {
        $szolgaltatasok = Kategoria::where('szolgaltatas', true)->get();

        $galeriaKategoriak = Kategoria::with(['galeria' => function ($q) {
            $q->whereNotNull('fajlnev');
        }])->whereHas('galeria', function ($q) {
            $q->whereNotNull('fajlnev');
        })->get();

        return view('home', compact('szolgaltatasok', 'galeriaKategoriak'));
    }
}
