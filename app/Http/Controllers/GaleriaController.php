<?php

namespace App\Http\Controllers;

use App\Models\Kategoria;

class GaleriaController extends Controller
{
    public function index()
    {
        $kategoriak = Kategoria::with(['galeria' => function ($q) {
            $q->whereNotNull('fajlnev');
        }])->whereHas('galeria', function ($q) {
            $q->whereNotNull('fajlnev');
        })->get();

        return view('galeria', compact('kategoriak'));
    }
}
