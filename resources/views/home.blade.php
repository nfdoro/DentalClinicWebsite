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
  "openingHoursSpecification": [
    {
      "@type": "OpeningHoursSpecification",
      "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
      "opens": "08:00",
      "closes": "18:00"
    }
  ],
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

  {{-- ======= Hero Carousel ======= --}}
  <section id="hero">

    {{-- SLIDE 1 — Fogszabályozás --}}
    <div class="hero-slide active" data-index="0">
      <h1 class="hero-slide-title">
        Látogasson el a fogorvosához,<br>
        és nyerjen vissza egy <em>tökéletes mosolyt</em>.
      </h1>
      <p class="hero-slide-sub">Modern, önligírozó fogszabályozóval, a fogív fokozatos, kíméletes rendezéséért.</p>
      <div class="hero-slide-btns">
        <a href="{{ route('szolgaltatas.show', 'fogszabalyozas') }}" class="hero-btn-primary">
          <i class="bi bi-arrow-right-circle"></i> Fogszabályozás részletek
        </a>
        <a href="{{ route('home') }}#contact" class="hero-btn-contact">
          <i class="bi bi-telephone"></i> Elérhetőség
        </a>
      </div>
      <div class="hero-infocard">
        <div class="hic-row">
          <div class="hic-num hic-text">Rögzített</div>
          <div class="hic-label">és kivehető készülék</div>
        </div>
        <div class="hic-row">
          <div class="hic-num hic-text">Egyéni</div>
          <div class="hic-label">Kezelési terv</div>
        </div>
        <div class="hic-row">
          <div class="hic-num hic-text">Önligírozó</div>
          <div class="hic-label">Fogszabályozó rendszer</div>
        </div>
      </div>
    </div>

    {{-- SLIDE 2 — Gyökérkezelés --}}
    <div class="hero-slide" data-index="1">
      <h2 class="hero-slide-title">
        Ne hagyja, hogy a fájdalomtól való félelem<br>
        megakadályozza a <em>fogmegőrzést</em>.
      </h2>
      <p class="hero-slide-sub">Helyi érzéstelenítéssel, gyorsan és fájdalommentesen mentjük meg a fertőzött fogat.</p>
      <div class="hero-slide-btns">
        <a href="{{ route('szolgaltatas.show', 'gyokerkezeles') }}" class="hero-btn-primary">
          <i class="bi bi-arrow-right-circle"></i> Gyökérkezelés részletek
        </a>
        <a href="{{ route('home') }}#contact" class="hero-btn-contact">
          <i class="bi bi-telephone"></i> Elérhetőség
        </a>
      </div>
      <div class="hero-infocard">
        <div class="hic-row">
          <div class="hic-num">2–4<sup>alk.</sup></div>
          <div class="hic-label">Jellemző alkalmak</div>
        </div>
        <div class="hic-row">
          <div class="hic-num hic-text">Helyi</div>
          <div class="hic-label">Érzéstelenítéssel</div>
        </div>
        <div class="hic-row">
          <div class="hic-num hic-text">Mentett</div>
          <div class="hic-label">Saját fog megőrzése</div>
        </div>
      </div>
    </div>

    {{-- SLIDE 3 — Fogtömés --}}
    <div class="hero-slide" data-index="2">
      <h2 class="hero-slide-title">
        A szuvasodás ellen az egyetlen válasz<br>
        az <em>időben elvégzett kezelés</em>.
      </h2>
      <p class="hero-slide-sub">Egyetlen alkalom alatt, fehér esztétikai tömőanyaggal helyreállítjuk a fog természetes megjelenését.</p>
      <div class="hero-slide-btns">
        <a href="{{ route('szolgaltatas.show', 'fogtomes') }}" class="hero-btn-primary">
          <i class="bi bi-arrow-right-circle"></i> Fogtömés részletek
        </a>
        <a href="{{ route('home') }}#contact" class="hero-btn-contact">
          <i class="bi bi-telephone"></i> Elérhetőség
        </a>
      </div>
      <div class="hero-infocard">
        <div class="hic-row">
          <div class="hic-num">1<sup>alk.</sup></div>
          <div class="hic-label">Egyetlen alkalom</div>
        </div>
        <div class="hic-row">
          <div class="hic-num hic-text">Fehér</div>
          <div class="hic-label">Esztétikai tömőanyag</div>
        </div>
        <div class="hic-row">
          <div class="hic-num hic-text">Azonnali</div>
          <div class="hic-label">Eredmény</div>
        </div>
      </div>
    </div>

    {{-- SLIDE 4 — Fogpótlás --}}
    <div class="hero-slide" data-index="3">
      <h2 class="hero-slide-title">
        Hiányzó fog? Állítsuk vissza<br>
        <em>mosolyát és önbizalmát</em>.
      </h2>
      <p class="hero-slide-sub">Koronától az implantátumig, tartós és természetes megjelenésű fogpótlással.</p>
      <div class="hero-slide-btns">
        <a href="{{ route('szolgaltatas.show', 'fogpotlas') }}" class="hero-btn-primary">
          <i class="bi bi-arrow-right-circle"></i> Fogpótlás részletek
        </a>
        <a href="{{ route('home') }}#contact" class="hero-btn-contact">
          <i class="bi bi-telephone"></i> Elérhetőség
        </a>
      </div>
      <div class="hero-infocard">
        <div class="hic-row">
          <div class="hic-num hic-text">Korona</div>
          <div class="hic-label">Híd · Implantátum</div>
        </div>
        <div class="hic-row">
          <div class="hic-num hic-text">Teljes</div>
          <div class="hic-label">Foghiány is pótolható</div>
        </div>
        <div class="hic-row">
          <div class="hic-num hic-text">Tartós</div>
          <div class="hic-label">Hosszú élettartam</div>
        </div>
      </div>
    </div>

    {{-- SLIDE 5 — Prevenció --}}
    <div class="hero-slide" data-index="4">
      <h2 class="hero-slide-title">
        Ne várjon a panaszra,<br>
        a megelőzés a <em>legokosabb befektetés</em>.
      </h2>
      <p class="hero-slide-sub">Évente kétszeri rutin vizsgálat megelőzi a fogínygyulladást, szuvasodást és csontpusztulást.</p>
      <div class="hero-slide-btns">
        <a href="{{ route('szolgaltatas.show', 'prevencio') }}" class="hero-btn-primary">
          <i class="bi bi-arrow-right-circle"></i> Prevenció részletek
        </a>
        <a href="{{ route('home') }}#contact" class="hero-btn-contact">
          <i class="bi bi-telephone"></i> Elérhetőség
        </a>
      </div>
      <div class="hero-infocard">
        <div class="hic-row">
          <div class="hic-num">2<sup>×</sup></div>
          <div class="hic-label">Évente ajánlott</div>
        </div>
        <div class="hic-row">
          <div class="hic-num hic-text">Fogkő</div>
          <div class="hic-label">Professzionális eltávolítás</div>
        </div>
        <div class="hic-row">
          <div class="hic-num hic-text">Szűrés</div>
          <div class="hic-label">Teljes vizsgálat</div>
        </div>
      </div>
    </div>

    {{-- SLIDE 6 — Foghúzás --}}
    <div class="hero-slide" data-index="5">
      <h2 class="hero-slide-title">
        Amikor a fog már nem menthető meg,<br>
        <em>gyors és fájdalommentes megoldás</em>.
      </h2>
      <p class="hero-slide-sub">Atraumatikus technikával végzett foghúzás, utólagos fogpótlás lehetőségével.</p>
      <div class="hero-slide-btns">
        <a href="{{ route('szolgaltatas.show', 'foghuzas') }}" class="hero-btn-primary">
          <i class="bi bi-arrow-right-circle"></i> Foghúzás részletek
        </a>
        <a href="{{ route('home') }}#contact" class="hero-btn-contact">
          <i class="bi bi-telephone"></i> Elérhetőség
        </a>
      </div>
      <div class="hero-infocard">
        <div class="hic-row">
          <div class="hic-num hic-text">Gyors</div>
          <div class="hic-label">Atraumatikus technika</div>
        </div>
        <div class="hic-row">
          <div class="hic-num hic-text">Helyi</div>
          <div class="hic-label">Érzéstelenítéssel</div>
        </div>
        <div class="hic-row">
          <div class="hic-num hic-text">Pótlás</div>
          <div class="hic-label">Utólag lehetséges</div>
        </div>
      </div>
    </div>

    {{-- SLIDE 7 — Fogfehérítés --}}
    <div class="hero-slide" data-index="6">
      <h2 class="hero-slide-title">
        Ragyogó mosoly, mert<br>
        az <em>első benyomás számít</em>.
      </h2>
      <p class="hero-slide-sub">Professzionális fogfehérítéssel akár 8 tónussal világosabb fogak. Rendelői és otthoni megoldás egyaránt.</p>
      <div class="hero-slide-btns">
        <a href="{{ route('szolgaltatas.show', 'fogfeherites') }}" class="hero-btn-primary">
          <i class="bi bi-arrow-right-circle"></i> Fogfehérítés részletek
        </a>
        <a href="{{ route('home') }}#contact" class="hero-btn-contact">
          <i class="bi bi-telephone"></i> Elérhetőség
        </a>
      </div>
      <div class="hero-infocard">
        <div class="hic-row">
          <div class="hic-num">akár 8<sup>tón</sup></div>
          <div class="hic-label">Világosabb árnyalat</div>
        </div>
        <div class="hic-row">
          <div class="hic-num hic-text">Gyors</div>
          <div class="hic-label">Rendelői fehérítés</div>
        </div>
        <div class="hic-row">
          <div class="hic-num hic-text">Otthoni</div>
          <div class="hic-label">Szett is elérhető</div>
        </div>
      </div>
    </div>

    {{-- Cím sor --}}
    <p class="hero-address" id="hero-address">
      Magyarország &nbsp;·&nbsp; Miskolc &nbsp;·&nbsp; Madarász Viktor utca 13/A &nbsp;·&nbsp; 2. emelet, 03 kapucsengő
    </p>

    {{-- Dots + Nyilak --}}
    <div class="hero-bottom">
      <div class="hero-dots" id="heroDots"></div>
      <div class="hero-arrows">
        <button class="hero-arrow-btn" id="heroPrev"><i class="bi bi-arrow-left"></i></button>
        <button class="hero-arrow-btn" id="heroNext"><i class="bi bi-arrow-right"></i></button>
      </div>
    </div>

    {{-- Progress bar --}}
    <div class="hero-progress-bar"><div class="hero-progress-inner" id="heroProgress"></div></div>

  </section>
  {{-- ======= End Hero Carousel ======= --}}

  <main id="main">

    {{-- ======= Rólunk ======= --}}
    <section id="about" class="rolunk-section">
      <div class="container">

        <div class="section-title fade-up">
          <h2>Rólunk</h2>
        </div>

        <div class="rolunk-wrap">

          {{-- BAL: Fotó + stat kártyák --}}
          <div class="rolunk-photo-col">
            <div class="rolunk-photo-frame-deco"></div>
            <div class="rolunk-photo">
              <img src="{{ asset('images/drnfcsongor.jpg') }}" alt="Dr. Nagy-Fazakas Csongor">
            </div>
            <div class="rolunk-stat-card stat-top">
              <div class="rolunk-stat-num">2016</div>
              <div class="rolunk-stat-label">Diploma éve</div>
            </div>
            <div class="rolunk-stat-card stat-mid">
              <div class="rolunk-stat-num">9<sup>+ év</sup></div>
              <div class="rolunk-stat-label">Tapasztalat</div>
            </div>
            <div class="rolunk-stat-card stat-bottom">
              <div class="rolunk-stat-num">200<sup>+</sup></div>
              <div class="rolunk-stat-label">Páciens</div>
            </div>
          </div>

          {{-- JOBB: Szöveg --}}
          <div class="rolunk-content">
            <h2 class="rolunk-heading">
              A pácienseim bizalma<br>a legfontosabb <em>értékem.</em>
            </h2>

            <p class="rolunk-bio">
              Munkám során legfontosabb szempont a páciensekkel kialakított közvetlen, bizalmi viszony, mely lehetővé teszi számukra a szakmai szempontok ismertetése mellett, egyéni elvárásaik érvényesítését.
            </p>
            <p class="rolunk-bio">
              A legnagyobb elégtétel számomra, távozó pácienseim elégedett mosolya. Ennek eléréséért, mindig azon vagyok, hogy szakmai tudásom legjavát nyújtsam (ezt folyamatos továbbképzésekkel gyarapítom) és, hogy egy olyan meghitt, szeretetteljes légkört biztosítsak, ahova örömmel térnek vissza.
            </p>

            <div class="rolunk-timeline">
              <div class="rolunk-tl-item">
                <div class="rolunk-tl-dot"></div>
                <div class="rolunk-tl-text">
                  <strong>2016:</strong> Diploma, Marosvásárhelyi Orvosi és Gyógyszerészeti Egyetem, Fogorvosi Kar
                </div>
              </div>
            </div>

            <div class="rolunk-sig-block">
              <div class="rolunk-sig-name">Dr. Nagy-Fazakas Csongor</div>
              <div class="rolunk-sig-role">Fogorvos · Rendelővezető</div>
            </div>
          </div>

        </div>
      </div>
    </section>
    {{-- ======= End Rólunk ======= --}}

    {{-- ======= Szolgáltatásaink ======= --}}
    <section id="what-we-do" class="what-we-do">
      <div class="container">
        <div class="section-title fade-up">
          <h2>Szolgáltatásaink</h2>
        </div>
        <div class="row g-4 service-grid">
          @foreach($szolgaltatasok as $kat)
          <div class="col-lg-4 col-md-6 d-flex align-items-stretch fade-scale">
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
        <div class="section-title fade-up">
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
        <div class="section-title fade-up">
          <h2>Csapatunk</h2>
          <p>Ismerje meg orvosainkat, asszisztenseinket</p>
        </div>
        <div class="row justify-content-center g-4 team-grid">
          <div class="col-lg-4 col-md-6 d-flex fade-scale">
            <div class="member w-100">
              <x-munkatars-avatar :kep="'images/drnfcsongor.jpg'" nev="Dr. Nagy-Fazakas Csongor" />
              <h4>Dr. Nagy-Fazakas Csongor</h4>
              <p>Fogorvos, rendelő vezető</p>
              <div class="social">
                <a href=""><i class="bi bi-facebook"></i></a>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 d-flex fade-scale">
            <div class="member w-100">
              <x-munkatars-avatar :kep="null" nev="Nagy-Fazakas Szilvia" />
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
        <div class="section-title fade-up">
          <h2>Elérhetőségek</h2>
        </div>

        <div class="row justify-content-center">
          <div class="col-lg-10">
            <div class="info-wrap">
              <div class="row">
                <div class="col-lg-4 info fade-up">
                  <i class="bi bi-geo-alt"></i>
                  <div class="info-text">
                    <h4>Helyszín:</h4>
                    <p>Magyarország, Miskolc<br>Madarász Viktor utca 13/A<br>2. emelet, 03 kapucsengő</p>
                  </div>
                </div>
                <div class="col-lg-4 info mt-4 mt-lg-0 fade-up">
                  <i class="bi bi-envelope"></i>
                  <div class="info-text">
                    <h4>Email:</h4>
                    <p><a href="mailto:{{ config('kapcsolat.email') }}">{{ config('kapcsolat.email') }}</a></p>
                  </div>
                </div>
                <div class="col-lg-4 info mt-4 mt-lg-0 fade-up">
                  <i class="bi bi-phone"></i>
                  <div class="info-text">
                    <h4>Telefonszám:</h4>
                    <p><a href="tel:{{ config('kapcsolat.telefon_hivas') }}">{{ config('kapcsolat.telefon') }}</a></p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="text-center mt-4 mb-2">
            <p class="text-muted small">
              Rendelőnk Miskolcon található, de pácienseinket fogadjuk
              <a href="{{ route('fogaszat.eszak-magyarorszag') }}">Eger, Ózd, Kazincbarcika, Nyíregyháza és Tiszaújváros</a> közeléből is.
            </p>
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

@push('scripts')
<script>
  gsap.registerPlugin(ScrollTrigger, ScrollToPlugin);

  /* ── 1. SCROLL PROGRESS BAR ── */
  const progressBar = document.getElementById('progress-bar');
  window.addEventListener('scroll', () => {
    const pct = window.scrollY / (document.body.scrollHeight - window.innerHeight) * 100;
    progressBar.style.width = pct + '%';
  }, { passive: true });

  /* ── 2. BACK TO TOP — smooth GSAP scroll ── */
  const btt = document.querySelector('.back-to-top');
  if (btt) {
    btt.addEventListener('click', e => {
      e.preventDefault();
      gsap.to(window, { duration: 1.2, scrollTo: 0, ease: 'power3.inOut' });
    });
  }

  /* ── 3. HERO CAROUSEL ── */
  (function() {
    const slides   = document.querySelectorAll('.hero-slide');
    const dotsWrap = document.getElementById('heroDots');
    const progEl   = document.getElementById('heroProgress');
    const TOTAL    = slides.length;
    const INTERVAL = 6000;
    const REDUCED  = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    let current = 0, autoTimer;

    /* Dots generálás */
    slides.forEach((_, i) => {
      const d = document.createElement('div');
      d.className = 'hero-dot' + (i === 0 ? ' active' : '');
      d.addEventListener('click', () => { goTo(i); startAuto(); });
      dotsWrap.appendChild(d);
    });

    function getDots() { return dotsWrap.querySelectorAll('.hero-dot'); }

    function animateIn(slide) {
      const els = slide.querySelectorAll('.hero-slide-title, .hero-slide-sub, .hero-slide-btns');
      const card = slide.querySelector('.hero-infocard');
      if (REDUCED) {
        gsap.set(els, { opacity: 1, y: 0 });
        if (card) gsap.set(card, { opacity: 1, x: 0 });
        return;
      }
      gsap.fromTo(els,
        { opacity: 0, y: 32 },
        { opacity: 1, y: 0, duration: 0.85, stagger: 0.15, ease: 'power3.out', delay: 0.2 }
      );
      if (card) gsap.fromTo(card,
        { opacity: 0, x: 20 },
        { opacity: 1, x: 0, duration: 0.8, ease: 'power2.out', delay: 0.7 }
      );
    }

    function goTo(idx) {
      const dots = getDots();
      slides[current].classList.add('leaving');
      dots[current].classList.remove('active');
      const next = (idx + TOTAL) % TOTAL;
      setTimeout(() => {
        slides[current].classList.remove('active', 'leaving');
        current = next;
        slides[current].classList.add('active');
        dots[current].classList.add('active');
        animateIn(slides[current]);
      }, 300);
    }

    function startProgress() {
      progEl.style.transition = 'none';
      progEl.style.width = '0%';
      requestAnimationFrame(() => requestAnimationFrame(() => {
        progEl.style.transition = 'width 6s linear';
        progEl.style.width = '100%';
      }));
    }

    function startAuto() {
      if (REDUCED) return;
      clearInterval(autoTimer);
      startProgress();
      autoTimer = setInterval(() => { goTo(current + 1); setTimeout(startProgress, 350); }, INTERVAL);
    }
    function stopAuto() { clearInterval(autoTimer); }

    document.getElementById('heroNext').addEventListener('click', () => { goTo(current + 1); startAuto(); });
    document.getElementById('heroPrev').addEventListener('click', () => { goTo(current - 1); startAuto(); });

    /* Első slide megjelenése */
    if (REDUCED) {
      /* Csökkentett mozgás: a tartalom animáció nélkül, azonnal látható. */
      gsap.set('.hero-slide.active .hero-slide-title, .hero-slide.active .hero-slide-sub, .hero-slide.active .hero-slide-btns, .hero-slide.active .hero-infocard, #hero-address', { opacity: 1, x: 0, y: 0 });
    } else {
      gsap.set('.hero-slide.active .hero-slide-title, .hero-slide.active .hero-slide-sub, .hero-slide.active .hero-slide-btns', { opacity: 0, y: 28 });
      gsap.set('.hero-slide.active .hero-infocard', { opacity: 0, x: 20 });
      gsap.set('#hero-address', { opacity: 0, y: 16 });

      const heroTl = gsap.timeline({ delay: 0.25 });
      heroTl
        .to('.hero-slide.active .hero-slide-title', { opacity: 1, y: 0, duration: 0.85, ease: 'power3.out' })
        .to('.hero-slide.active .hero-slide-sub',   { opacity: 1, y: 0, duration: 0.7,  ease: 'power3.out' }, '-=0.5')
        .to('.hero-slide.active .hero-slide-btns',  { opacity: 1, y: 0, duration: 0.7,  ease: 'power3.out' }, '-=0.5')
        .to('.hero-slide.active .hero-infocard',    { opacity: 1, x: 0, duration: 0.7,  ease: 'power2.out' }, '-=0.4')
        .to('#hero-address', { opacity: 1, y: 0, duration: 0.6, ease: 'power2.out' }, '-=0.3');
    }

    /* Hover / fókusz esetén az automatikus váltás megáll, elhagyáskor folytatódik. */
    const heroEl = document.getElementById('hero');
    if (heroEl) {
      heroEl.addEventListener('mouseenter', stopAuto);
      heroEl.addEventListener('mouseleave', startAuto);
      heroEl.addEventListener('focusin', stopAuto);
      heroEl.addEventListener('focusout', startAuto);
    }

    startAuto();
  })();

  /* ── 4. HERO — marble parallax ── */
  gsap.to('#hero', {
    backgroundPositionY: '30%',
    ease: 'none',
    scrollTrigger: {
      trigger: '#hero',
      start: 'top top',
      end: 'bottom top',
      scrub: 1.8
    }
  });

  /* ── 5. SECTION TITLE REVEAL (underline + fade-up) ── */
  gsap.utils.toArray('.section-title.fade-up').forEach(el => {
    ScrollTrigger.create({
      trigger: el,
      start: 'top 88%',
      onEnter: () => {
        gsap.to(el, { opacity: 1, y: 0, duration: 0.7, ease: 'power3.out' });
        setTimeout(() => el.classList.add('line-revealed'), 350);
      },
      once: true
    });
  });

  /* ── 6. ABOUT — Rólunk beúszás ── */
  ScrollTrigger.create({
    trigger: '#about .rolunk-wrap',
    start: 'top 83%',
    onEnter: () => {
      gsap.fromTo('.rolunk-photo-col',
        { opacity: 0, x: -40 },
        { opacity: 1, x: 0, duration: 1.0, ease: 'power3.out' }
      );
      gsap.fromTo('.rolunk-content',
        { opacity: 0, x: 40 },
        { opacity: 1, x: 0, duration: 1.0, ease: 'power3.out', delay: 0.15 }
      );
      gsap.fromTo('.rolunk-stat-card',
        { opacity: 0, scale: 0.85 },
        { opacity: 1, scale: 1, duration: 0.6, stagger: 0.12, ease: 'back.out(1.4)', delay: 0.4 }
      );
    },
    once: true
  });

  /* ── 7. SZOLGÁLTATÁSOK — kártyák lépcsőzetes megjelenése ── */
  ScrollTrigger.create({
    trigger: '.service-grid',
    start: 'top 85%',
    onEnter: () => {
      gsap.to('.service-grid .fade-scale', {
        opacity: 1, scale: 1,
        duration: 0.6,
        stagger: 0.09,
        ease: 'back.out(1.4)'
      });
    },
    once: true
  });

  /* ── 8. PORTFOLIO CONTAINER — fade-in ── */
  ScrollTrigger.create({
    trigger: '.portfolio-container',
    start: 'top 85%',
    onEnter: () => {
      gsap.fromTo('.portfolio-container',
        { opacity: 0, y: 30 },
        { opacity: 1, y: 0, duration: 0.8, ease: 'power3.out' }
      );
    },
    once: true
  });

  /* Galéria gomb */
  ScrollTrigger.create({
    trigger: '.text-center.mt-5',
    start: 'top 92%',
    onEnter: () => {
      gsap.fromTo('.portfolio-more-btn',
        { opacity: 0, y: 20 },
        { opacity: 1, y: 0, duration: 0.6, ease: 'power3.out' }
      );
    },
    once: true
  });

  /* ── 9. CSAPAT KÁRTYÁK ── */
  ScrollTrigger.create({
    trigger: '.team-grid',
    start: 'top 85%',
    onEnter: () => {
      gsap.to('.team-grid .fade-scale', {
        opacity: 1, scale: 1,
        duration: 0.65,
        stagger: 0.18,
        ease: 'back.out(1.3)'
      });
    },
    once: true
  });

  /* ── 10. CONTACT INFO ── */
  ScrollTrigger.create({
    trigger: '.info-wrap',
    start: 'top 85%',
    onEnter: () => {
      gsap.to('.info.fade-up', {
        opacity: 1, y: 0,
        duration: 0.6,
        stagger: 0.15,
        ease: 'power3.out'
      });
    },
    once: true
  });

  /* Térkép */
  ScrollTrigger.create({
    trigger: '#map',
    start: 'top 90%',
    onEnter: () => {
      gsap.fromTo('#map',
        { opacity: 0, y: 30 },
        { opacity: 1, y: 0, duration: 0.8, ease: 'power3.out' }
      );
    },
    once: true
  });

  /* ── 11. PORTFOLIO FILTER gomb click feedback ── */
  document.querySelectorAll('#portfolio-flters li').forEach(li => {
    li.addEventListener('click', function() {
      gsap.fromTo(this, { scale: 0.9 }, { scale: 1, duration: 0.28, ease: 'back.out(2)' });
    });
  });

  /* ── 12. SMOOTH SCROLL NAVIGÁCIÓ ── */
  document.querySelectorAll('a[href^="#"]').forEach(link => {
    link.addEventListener('click', function(e) {
      const id = this.getAttribute('href');
      if (id === '#' || id === '') return;
      const target = document.querySelector(id);
      if (target) {
        e.preventDefault();
        gsap.to(window, {
          duration: 1.1,
          scrollTo: { y: target, offsetY: 80 },
          ease: 'power3.inOut'
        });
      }
    });
  });
</script>
@endpush
