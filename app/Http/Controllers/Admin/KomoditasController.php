<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\KomoditasService;
use App\Services\KategoriService;
use Illuminate\Http\Request;

class KomoditasController extends Controller
{
    protected $service;
    protected $kategoriService;

    public function __construct(KomoditasService $service, KategoriService $kategoriService)
    {
        $this->service = $service;
        $this->kategoriService = $kategoriService;
    }

    public function index()
    {
        $komoditas = $this->service->getAll();
        $kategoris = $this->kategoriService->getAll();
        return view('admin.komoditas.index', compact('komoditas', 'kategoris'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'nama' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
        ]);

        $this->service->create($data);

        return redirect()->route('admin.komoditas.index')->with('success', 'Komoditas berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'nama' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
        ]);

        $this->service->update($id, $data);

        return redirect()->route('admin.komoditas.index')->with('success', 'Komoditas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        return redirect()->route('admin.komoditas.index')->with('success', 'Komoditas berhasil dihapus.');
    }
}
