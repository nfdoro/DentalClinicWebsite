@extends('layouts.app')

@section('title', 'Árlista - Dr. Nagy-Fazakas Csongor Fogászat Miskolc')
@section('description', 'Dr. Nagy-Fazakas Csongor fogászati rendelő árlistája. Gyökérkezelés, fogszabályozás, fogtömés, fogpótlás és fogfehérítés árak Miskolcon.')
@section('keywords', 'fogászat árlista miskolc, fogorvos árak, gyökérkezelés ár, fogszabályozás ár, fogtömés ár, fogpótlás ár')

@section('content')

  <main id="main">
    <div class="container">
      <section id="breadcrumbs" class="breadcrumbs"></section>

      <div class="section-title">
        <h2>Árlista</h2>
      </div>

      {{-- Gyors-navigáció --}}
      <div class="arlista-nav">
        @foreach($kategoriak as $kat)
          <a href="#kat-{{ $kat->slug }}" class="arlista-nav-link">{{ $kat->nev }}</a>
        @endforeach
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
                @if(is_numeric($adat->ar))
                  {{ number_format((float)$adat->ar, 0, ',', '.') }} Ft
                @else
                  {{ $adat->ar }}
                @endif
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

@endsection
