<?php

namespace App\Services;

use App\Repositories\Contracts\HargaRepositoryInterface;

class HargaService
{
    protected $repository;

    public function __construct(HargaRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->getWithRelations();
    }

    public function getById($id)
    {
        return $this->repository->find($id);
    }

    public function approve($id, $userId)
    {
        return $this->repository->update($id, [
            'status' => 'approved',
            'approved_by' => $userId
        ]);
    }

    public function reject($id)
    {
        return $this->repository->update($id, [
            'status' => 'draft',
            'approved_by' => null
        ]);
    }
}
