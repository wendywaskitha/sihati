<?php

namespace App\Services;

use App\Repositories\Contracts\KomoditasRepositoryInterface;

class KomoditasService
{
    protected $repository;

    public function __construct(KomoditasRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAll()
    {
        return $this->repository->getWithKategori();
    }

    public function getById($id)
    {
        return $this->repository->find($id);
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }
}
