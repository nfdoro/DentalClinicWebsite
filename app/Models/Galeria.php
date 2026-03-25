<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Galeria extends Model
{
    protected $table = 'galeria';

    protected $fillable = ['kategoria_id', 'fajlnev', 'rovidleiras'];

    public function kategoria(): BelongsTo
    {
        return $this->belongsTo(Kategoria::class, 'kategoria_id');
    }
}
