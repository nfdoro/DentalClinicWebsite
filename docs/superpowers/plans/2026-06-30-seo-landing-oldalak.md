# Országos Landing Oldalak — Implementációs Terv

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Miskolc-alapú, de országos elérhetőségű landing oldalak kiépítése — egy regionális oldal Észak-Magyarország célcsoportjának, és emelt szintű (SEO-optimalizált, FAQ-os, részletes) tartalom a három kiemelt kezelésnél: Invisalign, implantátum, fogfehérítés.

**Architecture:** Új route-ok, új Blade nézetek, és az admin panelen keresztül szerkeszthető "kiemelt leírás" mező a három prioritás-szolgáltatásnál. A meglévő `kategoriak` táblát egy `kiemelt_leiras` (longText) mezővel bővítjük, hogy az admin felületről szerkeszthető legyen a hosszabb tartalom. A regionális oldal statikus nézet, de valódi, hasznos tartalommal.

**Tech Stack:** Laravel 11, Blade templates, PHP 8.2+, MySQL, Schema.org JSON-LD

## Global Constraints

- Minden szöveg magyar nyelvű, természetes hangvételű (nem spam-szerű kulcsszó-halmozás)
- A regionális oldal NEM egy "kulcsszó oldal" — valódi megközelítési útmutatót tartalmaz
- A három kiemelt service oldal ugyanazt a `/szolgaltatasok/{slug}` route-ot használja — az emelt szintű tartalom az adatbázisból jön, nem külön oldal
- Nincs automatikus commit — a fejlesztő maga kommitol egy `seo/landing-pages` branchre
- Az Invisalign slug: `fogszabalyozas`, implantátum slug: `fogpotlas` (ellenőrizd az adatbázisban!)

---

### Task 1: kategoriak tábla bővítése — kiemelt_leiras mező

**Files:**
- Create: `database/migrations/2026_06_30_000003_add_kiemelt_leiras_to_kategoriak_table.php`

**Interfaces:**
- Produces: `kategoriak.kiemelt_leiras` (longText, nullable) — admin felületről szerkeszthető hosszú tartalom
- Consumes: Meglévő `kategoriak` tábla

- [ ] **Step 1: Migration létrehozása**

```bash
php artisan make:migration add_kiemelt_leiras_to_kategoriak_table
```

A generált fájlban:

```php
public function up(): void
{
    Schema::table('kategoriak', function (Blueprint $table) {
        $table->longText('kiemelt_leiras')->nullable()->after('leiras');
    });
}

public function down(): void
{
    Schema::table('kategoriak', function (Blueprint $table) {
        $table->dropColumn('kiemelt_leiras');
    });
}
```

- [ ] **Step 2: Migration futtatása**

```bash
php artisan migrate
```

- [ ] **Step 3: Kategoria model fillable frissítése**

A `app/Models/Kategoria.php`-ban a `$fillable` tömbhöz hozzáadni a `'kiemelt_leiras'` mezőt (ha van `$fillable`; ha nincs, add hozzá a listát).

- [ ] **Step 4: Ellenőrzés**

```bash
php artisan tinker
>>> Schema::hasColumn('kategoriak', 'kiemelt_leiras')   // true
```

---

### Task 2: Admin panel — kiemelt_leiras szerkesztése

**Files:**
- Módosítandó: az admin panel Kategoria szerkesztő nézete és controller-e (keresd meg a projektben — valószínűleg `resources/views/admin/kategoriak/` alatt van)

**Interfaces:**
- Consumes: `kiemelt_leiras` mező a `kategoriak` táblában (Task 1-ből)
- Produces: Admin felületen TinyMCE szerkesztő a kiemelt leíráshoz

> **Figyelem:** Mielőtt módosítod, olvasd el a meglévő kategória admin controller-t és form nézetet. Kövesd a már létező struktúrát.

- [ ] **Step 1: Kiemelt leírás mező hozzáadása a kategória admin form-hoz**

A meglévő kategória admin form nézetben (keress rá `kategoria` szóra az admin nézetekben) a meglévő `leiras` mező után add hozzá:

```html
<div class="mb-3">
  <label class="form-label">Kiemelt leírás (hosszú, SEO-optimalizált — csak Invisalign, implantátum, fogfehérítésnél töltsd ki)</label>
  <textarea name="kiemelt_leiras" id="tinymce-kiemelt" class="form-control" rows="10">{{ old('kiemelt_leiras', $kategoria->kiemelt_leiras) }}</textarea>
</div>
```

Ha a form oldalon még nincs TinyMCE, add hozzá a `@section('scripts')` vagy `@push('scripts')` blokkba:

```html
<script src="https://cdn.tiny.cloud/1/TINYMCE_API_KULCS/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
  tinymce.init({
    selector: '#tinymce-kiemelt',
    language: 'hu_HU',
    height: 600,
    plugins: 'link lists table code',
    toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter | bullist numlist | link | code',
    promotion: false,
  });
</script>
```

- [ ] **Step 2: Controller validáció bővítése**

A kategória admin controller `store()` és `update()` metódusaiban a validate tömbhöz hozzáadni:

```php
'kiemelt_leiras' => 'nullable|string',
```

- [ ] **Step 3: Ellenőrzés**

1. Admin felületen megnyitni az Invisalign / fogszabályozás kategória szerkesztőjét
2. A TinyMCE szerkesztő betölt a `kiemelt_leiras` mezőhöz
3. Szöveg beírása, mentés → az adatbázisban eltárolódik

---

### Task 3: Szolgáltatás oldal — kiemelt tartalom megjelenítése

**Files:**
- Modify: `resources/views/szolgaltatas.blade.php`
- Modify: `app/Http/Controllers/SzolgaltatasController.php`

**Interfaces:**
- Consumes: `$kategoria->kiemelt_leiras` (nullable longText)
- Produces: Ha `kiemelt_leiras` ki van töltve, egy kiegészítő "Részletes információk" szekció jelenik meg a szolgáltatás oldalon a normál `leiras` után

- [ ] **Step 1: SzolgaltatasController ellenőrzése**

Nyisd meg az `app/Http/Controllers/SzolgaltatasController.php`-t. A `show()` metódusban a `$kategoria->load()`-nál ellenőrizd, hogy `kiemelt_leiras` is elérhetővé válik (Eloquent automatikusan kezeli, de ha `$hidden`-be lenne zárva, add `$fillable`-be).

- [ ] **Step 2: Kiemelt szekció hozzáadása a nézethez**

A `resources/views/szolgaltatas.blade.php`-ban a `szolg-leiras` div után (de még a `szolg-egyeb` előtt) hozzáadni:

```blade
@if($kategoria->kiemelt_leiras)
<div class="szolg-kiemelt-leiras mt-5">
  <h2>Részletes információk: {{ $kategoria->nev }} Miskolcon</h2>
  {!! $kategoria->kiemelt_leiras !!}
</div>
@endif
```

- [ ] **Step 3: Ellenőrzés**

1. Admin felületen töltsd ki az Invisalign kategória `kiemelt_leiras` mezőjét néhány tesztelő bekezdéssel
2. Nyisd meg: `http://localhost/szolgaltatasok/fogszabalyozas`
3. A "Részletes információk" szekció megjelenik a normál leírás alatt
4. Ha egy másik (nem kiemelt) szolgáltatást nyitsz meg, a szekció nem jelenik meg

---

### Task 4: Regionális landing oldal — Észak-Magyarország

**Files:**
- Create: `resources/views/fogaszat/eszak-magyarorszag.blade.php`
- Modify: `routes/web.php`

**Interfaces:**
- Produces: `/fogaszat/eszak-magyarorszag` statikus oldal valódi tartalommal
- Produces: `LocalBusiness` schema a regionális oldalon

- [ ] **Step 1: Route hozzáadása**

A `routes/web.php`-ban:

```php
Route::get('/fogaszat/eszak-magyarorszag', function () {
    $szolgaltatasok = \App\Models\Kategoria::where('szolgaltatas', true)->get();
    return view('fogaszat.eszak-magyarorszag', compact('szolgaltatasok'));
})->name('fogaszat.eszak-magyarorszag');
```

- [ ] **Step 2: Nézet mappa és fájl létrehozása**

```bash
mkdir -p resources/views/fogaszat
```

A `resources/views/fogaszat/eszak-magyarorszag.blade.php` teljes tartalma:

```blade
@extends('layouts.app')

@section('title', 'Fogászat Észak-Magyarország — Dr. Nagy-Fazakas Csongor, Miskolc')
@section('description', 'Fogászati rendelő Miskolcon, Észak-Magyarország szívében. Eger, Ózd, Kazincbarcika, Nyíregyháza és Tiszaújváros közeléből is könnyen elérhető. Invisalign, implantátum, fogfehérítés.')
@section('og_image', asset('images/rolunk.jpg'))

@section('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "Dentist",
  "name": "Dr. Nagy-Fazakas Csongor Fogászati Rendelő",
  "description": "Fogászati rendelő Miskolcon, Észak-Magyarország egész területéről elérhető. Invisalign fogszabályozás, implantátum, fogfehérítés.",
  "url": "{{ url('/') }}",
  "telephone": "+36706276160",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "Madarász Viktor utca 13/A, 2. emelet",
    "addressLocality": "Miskolc",
    "postalCode": "3525",
    "addressCountry": "HU"
  },
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": 48.1065,
    "longitude": 20.7872
  },
  "areaServed": [
    { "@type": "City", "name": "Miskolc" },
    { "@type": "City", "name": "Eger" },
    { "@type": "City", "name": "Ózd" },
    { "@type": "City", "name": "Kazincbarcika" },
    { "@type": "City", "name": "Nyíregyháza" },
    { "@type": "City", "name": "Tiszaújváros" }
  ]
}
</script>
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    { "@type": "ListItem", "position": 1, "name": "Főoldal", "item": "{{ url('/') }}" },
    { "@type": "ListItem", "position": 2, "name": "Észak-Magyarország" }
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
      Észak-Magyarország
    </p>
    <h1 class="szolgaltatas-title">Fogászat Észak-Magyarország</h1>
  </div>
</div>

<main id="main">
  <section class="portfolio-details">
    <div class="container">

      <div class="row g-5 align-items-start">
        <div class="col-lg-7">

          <h2>Miért érdemes Miskolcra utazni fogászati kezelésre?</h2>
          <p>
            Dr. Nagy-Fazakas Csongor fogászati rendelője Miskolc belvárosában, a Madarász Viktor utcán található —
            Észak-Magyarország egyik legjobban megközelíthető pontján. Akár Egerből, Ózdról, Kazincbarcikáról
            vagy Nyíregyházáról érkezik, rendelőnk 30–90 perc alatt elérhető.
          </p>

          <h3>Megközelíthetőség városonként</h3>
          <div class="table-responsive">
            <table class="table table-bordered mt-3">
              <thead class="table-light">
                <tr><th>Város</th><th>Távolság</th><th>Menetidő</th><th>Útvonal</th></tr>
              </thead>
              <tbody>
                <tr><td>Eger</td><td>~60 km</td><td>~50 perc</td><td>M25 → Miskolc</td></tr>
                <tr><td>Ózd</td><td>~45 km</td><td>~45 perc</td><td>26-os főút</td></tr>
                <tr><td>Kazincbarcika</td><td>~20 km</td><td>~20 perc</td><td>26-os főút</td></tr>
                <tr><td>Nyíregyháza</td><td>~80 km</td><td>~70 perc</td><td>M3 → M30</td></tr>
                <tr><td>Tiszaújváros</td><td>~30 km</td><td>~30 perc</td><td>35-ös főút</td></tr>
              </tbody>
            </table>
          </div>

          <h3 class="mt-4">Miért éri meg az utazás?</h3>
          <ul>
            <li><strong>9+ év tapasztalat</strong> — komplex implantátum és fogszabályozó kezelések</li>
            <li><strong>Ingyenes első konzultáció</strong> fogszabályozáshoz</li>
            <li><strong>Egyéni, személyes ellátás</strong> — nem tömeggyártás</li>
            <li><strong>Parkolás</strong> — a rendelő közelében ingyenes parkoló érhető el</li>
          </ul>

          <h3 class="mt-4">Elérhető kezelések</h3>
          <div class="row g-3 mt-1">
            @foreach($szolgaltatasok as $s)
            <div class="col-md-6">
              <a href="{{ route('szolgaltatas.show', $s->slug) }}" class="d-flex align-items-center gap-2 text-decoration-none text-dark border rounded p-2">
                @if($s->icon)
                <img src="{{ asset($s->icon) }}" alt="{{ $s->nev }}" style="width:32px;height:32px;object-fit:contain">
                @endif
                <span>{{ $s->nev }}</span>
              </a>
            </div>
            @endforeach
          </div>

        </div>

        <div class="col-lg-5">
          <div class="szolg-info-card">
            <div class="szolg-info-card-body">
              <h4 class="mb-3">Kapcsolat & időpontfoglalás</h4>
              <div class="szolg-meta">
                <i class="bi bi-geo-alt-fill"></i>
                <span>3525 Miskolc, Madarász Viktor utca 13/A, 2. emelet</span>
              </div>
              <div class="szolg-meta">
                <i class="bi bi-telephone-fill"></i>
                <a href="tel:+36706276160">+36 70 627 6160</a>
              </div>
              <div class="szolg-meta">
                <i class="bi bi-envelope-fill"></i>
                <a href="mailto:info@fogaszat-miskolc.hu">info@fogaszat-miskolc.hu</a>
              </div>
            </div>
            <div class="szolg-cta-row">
              <a href="{{ route('arlista') }}" class="szolg-cta-btn">
                <i class="bi bi-list-ul"></i> Árlista megtekintése
              </a>
            </div>
          </div>

          <div class="mt-4">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d792.0695239355053!2d20.787230008431443!3d48.106499155561345!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47409ffcf4e6bc09%3A0xf5ca4089e8674680!2sMiskolc%2C%20Madar%C3%A1sz%20Viktor%20u.%2013%2C%203525%20Magyarorsz%C3%A1g!5e0!3m2!1shu!2sro!4v1682521750086!5m2!1shu!2sro"
              width="100%" height="300" style="border:0;border-radius:8px" allowfullscreen="" loading="lazy"
              title="Dr. Nagy-Fazakas Csongor fogászati rendelő helyszíne">
            </iframe>
          </div>
        </div>
      </div>

    </div>
  </section>
</main>

@endsection
```

- [ ] **Step 3: Sitemap bővítése**

A `resources/views/sitemap.blade.php`-ban a `</urlset>` elé:

```xml
    <url>
        <loc>{{ route('fogaszat.eszak-magyarorszag') }}</loc>
        <lastmod>2026-06-30</lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.6</priority>
    </url>
```

- [ ] **Step 4: Ellenőrzés**

1. `http://localhost/fogaszat/eszak-magyarorszag` — megnyílik hibamentesen
2. A táblázat és a szolgáltatás linkek megjelennek
3. Forrásban `areaServed` schema látható a `Dentist` JSON-LD-ben
4. `http://localhost/sitemap.xml` — az új URL megjelenik

---

### Task 5: Belső linkelés — footer és főoldal

**Files:**
- Modify: `resources/views/layouts/app.blade.php`
- Modify: `resources/views/home.blade.php`

**Interfaces:**
- Produces: Az Észak-Magyarország oldal linkelhető a footerből
- Produces: A főoldalon egy kis "Elérhető városok" szekció utal a regionális oldalra

- [ ] **Step 1: Footer link az Észak-Magyarország oldalra**

A `layouts/app.blade.php` footerben a "Gyors linkek" ul-jába:

```html
<li><i class="bx bx-chevron-right"></i> <a href="{{ route('fogaszat.eszak-magyarorszag') }}">Észak-Magyarország</a></li>
```

- [ ] **Step 2: Városok mention a főoldal contact szekciójában**

A `home.blade.php`-ban a contact szekció `info-wrap` div-je után, a térkép előtt hozzáadni:

```blade
<div class="text-center mt-4 mb-2">
  <p class="text-muted small">
    Rendelőnk Miskolcon található, de pácienseinket fogadjuk
    <a href="{{ route('fogaszat.eszak-magyarorszag') }}">Eger, Ózd, Kazincbarcika, Nyíregyháza és Tiszaújváros</a> közeléből is.
  </p>
</div>
```

- [ ] **Step 3: Ellenőrzés**

1. Főoldalon a Contact szekció alatt megjelenik a városok mention link
2. Footerben megjelenik az "Észak-Magyarország" link
3. Mindkét link az `/fogaszat/eszak-magyarorszag` oldalra visz
