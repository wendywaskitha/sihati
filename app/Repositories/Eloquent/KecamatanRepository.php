<?php

namespace App\Repositories\Eloquent;

use App\Models\Kecamatan;
use App\Repositories\Contracts\KecamatanRepositoryInterface;

class KecamatanRepository extends BaseRepository implements KecamatanRepositoryInterface
{
    public function __construct(Kecamatan $model)
    {
        parent::__construct($model);
    }
}
