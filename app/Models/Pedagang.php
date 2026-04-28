<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pedagang extends Model
{
    use HasFactory;

    protected $fillable = ['pasar_id', 'nama'];

    public function pasar(): BelongsTo
    {
        return $this->belongsTo(Pasar::class, 'pasar_id');
    }

    public function hargaDetails(): HasMany
    {
        return $this->hasMany(HargaDetail::class, 'pedagang_id');
    }
}
