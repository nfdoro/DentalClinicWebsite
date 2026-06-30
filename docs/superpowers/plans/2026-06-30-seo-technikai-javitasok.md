# Technikai SEO Javítások — Implementációs Terv

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** A meglévő fogászati weboldal technikai SEO hiányosságainak kijavítása meglévő fájlok módosításával — robots.txt, meta tagek, schema.org, sitemap, Google Fonts teljesítmény.

**Architecture:** Kizárólag meglévő Blade template-ek és statikus fájlok módosítása. Nincs új tábla, nincs új route, nincs új package. A változtatások azonnal élőbe kerülnek deployolás után.

**Tech Stack:** Laravel 11, Blade templates, HTML, JSON-LD

## Global Constraints

- Minden szöveg magyar nyelvű
- Domain neve: `fogaszat-miskolc.hu` — minden abszolút URL-ben ezt kell használni
- Nincs automatikus commit — a fejlesztő maga kommitol egy `seo/tech-fixes` branchre
- GA4 tracking ID és Search Console kód `.env`-ből jön (nem hard-coded)
- A `sameAs` Facebook URL és Google Business Profile URL egyelőre placeholder marad — a GBP létrehozása után töltendő ki

---

### Task 1: robots.txt — abszolút Sitemap URL

**Files:**
- Modify: `public/robots.txt`

**Interfaces:**
- Produces: Keresőrobotok számára helyes sitemap hivatkozás

- [ ] **Step 1: Megnyitni és megjavítani a robots.txt-t**

A teljes fájl tartalma legyen:

```
User-agent: *
Allow: /
Disallow: /storage/
Disallow: /.env
Disallow: /admin

Sitemap: https://fogaszat-miskolc.hu/sitemap.xml
```

- [ ] **Step 2: Ellenőrzés**

Böngészőben megnyitni: `http://localhost/robots.txt`  
Elvárt: a Sitemap sor `https://fogaszat-miskolc.hu/sitemap.xml`-t mutat, nem relatív útvonalat.

---

### Task 2: Layout — og:image, Twitter Cards, preconnect, GA4, Search Console

**Files:**
- Modify: `resources/views/layouts/app.blade.php`
- Modify: `.env.example`

**Interfaces:**
- Produces: `@yield('og_image')` — az egyes oldalak egyedi képet adhatnak meg
- Produces: GA4 tracking minden oldalon
- Produces: Search Console hitelesítő meta tag az adott oldalon

- [ ] **Step 1: `.env`-be felvenni az új változókat**

A `.env` fájlba (és `.env.example`-be) hozzáadni:

```env
GA4_TRACKING_ID=
SEARCH_CONSOLE_VERIFY=
```

- [ ] **Step 2: `config/services.php`-ba felvenni**

A `services.php` fájl végére, a záró `];` elé:

```php
'ga4' => [
    'tracking_id' => env('GA4_TRACKING_ID', ''),
],
'search_console' => [
    'verify' => env('SEARCH_CONSOLE_VERIFY', ''),
],
```

- [ ] **Step 3: `layouts/app.blade.php` `<head>` módosítása**

A jelenlegi `<head>` tartalom helyett az alábbi legyen (teljes `<head>` blokk):

```html
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>@yield('title', 'Dr. Nagy-Fazakas Csongor - Fogászati Rendelő Miskolc')</title>
  <meta name="description" content="@yield('description', 'Dr. Nagy-Fazakas Csongor fogászati rendelője Miskolcon. Gyökérkezelés, fogszabályozás, fogtömés, fogpótlás, fogfehérítés és prevenciós kezelések.')">
  <meta name="keywords" content="@yield('keywords', 'fogorvos miskolc, fogászat miskolc, gyökérkezelés, fogszabályozás, fogtömés, fogpótlás, fogfehérítés, dr nagy-fazakas csongor')">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="{{ url()->current() }}">

  {{-- Search Console hitelesítés (csak a főoldalon szükséges) --}}
  @if(config('services.search_console.verify'))
  <meta name="google-site-verification" content="{{ config('services.search_console.verify') }}">
  @endif

  {{-- Open Graph --}}
  <meta property="og:type" content="website">
  <meta property="og:title" content="@yield('title', 'Dr. Nagy-Fazakas Csongor - Fogászati Rendelő Miskolc')">
  <meta property="og:description" content="@yield('description', 'Dr. Nagy-Fazakas Csongor fogászati rendelője Miskolcon.')">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:locale" content="hu_HU">
  <meta property="og:site_name" content="Dr. Nagy-Fazakas Csongor Fogászat">
  <meta property="og:image" content="@yield('og_image', asset('images/rolunk.jpg'))">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">

  {{-- Twitter Card --}}
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="@yield('title', 'Dr. Nagy-Fazakas Csongor - Fogászati Rendelő Miskolc')">
  <meta name="twitter:description" content="@yield('description', 'Dr. Nagy-Fazakas Csongor fogászati rendelője Miskolcon.')">
  <meta name="twitter:image" content="@yield('og_image', asset('images/rolunk.jpg'))">

  @yield('schema')

  <link href="{{ asset('images/implant.png') }}" rel="icon">

  {{-- Google Fonts — preconnect a gyorsabb betöltésért --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400&family=Raleway:wght@300;400;500;600;700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&display=swap" rel="stylesheet">

  {{-- Vendor CSS --}}
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

  {{-- Template CSS --}}
  <link href="{{ asset('style.css') }}" rel="stylesheet">
  <link href="{{ asset('custom.css') }}" rel="stylesheet">

  @stack('styles')

  {{-- Google Analytics 4 --}}
  @if(config('services.ga4.tracking_id'))
  <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('services.ga4.tracking_id') }}"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '{{ config('services.ga4.tracking_id') }}');

    // Telefonhívás esemény követése
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('a[href^="tel:"]').forEach(function(el) {
        el.addEventListener('click', function() {
          gtag('event', 'phone_call', { 'event_category': 'contact', 'event_label': el.href });
        });
      });
    });
  </script>
  @endif
</head>
```

- [ ] **Step 4: Ellenőrzés**

1. Böngészőben `Ctrl+U` (forrás megtekintése)
2. Keresni: `og:image` — megjelenik a kép URL
3. Keresni: `twitter:card` — megjelenik `summary_large_image`
4. Keresni: `fonts.googleapis.com` — csak egy sor van és `display=swap` szerepel benne
5. Ha `GA4_TRACKING_ID` ki van töltve `.env`-ben: keresni `gtag` — megjelenik a script

---

### Task 3: home.blade.php — H1 javítás, openingHours, sameAs

**Files:**
- Modify: `resources/views/home.blade.php`

**Interfaces:**
- Consumes: Nincs külső függőség
- Produces: Egyetlen H1 tag az oldalon; kitöltött openingHours schema; sameAs placeholder a schema-ban

- [ ] **Step 1: Hero carousel H1 → H2 csere (2–7. slide)**

A `home.blade.php`-ban az 1. slide H1-je marad, a 2–7. slide `<h1>` tagjai `<h2>`-re változnak.

A jelenlegi kód minden slide-ban:
```html
<h1 class="hero-slide-title">...</h1>
```

Csak a **2. slide-tól** (`data-index="1"`-től) kell módosítani — ezek `<h2 class="hero-slide-title">`-re változnak. Az 1. slide (`data-index="0"`) `<h1>`-je **marad**.

2. slide (`data-index="1"`, Gyökérkezelés):
```html
<h2 class="hero-slide-title">
  Ne hagyja, hogy a fájdalomtól való félelem<br>
  megakadályozza a <em>fogmegőrzést</em>.
</h2>
```

3. slide (`data-index="2"`, Fogtömés):
```html
<h2 class="hero-slide-title">
  A szuvasodás ellen az egyetlen válasz<br>
  az <em>időben elvégzett kezelés</em>.
</h2>
```

4. slide (`data-index="3"`, Fogpótlás):
```html
<h2 class="hero-slide-title">
  Hiányzó fog? Állítsuk vissza<br>
  <em>mosolyát és önbizalmát</em>.
</h2>
```

5. slide (`data-index="4"`, Prevenció):
```html
<h2 class="hero-slide-title">
  Ne várjon a panaszra —<br>
  a megelőzés a <em>legokosabb befektetés</em>.
</h2>
```

6. slide (`data-index="5"`, Foghúzás):
```html
<h2 class="hero-slide-title">
  Amikor a fog már nem menthető meg,<br>
  <em>gyors és fájdalommentes megoldás</em>.
</h2>
```

7. slide (`data-index="6"`, Fogfehérítés):
```html
<h2 class="hero-slide-title">
  Ragyogó mosoly — mert<br>
  az <em>első benyomás számít</em>.
</h2>
```

- [ ] **Step 2: Schema.org — openingHoursSpecification kitöltése**

A `@section('schema')` blokkban az üres `"openingHoursSpecification": []` helyett:

```json
"openingHoursSpecification": [
  {
    "@type": "OpeningHoursSpecification",
    "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
    "opens": "08:00",
    "closes": "18:00"
  }
],
```

> **Figyelem:** Ha az aktuális nyitvatartás eltér (pl. szombat is nyitva, vagy más időpontok), módosítsd a fenti értékeket. A `dayOfWeek` lehetséges értékei: `Monday`, `Tuesday`, `Wednesday`, `Thursday`, `Friday`, `Saturday`, `Sunday`.

- [ ] **Step 3: Schema.org — sameAs és priceRange hozzáadása**

Az `"image"` sor után hozzáadni:

```json
"sameAs": [
  "https://www.facebook.com/FACEBOOK_URL_IDE",
  "https://g.page/GOOGLE_BUSINESS_ID_IDE"
],
```

> **Figyelem:** A `FACEBOOK_URL_IDE` és `GOOGLE_BUSINESS_ID_IDE` helyőrzőket a tényleges URL-ekkel kell kitölteni. A Facebook URL már most ismert ha van oldal. A Google Business Profile URL a GBP létrehozása után lesz elérhető.

- [ ] **Step 4: Ellenőrzés**

1. Böngészőben `Ctrl+U` → keresni `<h1` — csak 1 darab legyen
2. Google Rich Results Test: `https://search.google.com/test/rich-results` → beilleszteni az oldal URL-jét → ellenőrizni hogy a Dentist schema valid és nincs hiányzó mező
3. `openingHoursSpecification` megjelenik hibamentesen

---

### Task 4: sitemap.blade.php — lastmod hozzáadása

**Files:**
- Modify: `resources/views/sitemap.blade.php`

**Interfaces:**
- Produces: Minden URL mellé `<lastmod>` kerül, amit a Google prioritásként kezel frissítéseknél

- [ ] **Step 1: Sitemap template frissítése**

A teljes `sitemap.blade.php` tartalma:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ now()->toDateString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ route('arlista') }}</loc>
        <lastmod>2026-06-01</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>{{ route('galeria') }}</loc>
        <lastmod>2026-06-01</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    @foreach($kategoriak as $kat)
    <url>
        <loc>{{ route('szolgaltatas.show', $kat->slug) }}</loc>
        <lastmod>{{ $kat->updated_at->toDateString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.9</priority>
    </url>
    @endforeach
</urlset>
```

- [ ] **Step 2: Ellenőrzés**

Böngészőben megnyitni: `http://localhost/sitemap.xml`  
Elvárt: minden `<url>` blokkban megjelenik a `<lastmod>` tag, érvényes dátummal.

---

### Task 5: szolgaltatas.blade.php — BreadcrumbList schema + city keywords

**Files:**
- Modify: `resources/views/szolgaltatas.blade.php`

**Interfaces:**
- Consumes: `$kategoria` (Kategoria model, `nev`, `slug`, `leiras` mezőkkel)
- Produces: `BreadcrumbList` schema minden szolgáltatás oldalon; lokális + regionális kulcsszavak a meta description-ben

- [ ] **Step 1: Meta description kibővítése regionális kulcsszavakkal**

A fájl tetején a `@section('description', ...)` sor:

```php
@section('description', 'Dr. Nagy-Fazakas Csongor fogászati rendelő — ' . $kategoria->nev . ' Miskolcon. Elérhető Eger, Ózd, Kazincbarcika és Nyíregyháza közeléből is. Időpontfoglalás: +36 70 627 6160')
```

- [ ] **Step 2: BreadcrumbList schema hozzáadása a `@section('schema')` blokkhoz**

A meglévő `MedicalProcedure` script tag **mellé** (nem helyette) hozzáadni egy új script blokkot:

```html
@section('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "MedicalProcedure",
  "name": "{{ $kategoria->nev }}",
  "description": "{{ strip_tags($kategoria->leiras) }}",
  "provider": {
    "@type": "Dentist",
    "name": "Dr. Nagy-Fazakas Csongor",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "Madarász Viktor utca 13/A",
      "addressLocality": "Miskolc",
      "postalCode": "3525",
      "addressCountry": "HU"
    },
    "telephone": "+36706276160"
  }
}
</script>
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "Főoldal",
      "item": "{{ url('/') }}"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "Szolgáltatásaink",
      "item": "{{ url('/') }}#what-we-do"
    },
    {
      "@type": "ListItem",
      "position": 3,
      "name": "{{ $kategoria->nev }}"
    }
  ]
}
</script>
@endsection
```

- [ ] **Step 3: Ellenőrzés**

1. Megnyitni egy szolgáltatás oldalt pl. `/szolgaltatasok/fogszabalyozas`
2. `Ctrl+U` → keresni `BreadcrumbList` — megjelenik a schema
3. Google Rich Results Test-be beilleszteni az URL-t → `BreadcrumbList` és `MedicalProcedure` is valid legyen
4. Meta description-ben szerepeljen "Eger" és "Ózd"
