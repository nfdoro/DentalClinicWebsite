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
@if($kategoria->faqs->count())
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    @foreach($kategoria->faqs as $faq)
    {
      "@type": "Question",
      "name": {{ json_encode($faq->kerdes) }},
      "acceptedAnswer": {
        "@type": "Answer",
        "text": {{ json_encode($faq->valasz) }}
      }
    }{{ !$loop->last ? ',' : '' }}
    @endforeach
  ]
}
</script>
@endif
@endsection

@php
$gyorsTenyek = [
    'fogszabalyozas' => [
        ['icon' => 'calendar3', 'value' => '12–24 hó', 'label' => 'Kezelési idő'],
        ['icon' => 'cash-coin', 'value' => '0 Ft', 'label' => 'Konzultáció'],
        ['icon' => 'gear', 'value' => 'Kapcsos', 'label' => 'Rendszer'],
    ],
    'fogpotlas' => [
        ['icon' => 'calendar3', 'value' => '2–4 hó', 'label' => 'Oszteointegráció'],
        ['icon' => 'cash-coin', 'value' => '0 Ft', 'label' => 'Konzultáció'],
        ['icon' => 'shield-check', 'value' => 'Titán', 'label' => 'Implantátum anyaga'],
    ],
    'fogfeherites' => [
        ['icon' => 'clock', 'value' => '60–90 perc', 'label' => 'Rendelői kezelés'],
        ['icon' => 'cash-coin', 'value' => '0 Ft', 'label' => 'Konzultáció'],
        ['icon' => 'stars', 'value' => '1–3 év', 'label' => 'Eredmény tartóssága'],
    ],
][$kategoria->slug] ?? null;

$galeriaKepek = $kategoria->galeria;

// A kiemelt leírást <h3> határok mentén önálló szakaszokra bontjuk — mindegyik
// szakasz saját, nagy képpel párosítva, váltakozó (cikk-cakk) sorként jelenik meg,
// hogy a szöveg és a képek ne folyjanak egybe, hanem élesen elváljanak egymástól.
$kiemeltBevezeto = null;
$kiemeltSzakaszok = collect();
if ($kategoria->kiemelt_leiras) {
    $darabok = preg_split('/(?=<h3)/i', $kategoria->kiemelt_leiras, -1, PREG_SPLIT_NO_EMPTY);
    foreach ($darabok as $sorszam => $darab) {
        if (preg_match('/^<h3([^>]*)>/i', trim($darab), $m)) {
            $szamozott = preg_replace(
                '/^(\s*<h3[^>]*>)/i',
                '$1<span class="szolg-feature-num">' . str_pad($kiemeltSzakaszok->count() + 1, 2, '0', STR_PAD_LEFT) . '</span>',
                trim($darab),
                1
            );
            $kiemeltSzakaszok->push($szamozott);
        } else {
            $kiemeltBevezeto = ($kiemeltBevezeto ?? '') . $darab;
        }
    }
}

// Képek: az első a bevezető (hero) sorhoz, utána szakaszonként egy-egy —
// ami marad, az a görgő sávba kerül.
$heroKep = $galeriaKepek->get(0);
$szakaszKepek = [];
foreach ($kiemeltSzakaszok as $i => $sz) {
    $szakaszKepek[$i] = $galeriaKepek->get($i + 1);
}
$felhasznaltKepSzam = ($heroKep ? 1 : 0) + $kiemeltSzakaszok->count();
$stripKepek = $galeriaKepek->slice($felhasznaltKepSzam);

// A foglalás-CTA sáv a szakaszok felénél törje meg a hosszú listát.
$ctaUtanIndex = $kiemeltSzakaszok->count() >= 2 ? intdiv($kiemeltSzakaszok->count(), 2) - 1 : null;
@endphp

@section('content')

  {{-- ── Fejléc — márvány háttér ── --}}
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

      @if($gyorsTenyek)
      <div class="szolg-quickfacts">
        @foreach($gyorsTenyek as $qf)
        <div class="szolg-qf-card fade-scale">
          <i class="bi bi-{{ $qf['icon'] }}"></i>
          <div>
            <div class="szolg-qf-value">{{ $qf['value'] }}</div>
            <div class="szolg-qf-label">{{ $qf['label'] }}</div>
          </div>
        </div>
        @endforeach
      </div>
      @endif
    </div>
  </div>

  <main id="main">
    <section class="portfolio-details">
      <div class="container">
        <div class="szolg-article">

          {{-- ── Hero sor: bevezető szöveg + nagy kép, egyedi kezdő megjelenéssel ── --}}
          <div class="szolg-feature-row szolg-feature-row-hero fade-up">
            <div class="szolg-feature-content">
              <span class="szolg-hero-eyebrow">Átfogó útmutató</span>
              <div class="szolg-kiemelt-leiras szolg-hero-text">
                @if($kategoria->leiras)
                  {!! $kategoria->leiras !!}
                @endif
                @if($kiemeltBevezeto)
                  {!! $kiemeltBevezeto !!}
                @endif
              </div>
            </div>
            @if($heroKep)
            <div class="szolg-feature-img-wrap">
              <a href="{{ asset($heroKep->fajlnev) }}" class="szolg-feature-img portfolio-lightbox fade-scale" data-gallery="szolgGaleria" title="{{ $heroKep->rovidleiras }}">
                <img src="{{ asset($heroKep->fajlnev) }}" alt="{{ $heroKep->rovidleiras }}">
              </a>
            </div>
            @endif
          </div>

          {{-- ── Váltakozó (cikk-cakk) szakaszok ── --}}
          @foreach($kiemeltSzakaszok as $i => $szakasz)
          <div class="szolg-feature-row {{ $i % 2 === 1 ? 'szolg-feature-row-reverse' : '' }} fade-up">
            <div class="szolg-feature-content">
              <div class="szolg-kiemelt-leiras">{!! $szakasz !!}</div>
            </div>
            @if($szakaszKepek[$i] ?? null)
            <a href="{{ asset($szakaszKepek[$i]->fajlnev) }}" class="szolg-feature-img portfolio-lightbox fade-scale" data-gallery="szolgGaleria" title="{{ $szakaszKepek[$i]->rovidleiras }}">
              <img src="{{ asset($szakaszKepek[$i]->fajlnev) }}" alt="{{ $szakaszKepek[$i]->rovidleiras }}">
            </a>
            @endif
          </div>

          @if($ctaUtanIndex !== null && $i === $ctaUtanIndex)
          <div class="szolg-cta-band fade-up">
            <div>
              <div class="szolg-cta-title">Foglaljon időpontot konzultációra</div>
              <div class="szolg-cta-sub">
                <i class="bi bi-telephone-fill"></i> +36 70 627 6160
                <span class="szolg-cta-dot">·</span>
                <i class="bi bi-geo-alt-fill"></i> Miskolc, Madarász Viktor u. 13/A
              </div>
            </div>
            <div class="szolg-cta-actions">
              <a href="tel:+36706276160" class="szolg-cta-btn">
                <i class="bi bi-telephone"></i> Elérhetőség
              </a>
              <a href="{{ route('arlista') }}" class="szolg-cta-btn szolg-cta-btn-ghost">
                <i class="bi bi-list-ul"></i> Árlista
              </a>
            </div>
          </div>
          @endif
          @endforeach

          @if($ctaUtanIndex === null)
          <div class="szolg-cta-band fade-up">
            <div>
              <div class="szolg-cta-title">Foglaljon időpontot konzultációra</div>
              <div class="szolg-cta-sub">
                <i class="bi bi-telephone-fill"></i> +36 70 627 6160
                <span class="szolg-cta-dot">·</span>
                <i class="bi bi-geo-alt-fill"></i> Miskolc, Madarász Viktor u. 13/A
              </div>
            </div>
            <div class="szolg-cta-actions">
              <a href="tel:+36706276160" class="szolg-cta-btn">
                <i class="bi bi-telephone"></i> Elérhetőség
              </a>
              <a href="{{ route('arlista') }}" class="szolg-cta-btn szolg-cta-btn-ghost">
                <i class="bi bi-list-ul"></i> Árlista
              </a>
            </div>
          </div>
          @endif

          @if($stripKepek->count() > 0)
          <div class="szolg-gallery-strip fade-up">
            <div class="szolg-gallery-track">
              @foreach($stripKepek->concat($stripKepek) as $kep)
              <a href="{{ asset($kep->fajlnev) }}" class="portfolio-lightbox" data-gallery="szolgGaleria" title="{{ $kep->rovidleiras }}">
                <img src="{{ asset($kep->fajlnev) }}" alt="{{ $kep->rovidleiras }}">
              </a>
              @endforeach
            </div>
          </div>
          @endif

          {{-- ── GYIK szekció ── --}}
          @if($kategoria->faqs->count())
          <div class="szolg-faq fade-up">
            <h2>Gyakran ismételt kérdések</h2>
            <div class="accordion szolg-faq-accordion" id="faqAccordion">
              @foreach($kategoria->faqs as $faq)
              <div class="accordion-item">
                <h3 class="accordion-header">
                  <button class="accordion-button collapsed" type="button"
                          data-bs-toggle="collapse" data-bs-target="#faq{{ $faq->id }}"
                          aria-expanded="false" aria-controls="faq{{ $faq->id }}">
                    <span>{{ $faq->kerdes }}</span>
                    <i class="bi bi-plus szolg-faq-icon"></i>
                  </button>
                </h3>
                <div id="faq{{ $faq->id }}" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                  <div class="accordion-body">{{ $faq->valasz }}</div>
                </div>
              </div>
              @endforeach
            </div>
          </div>
          @endif

          {{-- ── Egyéb szolgáltatások ── --}}
          <div class="szolg-egyeb fade-up">
            <h5>Egyéb szolgáltatásaink</h5>
            <div class="szolg-egyeb-grid">
              @foreach(\App\Models\Kategoria::where('szolgaltatas', true)->where('slug', '!=', $kategoria->slug)->get() as $egyeb)
              <a href="{{ route('szolgaltatas.show', $egyeb->slug) }}" class="szolg-egyeb-item">
                <span class="szolg-egyeb-icon">
                  @if($egyeb->icon)
                  <img src="{{ asset($egyeb->icon) }}" alt="">
                  @else
                  <i class="bi bi-check2"></i>
                  @endif
                </span>
                <span class="szolg-egyeb-nev">{{ $egyeb->nev }}</span>
                <i class="bi bi-arrow-right szolg-egyeb-arrow"></i>
              </a>
              @endforeach
            </div>
          </div>

        </div>
      </div>
    </section>
  </main>

@endsection

@push('scripts')
<script>
  gsap.registerPlugin(ScrollTrigger);

  if (document.querySelector('.szolg-quickfacts')) {
    ScrollTrigger.create({
      trigger: '.szolg-quickfacts',
      start: 'top 90%',
      onEnter: () => {
        gsap.to('.szolg-quickfacts .fade-scale', {
          opacity: 1, scale: 1, duration: 0.55, stagger: 0.1, ease: 'back.out(1.4)'
        });
      },
      once: true
    });
  }

  gsap.utils.toArray('.szolg-article .fade-up').forEach((el) => {
    ScrollTrigger.create({
      trigger: el,
      start: 'top 88%',
      onEnter: () => gsap.to(el, { opacity: 1, y: 0, duration: 0.7, ease: 'power3.out' }),
      once: true
    });
  });

  gsap.utils.toArray('.szolg-article .szolg-feature-img.fade-scale').forEach((el) => {
    ScrollTrigger.create({
      trigger: el,
      start: 'top 90%',
      onEnter: () => gsap.to(el, { opacity: 1, scale: 1, duration: 0.6, ease: 'power2.out' }),
      once: true
    });
  });
</script>
@endpush
