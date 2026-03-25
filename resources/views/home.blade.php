@extends('layouts.app')

@section('title', 'Dr. Nagy-Fazakas Csongor - Fogászati Rendelő Miskolc')
@section('description', 'Dr. Nagy-Fazakas Csongor fogászati rendelője Miskolcon. Gyökérkezelés, fogszabályozás, fogtömés, fogpótlás, fogfehérítés és prevenciós kezelések. Hívjon minket: +36 70 627 6160')

@section('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "Dentist",
  "name": "Dr. Nagy-Fazakas Csongor Fogászati Rendelő",
  "description": "Fogászati rendelő Miskolcon. Gyökérkezelés, fogszabályozás, fogtömés, fogpótlás és fogfehérítés.",
  "url": "{{ url('/') }}",
  "telephone": "+36706276160",
  "email": "info@fogaszat-miskolc.hu",
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
  "openingHoursSpecification": [],
  "priceRange": "$$",
  "image": "{{ asset('images/rolunk.jpg') }}",
  "medicalSpecialty": "Dentist",
  "availableService": [
    @foreach($szolgaltatasok as $s)
    { "@type": "MedicalProcedure", "name": "{{ $s->nev }}" }{{ !$loop->last ? ',' : '' }}
    @endforeach
  ]
}
</script>
@endsection

@section('content')

  {{-- ======= Hero ======= --}}
  <section id="hero" class="d-flex flex-column justify-content-center align-items-center">
    <div class="container text-center" data-aos="fade-up">
      <h1>Látogasson el a <span>fogorvosához</span>,<br>
        ne hagyja, hogy a szenvedéstől való félelme nagyobb legyen,<br>
        mint <span>mosolygási vágya</span>.</h1>
      <h2>Magyarország <br>
        Miskolc <br>
        Madarász Viktor utca 13/A <br>
        2. emelet, 03 kapucsengő
      </h2>
    </div>
  </section>
  {{-- ======= End Hero ======= --}}

  <main id="main">

    {{-- ======= Rólunk ======= --}}
    <section id="about" class="about">
      <div class="container">
        <div class="section-title">
          <h2>Rólunk</h2>
        </div>
        <div class="row g-4">
          <div class="col-lg-6">
            <div class="about-img-wrap">
              <img src="{{ asset('images/rolunk.jpg') }}" class="img-fluid" alt="Dr. Nagy-Fazakas Csongor fogászati rendelő">
            </div>
          </div>
          <div class="col-lg-6 pt-4 pt-lg-0">
            <div>
              <ul>
                <li>
                  <h4>Munkám során legfontosabb szempont a páciensekkel kialakított közvetlen, bizalmi viszony,
                  mely lehetővé teszi számukra a szakmai szempontok ismertetése mellett, egyéni elvárásaik érvényesítését.</h4>
                </li>
                <li>
                  <h4>A legnagyobb elégtétel számomra, távozó pácienseim elégedett mosolya.
                  Ennek eléréséért, mindig azon vagyok, hogy szakmai tudásom legjavát nyújtsam (ezt folyamatos továbbképzésekkel gyarapítom) és,
                  hogy egy olyan meghitt, szeretetteljes légkört biztosítsak, ahova örömmel térnek vissza.</h4>
                </li>
              </ul>
            </div>
            <div class="row icon-boxes">
              <div class="col-md-6">
                <i class="bx bx-receipt"></i>
                <h5>2016-ban szereztem diplomát a Marosvásárhelyi Orvosi és Gyógyszerészeti Egyetem Fogorvosi Karán</h5>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    {{-- ======= End Rólunk ======= --}}

    {{-- ======= Szolgáltatásaink ======= --}}
    <section id="what-we-do" class="what-we-do">
      <div class="container">
        <div class="section-title">
          <h2>Szolgáltatásaink</h2>
        </div>
        <div class="row g-4">
          @foreach($szolgaltatasok as $kat)
          <div class="col-lg-4 col-md-6 d-flex align-items-stretch">
            <a href="{{ route('szolgaltatas.show', $kat->slug) }}" class="icon-box w-100 text-decoration-none">
              <div class="icon">
                @if($kat->icon)
                  <img src="{{ asset($kat->icon) }}" alt="{{ $kat->nev }}">
                @else
                  <i class="bx bx-plus-medical"></i>
                @endif
              </div>
              <h4>{{ $kat->nev }}</h4>
              <p>Kattintson ide a részletekért</p>
            </a>
          </div>
          @endforeach
        </div>
      </div>
    </section>
    {{-- ======= End Szolgáltatásaink ======= --}}

    {{-- ======= Galéria ======= --}}
    <section id="portfolio" class="portfolio">
      <div class="container">
        <div class="section-title">
          <h2>Galéria</h2>
          <p>Munkáink</p>
        </div>

        <div class="row">
          <div class="col-lg-12">
            <ul id="portfolio-flters">
              <li data-filter="*" class="filter-active">All</li>
              @foreach($galeriaKategoriak as $kat)
              @if($kat->galeria->count() > 0)
              <li data-filter=".filter-{{ $kat->slug }}">{{ $kat->nev }}</li>
              @endif
              @endforeach
            </ul>
          </div>
        </div>

        <div class="row portfolio-container">
          @foreach($galeriaKategoriak as $kat)
          @foreach($kat->galeria->take(2) as $kep)
          @if($kep->fajlnev)
          <div class="col-lg-3 col-md-4 portfolio-item filter-{{ $kat->slug }}">
            <div class="portfolio-wrap">
              <figure>
                <img src="{{ asset($kep->fajlnev) }}" class="img-fluid" alt="{{ $kep->rovidleiras }}">
                <a href="{{ asset($kep->fajlnev) }}" class="link-preview portfolio-lightbox" data-gallery="portfolioGallery" title="{{ $kep->rovidleiras }}">
                  <i class="bx bx-plus"></i>
                </a>
                <a href="{{ route('szolgaltatas.show', $kat->slug) }}" class="link-details" title="Részletek">
                  <i class="bx bx-link"></i>
                </a>
              </figure>
              <div class="portfolio-info">
                <p>{{ $kep->rovidleiras }}</p>
              </div>
            </div>
          </div>
          @endif
          @endforeach
          @endforeach
        </div>

        {{-- Galéria gomb --}}
        <div class="text-center mt-5">
          <a href="{{ route('galeria') }}" class="portfolio-more-btn">
            Összes kép megtekintése &nbsp;→
          </a>
        </div>

      </div>
    </section>
    {{-- ======= End Galéria ======= --}}

    {{-- ======= Csapatunk ======= --}}
    <section id="team" class="team">
      <div class="container">
        <div class="section-title">
          <h2>Csapatunk</h2>
          <p>Ismerje meg orvosainkat, asszisztenseinket</p>
        </div>
        <div class="row justify-content-center g-4">
          <div class="col-lg-4 col-md-6 d-flex">
            <div class="member w-100">
              <img src="{{ asset('images/drnfcsongor.jpg') }}" alt="Dr. Nagy-Fazakas Csongor">
              <h4>Dr. Nagy-Fazakas Csongor</h4>
              <p>Fogorvos, rendelő vezető</p>
              <div class="social">
                <a href=""><i class="bi bi-facebook"></i></a>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 d-flex">
            <div class="member w-100">
              <img src="{{ asset('images/szilvi.jpg') }}" alt="Nagy-Fazakas Szilvia">
              <h4>Nagy-Fazakas Szilvia</h4>
              <p>Asszisztens</p>
              <div class="social">
                <a href=""><i class="bi bi-facebook"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    {{-- ======= End Csapatunk ======= --}}

    {{-- ======= Elérhetőségek ======= --}}
    <section id="contact" class="contact section-bg">
      <div class="container">
        <div class="section-title">
          <h2>Elérhetőségek</h2>
        </div>

        <div class="row justify-content-center">
          <div class="col-lg-10">
            <div class="info-wrap">
              <div class="row">
                <div class="col-lg-4 info">
                  <i class="bi bi-geo-alt"></i>
                  <h4>Helyszín:</h4>
                  <p>Magyarország, Miskolc<br>Madarász Viktor utca 13/A<br>2. emelet, 03 kapucsengő</p>
                </div>
                <div class="col-lg-4 info mt-4 mt-lg-0">
                  <i class="bi bi-envelope"></i>
                  <h4>Email:</h4>
                  <p>info@fogaszat-miskolc.hu</p>
                </div>
                <div class="col-lg-4 info mt-4 mt-lg-0">
                  <i class="bi bi-phone"></i>
                  <h4>Telefonszám:</h4>
                  <p><a href="tel:+36706276160">+36 70 627 6160</a></p>
                </div>
              </div>
            </div>
          </div>

          <div id="map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d792.0695239355053!2d20.787230008431443!3d48.106499155561345!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47409ffcf4e6bc09%3A0xf5ca4089e8674680!2sMiskolc%2C%20Madar%C3%A1sz%20Viktor%20u.%2013%2C%203525%20Magyarorsz%C3%A1g!5e0!3m2!1shu!2sro!4v1682521750086!5m2!1shu!2sro"
              width="1000" height="450" style="border:0;" allowfullscreen="" loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"
              title="Dr. Nagy-Fazakas Csongor fogászati rendelő helyszíne"></iframe>
          </div>
        </div>
      </div>
    </section>
    {{-- ======= End Elérhetőségek ======= --}}

  </main>

@endsection
