<?php

namespace App\Http\Controllers;

use App\Models\Kategoria;

class ArlistaController extends Controller
{
    public function index()
    {
        $kategoriak = Kategoria::with('arlistaTetelei')->has('arlistaTetelei')->get();

        return view('arlista', compact('kategoriak'));
    }
}
