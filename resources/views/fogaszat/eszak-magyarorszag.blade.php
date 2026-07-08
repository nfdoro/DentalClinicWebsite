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
