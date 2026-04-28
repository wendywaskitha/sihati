<?php

namespace App\Repositories\Contracts;

interface HargaDetailRepositoryInterface extends RepositoryInterface
{
    public function getWithRelations();
    public function getByDateAndPasar($tanggal, $pasar_id);
}
