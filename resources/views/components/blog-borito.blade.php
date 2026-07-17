@props(['kep' => null, 'cim' => ''])

{{-- Blogkártya borítóképe, opcionális képpel.
     Kép hiányában vagy betöltési hiba esetén egységes, márkába illő placeholder. --}}
@php $utvonal = trim((string) $kep); @endphp

<div class="blog-card-img">
  @if($utvonal !== '')
    <img src="{{ asset($utvonal) }}" alt="{{ $cim }}" loading="lazy"
         onerror="this.style.display='none'; if(this.nextElementSibling){ this.nextElementSibling.hidden=false; }">
    <span class="blog-borito-ph" hidden aria-hidden="true"><i class="bi bi-emoji-smile"></i></span>
  @else
    <span class="blog-borito-ph" aria-hidden="true"><i class="bi bi-emoji-smile"></i></span>
  @endif
</div>
