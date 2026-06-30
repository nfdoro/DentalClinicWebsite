# Blog Infrastruktúra — Implementációs Terv

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Teljes blog és FAQ rendszer kiépítése a Laravel weboldalon — admin felületről kezelhető cikkek és GYIK kérdések, automatikus SEO meta tagekkel és schema.org structured data-val.

**Architecture:** Két új adatbázis tábla (`cikkek`, `faqs`), két új Eloquent model, két új controller, négy új Blade nézet (blog lista, blog részlet, FAQ szekció partial). Az admin panel meglévő struktúráját követi. A sitemap route kibővül a cikkekkel.

**Tech Stack:** Laravel 11, Blade templates, PHP 8.2+, MySQL, TinyMCE (CDN), PHPUnit

## Global Constraints

- Minden szöveg és label magyar nyelvű
- Slug automatikusan generálódik a cím alapján (`Str::slug()`)
- `published_at = null` → vázlat (nem jelenik meg a frontend-en)
- Nincs automatikus commit — a fejlesztő maga kommitol egy `seo/blog` branchre
- TinyMCE CDN-ről töltődik, nem npm-ből
- A `faqs` tábla `kategoria_id`-ja a meglévő `kategoriak` táblára mutat

---

### Task 1: Adatbázis — cikkek és faqs migration

**Files:**
- Create: `database/migrations/2026_06_30_000001_create_cikkek_table.php`
- Create: `database/migrations/2026_06_30_000002_create_faqs_table.php`

**Interfaces:**
- Produces: `cikkek` tábla — `Cikk` model alapja
- Produces: `faqs` tábla — `Faq` model alapja, `kategoria_id` foreign key a `kategoriak` táblára

- [ ] **Step 1: cikkek migration létrehozása**

```bash
php artisan make:migration create_cikkek_table
```

A generált fájlban az `up()` metódus tartalma:

```php
public function up(): void
{
    Schema::create('cikkek', function (Blueprint $table) {
        $table->id();
        $table->string('cim');
        $table->string('slug')->unique();
        $table->text('bevezeto');
        $table->longText('tartalom');
        $table->string('boritekep')->nullable();
        $table->string('meta_leiras', 320)->nullable();
        $table->string('kulcsszavak', 500)->nullable();
        $table->timestamp('published_at')->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('cikkek');
}
```

- [ ] **Step 2: faqs migration létrehozása**

```bash
php artisan make:migration create_faqs_table
```

A generált fájlban az `up()` metódus tartalma:

```php
public function up(): void
{
    Schema::create('faqs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('kategoria_id')->constrained('kategoriak')->cascadeOnDelete();
        $table->string('kerdes');
        $table->text('valasz');
        $table->unsignedTinyInteger('sorrend')->default(0);
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('faqs');
}
```

- [ ] **Step 3: Migrationök futtatása**

```bash
php artisan migrate
```

Elvárt output: `Running migrations... cikkek ✓  faqs ✓`

- [ ] **Step 4: Ellenőrzés**

```bash
php artisan tinker
>>> Schema::hasTable('cikkek')   // true
>>> Schema::hasTable('faqs')     // true
>>> Schema::getColumnListing('cikkek')
// ['id','cim','slug','bevezeto','tartalom','boritekep','meta_leiras','kulcsszavak','published_at','created_at','updated_at']
```

---

### Task 2: Modellek — Cikk és Faq

**Files:**
- Create: `app/Models/Cikk.php`
- Create: `app/Models/Faq.php`
- Modify: `app/Models/Kategoria.php`

**Interfaces:**
- Produces: `Cikk::published()` scope — csak közzétett cikkek
- Produces: `Cikk::$fillable` — minden szerkeszthető mező
- Produces: `Kategoria::faqs()` hasMany reláció
- Consumes: `kategoriak` tábla (Kategoria model) — már létezik

- [ ] **Step 1: Cikk model létrehozása**

```bash
php artisan make:model Cikk
```

A `app/Models/Cikk.php` teljes tartalma:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Cikk extends Model
{
    use HasFactory;

    protected $table = 'cikkek';

    protected $fillable = [
        'cim', 'slug', 'bevezeto', 'tartalom',
        'boritekep', 'meta_leiras', 'kulcsszavak', 'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function getOlvasasiIdoAttribute(): int
    {
        $szavak = str_word_count(strip_tags($this->tartalom));
        return max(1, (int) ceil($szavak / 200));
    }
}
```

- [ ] **Step 2: Faq model létrehozása**

```bash
php artisan make:model Faq
```

A `app/Models/Faq.php` teljes tartalma:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $table = 'faqs';

    protected $fillable = ['kategoria_id', 'kerdes', 'valasz', 'sorrend'];

    public function kategoria()
    {
        return $this->belongsTo(Kategoria::class);
    }
}
```

- [ ] **Step 3: Kategoria model bővítése**

A `app/Models/Kategoria.php`-ban az osztály body-ba hozzáadni:

```php
public function faqs()
{
    return $this->hasMany(Faq::class)->orderBy('sorrend');
}
```

- [ ] **Step 4: Ellenőrzés**

```bash
php artisan tinker
>>> App\Models\Cikk::published()->count()   // 0 (üres tábla)
>>> App\Models\Faq::count()                 // 0
>>> App\Models\Kategoria::first()->faqs     // üres collection
```

---

### Task 3: CikkController — index és show

**Files:**
- Create: `app/Http/Controllers/CikkController.php`
- Modify: `routes/web.php`

**Interfaces:**
- Consumes: `Cikk::published()` scope
- Produces: `blog.index` route → `/blog`
- Produces: `blog.show` route → `/blog/{slug}`
- Produces: `$cikkek` (paginated, 9/oldal) az index nézetbe
- Produces: `$cikk` és `$kapcsolodo` (3 db) a show nézetbe

- [ ] **Step 1: Controller létrehozása**

```bash
php artisan make:controller CikkController
```

A `app/Http/Controllers/CikkController.php` teljes tartalma:

```php
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
```

- [ ] **Step 2: Routes hozzáadása**

A `routes/web.php` fájlban az `use` blokk kibővítése:

```php
use App\Http\Controllers\CikkController;
```

Majd a route-ok közé:

```php
Route::get('/blog', [CikkController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [CikkController::class, 'show'])->name('blog.show');
```

- [ ] **Step 3: Ellenőrzés**

```bash
php artisan route:list | grep blog
```

Elvárt:
```
GET  blog          blog.index  CikkController@index
GET  blog/{slug}   blog.show   CikkController@show
```

---

### Task 4: Blog lista nézet

**Files:**
- Create: `resources/views/blog/index.blade.php`

**Interfaces:**
- Consumes: `$cikkek` (LengthAwarePaginator of Cikk)
- Produces: `/blog` oldal — kártya layout, lapozó

- [ ] **Step 1: `resources/views/blog/` mappa létrehozása és index nézet**

```bash
mkdir -p resources/views/blog
```

A `resources/views/blog/index.blade.php` teljes tartalma:

```blade
@extends('layouts.app')

@section('title', 'Blog — Dr. Nagy-Fazakas Csongor Fogászat Miskolc')
@section('description', 'Fogászati tippek, kezelési útmutatók és szakmai cikkek Dr. Nagy-Fazakas Csongor fogászati rendelőjétől. Invisalign, implantátum, fogfehérítés és prevenció témákban.')
@section('og_image', asset('images/rolunk.jpg'))

@section('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "Blog",
  "name": "Dr. Nagy-Fazakas Csongor Fogászati Blog",
  "url": "{{ route('blog.index') }}",
  "publisher": {
    "@type": "Dentist",
    "name": "Dr. Nagy-Fazakas Csongor",
    "url": "{{ url('/') }}"
  }
}
</script>
@endsection

@section('content')

<div class="szolg-page-header">
  <div class="container">
    <p class="szolgaltatas-breadcrumb">
      <a href="{{ route('home') }}">Főoldal</a>
      <span>/</span>
      Blog
    </p>
    <h1 class="szolgaltatas-title">Blog</h1>
  </div>
</div>

<main id="main">
  <section class="blog-lista-section">
    <div class="container">

      @if($cikkek->isEmpty())
        <p class="text-center text-muted py-5">Hamarosan érkeznek az első cikkeink.</p>
      @else
        <div class="row g-4">
          @foreach($cikkek as $cikk)
          <div class="col-lg-4 col-md-6">
            <a href="{{ route('blog.show', $cikk->slug) }}" class="blog-card text-decoration-none">
              @if($cikk->boritekep)
              <div class="blog-card-img">
                <img src="{{ asset($cikk->boritekep) }}" alt="{{ $cikk->cim }}" loading="lazy">
              </div>
              @endif
              <div class="blog-card-body">
                <p class="blog-card-date">{{ $cikk->published_at->format('Y. F j.') }}</p>
                <h2 class="blog-card-cim">{{ $cikk->cim }}</h2>
                <p class="blog-card-bevezeto">{{ Str::limit($cikk->bevezeto, 120) }}</p>
                <span class="blog-card-tovabb">Tovább olvasom →</span>
              </div>
            </a>
          </div>
          @endforeach
        </div>

        <div class="d-flex justify-content-center mt-5">
          {{ $cikkek->links() }}
        </div>
      @endif

    </div>
  </section>
</main>

@endsection
```

- [ ] **Step 2: Ellenőrzés**

1. `http://localhost/blog` — megnyílik hibamentesen
2. Ha nincs közzétett cikk: "Hamarosan érkeznek..." üzenet jelenik meg
3. Forrásban `<h1 class="szolgaltatas-title">Blog</h1>` látható

---

### Task 5: Blog részlet nézet + Article schema

**Files:**
- Create: `resources/views/blog/show.blade.php`

**Interfaces:**
- Consumes: `$cikk` (Cikk model), `$kapcsolodo` (Collection of Cikk, max 3)
- Produces: `/blog/{slug}` oldal — teljes cikk, Article schema, kapcsolódó cikkek

- [ ] **Step 1: show nézet létrehozása**

A `resources/views/blog/show.blade.php` teljes tartalma:

```blade
@extends('layouts.app')

@section('title', $cikk->cim . ' — Dr. Nagy-Fazakas Csongor Fogászat')
@section('description', $cikk->meta_leiras ?? Str::limit($cikk->bevezeto, 160))
@section('og_image', $cikk->boritekep ? asset($cikk->boritekep) : asset('images/rolunk.jpg'))

@section('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "Article",
  "headline": "{{ $cikk->cim }}",
  "description": "{{ $cikk->meta_leiras ?? Str::limit($cikk->bevezeto, 160) }}",
  "datePublished": "{{ $cikk->published_at->toIso8601String() }}",
  "dateModified": "{{ $cikk->updated_at->toIso8601String() }}",
  "author": {
    "@type": "Person",
    "name": "Dr. Nagy-Fazakas Csongor"
  },
  "publisher": {
    "@type": "Dentist",
    "name": "Dr. Nagy-Fazakas Csongor Fogászati Rendelő",
    "url": "{{ url('/') }}"
  },
  "image": "{{ $cikk->boritekep ? asset($cikk->boritekep) : asset('images/rolunk.jpg') }}",
  "url": "{{ route('blog.show', $cikk->slug) }}"
}
</script>
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    { "@type": "ListItem", "position": 1, "name": "Főoldal", "item": "{{ url('/') }}" },
    { "@type": "ListItem", "position": 2, "name": "Blog", "item": "{{ route('blog.index') }}" },
    { "@type": "ListItem", "position": 3, "name": "{{ $cikk->cim }}" }
  ]
}
</script>
@endsection

@section('content')

<div class="szolg-page-header">
  <div class="container">
    <p class="szolgaltatas-breadcrumb">
      <a href="{{ route('home') }}">Főoldal</a>
      <span>/</span>
      <a href="{{ route('blog.index') }}">Blog</a>
      <span>/</span>
      {{ Str::limit($cikk->cim, 40) }}
    </p>
    <h1 class="szolgaltatas-title">{{ $cikk->cim }}</h1>
  </div>
</div>

<main id="main">
  <article class="blog-cikk-section">
    <div class="container">
      <div class="blog-cikk-meta">
        <span>{{ $cikk->published_at->format('Y. F j.') }}</span>
        <span>·</span>
        <span>{{ $cikk->olvasasi_ido }} perc olvasás</span>
      </div>

      @if($cikk->boritekep)
      <div class="blog-cikk-boritekep">
        <img src="{{ asset($cikk->boritekep) }}" alt="{{ $cikk->cim }}" loading="eager">
      </div>
      @endif

      <div class="blog-cikk-tartalom">
        {!! $cikk->tartalom !!}
      </div>

      <div class="blog-cikk-cta">
        <p>Kérdése van? Hívjon minket!</p>
        <a href="tel:+36706276160" class="hero-btn-contact">
          <i class="bi bi-telephone"></i> +36 70 627 6160
        </a>
      </div>
    </div>
  </article>

  @if($kapcsolodo->isNotEmpty())
  <section class="blog-kapcsolodo-section">
    <div class="container">
      <h2 class="blog-kapcsolodo-cim">Kapcsolódó cikkek</h2>
      <div class="row g-4">
        @foreach($kapcsolodo as $k)
        <div class="col-lg-4 col-md-6">
          <a href="{{ route('blog.show', $k->slug) }}" class="blog-card text-decoration-none">
            @if($k->boritekep)
            <div class="blog-card-img">
              <img src="{{ asset($k->boritekep) }}" alt="{{ $k->cim }}" loading="lazy">
            </div>
            @endif
            <div class="blog-card-body">
              <p class="blog-card-date">{{ $k->published_at->format('Y. F j.') }}</p>
              <h3 class="blog-card-cim">{{ $k->cim }}</h3>
              <span class="blog-card-tovabb">Tovább olvasom →</span>
            </div>
          </a>
        </div>
        @endforeach
      </div>
    </div>
  </section>
  @endif
</main>

@endsection
```

- [ ] **Step 2: Ellenőrzés**

1. Admin felületről hozz létre egy teszt cikket és tedd közzé
2. `http://localhost/blog/{slug}` → megnyílik hibamentesen
3. Forrásban `Article` és `BreadcrumbList` schema látható
4. Olvasási idő megjelenik a meta sorban

---

### Task 6: Sitemap kiterjesztés — blog cikkek

**Files:**
- Modify: `resources/views/sitemap.blade.php`
- Modify: `routes/web.php`

**Interfaces:**
- Consumes: `Cikk::published()` — közzétett cikkek
- Produces: `/sitemap.xml` — blog cikkek URL-jei is szerepelnek

- [ ] **Step 1: sitemap route frissítése**

A `routes/web.php`-ban a sitemap route:

```php
Route::get('/sitemap.xml', function () {
    $kategoriak = \App\Models\Kategoria::where('szolgaltatas', true)->get();
    $cikkek = \App\Models\Cikk::published()->orderByDesc('published_at')->get();
    $content = view('sitemap', compact('kategoriak', 'cikkek'))->render();
    return response($content, 200)->header('Content-Type', 'application/xml');
})->name('sitemap');
```

- [ ] **Step 2: sitemap.blade.php bővítése**

A `</urlset>` záró tag elé hozzáadni:

```xml
    <url>
        <loc>{{ route('blog.index') }}</loc>
        <lastmod>{{ now()->toDateString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @foreach($cikkek as $cikk)
    <url>
        <loc>{{ route('blog.show', $cikk->slug) }}</loc>
        <lastmod>{{ $cikk->updated_at->toDateString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach
```

- [ ] **Step 3: Ellenőrzés**

`http://localhost/sitemap.xml` → `/blog` URL megjelenik; ha van közzétett cikk, az is megjelenik.

---

### Task 7: Admin panel — Blog CRUD + TinyMCE

**Files:**
- Modify: az admin panel fő controller-e és nézetei (keress rá a `admin` prefixű route-ra a `routes/web.php`-ban, és kövesd a meglévő mintát)

> **Megjegyzés:** Az admin panel struktúrája a projektben már létezik. Ez a task a meglévő admin minta követésével adja hozzá a blog kezelést. Mielőtt elkezded, olvasd el az admin panel meglévő controller-ét és nézeteit, hogy a stílushoz igazodj.

- [ ] **Step 1: Admin blog controller létrehozása**

```bash
php artisan make:controller Admin/CikkAdminController --resource
```

Az `app/Http/Controllers/Admin/CikkAdminController.php` metódusai:

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cikk;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CikkAdminController extends Controller
{
    public function index()
    {
        $cikkek = Cikk::orderByDesc('created_at')->paginate(20);
        return view('admin.cikkek.index', compact('cikkek'));
    }

    public function create()
    {
        return view('admin.cikkek.form', ['cikk' => new Cikk()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'cim'         => 'required|string|max:255',
            'bevezeto'    => 'required|string',
            'tartalom'    => 'required|string',
            'boritekep'   => 'nullable|image|max:4096',
            'meta_leiras' => 'nullable|string|max:320',
            'kulcsszavak' => 'nullable|string|max:500',
            'published_at'=> 'nullable|date',
        ]);

        $data['slug'] = Str::slug($data['cim']);

        if ($request->hasFile('boritekep')) {
            $data['boritekep'] = $request->file('boritekep')->store('images/blog', 'public');
        }

        Cikk::create($data);
        return redirect()->route('admin.cikkek.index')->with('success', 'Cikk mentve.');
    }

    public function edit(Cikk $cikk)
    {
        return view('admin.cikkek.form', compact('cikk'));
    }

    public function update(Request $request, Cikk $cikk)
    {
        $data = $request->validate([
            'cim'         => 'required|string|max:255',
            'bevezeto'    => 'required|string',
            'tartalom'    => 'required|string',
            'boritekep'   => 'nullable|image|max:4096',
            'meta_leiras' => 'nullable|string|max:320',
            'kulcsszavak' => 'nullable|string|max:500',
            'published_at'=> 'nullable|date',
        ]);

        if ($request->hasFile('boritekep')) {
            $data['boritekep'] = $request->file('boritekep')->store('images/blog', 'public');
        }

        $cikk->update($data);
        return redirect()->route('admin.cikkek.index')->with('success', 'Cikk frissítve.');
    }

    public function destroy(Cikk $cikk)
    {
        $cikk->delete();
        return redirect()->route('admin.cikkek.index')->with('success', 'Cikk törölve.');
    }
}
```

- [ ] **Step 2: Admin route-ok hozzáadása**

A meglévő admin route csoport belsejébe (kövesd a meglévő admin route prefixet/middleware-t):

```php
Route::resource('cikkek', \App\Http\Controllers\Admin\CikkAdminController::class)
    ->names('admin.cikkek');
```

- [ ] **Step 3: Admin blog lista nézet**

Létrehozni: `resources/views/admin/cikkek/index.blade.php`

A meglévő admin layout-ot kell kiterjeszteni (pl. `@extends('admin.layouts.app')` — ezt igazítsd a projekted admin layout nevéhez). Tartalma:

```blade
@extends('admin.layouts.app')  {{-- igazítsd a meglévő admin layout nevéhez --}}

@section('content')
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Cikkek</h1>
    <a href="{{ route('admin.cikkek.create') }}" class="btn btn-primary">+ Új cikk</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <table class="table table-bordered">
    <thead>
      <tr><th>Cím</th><th>Állapot</th><th>Közzétéve</th><th>Műveletek</th></tr>
    </thead>
    <tbody>
      @foreach($cikkek as $cikk)
      <tr>
        <td>{{ $cikk->cim }}</td>
        <td>{{ $cikk->published_at ? 'Közzétett' : 'Vázlat' }}</td>
        <td>{{ $cikk->published_at?->format('Y-m-d') ?? '—' }}</td>
        <td>
          <a href="{{ route('admin.cikkek.edit', $cikk) }}" class="btn btn-sm btn-secondary">Szerkesztés</a>
          <form method="POST" action="{{ route('admin.cikkek.destroy', $cikk) }}" class="d-inline">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-danger" onclick="return confirm('Biztos?')">Törlés</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  {{ $cikkek->links() }}
</div>
@endsection
```

- [ ] **Step 4: Admin blog form nézet (létrehozás + szerkesztés)**

Létrehozni: `resources/views/admin/cikkek/form.blade.php`

```blade
@extends('admin.layouts.app')  {{-- igazítsd a meglévő admin layout nevéhez --}}

@section('content')
<div class="container-fluid">
  <h1>{{ $cikk->id ? 'Cikk szerkesztése' : 'Új cikk' }}</h1>

  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  <form method="POST"
        action="{{ $cikk->id ? route('admin.cikkek.update', $cikk) : route('admin.cikkek.store') }}"
        enctype="multipart/form-data">
    @csrf
    @if($cikk->id) @method('PUT') @endif

    <div class="mb-3">
      <label class="form-label">Cím *</label>
      <input type="text" name="cim" class="form-control" value="{{ old('cim', $cikk->cim) }}" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Bevezető *</label>
      <textarea name="bevezeto" class="form-control" rows="3" required>{{ old('bevezeto', $cikk->bevezeto) }}</textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">Tartalom *</label>
      <textarea name="tartalom" id="tinymce-tartalom" class="form-control">{{ old('tartalom', $cikk->tartalom) }}</textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">Borítókép</label>
      <input type="file" name="boritekep" class="form-control" accept="image/*">
      @if($cikk->boritekep)
        <img src="{{ asset($cikk->boritekep) }}" alt="Borítókép" class="mt-2" style="max-height:120px">
      @endif
    </div>

    <div class="mb-3">
      <label class="form-label">Meta leírás (max 320 karakter)</label>
      <textarea name="meta_leiras" class="form-control" rows="2" maxlength="320">{{ old('meta_leiras', $cikk->meta_leiras) }}</textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">Kulcsszavak</label>
      <input type="text" name="kulcsszavak" class="form-control" value="{{ old('kulcsszavak', $cikk->kulcsszavak) }}" placeholder="pl. invisalign miskolc, fogszabályozás ár">
    </div>

    <div class="mb-3">
      <label class="form-label">Közzététel dátuma (üresen hagyva = vázlat)</label>
      <input type="datetime-local" name="published_at" class="form-control"
             value="{{ old('published_at', $cikk->published_at?->format('Y-m-d\TH:i')) }}">
    </div>

    <button type="submit" class="btn btn-primary">Mentés</button>
    <a href="{{ route('admin.cikkek.index') }}" class="btn btn-secondary">Mégse</a>
  </form>
</div>

<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
  tinymce.init({
    selector: '#tinymce-tartalom',
    language: 'hu_HU',
    height: 500,
    plugins: 'link image lists table code',
    toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code',
    promotion: false,
  });
</script>
@endsection
```

> **Figyelem:** A TinyMCE `no-api-key` kulcs helyett regisztrálj ingyenes API kulcsot a [tiny.cloud](https://www.tiny.cloud/) oldalon, és cseréld ki a CDN URL-ben.

- [ ] **Step 5: Ellenőrzés**

1. `http://localhost/admin/cikkek` → lista oldal megjelenik
2. "Új cikk" → TinyMCE szerkesztő betölt
3. Hozz létre egy teszt cikket `published_at` kitöltésével
4. `http://localhost/blog` → a cikk megjelenik
5. `http://localhost/blog/{slug}` → a cikk teljes tartalommal megjelenik

---

### Task 8: Admin panel — FAQ CRUD

**Files:**
- Create: `app/Http/Controllers/Admin/FaqAdminController.php`
- Create: `resources/views/admin/faqs/index.blade.php`
- Create: `resources/views/admin/faqs/form.blade.php`
- Modify: `resources/views/szolgaltatas.blade.php`

**Interfaces:**
- Consumes: `Faq` model, `Kategoria` model
- Produces: Admin felületen FAQ-ok szerkeszthetők szolgáltatásonként
- Produces: Szolgáltatás oldalakon GYIK szekció + `FAQPage` schema

- [ ] **Step 1: FaqAdminController létrehozása**

```bash
php artisan make:controller Admin/FaqAdminController --resource
```

A `app/Http/Controllers/Admin/FaqAdminController.php` tartalma:

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Kategoria;
use Illuminate\Http\Request;

class FaqAdminController extends Controller
{
    public function index()
    {
        $kategoriak = Kategoria::where('szolgaltatas', true)->with('faqs')->get();
        return view('admin.faqs.index', compact('kategoriak'));
    }

    public function create()
    {
        $kategoriak = Kategoria::where('szolgaltatas', true)->get();
        return view('admin.faqs.form', ['faq' => new Faq(), 'kategoriak' => $kategoriak]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kategoria_id' => 'required|exists:kategoriak,id',
            'kerdes'       => 'required|string|max:500',
            'valasz'       => 'required|string',
            'sorrend'      => 'integer|min:0|max:255',
        ]);

        Faq::create($data);
        return redirect()->route('admin.faqs.index')->with('success', 'GYIK hozzáadva.');
    }

    public function edit(Faq $faq)
    {
        $kategoriak = Kategoria::where('szolgaltatas', true)->get();
        return view('admin.faqs.form', compact('faq', 'kategoriak'));
    }

    public function update(Request $request, Faq $faq)
    {
        $data = $request->validate([
            'kategoria_id' => 'required|exists:kategoriak,id',
            'kerdes'       => 'required|string|max:500',
            'valasz'       => 'required|string',
            'sorrend'      => 'integer|min:0|max:255',
        ]);

        $faq->update($data);
        return redirect()->route('admin.faqs.index')->with('success', 'GYIK frissítve.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return redirect()->route('admin.faqs.index')->with('success', 'GYIK törölve.');
    }
}
```

- [ ] **Step 2: FAQ admin route-ok**

A meglévő admin route csoportba:

```php
Route::resource('faqs', \App\Http\Controllers\Admin\FaqAdminController::class)
    ->names('admin.faqs');
```

- [ ] **Step 3: FAQ admin lista nézet**

`resources/views/admin/faqs/index.blade.php`:

```blade
@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1>GYIK kérdések</h1>
    <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary">+ Új GYIK</a>
  </div>
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @foreach($kategoriak as $kat)
    @if($kat->faqs->count())
    <h5 class="mt-4">{{ $kat->nev }}</h5>
    <table class="table table-sm table-bordered">
      <thead><tr><th>#</th><th>Kérdés</th><th>Műveletek</th></tr></thead>
      <tbody>
        @foreach($kat->faqs as $faq)
        <tr>
          <td>{{ $faq->sorrend }}</td>
          <td>{{ $faq->kerdes }}</td>
          <td>
            <a href="{{ route('admin.faqs.edit', $faq) }}" class="btn btn-sm btn-secondary">Szerkesztés</a>
            <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}" class="d-inline">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-danger" onclick="return confirm('Biztos?')">Törlés</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    @endif
  @endforeach
</div>
@endsection
```

- [ ] **Step 4: FAQ admin form nézet**

`resources/views/admin/faqs/form.blade.php`:

```blade
@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
  <h1>{{ $faq->id ? 'GYIK szerkesztése' : 'Új GYIK kérdés' }}</h1>
  <form method="POST"
        action="{{ $faq->id ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}">
    @csrf
    @if($faq->id) @method('PUT') @endif

    <div class="mb-3">
      <label class="form-label">Szolgáltatás *</label>
      <select name="kategoria_id" class="form-select" required>
        @foreach($kategoriak as $kat)
        <option value="{{ $kat->id }}" {{ old('kategoria_id', $faq->kategoria_id) == $kat->id ? 'selected' : '' }}>
          {{ $kat->nev }}
        </option>
        @endforeach
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Kérdés *</label>
      <input type="text" name="kerdes" class="form-control" value="{{ old('kerdes', $faq->kerdes) }}" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Válasz *</label>
      <textarea name="valasz" class="form-control" rows="4" required>{{ old('valasz', $faq->valasz) }}</textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">Sorrend (kisebb szám = előrébb)</label>
      <input type="number" name="sorrend" class="form-control" value="{{ old('sorrend', $faq->sorrend ?? 0) }}" min="0">
    </div>

    <button type="submit" class="btn btn-primary">Mentés</button>
    <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary">Mégse</a>
  </form>
</div>
@endsection
```

- [ ] **Step 5: GYIK szekció + FAQPage schema a szolgáltatás oldalon**

A `SzolgaltatasController::show()` metódusban betölteni a FAQ-okat:

```php
public function show(string $slug)
{
    $kategoria = Kategoria::where('slug', $slug)->where('szolgaltatas', true)->firstOrFail();
    $kategoria->load('galeria', 'faqs');
    return view('szolgaltatas', compact('kategoria'));
}
```

A `resources/views/szolgaltatas.blade.php`-ban a `szolg-egyeb` div elé hozzáadni:

```blade
@if($kategoria->faqs->count())
<div class="szolg-faq">
  <h2>Gyakran Ismételt Kérdések</h2>
  <div class="accordion" id="faqAccordion">
    @foreach($kategoria->faqs as $faq)
    <div class="accordion-item">
      <h3 class="accordion-header">
        <button class="accordion-button collapsed" type="button"
                data-bs-toggle="collapse" data-bs-target="#faq{{ $faq->id }}">
          {{ $faq->kerdes }}
        </button>
      </h3>
      <div id="faq{{ $faq->id }}" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
        <div class="accordion-body">{{ $faq->valasz }}</div>
      </div>
    </div>
    @endforeach
  </div>
</div>
@endif
```

A `@section('schema')` blokkba (a meglévő scriptek mellé) FAQ schema:

```html
@if($kategoria->faqs->count())
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    @foreach($kategoria->faqs as $faq)
    {
      "@type": "Question",
      "name": "{{ $faq->kerdes }}",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "{{ $faq->valasz }}"
      }
    }{{ !$loop->last ? ',' : '' }}
    @endforeach
  ]
}
</script>
@endif
```

- [ ] **Step 6: Ellenőrzés**

1. Admin felületről adj hozzá 3 GYIK kérdést egy szolgáltatáshoz
2. Nyisd meg az adott szolgáltatás oldalt — accordion GYIK szekció megjelenik
3. Google Rich Results Test → `FAQPage` schema valid legyen

---

### Task 9: Navigáció és footer frissítése

**Files:**
- Modify: `resources/views/layouts/app.blade.php`

**Interfaces:**
- Produces: "Blog" menüpont a navigációban és a footerben

- [ ] **Step 1: Blog link a navba**

A `<nav>` `<ul>`-jában az `Árlista` li elé:

```html
<li><a class="nav-link" href="{{ route('blog.index') }}">Blog</a></li>
```

- [ ] **Step 2: Blog link a footerbe**

A "Gyors linkek" footer szekció `<ul>`-jában hozzáadni:

```html
<li><i class="bx bx-chevron-right"></i> <a href="{{ route('blog.index') }}">Blog</a></li>
```

- [ ] **Step 3: Ellenőrzés**

1. Főoldalon a navigációban megjelenik a "Blog" menüpont
2. Footerben a "Gyors linkek" között megjelenik a Blog link
3. Mindkét link a `/blog` oldalra visz
