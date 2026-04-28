<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PasarService;
use App\Services\KecamatanService;
use Illuminate\Http\Request;

class PasarController extends Controller
{
    protected $service;
    protected $kecamatanService;

    public function __construct(PasarService $service, KecamatanService $kecamatanService)
    {
        $this->service = $service;
        $this->kecamatanService = $kecamatanService;
    }

    public function index()
    {
        $pasars = $this->service->getAll();
        $kecamatans = $this->kecamatanService->getAll();
        return view('admin.pasars.index', compact('pasars', 'kecamatans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'nama' => 'required|string|max:255',
        ]);

        $this->service->create($data);

        return redirect()->route('admin.pasars.index')->with('success', 'Pasar berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'nama' => 'required|string|max:255',
        ]);

        $this->service->update($id, $data);

        return redirect()->route('admin.pasars.index')->with('success', 'Pasar berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        return redirect()->route('admin.pasars.index')->with('success', 'Pasar berhasil dihapus.');
    }
}
