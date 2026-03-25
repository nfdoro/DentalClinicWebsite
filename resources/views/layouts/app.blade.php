<!DOCTYPE html>
<html lang="hu">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>@yield('title', 'Dr. Nagy-Fazakas Csongor - Fogászati Rendelő Miskolc')</title>
  <meta name="description" content="@yield('description', 'Dr. Nagy-Fazakas Csongor fogászati rendelője Miskolcon. Gyökérkezelés, fogszabályozás, fogtömés, fogpótlás, fogfehérítés és prevenciós kezelések.')">
  <meta name="keywords" content="@yield('keywords', 'fogorvos miskolc, fogászat miskolc, gyökérkezelés, fogszabályozás, fogtömés, fogpótlás, fogfehérítés, dr nagy-fazakas csongor')">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="{{ url()->current() }}">

  {{-- Open Graph --}}
  <meta property="og:type" content="website">
  <meta property="og:title" content="@yield('title', 'Dr. Nagy-Fazakas Csongor - Fogászati Rendelő Miskolc')">
  <meta property="og:description" content="@yield('description', 'Dr. Nagy-Fazakas Csongor fogászati rendelője Miskolcon.')">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:locale" content="hu_HU">
  <meta property="og:site_name" content="Dr. Nagy-Fazakas Csongor Fogászat">

  @yield('schema')

  <link href="{{ asset('images/implant.png') }}" rel="icon">

  {{-- Google Fonts --}}
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  {{-- Vendor CSS --}}
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

  {{-- Template CSS --}}
  <link href="{{ asset('style.css') }}" rel="stylesheet">

  {{-- Modern override CSS --}}
  <link href="{{ asset('custom.css') }}" rel="stylesheet">

  @stack('styles')
</head>
<body>

  {{-- ======= Header ======= --}}
  <header id="header" class="fixed-top d-flex align-items-center">
    <div class="container d-flex align-items-center">

      <div class="logo me-auto">
        <h3><a href="{{ route('home') }}">Dr. Nagy-Fazakas Csongor</a></h3>
      </div>

      <nav id="navbar" class="navbar order-last order-lg-0">
        <ul>
          <li><a class="nav-link scrollto" href="{{ route('home') }}#about">Rólunk</a></li>

          <li class="dropdown">
            <a href="{{ route('home') }}#what-we-do">
              <span>Szolgáltatásaink</span>
              <i class="bi bi-chevron-down"></i>
            </a>
            <ul>
              @foreach(\App\Models\Kategoria::where('szolgaltatas', true)->get() as $kat)
              <li><a href="{{ route('szolgaltatas.show', $kat->slug) }}">{{ $kat->nev }}</a></li>
              @endforeach
            </ul>
          </li>

          <li><a class="nav-link scrollto" href="{{ route('home') }}#portfolio">Galéria</a></li>
          <li><a class="nav-link scrollto" href="{{ route('home') }}#team">Csapatunk</a></li>
          <li><a class="nav-link scrollto" href="{{ route('arlista') }}">Árlista</a></li>
          <li><a class="nav-link scrollto" href="{{ route('home') }}#contact">Elérhetőségek</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav>

      <div class="header-social-links d-flex align-items-center">
        <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
      </div>

    </div>
  </header>
  {{-- ======= End Header ======= --}}

  @yield('content')

  {{-- ======= Footer ======= --}}
  <footer id="footer">
    <div class="footer-top">
      <div class="container">
        <div class="row">
          <div class="col-lg-3 col-md-6 footer-contact">
            <h3>Dr. Nagy-Fazakas Csongor</h3>
            <p>
              Magyarország, Miskolc <br>
              Madarász Viktor utca 13/A <br>
              2. emelet, 03 kapucsengő <br><br>
              <strong>Telefon:</strong> +36 70 627 6160<br>
              <strong>Email:</strong> info@fogaszat-miskolc.hu<br>
            </p>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Szolgáltatásaink</h4>
            <ul>
              @foreach(\App\Models\Kategoria::where('szolgaltatas', true)->get() as $kat)
              <li><i class="bx bx-chevron-right"></i> <a href="{{ route('szolgaltatas.show', $kat->slug) }}">{{ $kat->nev }}</a></li>
              @endforeach
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Gyors linkek</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="{{ route('home') }}">Főoldal</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="{{ route('home') }}#about">Rólunk</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="{{ route('galeria') }}">Galéria</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="{{ route('arlista') }}">Árlista</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="{{ route('home') }}#contact">Kapcsolat</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="container d-md-flex py-4">
      <div class="me-md-auto text-center text-md-start">
        <div class="copyright">
          &copy; {{ date('Y') }} <strong><span>Dr. Nagy-Fazakas Csongor Fogászati Rendelő</span></strong>. Minden jog fenntartva.
        </div>
      </div>
      <div class="social-links text-center text-md-right pt-3 pt-md-0">
        <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
      </div>
    </div>
  </footer>
  {{-- ======= End Footer ======= --}}

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  {{-- Vendor JS --}}
  <script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/waypoints/noframework.waypoints.js') }}"></script>

  {{-- Main JS --}}
  <script src="{{ asset('assets/js/main.js') }}"></script>

  @stack('scripts')
</body>
</html>
