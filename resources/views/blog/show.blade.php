@extends('layouts.app')

@section('title', $cikk->cim . ' - Dr. Nagy-Fazakas Csongor Fogászat')
@section('description', $cikk->meta_leiras ?? Str::limit($cikk->bevezeto, 160))
@section('og_image', $cikk->boritekep ? asset($cikk->boritekep) : asset('images/rolunk.jpg'))

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

      <div class="blog-cikk-cta">
        <p>Kérdése van? Hívjon minket!</p>
        <a href="tel:{{ config('kapcsolat.telefon_hivas') }}" class="hero-btn-contact">
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
