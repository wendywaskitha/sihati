<?php

namespace App\Services;

use App\Repositories\Contracts\HargaDetailRepositoryInterface;
use App\Repositories\Contracts\HargaRepositoryInterface;
use App\Models\HargaDetail;

class HargaDetailService
{
    protected $repository;
    protected $hargaRepository;

    public function __construct(
        HargaDetailRepositoryInterface $repository,
        HargaRepositoryInterface $hargaRepository
    ) {
        $this->repository = $repository;
        $this->hargaRepository = $hargaRepository;
    }

    public function getAll()
    {
        return $this->repository->getWithRelations();
    }

    public function create(array $data)
    {
        // 1. Save Harga Detail
        $hargaDetail = $this->repository->create($data);

        // 2. Trigger Aggregation
        $this->aggregate($hargaDetail->komoditas_id, $hargaDetail->pasar_id, $hargaDetail->tanggal);

        return $hargaDetail;
    }

    public function aggregate($komoditas_id, $pasar_id, $tanggal)
    {
        // Fetch all details for this composite key
        $details = HargaDetail::where('komoditas_id', $komoditas_id)
            ->where('pasar_id', $pasar_id)
            ->where('tanggal', $tanggal)
            ->get();

        if ($details->isEmpty()) {
            return;
        }

        $prices = $details->pluck('harga');
        $min = $prices->min();
        $max = $prices->max();
        $avg = $prices->avg();

        // Update or Create Harga record
        $this->hargaRepository->createOrUpdateAggregate([
            'komoditas_id' => $komoditas_id,
            'pasar_id' => $pasar_id,
            'tanggal' => $tanggal,
            'harga_min' => $min,
            'harga_max' => $max,
            'harga_avg' => $avg,
            'status' => 'draft', // Reset to draft on change
        ]);
    }
    public function update($id, array $data)
    {
        $hargaDetail = $this->repository->update($id, $data);
        $this->aggregate($hargaDetail->komoditas_id, $hargaDetail->pasar_id, $hargaDetail->tanggal);
        return $hargaDetail;
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }

    public function delete($id)
    {
        $hargaDetail = $this->repository->find($id);
        $this->repository->delete($id);
        $this->aggregate($hargaDetail->komoditas_id, $hargaDetail->pasar_id, $hargaDetail->tanggal);
    }
}
