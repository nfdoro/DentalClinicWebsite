# SEO Fejlesztési Terv — Dr. Nagy-Fazakas Csongor Fogászati Rendelő

**Dátum:** 2026-06-30  
**Projekt:** DentalClinicWebsite (Laravel)  
**Cél:** Miskolc-alapú, de országos láthatóság — helyi erő + nemzeti tartalom stratégia

---

## Összefoglaló

A weboldal már rendelkezik alapvető SEO elemekkel (title tagek, meta description, schema.org, sitemap.xml, canonical URL-ek, Open Graph részben). A fejlesztés célja ezeket kiegészíteni és egy teljes, kétszintű SEO stratégiát felépíteni:

1. **Helyi szint:** Miskolc és környéke (Ózd, Kazincbarcika, Eger, Nyíregyháza) — Google Business Profile + local schema
2. **Országos szint:** Invisalign, implantátum, fogfehérítés kulcsszavakra Magyarország-szerte — blog + landing oldalak

**Kiválasztott megközelítés:** Párhuzamos (technikai javítások + tartalom infrastruktúra egyszerre)

---

## 1. pillér: Technikai SEO javítások

### 1.1 Kritikus hibák

**robots.txt — abszolút Sitemap URL**
- Jelenlegi: `Sitemap: /sitemap.xml`
- Javított: `Sitemap: https://fogaszat-miskolc.hu/sitemap.xml`

**Hero carousel H1 probléma**
- Jelenlegi: minden slide-nak van `<h1>` tagje (7 db H1 az oldalon)
- Javítás: csak az első slide marad `<h1>`, a többi `<h2>`-re vált
- Fájl: `resources/views/home.blade.php`

**Hiányzó `og:image` meta tag**
- Hozzáadandó a `layouts/app.blade.php` `<head>` részéhez:
  ```html
  <meta property="og:image" content="@yield('og_image', asset('images/rolunk.jpg'))">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  ```

**`openingHoursSpecification` üres a schema-ban**
- Jelenlegi: `"openingHoursSpecification": []`
- Kitöltendő a valós nyitvatartással (pl. H-P 08:00–18:00)
- Fájl: `resources/views/home.blade.php`

### 1.2 Közepes javítások

**Google Fonts — preconnect + display=swap**
- Jelenlegi: render-blocking betöltés, nincs `display=swap`
- Javítás a `layouts/app.blade.php`-ban:
  ```html
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="...&display=swap" rel="stylesheet">
  ```

**Sitemap — `<lastmod>` hozzáadása**
- Minden URL mellé kerüljön `<lastmod>` dátum
- Statikus oldalaknál fix dátum, szolgáltatásoknál `$kategoria->updated_at`
- Fájl: `resources/views/sitemap.blade.php`

**BreadcrumbList schema — szolgáltatás oldalak**
- A `szolgaltatas.blade.php`-ban már van vizuális breadcrumb
- Hozzáadandó `BreadcrumbList` structured data a `@section('schema')` blokkba:
  ```json
  {
    "@type": "BreadcrumbList",
    "itemListElement": [
      { "@type": "ListItem", "position": 1, "name": "Főoldal", "item": "/" },
      { "@type": "ListItem", "position": 2, "name": "Szolgáltatásaink", "item": "/#what-we-do" },
      { "@type": "ListItem", "position": 3, "name": "{{ $kategoria->nev }}" }
    ]
  }
  ```

**`sameAs` linkek a Dentist schema-ban**
- A `home.blade.php` Dentist schema-ba bekerül:
  ```json
  "sameAs": [
    "https://www.facebook.com/[FACEBOOK_URL]",
    "https://g.page/[GOOGLE_BUSINESS_ID]"
  ]
  ```
- Google Business Profile URL a profil létrehozása után töltendő ki

**FAQ schema — szolgáltatás oldalak**
- Minden szolgáltatás oldalhoz GYIK szekció + `FAQPage` schema
- Kérdések külön `faqs` táblában tárolva (`id`, `kategoria_id`, `kerdes`, `valasz`, `sorrend`) — így egy szolgáltatáshoz több GYIK is felvehető, sorrendezhetők, és admin panelről szerkeszthetők
- Google "People Also Ask" dobozban való megjelenés lehetősége

### 1.3 Kis javítások

**Twitter Card meta tagek**
```html
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="@yield('title')">
<meta name="twitter:description" content="@yield('description')">
<meta name="twitter:image" content="@yield('og_image', asset('images/rolunk.jpg'))">
```

**Search Console hitelesítő meta tag**
```html
@yield('search_console_verify')
```
- A főoldalon (`home.blade.php`) kitölthető a Search Console által adott kóddal

---

## 2. pillér: Local SEO + Google eszközök

### 2.1 Google Business Profile (kódon kívüli feladat)

Lépések (te végzed el):
1. Nyiss fiókot: [business.google.com](https://business.google.com)
2. Kategória: "Fogászat" + "Fogász"
3. Kötelező mezők: cím (3525 Miskolc, Madarász Viktor utca 13/A), telefon (+36 70 627 6160), nyitvatartás
4. Fotók: min. 10 fotó (rendelő belülről, kívülről, csapat)
5. Hitelesítés: postai kártya vagy telefonhívás
6. Miután megvan az URL: bekerül a `sameAs` schema mezőbe

### 2.2 Google Search Console

1. Regisztráció: [search.google.com/search-console](https://search.google.com/search-console)
2. Domain hitelesítés: DNS TXT rekord a tárhelyszolgáltatónál
3. Alternatíva: HTML meta tag (`@yield('search_console_verify')` a layout-ban)
4. Sitemap beküldése: `https://fogaszat-miskolc.hu/sitemap.xml`

### 2.3 Google Analytics 4

1. Új GA4 property létrehozása a Google Analytics fiókban
2. Tracking kód (G-XXXXXXXX) bekerül a `layouts/app.blade.php` `<head>` részébe
3. Eseménykövetés: `tel:` linkre kattintás (telefonhívás mérése)

### 2.4 Lokális kulcsszavak kiterjesztése

A szolgáltatás oldalak meta description-jeibe bekerülnek a közeli városok:
- Ózd, Kazincbarcika, Eger, Nyíregyháza, Tiszaújváros
- Példa: *"Fogászat Miskolcon — Eger, Ózd és Kazincbarcika közeléből is elérhető. Hívjon időpontért!"*
- Fájl: `resources/views/szolgaltatas.blade.php` `@section('description')`

---

## 3. pillér: Blog infrastruktúra

### 3.1 Adatbázis

Új `cikkek` migration:
```
id
cim           (string)
slug          (string, unique)
bevezeto      (text) — rövid összefoglaló, lista oldalon jelenik meg
tartalom      (longText) — teljes HTML tartalom
boritekep     (string, nullable) — fájlútvonal
meta_leiras   (string, nullable) — egyedi meta description
kulcsszavak   (string, nullable) — egyedi keywords
published_at  (timestamp, nullable) — null = vázlat
created_at / updated_at
```

### 3.2 Routes

```php
Route::get('/blog', [CikkController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [CikkController::class, 'show'])->name('blog.show');
```

### 3.3 Admin panel kibővítése

- Új "Cikkek" menüpont az admin felületen
- CRUD: létrehozás, szerkesztés, törlés, közzététel/vázlat kapcsoló
- WYSIWYG szerkesztő (TinyMCE) a `tartalom` mezőhöz — Laravel admin panelbe egyszerűen integrálható CDN-ről
- Borítókép feltöltés
- Meta description és kulcsszó mező (egyedi SEO minden cikkhez)

### 3.4 SEO minden cikknél automatikusan

- `<title>`: `$cikk->cim . ' - Dr. Nagy-Fazakas Csongor Fogászat'`
- `<meta description>`: `$cikk->meta_leiras ?? $cikk->bevezeto`
- `Article` schema.org structured data
- `BreadcrumbList` schema: Főoldal → Blog → Cikk
- Canonical URL automatikus
- `og:image`: borítókép

### 3.5 Sitemap kiterjesztés

```xml
<url>
    <loc>{{ route('blog.show', $cikk->slug) }}</loc>
    <lastmod>{{ $cikk->updated_at->toDateString() }}</lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.7</priority>
</url>
```

### 3.6 Blog megjelenés

- Lista oldal (`/blog`): kártya layout — borítókép, cím, bevezető, dátum, olvasási idő
- Cikk oldal: teljes tartalom, kapcsolódó cikkek szekció (belső linkelés)
- Navigációba bekerül: "Blog" menüpont

### 3.7 Tartalom stratégia (példa cikkek)

Ezeket te írod (AI segítséggel is lehet):

| Cikk | Célzott kulcsszó | Típus |
|------|-----------------|-------|
| Mennyibe kerül az Invisalign Magyarországon 2026-ban? | invisalign ár magyarország | Országos |
| Implantátum vagy híd — melyiket válasszam? | fogimplantátum vagy híd | Országos |
| Fogfehérítés előtt-után: mit várj a kezeléstől? | fogfehérítés tapasztalatok | Országos |
| Fogorvos Miskolcon — miért válasszon minket? | fogorvos miskolc | Helyi |
| Miskolctól Egerig — miért érdemes utazni a fogorvoshoz? | fogászat eger miskolc | Regionális |

---

## 4. pillér: Országos láthatóság — Landing oldalak + belső linkelés

### 4.1 Emelt szintű szolgáltatás oldalak

A három kiemelt kezelésnél (Invisalign, implantátum, fogfehérítés) a jelenlegi rövid szövegek helyett hosszabb, informatívabb tartalom kerül az admin felületről szerkesztve:
- Részletes leírás (500-1000 szó)
- GYIK szekció (FAQPage schema)
- Előtt-után képek galériával
- Árinformáció (vagy "Kérjen árajánlatot" CTA)
- Belső linkek kapcsolódó cikkekre

### 4.2 Regionális landing oldal

```
/fogaszat/eszak-magyarorszag
```
- Egyedi tartalom: megközelíthetőség Eger, Ózd, Kazincbarcika, Nyíregyháza irányából
- Menetidők, közlekedési opciók
- Miért érdemes utazni (ár-érték, szakértelem, Invisalign partner)
- Nem spam: valódi, hasznos információ

### 4.3 Belső linkelési struktúra

```
Főoldal
├── Szolgáltatások (minden slug)
│   ├── → kapcsolódó blog cikkek
│   └── → egyéb szolgáltatások (már megvan "Egyéb szolgáltatásaink")
├── Blog
│   ├── → releváns szolgáltatás oldalak
│   └── → kapcsolódó cikkek
├── Regionális oldal
│   └── → főoldal + szolgáltatások
└── Árlista → minden szolgáltatás oldal
```

### 4.4 Frissített sitemap prioritások

```
/                           priority 1.0
/blog                       priority 0.8
/arlista                    priority 0.8
/szolgaltatasok/{slug}      priority 0.9
/blog/{slug}                priority 0.7
/galeria                    priority 0.7
/fogaszat/eszak-magyarorszag priority 0.6
```

---

## Mérési terv

| Eszköz | Mit mér | Mikor |
|--------|---------|-------|
| Google Search Console | Impressziók, kattintások, rangsor | Hetente ellenőrzés |
| Google Analytics 4 | Látogatók, forrás, telefon kattintás | Havonta riport |
| Google Business Profile | Helyikeresések, irány kérés, hívások | Havonta |

---

## Implementációs sorrend (javasolt)

1. **Hét 1-2:** Technikai SEO javítások (kód) + Google Search Console + GA4 beállítás
2. **Hét 2-3:** Blog infrastruktúra (migration, controller, views, admin panel)
3. **Hét 3-4:** Google Business Profile létrehozása + első 3 blog cikk
4. **Hónap 2:** Emelt szintű szolgáltatás oldalak (Invisalign, implantátum, fogfehérítés)
5. **Hónap 2-3:** Regionális landing oldal + belső linkelés finomhangolása
6. **Folyamatos:** Havi 1-2 blog cikk közzététele
