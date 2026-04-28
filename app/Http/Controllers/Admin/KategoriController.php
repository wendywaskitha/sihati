<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\KategoriService;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    protected $service;

    public function __construct(KategoriService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $kategoris = $this->service->getAll();
        return view('admin.kategoris.index', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255|unique:kategoris,nama',
        ]);

        $this->service->create($data);

        return redirect()->route('admin.kategoris.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255|unique:kategoris,nama,' . $id,
        ]);

        $this->service->update($id, $data);

        return redirect()->route('admin.kategoris.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kategori = \App\Models\Kategori::findOrFail($id);

        if ($kategori->komoditas()->count() > 0) {
            return redirect()->route('admin.kategoris.index')->with('error', 'Kategori tidak dapat dihapus karena masih memiliki komoditas terkait.');
        }

        $this->service->delete($id);

        return redirect()->route('admin.kategoris.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
