<?php

namespace App\Repositories\Eloquent;

use App\Models\Pedagang;
use App\Repositories\Contracts\PedagangRepositoryInterface;

class PedagangRepository extends BaseRepository implements PedagangRepositoryInterface
{
    public function __construct(Pedagang $model)
    {
        parent::__construct($model);
    }

    public function getWithPasar()
    {
        return $this->model->with('pasar.kecamatan')->get();
    }
}
