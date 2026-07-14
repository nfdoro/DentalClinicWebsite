@extends('layouts.app')

@section('title', 'Árlista - Dr. Nagy-Fazakas Csongor Fogászat Miskolc')
@section('description', 'Dr. Nagy-Fazakas Csongor fogászati rendelő árlistája. Gyökérkezelés, fogszabályozás, fogtömés, fogpótlás és fogfehérítés árak Miskolcon.')
@section('keywords', 'fogászat árlista miskolc, fogorvos árak, gyökérkezelés ár, fogszabályozás ár, fogtömés ár, fogpótlás ár')

@section('content')

  <main id="main" class="arlista-main">
    <div class="container">
      <section id="breadcrumbs" class="breadcrumbs"></section>

      <div class="section-title">
        <h2>Árlista</h2>
      </div>

      {{-- Gyors-navigáció --}}
      <div id="arlista-nav-sentinel" aria-hidden="true"></div>
      <div class="arlista-nav" id="arlistaNav">
        @foreach($kategoriak as $kat)
          <a href="#kat-{{ $kat->slug }}" class="arlista-nav-link">{{ $kat->nev }}</a>
        @endforeach
      </div>

      {{-- Görgetéskor balra kicsúszó dokkolt változat (asztalon pill-oszlop, mobilon hamburger) --}}
      <div class="arlista-nav-docked" id="arlistaNavDocked">
        <button type="button" class="arlista-nav-toggle" aria-label="Kategóriák megnyitása" aria-expanded="false">
          <i class="bi bi-list"></i>
        </button>
        <div class="arlista-nav-docked-list">
          @foreach($kategoriak as $kat)
            <a href="#kat-{{ $kat->slug }}" class="arlista-nav-link">{{ $kat->nev }}</a>
          @endforeach
        </div>
      </div>

      <div class="row">
        @foreach($kategoriak as $kat)
          <table id="kat-{{ $kat->slug }}">
            <tr>
              <td><h3>{{ $kat->nev }}</h3></td>
            </tr>
          </table>
          @foreach($kat->arlistaTetelei as $adat)
          <table>
            <tr>
              <td class="arlista-muveletnev">{{ $adat->muveletnev }}</td>
              <td style="text-align: right"><strong>
                {{ $adat->ar_formatted }}
                @if($adat->kiegeszites)
                  <span style="font-weight:400; color:#999; font-size:0.88em;"> / {{ $adat->kiegeszites }}</span>
                @endif
              </strong></td>
            </tr>
          </table>
          @endforeach
        @endforeach
      </div>
    </div>
  </main>

  <script>
    (function () {
      var nav = document.getElementById('arlistaNav');
      var docked = document.getElementById('arlistaNavDocked');
      var sentinel = document.getElementById('arlista-nav-sentinel');
      if (!nav || !docked || !sentinel) return;

      var toggle = docked.querySelector('.arlista-nav-toggle');

      function setDocked(on) {
        nav.classList.toggle('is-hidden', on);
        docked.classList.toggle('is-visible', on);
        if (!on) {
          docked.classList.remove('open');
          if (toggle) toggle.setAttribute('aria-expanded', 'false');
        }
      }

      // A fejléc (~72px) + kis térköz alatt vált dokkolt állapotba
      var io = new IntersectionObserver(function (entries) {
        setDocked(!entries[0].isIntersecting);
      }, { rootMargin: '-80px 0px 0px 0px', threshold: 0 });
      io.observe(sentinel);

      if (toggle) {
        toggle.addEventListener('click', function () {
          var open = docked.classList.toggle('open');
          toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
      }

      docked.querySelectorAll('.arlista-nav-docked-list a').forEach(function (link) {
        link.addEventListener('click', function () {
          docked.classList.remove('open');
          if (toggle) toggle.setAttribute('aria-expanded', 'false');
        });
      });
    })();
  </script>

@endsection
