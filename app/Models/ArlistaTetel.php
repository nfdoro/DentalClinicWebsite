<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArlistaTetel extends Model
{
    use HasFactory;

    protected $table = 'adatok';

    protected $fillable = ['kategoria_id', 'muveletnev', 'ar', 'kiegeszites'];

    public function kategoria(): BelongsTo
    {
        return $this->belongsTo(Kategoria::class, 'kategoria_id');
    }
}
