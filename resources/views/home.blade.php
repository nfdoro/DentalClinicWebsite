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
    <div class="container text-center">
      <h1>
        <span class="hero-line">Látogasson el a <span>fogorvosához</span>,</span>
        <span class="hero-line">ne hagyja, hogy a szenvedéstől való félelme nagyobb legyen,</span>
        <span class="hero-line">mint <span>mosolygási vágya</span>.</span>
      </h1>
      <h2 id="hero-address">Magyarország &nbsp;·&nbsp; Miskolc &nbsp;·&nbsp; Madarász Viktor utca 13/A &nbsp;·&nbsp; 2. emelet, 03 kapucsengő</h2>
    </div>
    <div class="hero-scroll-hint" id="scrollHint">
      <span>Görgessen</span>
      <div class="scroll-line"></div>
    </div>
  </section>
  {{-- ======= End Hero ======= --}}

  <main id="main">

    {{-- ======= Rólunk ======= --}}
    <section id="about" class="about">
      <div class="container">
        <div class="section-title fade-up">
          <h2>Rólunk</h2>
        </div>
        <div class="row g-4">
          <div class="col-lg-6">
            <div class="about-img-wrap fade-left">
              <img src="{{ asset('images/rolunk.jpg') }}" class="img-fluid" alt="Dr. Nagy-Fazakas Csongor fogászati rendelő">
            </div>
          </div>
          <div class="col-lg-6 pt-4 pt-lg-0 fade-right">
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
              <img src="{{ asset('images/drnfcsongor.jpg') }}" alt="Dr. Nagy-Fazakas Csongor">
              <h4>Dr. Nagy-Fazakas Csongor</h4>
              <p>Fogorvos, rendelő vezető</p>
              <div class="social">
                <a href=""><i class="bi bi-facebook"></i></a>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 d-flex fade-scale">
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
        <div class="section-title fade-up">
          <h2>Elérhetőségek</h2>
        </div>

        <div class="row justify-content-center">
          <div class="col-lg-10">
            <div class="info-wrap">
              <div class="row">
                <div class="col-lg-4 info fade-up">
                  <i class="bi bi-geo-alt"></i>
                  <h4>Helyszín:</h4>
                  <p>Magyarország, Miskolc<br>Madarász Viktor utca 13/A<br>2. emelet, 03 kapucsengő</p>
                </div>
                <div class="col-lg-4 info mt-4 mt-lg-0 fade-up">
                  <i class="bi bi-envelope"></i>
                  <h4>Email:</h4>
                  <p>info@fogaszat-miskolc.hu</p>
                </div>
                <div class="col-lg-4 info mt-4 mt-lg-0 fade-up">
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

  /* ── 3. HERO — sorok beúszása ── */
  gsap.set('.hero-line', { opacity: 0, y: 28 });
  gsap.set('#hero-address', { opacity: 0, y: 20 });

  const heroTl = gsap.timeline({ delay: 0.25 });
  heroTl
    .to('.hero-line', {
      opacity: 1, y: 0,
      duration: 0.85,
      stagger: 0.2,
      ease: 'power3.out'
    })
    .to('#hero-address', {
      opacity: 1, y: 0,
      duration: 0.7,
      ease: 'power2.out'
    }, '-=0.3')
    .to('#scrollHint', {
      opacity: 1,
      duration: 0.6,
      ease: 'power2.out'
    }, '+=0.2');

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

  /* Scroll hint eltűnik görgetésre */
  gsap.to('#scrollHint', {
    opacity: 0, y: -20,
    scrollTrigger: {
      trigger: '#hero',
      start: 'top top',
      end: '25% top',
      scrub: true
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

  /* ── 6. ABOUT — kép és szöveg beúszás ── */
  ScrollTrigger.create({
    trigger: '#about .row',
    start: 'top 83%',
    onEnter: () => {
      gsap.to('.fade-left', { opacity: 1, x: 0, duration: 1.0, ease: 'power3.out' });
      gsap.to('.fade-right', { opacity: 1, x: 0, duration: 1.0, ease: 'power3.out', delay: 0.15 });
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
