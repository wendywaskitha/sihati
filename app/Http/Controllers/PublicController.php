<?php

namespace App\Http\Controllers;

use App\Services\HargaService;
use App\Services\KomoditasService;
use App\Services\PasarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicController extends Controller
{
    protected $hargaService;
    protected $komoditasService;
    protected $pasarService;

    public function __construct(
        HargaService $hargaService,
        KomoditasService $komoditasService,
        PasarService $pasarService
    ) {
        $this->hargaService = $hargaService;
        $this->komoditasService = $komoditasService;
        $this->pasarService = $pasarService;
    }

    public function index(Request $request)
    {
        $query = \App\Models\Harga::where('status', 'approved');

        if ($request->filled('tanggal')) {
            $view_date = $request->tanggal;
            $query->where('tanggal', $request->tanggal);
        } else {
            $latestDate = \App\Models\Harga::where('status', 'approved')->max('tanggal');
            if ($latestDate) {
                $query->where('tanggal', $latestDate);
                $view_date = $latestDate;
            } else {
                $view_date = now()->toDateString();
            }
        }

        if ($request->filled('komoditas_id')) {
            $query->where('komoditas_id', $request->komoditas_id);
        }

        if ($request->filled('pasar_id')) {
            $query->where('pasar_id', $request->pasar_id);
        }

        $hargas = $query->with(['komoditas', 'pasar'])->paginate(10)->withQueryString();
        $komoditas = $this->komoditasService->getAll();
        $pasars = $this->pasarService->getAll();

        // Price fluctuation chart data
        $chart_labels = [];
        $chart_datasets = [];
        $selected_komoditas_name = '';

        $startOfMonth = \Carbon\Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = \Carbon\Carbon::now()->endOfMonth()->toDateString();

        if ($request->filled('komoditas_id')) {
            $selected_komoditas = \App\Models\Komoditas::find($request->komoditas_id);
            $selected_komoditas_name = $selected_komoditas->nama ?? '';

            $fluctuationQuery = \App\Models\Harga::where('status', 'approved')
                ->where('komoditas_id', $request->komoditas_id)
                ->whereBetween('tanggal', [$startOfMonth, $endOfMonth]);
            
            if ($request->filled('pasar_id')) {
                $fluctuationQuery->where('pasar_id', $request->pasar_id);
            }

            $fluctuations = $fluctuationQuery->select('tanggal', DB::raw('AVG(harga_avg) as avg_price'))
                ->groupBy('tanggal')
                ->orderBy('tanggal', 'asc')
                ->get();

            $data_points = [];
            foreach ($fluctuations as $f) {
                $chart_labels[] = \Carbon\Carbon::parse($f->tanggal)->format('d/m/Y');
                $data_points[] = round($f->avg_price);
            }

            $chart_datasets[] = [
                'label' => $selected_komoditas_name . ' (' . ($selected_komoditas->satuan ?? 'Kg') . ')',
                'data' => $data_points,
                'borderColor' => '#667eea',
                'backgroundColor' => 'rgba(102, 126, 234, 0.1)',
                'borderWidth' => 3,
                'fill' => false,
                'tension' => 0.3,
                'pointRadius' => 4,
            ];
        } else {
            // Fetch multiple commodities trend
            $dateQuery = \App\Models\Harga::where('status', 'approved')
                ->whereBetween('tanggal', [$startOfMonth, $endOfMonth]);
            if ($request->filled('pasar_id')) {
                $dateQuery->where('pasar_id', $request->pasar_id);
            }
            $dates = $dateQuery->orderBy('tanggal', 'asc')->pluck('tanggal')->unique();

            foreach ($dates as $date) {
                $chart_labels[] = \Carbon\Carbon::parse($date)->format('d/m/Y');
            }

            $all_komoditas = \App\Models\Komoditas::all();
            foreach ($all_komoditas as $index => $k) {
                $data_points = [];
                foreach ($dates as $date) {
                    $q = \App\Models\Harga::where('status', 'approved')
                        ->where('komoditas_id', $k->id)
                        ->where('tanggal', $date);

                    if ($request->filled('pasar_id')) {
                        $q->where('pasar_id', $request->pasar_id);
                    }

                    $avg = $q->avg('harga_avg');
                    $data_points[] = $avg ? round($avg) : null;
                }

                if (array_filter($data_points)) {
                    // Generate a visually appealing distinct color based on index
                    $hue = ($index * 137.5) % 360; // Golden angle for color distribution
                    
                    $chart_datasets[] = [
                        'label' => $k->nama . ' (' . ($k->satuan ?? 'Kg') . ')',
                        'data' => $data_points,
                        'borderColor' => "hsl($hue, 70%, 50%)",
                        'backgroundColor' => "hsl($hue, 70%, 50%, 0.1)",
                        'borderWidth' => 2,
                        'fill' => false,
                        'tension' => 0.3,
                        'pointRadius' => 3,
                    ];
                }
            }
        }

        return view('welcome', compact('hargas', 'komoditas', 'pasars', 'chart_labels', 'chart_datasets', 'selected_komoditas_name', 'view_date'));
    }

    public function hargaPerPasar(Request $request)
    {
        $query = \App\Models\Harga::where('status', 'approved');

        if ($request->filled('tanggal')) {
            $query->where('tanggal', $request->tanggal);
        } else {
            $latestDate = \App\Models\Harga::where('status', 'approved')->max('tanggal');
            if ($latestDate) {
                $query->where('tanggal', $latestDate);
            }
        }

        $hargas = $query->with(['komoditas', 'pasar'])->get()->groupBy('pasar_id');
        $pasars = $this->pasarService->getAll();

        return view('public.harga_pasar', compact('hargas', 'pasars'));
    }

    public function grafik(Request $request)
    {
        $komoditas = $this->komoditasService->getAll();
        $selected_komoditas_id = $request->input('komoditas_id', $komoditas->first()->id ?? null);
        
        $labels = [];
        $data_min = [];
        $data_max = [];
        $data_avg = [];
        $selected_komoditas_name = '';

        if ($selected_komoditas_id) {
            $selected_komoditas = \App\Models\Komoditas::find($selected_komoditas_id);
            $selected_komoditas_name = $selected_komoditas->nama . ' / ' . ($selected_komoditas->satuan ?? 'Kg');

            $latestDate = \App\Models\Harga::where('status', 'approved')
                ->where('komoditas_id', $selected_komoditas_id)
                ->max('tanggal');

            if ($latestDate) {
                $hargas = \App\Models\Harga::where('status', 'approved')
                    ->where('komoditas_id', $selected_komoditas_id)
                    ->where('tanggal', $latestDate)
                    ->with('pasar')
                    ->get();

                foreach ($hargas as $harga) {
                    $labels[] = $harga->pasar->nama ?? 'Unknown';
                    $data_min[] = $harga->harga_min;
                    $data_max[] = $harga->harga_max;
                    $data_avg[] = $harga->harga_avg;
                }
            }
        }

        return view('public.grafik', compact('komoditas', 'selected_komoditas_id', 'selected_komoditas_name', 'labels', 'data_min', 'data_max', 'data_avg'));
    }
}
