<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Harga;
use App\Models\Komoditas;
use App\Models\Pasar;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = Harga::where('status', 'approved');

        if ($request->filled('tanggal_mulai')) {
            $query->where('tanggal', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->where('tanggal', '<=', $request->tanggal_selesai);
        }
        if ($request->filled('pasar_id')) {
            $query->where('pasar_id', $request->pasar_id);
        }
        if ($request->filled('komoditas_id')) {
            $query->where('komoditas_id', $request->komoditas_id);
        }

        $hargas = $query->with(['pasar', 'komoditas'])->orderBy('tanggal', 'desc')->paginate(15)->withQueryString();
        $pasars = Pasar::all();
        $komoditas = Komoditas::all();

        return view('admin.laporan.index', compact('hargas', 'pasars', 'komoditas'));
    }

    public function cetak(Request $request)
    {
        $query = Harga::where('status', 'approved');

        if ($request->filled('tanggal_mulai')) {
            $query->where('tanggal', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->where('tanggal', '<=', $request->tanggal_selesai);
        }
        if ($request->filled('pasar_id')) {
            $query->where('pasar_id', $request->pasar_id);
        }
        if ($request->filled('komoditas_id')) {
            $query->where('komoditas_id', $request->komoditas_id);
        }

        $hargas = $query->with(['pasar', 'komoditas'])->orderBy('tanggal', 'desc')->get();
        
        $selected_pasar = $request->filled('pasar_id') ? Pasar::find($request->pasar_id) : null;
        $selected_komoditas = $request->filled('komoditas_id') ? Komoditas::find($request->komoditas_id) : null;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan.cetak', compact('hargas', 'selected_pasar', 'selected_komoditas', 'request'));
        return $pdf->stream('laporan-harga-komoditas.pdf');
    }
}
