<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DesaService;
use App\Services\KecamatanService;
use Illuminate\Http\Request;

class DesaController extends Controller
{
    protected $service;
    protected $kecamatanService;

    public function __construct(DesaService $service, KecamatanService $kecamatanService)
    {
        $this->service = $service;
        $this->kecamatanService = $kecamatanService;
    }

    public function index()
    {
        $desas = $this->service->getAll();
        $kecamatans = $this->kecamatanService->getAll();
        return view('admin.desas.index', compact('desas', 'kecamatans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'nama' => 'required|string|max:255',
        ]);

        $this->service->create($data);

        return redirect()->route('admin.desas.index')->with('success', 'Desa berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'kecamatan_id' => 'required|exists:kecamatans,id',
            'nama' => 'required|string|max:255',
        ]);

        $this->service->update($id, $data);

        return redirect()->route('admin.desas.index')->with('success', 'Desa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        return redirect()->route('admin.desas.index')->with('success', 'Desa berhasil dihapus.');
    }
}
