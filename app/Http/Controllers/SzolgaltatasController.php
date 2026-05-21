<?php

namespace App\Http\Controllers;

use App\Models\Kategoria;
use Illuminate\Http\Response;

class SzolgaltatasController extends Controller
{
    public function show(string $slug)
    {
        $kategoria = Kategoria::where('slug', $slug)
            ->with(['galeria' => function ($q) {
                $q->whereNotNull('fajlnev');
            }])
            ->firstOrFail();

        return view('szolgaltatas', compact('kategoria'));
    }
}
