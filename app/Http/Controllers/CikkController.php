<?php

namespace App\Http\Controllers;

use App\Models\Cikk;
use Illuminate\Http\Request;

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

        return view('blog.show', compact('cikk', 'kapcsolodo'));
    }
}
