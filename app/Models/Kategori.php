<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    use HasFactory;

    protected $fillable = ['nama'];

    public function komoditas(): HasMany
    {
        return $this->hasMany(Komoditas::class, 'kategori_id');
    }
}
