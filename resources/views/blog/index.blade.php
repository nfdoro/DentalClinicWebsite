@extends('layouts.app')

@section('title', 'Blog - Dr. Nagy-Fazakas Csongor Fogászat Miskolc')
@section('description', 'Fogászati tippek, kezelési útmutatók és szakmai cikkek Dr. Nagy-Fazakas Csongor fogászati rendelőjétől. Fogszabályozás, implantátum, fogfehérítés és prevenció témákban.')
@section('og_image', asset('images/og-share.jpg'))

@section('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "Blog",
  "name": "Dr. Nagy-Fazakas Csongor Fogászati Blog",
  "url": "{{ route('blog.index') }}",
  "publisher": {
    "@type": "Dentist",
    "name": "Dr. Nagy-Fazakas Csongor",
    "url": "{{ url('/') }}"
  }
}
</script>
@endsection

@section('content')

<div class="szolg-page-header">
  <div class="container">
    <p class="szolgaltatas-breadcrumb">
      <a href="{{ route('home') }}">Főoldal</a>
      <span>/</span>
      Blog
    </p>
    <h1 class="szolgaltatas-title">Blog</h1>
    <p class="blog-lead">Olvasson fogászati tippeket, kezelési útmutatókat és szakmai cikkeket a rendelőnktől.</p>
  </div>
</div>

<main id="main">
  <section class="blog-lista-section">
    <div class="container">

      @if($cikkek->isEmpty())
        <p class="blog-lista-ures">Hamarosan érkeznek az első cikkeink.</p>
      @else
        <div class="row g-4 blog-lista-grid">
          @foreach($cikkek as $cikk)
          <div class="col-lg-4 col-md-6 d-flex">
            <a href="{{ route('blog.show', $cikk->slug) }}" class="blog-card">
              <x-blog-borito :kep="$cikk->boritekep" :cim="$cikk->cim" />
              <div class="blog-card-body">
                <p class="blog-card-date">{{ $cikk->published_at->translatedFormat('Y. F j.') }}</p>
                <h2 class="blog-card-cim">{{ $cikk->cim }}</h2>
                <p class="blog-card-bevezeto">{{ Str::limit($cikk->bevezeto, 120) }}</p>
                <span class="blog-card-tovabb">Tovább olvasom <i class="bi bi-arrow-right"></i></span>
              </div>
            </a>
          </div>
          @endforeach
        </div>

        <div class="d-flex justify-content-center mt-5">
          {{ $cikkek->links() }}
        </div>
      @endif

    </div>
  </section>
</main>

@endsection
