<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sihati - Harga per Pasar</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
                <h1 class="fw-bold mb-3">Daftar Harga Berdasarkan Pasar</h1>
                <p class="fs-5 text-white-50">Memudahkan Anda membandingkan harga berbagai komoditas di satu tempat.</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mb-5">
        <!-- Date Filter -->
        <div class="card card-premium p-4 mb-4">
            <form action="{{ route('public.harga_pasar') }}" method="GET" class="row g-3 align-items-end justify-content-center">
                <div class="col-md-4">
                    <label for="tanggal" class="form-label text-secondary fw-medium">Pilih Tanggal</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ request('tanggal', date('Y-m-d')) }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-premium w-100 py-2 fw-bold rounded-pill">
                        <i class="fa-solid fa-magnifying-glass me-2"></i> Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Markets Grid -->
        <div class="row g-4">
            @forelse($pasars as $pasar)
            <div class="col-md-6">
                <div class="card card-premium p-4 h-100">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="fa-solid fa-store me-2 text-warning"></i>{{ $pasar->nama }}
                    </h5>
                    <p class="text-secondary small mb-3"><i class="fa-solid fa-map-marker-alt me-1"></i> {{ $pasar->kecamatan->nama ?? '-' }}</p>
                    
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless align-middle">
                            <thead class="text-secondary fw-medium border-bottom" style="font-size: 0.85rem;">
                                <tr>
                                    <th>Komoditas</th>
                                    <th class="text-end d-none d-md-table-cell">Min</th>
                                    <th class="text-end d-none d-md-table-cell">Max</th>
                                    <th class="text-end">Rata-Rata</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $pasarHargas = $hargas->get($pasar->id) ?? collect();
                                @endphp
                                @forelse($pasarHargas as $harga)
                                <tr>
                                    <td class="py-2 text-dark fw-medium">
                                        <div>{{ $harga->komoditas->nama ?? '-' }}</div>
                                        <small class="text-muted" style="font-size: 0.8rem;">Satuan: {{ $harga->komoditas->satuan ?? 'Kg' }}</small>
                                        <div class="d-md-none text-secondary mt-1" style="font-size: 0.75rem;">
                                            Min: Rp {{ number_format($harga->harga_min, 0, ',', '.') }} | Max: Rp {{ number_format($harga->harga_max, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="py-2 text-end text-secondary small d-none d-md-table-cell">
                                        Rp {{ number_format($harga->harga_min, 0, ',', '.') }}
                                    </td>
                                    <td class="py-2 text-end text-secondary small d-none d-md-table-cell">
                                        Rp {{ number_format($harga->harga_max, 0, ',', '.') }}
                                    </td>
                                    <td class="py-2 text-end fw-bold text-success">
                                        Rp {{ number_format($harga->harga_avg, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3 small">
                                        Tidak ada data harga untuk tanggal ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-muted py-5">
                <p>Belum ada data pasar yang terdaftar.</p>
            </div>
            @endforelse
        </div>
    </div>

    @include('partials.public_footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
