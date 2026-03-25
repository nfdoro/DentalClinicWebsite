<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Adat extends Model
{
    protected $table = 'adatok';

    protected $fillable = ['kategoria_id', 'muveletnev', 'ar'];

    public function kategoria(): BelongsTo
    {
        return $this->belongsTo(Kategoria::class, 'kategoria_id');
    }
}
