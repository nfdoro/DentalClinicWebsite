@extends('layouts.app')

@section('title', $kategoria->nev . ' Miskolc - Dr. Nagy-Fazakas Csongor Fogászat')
@section('description', 'Dr. Nagy-Fazakas Csongor fogászati rendelő — ' . $kategoria->nev . ' Miskolcon. Elérhető Eger, Ózd, Kazincbarcika és Nyíregyháza közeléből is. Időpontfoglalás: +36 70 627 6160')
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
      "item": "{{ url('/') }}"
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

@section('content')

  {{-- ── Sötét fejléc (Magazine stílus) ── --}}
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
    <section class="portfolio-details">
      <div class="container">

        {{-- ── Magazine grid: Bal carousel + Jobb tartalom ── --}}
        <div class="szolg-mag-grid">

          {{-- Bal: sticky carousel + info kártya --}}
          <div class="szolg-mag-left">

            @if($kategoria->galeria->count() > 0)
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
            @endif

            {{-- Info kártya a carousel alatt --}}
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
                  <i class="bi bi-list-ul"></i> Árlista
                </a>
              </div>
            </div>

          </div>

          {{-- Jobb: Lead + Body szöveg --}}
          <div class="szolg-mag-right">
            @if($kategoria->leiras)
            <div class="szolg-leiras">
              {!! $kategoria->leiras !!}
            </div>
            @endif
          </div>

        </div>

        {{-- ── Egyéb szolgáltatások ── --}}
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
