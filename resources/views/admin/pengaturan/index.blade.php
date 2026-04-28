@extends('layouts.admin')

@section('title', 'Pengaturan Aplikasi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-dark mb-0"><i class="fa-solid fa-gears text-primary me-2"></i> Pengaturan Aplikasi</h4>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4">
        {{ session('success') }}
    </div>
@endif

<form action="{{ route('admin.pengaturan.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="row g-4">
        <!-- Kolom Kiri: Metadata Aplikasi -->
        <div class="col-lg-6">
            <div class="card card-premium p-4 h-100">
                <h5 class="fw-bold text-primary mb-3">
                    <i class="fa-solid fa-circle-info me-2"></i> Metadata Aplikasi
                </h5>
                <p class="text-muted small mb-4">Konfigurasi informasi dasar serta branding aplikasi Sihati.</p>

                <div class="mb-3">
                    <label for="app_name" class="form-label text-secondary fw-medium">Nama Aplikasi</label>
                    <input type="text" class="form-control" id="app_name" name="app_name" value="{{ $settings['app_name'] ?? '' }}" required>
                </div>

                <div class="mb-3">
                    <label for="contact_phone" class="form-label text-secondary fw-medium">Nomor Kontak</label>
                    <input type="text" class="form-control" id="contact_phone" name="contact_phone" value="{{ $settings['contact_phone'] ?? '' }}" required>
                </div>

                <div class="mb-3">
                    <label for="app_description" class="form-label text-secondary fw-medium">Deskripsi Aplikasi</label>
                    <textarea class="form-control" id="app_description" name="app_description" rows="3" required>{{ $settings['app_description'] ?? '' }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="app_footer" class="form-label text-secondary fw-medium">Teks Footer</label>
                    <input type="text" class="form-control" id="app_footer" name="app_footer" value="{{ $settings['app_footer'] ?? '' }}" required>
                </div>

                <div class="row g-2 mt-2">
                    <div class="col-md-6">
                        <label for="app_logo" class="form-label text-secondary fw-medium">Logo Aplikasi</label>
                        <input type="file" class="form-control" id="app_logo" name="app_logo" accept="image/*">
                        @if(isset($settings['app_logo']))
                            <div class="mt-2">
                                <img src="{{ asset($settings['app_logo']) }}" alt="Logo" class="img-thumbnail" style="height: 50px;">
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label for="app_favicon" class="form-label text-secondary fw-medium">Favicon</label>
                        <input type="file" class="form-control" id="app_favicon" name="app_favicon" accept="image/x-icon,image/png">
                        @if(isset($settings['app_favicon']))
                            <div class="mt-2">
                                <img src="{{ asset($settings['app_favicon']) }}" alt="Favicon" class="img-thumbnail" style="height: 30px;">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Informasi Instansi & Pejabat -->
        <div class="col-lg-6">
            <!-- Informasi Instansi -->
            <div class="card card-premium p-4 mb-4">
                <h5 class="fw-bold text-primary mb-3">
                    <i class="fa-solid fa-building me-2"></i> Informasi Instansi
                </h5>
                
                <div class="mb-3">
                    <label for="instansi_nama" class="form-label text-secondary fw-medium">Nama Instansi</label>
                    <input type="text" class="form-control" id="instansi_nama" name="instansi_nama" value="{{ $settings['instansi_nama'] ?? '' }}" required>
                </div>

                <div class="mb-3">
                    <label for="instansi_email" class="form-label text-secondary fw-medium">Email Instansi</label>
                    <input type="email" class="form-control" id="instansi_email" name="instansi_email" value="{{ $settings['instansi_email'] ?? '' }}" required>
                </div>

                <div class="mb-1">
                    <label for="instansi_alamat" class="form-label text-secondary fw-medium">Alamat Instansi</label>
                    <input type="text" class="form-control" id="instansi_alamat" name="instansi_alamat" value="{{ $settings['instansi_alamat'] ?? '' }}" required>
                </div>
            </div>

            <!-- Informasi Pejabat -->
            <div class="card card-premium p-4">
                <h5 class="fw-bold text-primary mb-3">
                    <i class="fa-solid fa-user-tie me-2"></i> Penandatangan Laporan
                </h5>

                <div class="mb-3">
                    <label for="instansi_kepala" class="form-label text-secondary fw-medium">Nama Kepala Dinas</label>
                    <input type="text" class="form-control" id="instansi_kepala" name="instansi_kepala" value="{{ $settings['instansi_kepala'] ?? '' }}" required>
                </div>

                <div class="row g-2">
                    <div class="col-md-6">
                        <label for="instansi_pangkat" class="form-label text-secondary fw-medium">Pangkat / Golongan</label>
                        <input type="text" class="form-control" id="instansi_pangkat" name="instansi_pangkat" value="{{ $settings['instansi_pangkat'] ?? '' }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="instansi_nip" class="form-label text-secondary fw-medium">NIP</label>
                        <input type="text" class="form-control" id="instansi_nip" name="instansi_nip" value="{{ $settings['instansi_nip'] ?? '' }}" required>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Area -->
    <div class="text-end mt-4">
        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow-sm">
            <i class="fa-solid fa-floppy-disk me-2"></i> Simpan Semua Pengaturan
        </button>
    </div>
</form>
@endsection
