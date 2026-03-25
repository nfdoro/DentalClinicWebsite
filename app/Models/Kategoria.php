<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategoria extends Model
{
    protected $table = 'kategoriak';

    protected $fillable = ['nev', 'leiras', 'icon', 'szolgaltatas', 'slug'];

    protected $casts = [
        'szolgaltatas' => 'boolean',
    ];

    public function adatok(): HasMany
    {
        return $this->hasMany(Adat::class, 'kategoria_id');
    }

    public function galeria(): HasMany
    {
        return $this->hasMany(Galeria::class, 'kategoria_id');
    }
}
