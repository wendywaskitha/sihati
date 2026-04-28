<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ \App\Models\Pengaturan::getValue('app_name', 'Sihati') }} - Informasi Harga Pasar Komoditi Pertanian</title>
    @if(\App\Models\Pengaturan::getValue('app_favicon'))
        <link rel="icon" type="image/x-icon" href="{{ asset(\App\Models\Pengaturan::getValue('app_favicon')) }}">
    @endif

    <!-- PWA Setup -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#667eea">
    <link rel="apple-touch-icon" href="/pwa-icon.png">
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('PWA Service Worker Registered!'))
                    .catch(err => console.error('PWA Registration Failed!', err));
            });
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f8f9fa;
        }
        
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 0;
            border-radius: 0 0 40px 40px;
            margin-bottom: 25px;
        }

        .card-premium {
            background: #ffffff;
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .form-control, .form-select {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #e2e8f0;
        }

        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.25);
            border-color: #667eea;
        }

        .btn-premium {
            background: linear-gradient(to right, #667eea, #764ba2);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(118, 75, 162, 0.4);
            color: white;
        }

        @media (max-width: 768px) {
            .hero-section {
                padding: 25px 0;
                border-radius: 0 0 20px 20px;
                margin-bottom: 15px;
            }
            .hero-section h1 {
                font-size: 1.4rem;
            }
            .hero-section p {
                font-size: 0.85rem !important;
            }
            .card-premium {
                padding: 12px !important;
                border-radius: 12px;
            }
            .table th, .table td {
                padding: 6px;
                font-size: 0.85rem;
            }
            .fs-5 {
                font-size: 1.05rem !important;
            }
        }
    </style>
</head>
<body>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            @include('partials.public_nav')
            
            <div class="text-center mt-3 mt-lg-4">
                <h1 class="fw-bold mb-3">Pantau Harga Komoditi Pertanian</h1>
                <p class="fs-5 text-white-50">Informasi harga pasar harian yang akurat, transparan, dan mudah diakses.</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mb-5">
        <!-- Filter Card -->
        <div class="card card-premium p-3 mb-3">
            <form action="{{ url('/') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label for="tanggal" class="form-label text-secondary fw-medium">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ request('tanggal', date('Y-m-d')) }}">
                </div>
                <div class="col-md-3">
                    <label for="komoditas_id" class="form-label text-secondary fw-medium">Komoditas</label>
                    <select class="form-select" id="komoditas_id" name="komoditas_id">
                        <option value="">Semua Komoditas</option>
                        @foreach($komoditas as $item)
                            <option value="{{ $item->id }}" {{ request('komoditas_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }} ({{ $item->satuan ?? 'Kg' }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="pasar_id" class="form-label text-secondary fw-medium">Pasar</label>
                    <select class="form-select" id="pasar_id" name="pasar_id">
                        <option value="">Semua Pasar</option>
                        @foreach($pasars as $pasar)
                            <option value="{{ $pasar->id }}" {{ request('pasar_id') == $pasar->id ? 'selected' : '' }}>{{ $pasar->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-premium w-100 py-2 fw-bold rounded-pill">
                        <i class="fa-solid fa-magnifying-glass me-2"></i> Cari Harga
                    </button>
                </div>
            </form>
        </div>

        @if(count($chart_labels) > 0)
        <!-- Fluctuation Chart -->
        <div class="card card-premium p-3 mb-3" id="chartWrapper">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="fa-solid fa-chart-line text-primary me-2"></i> 
                    Grafik Fluktuasi Harga (Bulan Ini) {{ $selected_komoditas_name ? ': ' . $selected_komoditas_name : '(Semua Komoditas)' }}
                </h5>
                <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="toggleFullscreen()">
                    <i class="fa-solid fa-expand me-1"></i> Full Screen
                </button>
            </div>
            <div style="position: relative; height: 350px; width: 100%;" id="canvasWrapper">
                <canvas id="fluctuationChart"></canvas>
            </div>
        </div>

        <style>
            #chartWrapper:fullscreen {
                background: #ffffff !important;
                padding: 30px !important;
                width: 100vw !important;
                height: 100vh !important;
                display: flex !important;
                flex-direction: column !important;
                justify-content: center !important;
            }
            #chartWrapper:fullscreen #canvasWrapper {
                height: 85% !important;
            }
        </style>

        <script>
            function toggleFullscreen() {
                const elem = document.getElementById('chartWrapper');
                if (!document.fullscreenElement) {
                    if (elem.requestFullscreen) {
                        elem.requestFullscreen();
                    } else if (elem.webkitRequestFullscreen) { /* Safari */
                        elem.webkitRequestFullscreen();
                    } else if (elem.msRequestFullscreen) { /* IE11 */
                        elem.msRequestFullscreen();
                    }
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    }
                }
            }
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('fluctuationChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($chart_labels) !!},
                        datasets: {!! json_encode($chart_datasets) !!}
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: false,
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp ' + value.toLocaleString('id-ID');
                                    }
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        return label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                                    }
                                }
                            }
                        }
                    }
                });
            });
        </script>
        @endif

        <!-- Price Table -->
        <div class="card card-premium p-3">
            <h5 class="fw-bold text-dark mb-4">Harga Komoditas Terkini ({{ \Carbon\Carbon::parse($view_date)->format('d/m/Y') }})</h5>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="d-none d-sm-table-cell">No</th>
                            <th>Komoditas</th>
                            <th>Pasar</th>
                            <th class="d-none d-md-table-cell">Harga Min</th>
                            <th class="d-none d-md-table-cell">Harga Max</th>
                            <th>Harga Rata-Rata</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hargas as $index => $harga)
                        <tr>
                            <td class="d-none d-sm-table-cell">{{ $index + 1 }}</td>
                            <td class="fw-bold text-dark">
                                <div>{{ $harga->komoditas->nama ?? '-' }}</div>
                                <small class="text-muted fw-normal" style="font-size: 0.8rem;">Satuan: {{ $harga->komoditas->satuan ?? 'Kg' }}</small>
                                <div class="d-md-none text-secondary mt-1 fw-normal" style="font-size: 0.75rem;">
                                    Min: Rp {{ number_format($harga->harga_min, 0, ',', '.') }} | Max: Rp {{ number_format($harga->harga_max, 0, ',', '.') }}
                                </div>
                            </td>
                            <td><span class="badge bg-light text-primary rounded-pill px-3">{{ $harga->pasar->nama ?? '-' }}</span></td>
                            <td class="text-secondary d-none d-md-table-cell">Rp {{ number_format($harga->harga_min, 0, ',', '.') }}</td>
                            <td class="text-secondary d-none d-md-table-cell">Rp {{ number_format($harga->harga_max, 0, ',', '.') }}</td>
                            <td class="fw-bold text-success fs-5">Rp {{ number_format($harga->harga_avg, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="fa-solid fa-folder-open fa-3x mb-3 text-secondary opacity-50"></i>
                                <p class="mb-0">Tidak ada data harga yang ditemukan untuk filter ini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4 d-flex justify-content-center">
                {{ $hargas->links() }}
            </div>
        </div>
    </div>

    @include('partials.public_footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
