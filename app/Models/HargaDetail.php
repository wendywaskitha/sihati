<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HargaDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'komoditas_id',
        'pedagang_id',
        'pasar_id',
        'tanggal',
        'harga',
        'created_by'
    ];

    public function komoditas(): BelongsTo
    {
        return $this->belongsTo(Komoditas::class, 'komoditas_id');
    }

    public function pedagang(): BelongsTo
    {
        return $this->belongsTo(Pedagang::class, 'pedagang_id');
    }

    public function pasar(): BelongsTo
    {
        return $this->belongsTo(Pasar::class, 'pasar_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getIsApprovedAttribute()
    {
        return \App\Models\Harga::where('komoditas_id', $this->komoditas_id)
            ->where('pasar_id', $this->pasar_id)
            ->where('tanggal', $this->tanggal)
            ->where('status', 'approved')
            ->exists();
    }
}
