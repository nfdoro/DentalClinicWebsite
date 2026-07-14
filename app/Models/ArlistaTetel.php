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

    /**
     * Az ár megjelenítésre formázva.
     * Kezeli az egyetlen összeget (25000) és az intervallumot (25000-30000) is.
     */
    public function getArFormattedAttribute(): string
    {
        $raw = trim((string) $this->ar);

        if ($raw === '') {
            return '';
        }

        // Egyetlen szám, pl.: 25000
        if (is_numeric($raw)) {
            return number_format((float) $raw, 0, ',', '.') . ' Ft';
        }

        // Intervallum: két szám kötőjellel/nagykötőjellel elválasztva, pl.: 25000-30000
        if (preg_match('/^(\d[\d\s.]*?)\s*[-–—]\s*(\d[\d\s.]*)$/u', $raw, $m)) {
            $tol = number_format((float) preg_replace('/\D/', '', $m[1]), 0, ',', '.');
            $ig  = number_format((float) preg_replace('/\D/', '', $m[2]), 0, ',', '.');

            return $tol . ' – ' . $ig . ' Ft';
        }

        // Egyéb szöveg (pl. „Egyedi árajánlat")
        return $raw;
    }
}
