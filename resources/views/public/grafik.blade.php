<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sihati - Grafik Perbandingan</title>

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ \App\Models\Pengaturan::getValue('app_name', 'Sihati') }} - Grafik Perbandingan Harga">
    <meta property="og:description" content="Analisis tren dan komparasi grafik fluktuasi harga komoditi pangan secara transparan.">
    <meta property="og:image" content="{{ asset('pwa-icon.png') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ \App\Models\Pengaturan::getValue('app_name', 'Sihati') }} - Grafik Perbandingan Harga">
    <meta property="twitter:description" content="Analisis tren dan komparasi grafik fluktuasi harga komoditi pangan secara transparan.">
    <meta property="twitter:image" content="{{ asset('pwa-icon.png') }}">

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
    <!-- Google Fonts -->
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

        .form-select {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #e2e8f0;
        }

        .btn-premium {
            background: linear-gradient(to right, #667eea, #764ba2);
            border: none;
            color: white;
            transition: all 0.3s ease;
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
        }
    </style>
</head>
<body>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            @include('partials.public_nav')
            
            <div class="text-center mt-3 mt-lg-4">
                <h1 class="fw-bold mb-3">Grafik Perbandingan Harga</h1>
                <p class="fs-5 text-white-50">Perbandingan harga komoditas yang sama antar berbagai pasar.</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mb-5">
        <!-- Commodity Filter -->
        <div class="card card-premium p-4 mb-4">
            <form action="{{ route('public.grafik') }}" method="GET" class="row g-3 align-items-end justify-content-center">
                <div class="col-md-5">
                    <label for="komoditas_id" class="form-label text-secondary fw-medium">Pilih Komoditas</label>
                    <select class="form-select" id="komoditas_id" name="komoditas_id">
                        @foreach($komoditas as $item)
                            <option value="{{ $item->id }}" {{ $selected_komoditas_id == $item->id ? 'selected' : '' }}>
                                {{ $item->nama }} ({{ $item->satuan ?? 'Kg' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-premium w-100 py-2 fw-bold rounded-pill">
                        <i class="fa-solid fa-chart-pie me-2"></i> Tampilkan
                    </button>
                </div>
            </form>
        </div>

        <!-- Chart Card -->
        <div class="card card-premium p-4">
            <h5 class="fw-bold text-dark mb-4 text-center">
                Perbandingan Harga: {{ $selected_komoditas_name }}
            </h5>
            
            @if(count($labels) > 0)
                <div style="position: relative; height: 400px; width: 100%;">
                    <canvas id="comparisonChart"></canvas>
                </div>
            @else
                <div class="text-center text-muted py-5">
                    <i class="fa-solid fa-chart-area fa-3x mb-3 opacity-50"></i>
                    <p class="mb-0">Tidak ada data harga yang cukup untuk membuat grafik perbandingan saat ini.</p>
                </div>
            @endif
        </div>
    </div>

    @include('partials.public_footer')

    @if(count($labels) > 0)
    <script>
        const ctx = document.getElementById('comparisonChart').getContext('2d');
        const comparisonChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [
                    {
                        label: 'Harga Min (Rp)',
                        data: {!! json_encode($data_min) !!},
                        backgroundColor: 'rgba(255, 193, 7, 0.6)',
                        borderColor: 'rgb(255, 193, 7)',
                        borderWidth: 1,
                        borderRadius: 5
                    },
                    {
                        label: 'Harga Max (Rp)',
                        data: {!! json_encode($data_max) !!},
                        backgroundColor: 'rgba(220, 53, 69, 0.6)',
                        borderColor: 'rgb(220, 53, 69)',
                        borderWidth: 1,
                        borderRadius: 5
                    },
                    {
                        label: 'Harga Rata-Rata (Rp)',
                        data: {!! json_encode($data_avg) !!},
                        backgroundColor: 'rgba(25, 135, 84, 0.8)',
                        borderColor: 'rgb(25, 135, 84)',
                        borderWidth: 1,
                        borderRadius: 5
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
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
                                let label = '{{ $selected_komoditas_name }} (' + (context.dataset.label || '') + ')';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    </script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
