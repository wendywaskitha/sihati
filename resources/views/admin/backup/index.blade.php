@extends('layouts.admin')

@section('title', 'Backup & Restore')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-dark mb-0"><i class="fa-solid fa-database text-primary me-2"></i> Backup & Restore Data</h4>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4">
        <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger border-0 shadow-sm mb-4">
        <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
    </div>
@endif

<div class="row g-4">
    <!-- Kolom Backup & Restore -->
    <div class="col-lg-6">
        <div class="card card-premium p-4 h-100">
            <h5 class="fw-bold text-primary mb-3">
                <i class="fa-solid fa-file-export me-2"></i> Ekspor & Impor Database
            </h5>
            <p class="text-muted small mb-4">Lakukan pencadangan (backup) seluruh tabel sistem atau pulihkan (restore) data menggunakan file JSON.</p>

            <!-- Ekspor -->
            <div class="mb-4 pb-3 border-bottom">
                <label class="form-label text-secondary fw-medium d-block mb-2">Unduh Cadangan</label>
                <form action="{{ route('admin.backup.export') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-premium w-100 py-2 rounded-pill fw-bold">
                        <i class="fa-solid fa-cloud-arrow-down me-2"></i> Download Backup JSON
                    </button>
                </form>
            </div>

            <!-- Impor -->
            <div>
                <label class="form-label text-secondary fw-medium mb-2">Pulihkan Data</label>
                <form action="{{ route('admin.backup.restore') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <input type="file" class="form-control rounded-pill" name="backup_file" required accept=".json">
                    </div>
                    <button type="submit" class="btn btn-light text-primary border rounded-pill w-100 py-2 fw-bold" onclick="return confirm('PERINGATAN: Memulihkan data akan menghapus data tabel saat ini! Lanjutkan?')">
                        <i class="fa-solid fa-cloud-arrow-up me-2"></i> Restore Backup File
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Kolom Reset Data -->
    <div class="col-lg-6">
        <div class="card card-premium p-4 h-100">
            <h5 class="fw-bold text-danger mb-3">
                <i class="fa-solid fa-trash-arrow-up me-2"></i> Pembersihan Data Massal
            </h5>
            <p class="text-muted small mb-4">Opsi pembersihan untuk meringankan beban kapasitas penyimpanan atau mereset transaksi lama.</p>

            <!-- Hapus Semua Draft -->
            <div class="mb-4 pb-3 border-bottom">
                <label class="form-label text-secondary fw-medium d-block mb-1">Input Harga (Draft)</label>
                <span class="d-block text-muted small mb-3">Hanya menghapus data input harga pedagang yang belum disetujui admin.</span>
                <form action="{{ route('admin.backup.clear_draft') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100 py-2 rounded-pill fw-bold" onclick="return confirm('Yakin ingin menghapus seluruh Input Harga berstatus Draft?')">
                        <i class="fa-solid fa-eraser me-2"></i> Hapus Semua Draft
                    </button>
                </form>
            </div>

            <!-- Hapus Semua Approved -->
            <div>
                <label class="form-label text-secondary fw-medium d-block mb-1">Validasi Harga (Approved)</label>
                <span class="d-block text-muted small mb-3">Menghapus data harga harian yang sudah tervalidasi beserta rinciannya.</span>
                <form action="{{ route('admin.backup.clear_approved') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100 py-2 rounded-pill fw-bold" onclick="return confirm('PERINGATAN KRITIS: Seluruh data harga publik akan kosong! Yakin lanjutkan?')">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i> Hapus Semua Approved
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
