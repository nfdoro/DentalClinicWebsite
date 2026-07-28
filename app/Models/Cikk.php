<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cikk extends Model
{
    use HasFactory;

    protected $table = 'cikkek';

    protected $fillable = [
        'cim', 'slug', 'bevezeto', 'tartalom',
        'boritekep', 'meta_leiras', 'kulcsszavak', 'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    /**
     * Az admin listában megjelenő állapot:
     *  - Vázlat: nincs közzétételi dátum
     *  - Folyamatban: van dátum, de még a jövőben van (ütemezve, még nem látszik)
     *  - Közzétett: a dátum már elmúlt, a cikk publikus
     */
    public function getAllapotAttribute(): string
    {
        if (! $this->published_at) {
            return 'Vázlat';
        }

        return $this->published_at->isFuture() ? 'Folyamatban' : 'Közzétett';
    }

    public function getOlvasasiIdoAttribute(): int
    {
        $szavak = str_word_count(strip_tags($this->tartalom));
        return max(1, (int) ceil($szavak / 200));
    }
}
