<?php

namespace App\Repositories\Eloquent;

use App\Models\Harga;
use App\Repositories\Contracts\HargaRepositoryInterface;

class HargaRepository extends BaseRepository implements HargaRepositoryInterface
{
    public function __construct(Harga $model)
    {
        parent::__construct($model);
    }

    public function getWithRelations()
    {
        return $this->model->with(['komoditas', 'pasar', 'approver'])->get();
    }

    public function getApprovedByDate($tanggal)
    {
        return $this->model->where('tanggal', $tanggal)
                           ->where('status', 'approved')
                           ->with(['komoditas', 'pasar'])
                           ->get();
    }

    public function findByCompositeKey($komoditas_id, $pasar_id, $tanggal)
    {
        return $this->model->where('komoditas_id', $komoditas_id)
                           ->where('pasar_id', $pasar_id)
                           ->where('tanggal', $tanggal)
                           ->first();
    }

    public function createOrUpdateAggregate(array $data)
    {
        return $this->model->updateOrCreate(
            [
                'komoditas_id' => $data['komoditas_id'],
                'pasar_id' => $data['pasar_id'],
                'tanggal' => $data['tanggal']
            ],
            [
                'harga_min' => $data['harga_min'],
                'harga_max' => $data['harga_max'],
                'harga_avg' => $data['harga_avg'],
                'status' => $data['status'] ?? 'draft'
            ]
        );
    }
}
