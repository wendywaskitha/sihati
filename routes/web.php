<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\KomoditasController;
use App\Http\Controllers\Admin\KecamatanController;
use App\Http\Controllers\Admin\DesaController;
use App\Http\Controllers\Admin\PasarController;
use App\Http\Controllers\Admin\PedagangController;
use App\Http\Controllers\Admin\HargaDetailController;
use App\Http\Controllers\Admin\HargaController;
use App\Http\Controllers\PublicController;

Route::get('/', [PublicController::class, 'index'])->name('public.home');
Route::get('/harga-pasar', [PublicController::class, 'hargaPerPasar'])->name('public.harga_pasar');
Route::get('/grafik', [PublicController::class, 'grafik'])->name('public.grafik');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('admin')->name('admin.')->group(function () {
        
        // Akses untuk Admin & Petugas Lapangan
        Route::middleware('role:admin,petugas')->group(function () {
            Route::get('/dashboard', function () {
                $counts = [
                    'kategori' => \App\Models\Kategori::count(),
                    'komoditas' => \App\Models\Komoditas::count(),
                    'pasar' => \App\Models\Pasar::count(),
                    'pedagang' => \App\Models\Pedagang::count(),
                ];

                $isAdmin = auth()->user()->role === 'admin';
                $pending_hargas = [];
                $chart_labels = [];
                $chart_data = [];
                $cat_labels = [];
                $cat_data = [];
                $trend_labels = [];
                $trend_datasets = [];
                $disp_labels = [];
                $disp_min = [];
                $disp_max = [];

                if ($isAdmin) {
                    // Fetch recent pending or draft prices
                    $pending_hargas = \App\Models\Harga::whereIn('status', ['pending', 'draft'])
                        ->with(['pasar', 'komoditas'])
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();

                    // Submissions per Pasar
                    $submissions_per_pasar = \App\Models\Harga::select('pasar_id', \DB::raw('count(*) as total'))
                        ->groupBy('pasar_id')
                        ->with('pasar')
                        ->get();

                    foreach ($submissions_per_pasar as $sub) {
                        $chart_labels[] = $sub->pasar->nama ?? 'Pasar';
                        $chart_data[] = $sub->total;
                    }

                    // Komoditas per Kategori
                    $komoditas_per_kategori = \App\Models\Komoditas::select('kategori_id', \DB::raw('count(*) as total'))
                        ->groupBy('kategori_id')
                        ->with('kategori')
                        ->get();

                    $cat_labels = [];
                    $cat_data = [];
                    foreach ($komoditas_per_kategori as $cat) {
                        $cat_labels[] = $cat->kategori->nama ?? 'Umum';
                        $cat_data[] = $cat->total;
                    }

                    // 2. Tren Harga Komoditas Utama (30 Hari Terakhir)
                    $startDate = now()->subDays(30)->format('Y-m-d');
                    $price_trend = \App\Models\Harga::select('tanggal', 'komoditas_id', \DB::raw('AVG(harga_avg) as avg_price'))
                        ->where('tanggal', '>=', $startDate)
                        ->where('status', 'approved')
                        ->groupBy('tanggal', 'komoditas_id')
                        ->orderBy('tanggal', 'asc')
                        ->with('komoditas')
                        ->get();

                    $trend_labels = $price_trend->pluck('tanggal')->unique()->values()->toArray();
                    $commodities_trend = [];
                    foreach($price_trend as $pt) {
                        $comName = $pt->komoditas->nama ?? 'Unknown';
                        if(!isset($commodities_trend[$comName])) {
                            $commodities_trend[$comName] = array_fill(0, count($trend_labels), 0);
                        }
                        $labelIndex = array_search($pt->tanggal, $trend_labels);
                        if($labelIndex !== false) {
                            $commodities_trend[$comName][$labelIndex] = (int)$pt->avg_price;
                        }
                    }

                    $trend_datasets = [];
                    $colors = ['#667eea', '#48bb78', '#ed8936', '#f56565', '#9f7aea', '#4a5568'];
                    $colorIndex = 0;
                    foreach($commodities_trend as $name => $data) {
                        $trend_datasets[] = [
                            'label' => $name,
                            'data' => $data,
                            'borderColor' => $colors[$colorIndex % count($colors)],
                            'backgroundColor' => 'transparent',
                            'borderWidth' => 2,
                            'tension' => 0.3
                        ];
                        $colorIndex++;
                    }

                    // 3. Disparitas Harga Minimum vs Maksimum
                    $disparitas_prices = \App\Models\Harga::where('status', 'approved')
                        ->orderBy('tanggal', 'desc')
                        ->with(['komoditas', 'pasar'])
                        ->take(8)
                        ->get();

                    $disp_labels = [];
                    $disp_min = [];
                    $disp_max = [];
                    foreach($disparitas_prices as $dp) {
                        $disp_labels[] = ($dp->komoditas->nama ?? 'Unknown') . ' (' . ($dp->pasar->nama ?? 'Pasar') . ')';
                        $disp_min[] = (int)$dp->harga_min;
                        $disp_max[] = (int)$dp->harga_max;
                    }
                }

                return view('admin.dashboard', compact(
                    'counts', 'isAdmin', 'pending_hargas', 
                    'chart_labels', 'chart_data', 
                    'cat_labels', 'cat_data',
                    'trend_labels', 'trend_datasets',
                    'disp_labels', 'disp_min', 'disp_max'
                ));
            })->name('dashboard');

            Route::resource('harga_details', HargaDetailController::class);
        });

        // Khusus Akses Admin
        Route::middleware('role:admin')->group(function () {
            Route::resource('kategoris', KategoriController::class);
            Route::resource('komoditas', KomoditasController::class);
            Route::resource('kecamatans', KecamatanController::class);
            Route::resource('desas', DesaController::class);
            Route::resource('pasars', PasarController::class);
            Route::resource('pedagangs', PedagangController::class);
            
            Route::get('/hargas', [HargaController::class, 'index'])->name('hargas.index');
            Route::post('/hargas/{id}/approve', [HargaController::class, 'approve'])->name('hargas.approve');
            Route::post('/hargas/{id}/reject', [HargaController::class, 'reject'])->name('hargas.reject');

            Route::get('/laporan', [\App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('laporan.index');
            Route::get('/laporan/cetak', [\App\Http\Controllers\Admin\LaporanController::class, 'cetak'])->name('laporan.cetak');

            Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

            Route::get('/pengaturan', [\App\Http\Controllers\Admin\PengaturanController::class, 'index'])->name('pengaturan.index');
            Route::post('/pengaturan', [\App\Http\Controllers\Admin\PengaturanController::class, 'update'])->name('pengaturan.update');

            Route::get('/backup', [\App\Http\Controllers\Admin\BackupController::class, 'index'])->name('backup.index');
            Route::post('/backup/export', [\App\Http\Controllers\Admin\BackupController::class, 'backup'])->name('backup.export');
            Route::post('/backup/restore', [\App\Http\Controllers\Admin\BackupController::class, 'restore'])->name('backup.restore');
            Route::post('/backup/clear-draft', [\App\Http\Controllers\Admin\BackupController::class, 'clearDraft'])->name('backup.clear_draft');
            Route::post('/backup/clear-approved', [\App\Http\Controllers\Admin\BackupController::class, 'clearApproved'])->name('backup.clear_approved');
        });
    });
});
