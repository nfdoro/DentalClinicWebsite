@extends('layouts.app')

@section('title', $cikk->cim . ' - Dr. Nagy-Fazakas Csongor Fogászat')
@section('description', $cikk->meta_leiras ?? Str::limit($cikk->bevezeto, 160))
@section('og_image', $ogKep)
@section('og_type', 'article')

@section('og_extra')
<meta property="article:published_time" content="{{ $cikk->published_at->toIso8601String() }}">
<meta property="article:modified_time" content="{{ $cikk->updated_at->toIso8601String() }}">
<meta property="article:author" content="Dr. Nagy-Fazakas Csongor">
<meta property="article:section" content="Fogászat">
@endsection

@section('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "Article",
  "headline": "{{ $cikk->cim }}",
  "description": "{{ $cikk->meta_leiras ?? Str::limit($cikk->bevezeto, 160) }}",
  "datePublished": "{{ $cikk->published_at->toIso8601String() }}",
  "dateModified": "{{ $cikk->updated_at->toIso8601String() }}",
  "author": {
    "@type": "Person",
    "name": "Dr. Nagy-Fazakas Csongor"
  },
  "publisher": {
    "@type": "Dentist",
    "name": "Dr. Nagy-Fazakas Csongor Fogászati Rendelő",
    "url": "{{ url('/') }}"
  },
  "image": "{{ $cikk->boritekep ? asset($cikk->boritekep) : asset('images/rolunk.jpg') }}",
  "url": "{{ route('blog.show', $cikk->slug) }}"
}
</script>
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    { "@type": "ListItem", "position": 1, "name": "Főoldal", "item": "{{ url('/') }}" },
    { "@type": "ListItem", "position": 2, "name": "Blog", "item": "{{ route('blog.index') }}" },
    { "@type": "ListItem", "position": 3, "name": "{{ $cikk->cim }}" }
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
      <a href="{{ route('blog.index') }}">Blog</a>
      <span>/</span>
      {{ Str::limit($cikk->cim, 40) }}
    </p>
    <h1 class="szolgaltatas-title">{{ $cikk->cim }}</h1>
  </div>
</div>

<main id="main">
  <article class="blog-cikk-section">
    <div class="container">
      <div class="blog-cikk-meta">
        <span>{{ $cikk->published_at->translatedFormat('Y. F j.') }}</span>
        <span>·</span>
        <span>{{ $cikk->olvasasi_ido }} perc olvasás</span>
      </div>

      @if($cikk->boritekep)
      <div class="blog-cikk-boritekep">
        <img src="{{ asset($cikk->boritekep) }}" alt="{{ $cikk->cim }}" loading="eager">
      </div>
      @endif

      @php
        // A CMS-ből érkező táblázatokat vízszintesen görgethető keretbe fogjuk,
        // hogy mobilon ne okozzanak vízszintes túlcsordulást.
        $tartalom = preg_replace('/<table/i', '<div class="blog-table-wrap"><table', $cikk->tartalom);
        $tartalom = preg_replace('#</table>#i', '</table></div>', $tartalom);
      @endphp
      <div class="blog-cikk-tartalom">
        {!! $tartalom !!}
      </div>

      @php
        $cikkUrl = route('blog.show', $cikk->slug);
        $fbAppId = config('services.facebook.app_id');
        // A Share Dialog a bevezetővel előtölti a poszt szövegét (quote), de ez csak akkor
        // működik jól a látogatóknál, ha az app Live és a domain fel van véve, ezért kapcsolóhoz
        // kötjük. Enélkül a megbízható, egyszerű link-megosztás megy.
        $fbShareUrl = ($fbAppId && config('services.facebook.share_dialog'))
            ? 'https://www.facebook.com/dialog/share?app_id='.$fbAppId.'&display=popup'
                .'&href='.urlencode($cikkUrl)
                .'&quote='.urlencode($cikk->bevezeto)
                .'&redirect_uri='.urlencode($cikkUrl)
            : 'https://www.facebook.com/sharer/sharer.php?u='.urlencode($cikkUrl);
      @endphp
      <div class="blog-megosztas" aria-label="Cikk megosztása">
        <span class="blog-megosztas-cimke">Megosztás</span>
        <button type="button" class="blog-share-btn blog-share-fb" aria-haspopup="dialog" aria-label="Megosztás Facebookon">
          <i class="bi bi-facebook" aria-hidden="true"></i>
        </button>
        <button type="button" class="blog-share-btn blog-share-copy" data-url="{{ route('blog.show', $cikk->slug) }}"
                aria-label="Link másolása a vágólapra">
          <i class="bi bi-link-45deg" aria-hidden="true"></i>
        </button>
      </div>
      <div class="blog-share-modal" id="blogShareModal" hidden>
        <div class="blog-share-modal-overlay" data-close></div>
        <div class="blog-share-modal-box" role="dialog" aria-modal="true" aria-labelledby="blogShareModalCim">
          <button type="button" class="blog-share-modal-x" data-close aria-label="Bezárás"><i class="bi bi-x-lg"></i></button>
          <h3 id="blogShareModalCim">Megosztás a Facebookra</h3>
          <p class="blog-share-modal-lead">
            1. Másold ki a leírást. &nbsp;2. Kattints a „Tovább a Facebookra" gombra, és illeszd be a bejegyzés szövegébe (Ctrl+V). A cikk linkje a kártyát automatikusan hozzáadja.
          </p>
          <textarea class="blog-share-modal-szoveg" id="blogShareModalSzoveg" readonly rows="5">{{ $cikk->bevezeto }}</textarea>
          <div class="blog-share-modal-gombok">
            <button type="button" class="blog-share-modal-copy" id="blogShareModalCopy">
              <i class="bi bi-clipboard" aria-hidden="true"></i> <span>Leírás másolása</span>
            </button>
            <a class="blog-share-modal-fb" href="{{ $fbShareUrl }}" target="_blank" rel="noopener">
              <i class="bi bi-facebook" aria-hidden="true"></i> Tovább a Facebookra
            </a>
          </div>
        </div>
      </div>

      <div class="blog-cikk-cta">
        <p>Kérdése van? Hívjon minket!</p>
        <a href="tel:{{ config('kapcsolat.telefon_hivas') }}" class="szolg-cta-btn">
          <i class="bi bi-telephone"></i> {{ config('kapcsolat.telefon') }}
        </a>
      </div>

      <div class="blog-cikk-vissza">
        <a href="{{ route('blog.index') }}"><i class="bi bi-arrow-left"></i> Vissza a bloghoz</a>
      </div>
    </div>
  </article>

  @if($kapcsolodo->isNotEmpty())
  <section class="blog-kapcsolodo-section">
    <div class="container">
      <h2 class="blog-kapcsolodo-cim">Kapcsolódó cikkek</h2>
      <div class="row g-4 blog-lista-grid">
        @foreach($kapcsolodo as $k)
        <div class="col-lg-4 col-md-6 d-flex">
          <a href="{{ route('blog.show', $k->slug) }}" class="blog-card">
            <x-blog-borito :kep="$k->boritekep" :cim="$k->cim" />
            <div class="blog-card-body">
              <p class="blog-card-date">{{ $k->published_at->translatedFormat('Y. F j.') }}</p>
              <h3 class="blog-card-cim">{{ $k->cim }}</h3>
              <span class="blog-card-tovabb">Tovább olvasom <i class="bi bi-arrow-right"></i></span>
            </div>
          </a>
        </div>
        @endforeach
      </div>
    </div>
  </section>
  @endif
</main>

@endsection

@push('scripts')
<script>
  (function () {
    // Megbízható másolás: execCommand a click gesztuson belül; navigator.clipboard tartaléknak.
    function masol(szoveg) {
      var ta = document.createElement('textarea');
      ta.value = szoveg;
      ta.setAttribute('readonly', '');
      ta.style.position = 'fixed';
      ta.style.top = '0';
      ta.style.left = '0';
      ta.style.opacity = '0';
      document.body.appendChild(ta);
      ta.focus();
      ta.select();
      var ok = false;
      try { ok = document.execCommand('copy'); } catch (e) { ok = false; }
      document.body.removeChild(ta);
      if (!ok && navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(szoveg).then(function () {}, function () {});
      }
      return ok;
    }

    // Link másolása gomb
    document.querySelectorAll('.blog-share-copy').forEach(function (btn) {
      btn.addEventListener('click', function () {
        masol(btn.getAttribute('data-url'));
        var i = btn.querySelector('i');
        var eredeti = i.className;
        i.className = 'bi bi-check-lg';
        btn.classList.add('is-copied');
        setTimeout(function () { i.className = eredeti; btn.classList.remove('is-copied'); }, 1600);
      });
    });

    // Facebook megosztás modál: leírás kimásolása, majd tovább a Facebookra (a cikk linkjével).
    var modal = document.getElementById('blogShareModal');
    var fbBtn = document.querySelector('.blog-share-fb');
    if (modal && fbBtn) {
      var szovegEl = document.getElementById('blogShareModalSzoveg');
      var copyBtn = document.getElementById('blogShareModalCopy');

      function nyit() {
        modal.hidden = false;
        requestAnimationFrame(function () { modal.classList.add('is-open'); });
        setTimeout(function () { szovegEl.focus(); szovegEl.select(); }, 60);
      }
      function zar() {
        modal.classList.remove('is-open');
        setTimeout(function () { modal.hidden = true; }, 250);
      }

      fbBtn.addEventListener('click', nyit);

      copyBtn.addEventListener('click', function () {
        masol(szovegEl.value);
        szovegEl.focus();
        szovegEl.select();
        var sp = copyBtn.querySelector('span');
        var eredeti = sp.textContent;
        sp.textContent = 'Másolva!';
        copyBtn.classList.add('is-copied');
        setTimeout(function () { sp.textContent = eredeti; copyBtn.classList.remove('is-copied'); }, 1600);
      });

      // A "Tovább a Facebookra" link a cikk megosztási URL-jét nyitja (Blade-ben beállítva);
      // megnyitás után zárjuk a modált.
      var fbLink = modal.querySelector('.blog-share-modal-fb');
      if (fbLink) fbLink.addEventListener('click', function () { setTimeout(zar, 150); });

      modal.querySelectorAll('[data-close]').forEach(function (el) { el.addEventListener('click', zar); });
      document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !modal.hidden) zar(); });
    }
  })();
</script>
@endpush
