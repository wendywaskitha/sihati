<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\KecamatanService;
use Illuminate\Http\Request;

class KecamatanController extends Controller
{
    protected $service;

    public function __construct(KecamatanService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $kecamatans = $this->service->getAll();
        return view('admin.kecamatans.index', compact('kecamatans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255|unique:kecamatans,nama',
        ]);

        $this->service->create($data);

        return redirect()->route('admin.kecamatans.index')->with('success', 'Kecamatan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255|unique:kecamatans,nama,' . $id,
        ]);

        $this->service->update($id, $data);

        return redirect()->route('admin.kecamatans.index')->with('success', 'Kecamatan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        return redirect()->route('admin.kecamatans.index')->with('success', 'Kecamatan berhasil dihapus.');
    }
}
