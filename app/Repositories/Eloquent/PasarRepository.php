<?php

namespace App\Repositories\Eloquent;

use App\Models\Pasar;
use App\Repositories\Contracts\PasarRepositoryInterface;

class PasarRepository extends BaseRepository implements PasarRepositoryInterface
{
    public function __construct(Pasar $model)
    {
        parent::__construct($model);
    }

    public function getWithKecamatan()
    {
        return $this->model->with('kecamatan')->get();
    }
}
