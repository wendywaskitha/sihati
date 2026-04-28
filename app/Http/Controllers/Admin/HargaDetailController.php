<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\HargaDetailService;
use App\Services\PasarService;
use App\Services\PedagangService;
use App\Services\KomoditasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HargaDetailController extends Controller
{
    protected $service;
    protected $pasarService;
    protected $pedagangService;
    protected $komoditasService;

    public function __construct(
        HargaDetailService $service,
        PasarService $pasarService,
        PedagangService $pedagangService,
        KomoditasService $komoditasService
    ) {
        $this->service = $service;
        $this->pasarService = $pasarService;
        $this->pedagangService = $pedagangService;
        $this->komoditasService = $komoditasService;
    }

    public function index(Request $request)
    {
        $query = \App\Models\HargaDetail::with(['komoditas', 'pedagang', 'pasar', 'creator']);

        $tanggal = $request->filled('tanggal') ? $request->tanggal : now()->format('Y-m-d');
        $query->where('tanggal', $tanggal);

        if ($request->filled('pasar_id')) {
            $query->where('pasar_id', $request->pasar_id);
        }
        if ($request->filled('komoditas_id')) {
            $query->where('komoditas_id', $request->komoditas_id);
        }

        $hargaDetails = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $pasars = \App\Models\Pasar::all();
        $komoditas = \App\Models\Komoditas::all();

        return view('admin.harga_details.index', compact('hargaDetails', 'pasars', 'komoditas', 'tanggal'));
    }

    public function create()
    {
        $pasars = $this->pasarService->getAll();
        $pedagangs = $this->pedagangService->getAll();
        $komoditas = $this->komoditasService->getAll();
        return view('admin.harga_details.create', compact('pasars', 'pedagangs', 'komoditas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pasar_id' => 'required|exists:pasars,id',
            'pedagang_id' => 'required',
            'tanggal' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.komoditas_id' => 'required|exists:komoditas,id',
            'items.*.harga' => 'required|numeric|min:0',
        ]);

        $pedagang_id = $request->pedagang_id;
        if (!is_numeric($pedagang_id)) {
            $newPedagang = $this->pedagangService->create([
                'pasar_id' => $request->pasar_id,
                'nama' => $pedagang_id,
            ]);
            $pedagang_id = $newPedagang->id;
        }

        foreach ($request->items as $item) {
            $this->service->create([
                'komoditas_id' => $item['komoditas_id'],
                'pedagang_id' => $pedagang_id,
                'pasar_id' => $request->pasar_id,
                'tanggal' => $request->tanggal,
                'harga' => $item['harga'],
                'created_by' => Auth::id(),
            ]);
        }

        return redirect()->route('admin.harga_details.index')->with('success', 'Data harga berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'harga' => 'required|numeric|min:0',
        ]);

        $detail = \App\Models\HargaDetail::findOrFail($id);
        
        $isApproved = \App\Models\Harga::where('komoditas_id', $detail->komoditas_id)
            ->where('pasar_id', $detail->pasar_id)
            ->where('tanggal', $detail->tanggal)
            ->where('status', 'approved')
            ->exists();

        if ($isApproved) {
            return redirect()->back()->with('error', 'Data harga telah divalidasi dan tidak dapat diubah.');
        }

        $this->service->update($id, [
            'harga' => $request->harga
        ]);

        return redirect()->route('admin.harga_details.index')->with('success', 'Data harga berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $detail = \App\Models\HargaDetail::findOrFail($id);
        
        $isApproved = \App\Models\Harga::where('komoditas_id', $detail->komoditas_id)
            ->where('pasar_id', $detail->pasar_id)
            ->where('tanggal', $detail->tanggal)
            ->where('status', 'approved')
            ->exists();

        if ($isApproved) {
            return redirect()->back()->with('error', 'Data harga telah divalidasi dan tidak dapat dihapus.');
        }

        $this->service->delete($id);

        return redirect()->route('admin.harga_details.index')->with('success', 'Data harga berhasil dihapus.');
    }
}
