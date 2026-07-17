@extends('layouts.app')

@section('title', 'Fogászat Észak-Magyarország - Dr. Nagy-Fazakas Csongor, Miskolc')
@section('description', 'Fogászati rendelő Miskolcon, Észak-Magyarország szívében. Eger, Ózd, Kazincbarcika, Nyíregyháza és Tiszaújváros közeléből is könnyen elérhető. Fogszabályozás, implantátum, fogfehérítés.')
@section('og_image', asset('images/rolunk.jpg'))

@section('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "Dentist",
  "name": "Dr. Nagy-Fazakas Csongor Fogászati Rendelő",
  "description": "Fogászati rendelő Miskolcon, Észak-Magyarország egész területéről elérhető. Fogszabályozás, implantátum, fogfehérítés.",
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
  <section class="portfolio-details eszak-section">
    <div class="container">
      <div class="eszak-grid">

        <div class="eszak-main">
          <h2 class="eszak-h2">Miért érdemes Miskolcra utazni fogászati kezelésre?</h2>
          <p class="eszak-lead">
            Dr. Nagy-Fazakas Csongor fogászati rendelője Miskolc belvárosában, a Madarász Viktor utcán található,
            Észak-Magyarország jól megközelíthető pontján. Akár Egerből, Ózdról, Kazincbarcikáról vagy
            Nyíregyházáról érkezik, a rendelő autóval kényelmesen elérhető.
          </p>

          <h3 class="eszak-h3">Megközelíthetőség városonként</h3>
          <div class="eszak-table-wrap">
            <table class="eszak-table">
              <thead>
                <tr><th>Város</th><th>Távolság</th><th>Menetidő</th><th>Útvonal</th></tr>
              </thead>
              <tbody>
                <tr><td>Eger</td><td>~60 km</td><td>~50 perc</td><td>M25, Miskolc</td></tr>
                <tr><td>Ózd</td><td>~45 km</td><td>~45 perc</td><td>26-os főút</td></tr>
                <tr><td>Kazincbarcika</td><td>~20 km</td><td>~20 perc</td><td>26-os főút</td></tr>
                <tr><td>Nyíregyháza</td><td>~80 km</td><td>~70 perc</td><td>M3, M30</td></tr>
                <tr><td>Tiszaújváros</td><td>~30 km</td><td>~30 perc</td><td>35-ös főút</td></tr>
              </tbody>
            </table>
          </div>

          <h3 class="eszak-h3">Miért éri meg az utazás?</h3>
          <ul class="eszak-elonyok">
            <li><strong>10+ év tapasztalat:</strong> komplex implantátum és fogszabályozó kezelések</li>
            <li><strong>Fogszabályozási konzultáció</strong> lenyomattal és kezelési tervvel</li>
            <li><strong>Egyéni, személyes ellátás,</strong> nem tömeggyártás</li>
            <li><strong>Parkolás:</strong> a rendelő közelében ingyenes parkoló érhető el</li>
          </ul>

          <h3 class="eszak-h3">Elérhető kezelések</h3>
          <div class="szolg-egyeb-grid">
            @foreach($szolgaltatasok as $s)
            <a href="{{ route('szolgaltatas.show', $s->slug) }}" class="szolg-egyeb-item">
              <span class="szolg-egyeb-icon">
                @if($s->icon)
                <img src="{{ asset($s->icon) }}" alt="">
                @else
                <i class="bi bi-check2"></i>
                @endif
              </span>
              <span class="szolg-egyeb-nev">{{ $s->nev }}</span>
              <i class="bi bi-arrow-right szolg-egyeb-arrow"></i>
            </a>
            @endforeach
          </div>
        </div>

        <aside class="eszak-side">
          <div class="eszak-card">
            <h3 class="eszak-card-title">Kapcsolat és időpontfoglalás</h3>
            <div class="eszak-meta">
              <i class="bi bi-geo-alt-fill" aria-hidden="true"></i>
              <span>{{ config('kapcsolat.cim.egysoros') }}</span>
            </div>
            <div class="eszak-meta">
              <i class="bi bi-telephone-fill" aria-hidden="true"></i>
              <a href="tel:{{ config('kapcsolat.telefon_hivas') }}">{{ config('kapcsolat.telefon') }}</a>
            </div>
            <div class="eszak-meta">
              <i class="bi bi-envelope-fill" aria-hidden="true"></i>
              <a href="mailto:{{ config('kapcsolat.email') }}">{{ config('kapcsolat.email') }}</a>
            </div>
            <div class="eszak-card-actions">
              <a href="tel:{{ config('kapcsolat.telefon_hivas') }}" class="eszak-btn">
                <i class="bi bi-telephone" aria-hidden="true"></i> Hívás
              </a>
              <a href="{{ route('arlista') }}" class="eszak-btn eszak-btn-ghost">
                <i class="bi bi-list-ul" aria-hidden="true"></i> Árlista
              </a>
            </div>
          </div>

          <div class="eszak-map">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d792.0695239355053!2d20.787230008431443!3d48.106499155561345!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47409ffcf4e6bc09%3A0xf5ca4089e8674680!2sMiskolc%2C%20Madar%C3%A1sz%20Viktor%20u.%2013%2C%203525%20Magyarorsz%C3%A1g!5e0!3m2!1shu!2sro!4v1682521750086!5m2!1shu!2sro"
              width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"
              title="Dr. Nagy-Fazakas Csongor fogászati rendelő helyszíne">
            </iframe>
          </div>
        </aside>

      </div>
    </div>
  </section>
</main>

@endsection
