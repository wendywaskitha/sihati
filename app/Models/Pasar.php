<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pasar extends Model
{
    use HasFactory;

    protected $fillable = ['kecamatan_id', 'nama'];

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    public function pedagangs(): HasMany
    {
        return $this->hasMany(Pedagang::class, 'pasar_id');
    }

    public function hargaDetails(): HasMany
    {
        return $this->hasMany(HargaDetail::class, 'pasar_id');
    }

    public function hargas(): HasMany
    {
        return $this->hasMany(Harga::class, 'pasar_id');
    }
}
