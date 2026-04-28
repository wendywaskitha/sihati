<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kecamatan extends Model
{
    use HasFactory;

    protected $fillable = ['nama'];

    public function desas(): HasMany
    {
        return $this->hasMany(Desa::class, 'kecamatan_id');
    }

    public function pasars(): HasMany
    {
        return $this->hasMany(Pasar::class, 'kecamatan_id');
    }
}
