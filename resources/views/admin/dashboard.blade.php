@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card card-premium p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted fw-bold text-uppercase">Kategori</h6>
                    <h3 class="fw-bold text-dark mb-0">{{ $counts['kategori'] }}</h3>
                </div>
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                    <i class="fa-solid fa-tags fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-premium p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted fw-bold text-uppercase">Komoditas</h6>
                    <h3 class="fw-bold text-dark mb-0">{{ $counts['komoditas'] }}</h3>
                </div>
                <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success">
                    <i class="fa-solid fa-leaf fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-premium p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted fw-bold text-uppercase">Pasar</h6>
                    <h3 class="fw-bold text-dark mb-0">{{ $counts['pasar'] }}</h3>
                </div>
                <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning">
                    <i class="fa-solid fa-store fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-premium p-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted fw-bold text-uppercase">Pedagang</h6>
                    <h3 class="fw-bold text-dark mb-0">{{ $counts['pedagang'] }}</h3>
                </div>
                <div class="bg-info bg-opacity-10 p-3 rounded-circle text-info">
                    <i class="fa-solid fa-users fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

@if($isAdmin)
<!-- Row 2: Top Charts side by side -->
<div class="row g-3 mb-3">
    <!-- Chart Widget 1: Tren Harga -->
    <div class="col-lg-6">
        <div class="card card-premium p-3 h-100" id="trendChartWrapper">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold text-dark mb-0" style="font-size: 0.9rem;"><i class="fa-solid fa-chart-line text-primary me-2"></i> Tren Harga Komoditas (30 Hari)</h6>
                <button class="btn btn-sm btn-light text-primary rounded-pill px-2 py-0 fw-bold" style="font-size: 0.75rem;" onclick="toggleTrendFullscreen()">
                    <i class="fa-solid fa-expand"></i>
                </button>
            </div>
            <div style="position: relative; height: 220px; width: 100%;" id="trendCanvasWrapper">
                <canvas id="adminTrendChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart Widget 2: Disparitas Harga -->
    <div class="col-lg-6">
        <div class="card card-premium p-3 h-100">
            <h6 class="fw-bold text-dark mb-2" style="font-size: 0.9rem;"><i class="fa-solid fa-scale-balanced text-danger me-2"></i> Disparitas Harga Min vs Max</h6>
            <div style="position: relative; height: 220px; width: 100%;">
                <canvas id="disparityChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Row 3: Submisi, Sebaran, & Tabel Validasi -->
<div class="row g-3 mb-4">
    <!-- Submisi Data per Pasar -->
    <div class="col-lg-4">
        <div class="card card-premium p-3 h-100">
            <h6 class="fw-bold text-dark mb-2" style="font-size: 0.9rem;"><i class="fa-solid fa-chart-column text-primary me-2"></i> Submisi per Pasar</h6>
            <div style="position: relative; height: 180px; width: 100%;">
                <canvas id="adminSubmissionsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Sebaran Kategori -->
    <div class="col-lg-4">
        <div class="card card-premium p-3 h-100">
            <h6 class="fw-bold text-dark mb-2" style="font-size: 0.9rem;"><i class="fa-solid fa-chart-pie text-success me-2"></i> Sebaran per Kategori</h6>
            <div style="position: relative; height: 180px; width: 100%;">
                <canvas id="categoryDistributionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Table Validasi -->
    <div class="col-lg-4">
        <div class="card card-premium p-3 h-100">
            <h6 class="fw-bold text-dark mb-2" style="font-size: 0.9rem;"><i class="fa-solid fa-hourglass-half text-warning me-2"></i> Draft / Belum Valid</h6>
            <div class="table-responsive" style="max-height: 160px; overflow-y: auto;">
                <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.8rem;">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th>Komoditas</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pending_hargas as $harga)
                        <tr>
                            <td class="fw-bold text-dark">{{ $harga->komoditas->nama ?? '-' }}</td>
                            <td class="text-success fw-bold">Rp {{ number_format($harga->harga_avg, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-3">Kosong.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(count($pending_hargas) > 0)
                <div class="text-end mt-2">
                    <a href="{{ route('admin.hargas.index') }}" class="btn btn-xs btn-light text-primary rounded-pill px-2 py-1 fw-bold" style="font-size: 0.75rem;">
                        Periksa <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>
<style>
    #trendChartWrapper:fullscreen {
        background: #ffffff !important;
        padding: 30px !important;
        width: 100vw !important;
        height: 100vh !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: center !important;
    }
    #trendChartWrapper:fullscreen #trendCanvasWrapper {
        height: 85% !important;
    }
</style>

<script>
    function toggleTrendFullscreen() {
        const elem = document.getElementById('trendChartWrapper');
        if (!document.fullscreenElement) {
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.webkitRequestFullscreen) {
                elem.webkitRequestFullscreen();
            } else if (elem.msRequestFullscreen) {
                elem.msRequestFullscreen();
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Bar Chart: Submisi per Pasar
        const ctxBar = document.getElementById('adminSubmissionsChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chart_labels) !!},
                datasets: [{
                    label: 'Total Input Harga',
                    data: {!! json_encode($chart_data) !!},
                    backgroundColor: 'rgba(102, 126, 234, 0.8)',
                    borderColor: '#667eea',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });

        // 2. Doughnut Chart: Sebaran Komoditas per Kategori
        const ctxPie = document.getElementById('categoryDistributionChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($cat_labels) !!},
                datasets: [{
                    data: {!! json_encode($cat_data) !!},
                    backgroundColor: [
                        'rgba(102, 126, 234, 0.85)',
                        'rgba(72, 187, 120, 0.85)',
                        'rgba(237, 137, 54, 0.85)',
                        'rgba(245, 101, 101, 0.85)',
                        'rgba(159, 122, 234, 0.85)',
                        'rgba(74, 85, 104, 0.85)'
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 15, font: { weight: 600 } } }
                }
            }
        });

        // 3. Line Chart: Tren Harga Komoditas
        const isMobile = window.innerWidth < 768;
        const trendDatasets = {!! json_encode($trend_datasets) !!}.map(ds => {
            return {
                ...ds,
                borderWidth: isMobile ? 1.5 : 2,
                pointRadius: isMobile ? 0 : 2,
                pointHoverRadius: 4
            };
        });

        const ctxLine = document.getElementById('adminTrendChart').getContext('2d');
        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: {!! json_encode($trend_labels) !!},
                datasets: trendDatasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        ticks: {
                            autoSkip: true,
                            maxTicksLimit: isMobile ? 4 : 8,
                            maxRotation: 0,
                            minRotation: 0,
                            callback: function(val, index) {
                                let label = this.getLabelForValue(val);
                                if (label && label.length >= 10) {
                                    const parts = label.split('-');
                                    if (parts.length === 3) {
                                        return parts[2] + '/' + parts[1];
                                    }
                                }
                                return label;
                            }
                        }
                    },
                    y: {
                        ticks: {
                            callback: function(value) { return 'Rp ' + value.toLocaleString('id-ID'); }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: !isMobile,
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8,
                            padding: 8
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) { return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID'); }
                        }
                    }
                }
            }
        });

        // 4. Grouped Bar Chart: Disparitas Harga
        const ctxDisp = document.getElementById('disparityChart').getContext('2d');
        new Chart(ctxDisp, {
            type: 'bar',
            data: {
                labels: {!! json_encode($disp_labels) !!},
                datasets: [
                    {
                        label: 'Harga Min',
                        data: {!! json_encode($disp_min) !!},
                        backgroundColor: 'rgba(245, 101, 101, 0.8)',
                        borderRadius: 4
                    },
                    {
                        label: 'Harga Max',
                        data: {!! json_encode($disp_max) !!},
                        backgroundColor: 'rgba(72, 187, 120, 0.8)',
                        borderRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: isMobile ? 'y' : 'x',
                scales: {
                    x: {
                        ticks: {
                            callback: function(value) { 
                                if (isMobile) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                                let label = this.getLabelForValue(value);
                                return label.length > 15 ? label.substring(0, 15) + '...' : label;
                            }
                        }
                    },
                    y: {
                        ticks: {
                            callback: function(value) { 
                                if (!isMobile) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                                let label = this.getLabelForValue(value);
                                return label.length > 15 ? label.substring(0, 15) + '...' : label;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@else
<div class="row">
    <div class="col-md-12">
        <div class="card card-premium p-4">
            <h5 class="fw-bold text-dark mb-3">Selamat Datang di Sihati</h5>
            <p class="text-secondary">Silakan gunakan menu di sebelah kiri untuk mengelola data atau melakukan transaksi harga pasar.</p>
        </div>
    </div>
</div>
@endif
@endsection
