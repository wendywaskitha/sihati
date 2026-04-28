<?php

namespace App\Repositories\Eloquent;

use App\Models\HargaDetail;
use App\Repositories\Contracts\HargaDetailRepositoryInterface;

class HargaDetailRepository extends BaseRepository implements HargaDetailRepositoryInterface
{
    public function __construct(HargaDetail $model)
    {
        parent::__construct($model);
    }

    public function getWithRelations()
    {
        return $this->model->with(['komoditas', 'pedagang', 'pasar', 'creator'])->get();
    }

    public function getByDateAndPasar($tanggal, $pasar_id)
    {
        return $this->model->where('tanggal', $tanggal)
                           ->where('pasar_id', $pasar_id)
                           ->get();
    }
}
