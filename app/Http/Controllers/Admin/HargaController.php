<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\HargaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HargaController extends Controller
{
    protected $service;

    public function __construct(HargaService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $query = \App\Models\Harga::with(['komoditas', 'pasar']);

        if ($request->filled('pasar_id')) {
            $query->where('pasar_id', $request->pasar_id);
        }
        if ($request->filled('komoditas_id')) {
            $query->where('komoditas_id', $request->komoditas_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $query->orderByRaw("CASE WHEN status = 'draft' THEN 0 ELSE 1 END")
              ->orderBy('tanggal', 'desc');

        $hargas = $query->paginate(15)->withQueryString();
        $pasars = \App\Models\Pasar::all();
        $komoditas = \App\Models\Komoditas::all();

        return view('admin.hargas.index', compact('hargas', 'pasars', 'komoditas'));
    }

    public function approve($id)
    {
        $this->service->approve($id, Auth::id());
        return redirect()->route('admin.hargas.index')->with('success', 'Harga berhasil divalidasi dan dipublikasikan.');
    }

    public function reject($id)
    {
        $this->service->reject($id);
        return redirect()->route('admin.hargas.index')->with('success', 'Harga dikembalikan ke status draft.');
    }
}
