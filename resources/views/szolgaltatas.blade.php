@extends('layouts.app')

@section('title', $kategoria->nev . ' Miskolc - Dr. Nagy-Fazakas Csongor Fogászat')
@section('description', 'Dr. Nagy-Fazakas Csongor fogászati rendelő - ' . $kategoria->nev . ' kezelések Miskolcon. Hívjon időpontért: +36 70 627 6160')
@section('keywords', strtolower($kategoria->nev) . ' miskolc, ' . strtolower($kategoria->nev) . ' ár, fogorvos miskolc')

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
@endsection

@section('content')

  {{-- Világos oldal fejléc --}}
  <div class="szolg-page-header">
    <div class="container">
      <p class="szolgaltatas-breadcrumb">
        <a href="{{ route('home') }}">Főoldal</a>
        <span>/</span>
        <a href="{{ route('home') }}#what-we-do">Szolgáltatásaink</a>
        <span>/</span>
        {{ $kategoria->nev }}
      </p>
      <h1 class="szolgaltatas-title">{{ $kategoria->nev }}</h1>
    </div>
  </div>

  <main id="main">
    <section id="portfolio-details" class="portfolio-details">
      <div class="container">

        <div class="row gy-4">

          {{-- Bal: képek --}}
          @if($kategoria->galeria->count() > 0)
          <div class="col-lg-6">
            <div class="portfolio-details-slider swiper">
              <div class="swiper-wrapper">
                @foreach($kategoria->galeria as $kep)
                <div class="swiper-slide">
                  <img src="{{ asset($kep->fajlnev) }}" alt="{{ $kep->rovidleiras }}">
                </div>
                @endforeach
              </div>
              <div class="swiper-pagination"></div>
              <div class="swiper-button-prev"></div>
              <div class="swiper-button-next"></div>
            </div>
          </div>
          @endif

          {{-- Jobb: info + leírás --}}
          <div class="{{ $kategoria->galeria->count() > 0 ? 'col-lg-6' : 'col-lg-12' }}">

            {{-- Info kártya --}}
            <div class="szolg-info-card">
              <div class="szolg-info-card-body">
                <div class="szolg-meta">
                  <i class="bi bi-geo-alt-fill"></i>
                  <span>Miskolc, Madarász Viktor utca 13/A</span>
                </div>
                <div class="szolg-meta">
                  <i class="bi bi-telephone-fill"></i>
                  <a href="tel:+36706276160">+36 70 627 6160</a>
                </div>
              </div>
              <div class="szolg-cta-row">
                <a href="{{ route('arlista') }}" class="szolg-cta-btn">
                  <i class="bi bi-list-ul"></i>
                  Árlista
                </a>
              </div>
            </div>

            {{-- Leírás --}}
            @if($kategoria->leiras)
            <div class="szolg-leiras">
              {!! $kategoria->leiras !!}
            </div>
            @endif

          </div>
        </div>

        {{-- Többi szolgáltatás --}}
        <div class="szolg-egyeb">
          <h5>Egyéb szolgáltatásaink</h5>
          <div class="szolg-egyeb-grid">
            @foreach(\App\Models\Kategoria::where('szolgaltatas', true)->where('slug', '!=', $kategoria->slug)->get() as $egyeb)
            <a href="{{ route('szolgaltatas.show', $egyeb->slug) }}" class="szolg-egyeb-item">
              @if($egyeb->icon)
              <img src="{{ asset($egyeb->icon) }}" alt="{{ $egyeb->nev }}">
              @endif
              <span>{{ $egyeb->nev }}</span>
            </a>
            @endforeach
          </div>
        </div>

      </div>
    </section>
  </main>

@endsection
