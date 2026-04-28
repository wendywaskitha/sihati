<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PedagangService;
use App\Services\PasarService;
use Illuminate\Http\Request;

class PedagangController extends Controller
{
    protected $service;
    protected $pasarService;

    public function __construct(PedagangService $service, PasarService $pasarService)
    {
        $this->service = $service;
        $this->pasarService = $pasarService;
    }

    public function index()
    {
        $pedagangs = $this->service->getAll();
        $pasars = $this->pasarService->getAll();
        return view('admin.pedagangs.index', compact('pedagangs', 'pasars'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pasar_id' => 'required|exists:pasars,id',
            'nama' => 'required|string|max:255',
        ]);

        $this->service->create($data);

        return redirect()->route('admin.pedagangs.index')->with('success', 'Pedagang berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'pasar_id' => 'required|exists:pasars,id',
            'nama' => 'required|string|max:255',
        ]);

        $this->service->update($id, $data);

        return redirect()->route('admin.pedagangs.index')->with('success', 'Pedagang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        return redirect()->route('admin.pedagangs.index')->with('success', 'Pedagang berhasil dihapus.');
    }
}
