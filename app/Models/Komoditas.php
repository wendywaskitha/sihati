<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Komoditas extends Model
{
    use HasFactory;

    protected $table = 'komoditas';

    protected $fillable = ['kategori_id', 'nama', 'satuan'];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function hargaDetails(): HasMany
    {
        return $this->hasMany(HargaDetail::class, 'komoditas_id');
    }

    public function hargas(): HasMany
    {
        return $this->hasMany(Harga::class, 'komoditas_id');
    }
}
