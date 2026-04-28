<?php

namespace App\Repositories\Eloquent;

use App\Models\Komoditas;
use App\Repositories\Contracts\KomoditasRepositoryInterface;

class KomoditasRepository extends BaseRepository implements KomoditasRepositoryInterface
{
    public function __construct(Komoditas $model)
    {
        parent::__construct($model);
    }

    public function getWithKategori()
    {
        return $this->model->with('kategori')->get();
    }
}
