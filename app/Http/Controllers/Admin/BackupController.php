<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Harga;
use App\Models\HargaDetail;

class BackupController extends Controller
{
    public function index()
    {
        return view('admin.backup.index');
    }

    public function backup()
    {
        $tables = ['users', 'kategoris', 'komoditas', 'kecamatans', 'desas', 'pasars', 'pedagangs', 'pengaturans', 'hargas', 'harga_details'];
        $backup = [];
        
        foreach ($tables as $table) {
            $backup[$table] = DB::table($table)->get()->toArray();
        }

        return response()->json($backup, 200, [
            'Content-Disposition' => 'attachment; filename="sihati_backup_'.date('Y-m-d_H-i-s').'.json"',
        ]);
    }

    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file'
        ]);

        $file = $request->file('backup_file');
        $content = file_get_contents($file->getRealPath());
        $data = json_decode($content, true);

        if (!$data || !is_array($data)) {
            return redirect()->back()->with('error', 'Format file backup tidak valid.');
        }

        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            foreach ($data as $table => $rows) {
                if (DB::getSchemaBuilder()->hasTable($table)) {
                    DB::table($table)->truncate();
                    foreach ($rows as $row) {
                        DB::table($table)->insert((array)$row);
                    }
                }
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return redirect()->back()->with('success', 'Data berhasil dipulihkan dari backup.');
        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memulihkan data: ' . $e->getMessage());
        }
    }

    public function clearDraft()
    {
        $approvedHargas = Harga::where('status', 'approved')->get();
        $keepIds = [];
        
        foreach ($approvedHargas as $h) {
            $ids = HargaDetail::where('komoditas_id', $h->komoditas_id)
                ->where('pasar_id', $h->pasar_id)
                ->where('tanggal', $h->tanggal)
                ->pluck('id')
                ->toArray();
            $keepIds = array_merge($keepIds, $ids);
        }
        
        $deletedCount = HargaDetail::whereNotIn('id', $keepIds)->delete();

        return redirect()->back()->with('success', "Berhasil menghapus $deletedCount data Input Harga berstatus Draft.");
    }

    public function clearApproved()
    {
        $approvedHargas = Harga::where('status', 'approved')->get();
        $deletedDetailsCount = 0;
        
        foreach ($approvedHargas as $h) {
            $deletedDetailsCount += HargaDetail::where('komoditas_id', $h->komoditas_id)
                ->where('pasar_id', $h->pasar_id)
                ->where('tanggal', $h->tanggal)
                ->delete();
        }

        $deletedAggregatesCount = Harga::where('status', 'approved')->delete();

        return redirect()->back()->with('success', "Berhasil menghapus $deletedAggregatesCount data Validasi Harga Approved beserta $deletedDetailsCount rincian harganya.");
    }
}
