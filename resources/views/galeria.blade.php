@extends('layouts.app')

@section('title', 'Galéria - Dr. Nagy-Fazakas Csongor Fogászat Miskolc')
@section('description', 'Dr. Nagy-Fazakas Csongor fogászati rendelő galéria - gyökérkezelés, fogszabályozás, fogtömés, fogpótlás és fogfehérítés előtte-utána képek.')

@section('content')

  <main id="main">

    {{-- ======= Galéria oldal ======= --}}
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
              @foreach($kategoriak as $kat)
              @if($kat->galeria->count() > 0)
              <li data-filter=".filter-{{ $kat->slug }}">{{ $kat->nev }}</li>
              @endif
              @endforeach
            </ul>
          </div>
        </div>

        <div class="row portfolio-container">
          @foreach($kategoriak as $kat)
          @foreach($kat->galeria as $kep)
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

      </div>
    </section>

  </main>

@endsection
