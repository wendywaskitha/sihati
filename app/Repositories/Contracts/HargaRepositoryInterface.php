<?php

namespace App\Repositories\Contracts;

interface HargaRepositoryInterface extends RepositoryInterface
{
    public function getWithRelations();
    public function getApprovedByDate($tanggal);
    public function findByCompositeKey($komoditas_id, $pasar_id, $tanggal);
    public function createOrUpdateAggregate(array $data);
}
