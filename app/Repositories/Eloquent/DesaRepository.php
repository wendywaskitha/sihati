<?php

namespace App\Repositories\Eloquent;

use App\Models\Desa;
use App\Repositories\Contracts\DesaRepositoryInterface;

class DesaRepository extends BaseRepository implements DesaRepositoryInterface
{
    public function __construct(Desa $model)
    {
        parent::__construct($model);
    }

    public function getWithKecamatan()
    {
        return $this->model->with('kecamatan')->get();
    }
}
