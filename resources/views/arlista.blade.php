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
        <div class="arlista-search">
          <span class="arlista-search-box">
            <i class="bi bi-search" aria-hidden="true"></i>
            <input type="search" class="arlista-search-input" placeholder="Keresés a beavatkozások között…" aria-label="Keresés az árlistában" autocomplete="off">
          </span>
        </div>
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
          <div class="arlista-search">
            <span class="arlista-search-box">
              <i class="bi bi-search" aria-hidden="true"></i>
              <input type="search" size="1" class="arlista-search-input" placeholder="Keresés…" aria-label="Keresés az árlistában" autocomplete="off">
            </span>
          </div>
          @foreach($kategoriak as $kat)
            <a href="#kat-{{ $kat->slug }}" class="arlista-nav-link">{{ $kat->nev }}</a>
          @endforeach
        </div>
      </div>

      <div class="row">
        @foreach($kategoriak as $kat)
          <table id="kat-{{ $kat->slug }}" class="arlista-kat">
            <tr>
              <td><h3>{{ $kat->nev }}</h3></td>
            </tr>
          </table>
          @foreach($kat->arlistaTetelei as $adat)
          <table class="arlista-item">
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
        <p id="arlista-no-results" class="arlista-no-results" style="display:none;">Nincs a keresésnek megfelelő tétel.</p>
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

      // ── Dokkolt oszlop szélessége = a legszélesebb kategória-gomb ──
      var dockList = docked.querySelector('.arlista-nav-docked-list');
      function sizeDockList() {
        if (!dockList) return;
        if (window.innerWidth <= 768) { dockList.style.width = ''; return; }
        var links = dockList.querySelectorAll('.arlista-nav-link');
        if (!links.length) return;
        dockList.style.width = 'auto';
        dockList.style.alignItems = 'flex-start';
        var max = 0;
        for (var i = 0; i < links.length; i++) {
          if (links[i].offsetWidth > max) max = links[i].offsetWidth;
        }
        dockList.style.alignItems = 'stretch';
        dockList.style.width = Math.ceil(max) + 'px';
      }
      sizeDockList();
      window.addEventListener('resize', sizeDockList);
      if (document.fonts && document.fonts.ready) {
        document.fonts.ready.then(sizeDockList);
      }

      // ── Keresés / szűrés ──
      var inputs = document.querySelectorAll('.arlista-search-input');
      var noResults = document.getElementById('arlista-no-results');
      var rowEl = document.querySelector('.arlista-main .row');
      if (inputs.length && rowEl) {
        // ékezet-érzéketlen összehasonlításhoz (kombináló jelek 0x0300–0x036F kihagyása)
        var norm = function (s) {
          s = (s || '').toLowerCase().normalize('NFD');
          var out = '';
          for (var i = 0; i < s.length; i++) {
            var c = s.charCodeAt(i);
            if (c < 0x0300 || c > 0x036f) out += s.charAt(i);
          }
          return out;
        };
        var applyFilter = function (query) {
          var q = norm(query.trim());
          var kids = rowEl.children, currentHeader = null, headerHasMatch = false, anyMatch = false;
          var flush = function () {
            if (currentHeader) currentHeader.style.display = headerHasMatch ? '' : 'none';
          };
          for (var i = 0; i < kids.length; i++) {
            var el = kids[i];
            if (el.classList.contains('arlista-kat')) {
              flush();
              currentHeader = el;
              headerHasMatch = false;
            } else if (el.classList.contains('arlista-item')) {
              var match = !q || norm(el.textContent).indexOf(q) !== -1;
              el.style.display = match ? '' : 'none';
              if (match) { headerHasMatch = true; anyMatch = true; }
            }
          }
          flush();
          if (noResults) noResults.style.display = (q && !anyMatch) ? '' : 'none';
        };
        inputs.forEach(function (inp) {
          inp.addEventListener('input', function () {
            inputs.forEach(function (other) { if (other !== inp) other.value = inp.value; });
            applyFilter(inp.value);
          });
        });
      }
    })();
  </script>

@endsection
