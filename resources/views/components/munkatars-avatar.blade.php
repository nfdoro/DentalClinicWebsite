@props(['kep' => null, 'nev' => ''])

{{-- Munkatárs profilkép, opcionális képpel.
     - Ha nincs kép: egységes, semleges (nemet és életkort nem sugalló) placeholder.
     - Ha van kép, de betöltéskor hibázik: futásidőben placeholderre vált. --}}

@php
    $utvonal = trim((string) $kep);
    $label = trim($nev) !== '' ? $nev . ' profilképe' : 'Munkatárs profilképe';
@endphp

@if($utvonal !== '')
    <img src="{{ asset($utvonal) }}" alt="{{ $nev }}"
         onerror="this.style.display='none'; if(this.nextElementSibling){ this.nextElementSibling.hidden=false; }">
    <span class="munkatars-placeholder" hidden role="img" aria-label="{{ $label }} nem érhető el">
        <i class="bi bi-person" aria-hidden="true"></i>
    </span>
@else
    <span class="munkatars-placeholder" role="img" aria-label="{{ $label }} hamarosan elérhető">
        <i class="bi bi-person" aria-hidden="true"></i>
    </span>
@endif
