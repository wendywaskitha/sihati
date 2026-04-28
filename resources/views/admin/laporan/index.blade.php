@extends('layouts.admin')

@section('title', 'Laporan Harga Komoditas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-dark mb-0"><i class="fa-solid fa-file-invoice-dollar text-primary me-2"></i> Laporan Harga Komoditas</h4>
    
    <a href="{{ route('admin.laporan.cetak', request()->all()) }}" target="_blank" class="btn btn-primary rounded-pill px-4 fw-bold">
        <i class="fa-solid fa-print me-2"></i> Cetak Laporan
    </a>
</div>

<!-- Filter Card -->
<div class="card card-premium p-4 mb-4">
    <form action="{{ route('admin.laporan.index') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-3">
            <label for="tanggal_mulai" class="form-label text-secondary fw-medium">Tanggal Mulai</label>
            <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
        </div>
        <div class="col-md-3">
            <label for="tanggal_selesai" class="form-label text-secondary fw-medium">Tanggal Selesai</label>
            <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}">
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
            <label for="komoditas_id" class="form-label text-secondary fw-medium">Komoditas</label>
            <select class="form-select" id="komoditas_id" name="komoditas_id">
                <option value="">Semua Komoditas</option>
                @foreach($komoditas as $item)
                    <option value="{{ $item->id }}" {{ request('komoditas_id') == $item->id ? 'selected' : '' }}>{{ $item->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 text-end">
            <a href="{{ route('admin.laporan.index') }}" class="btn btn-outline-secondary rounded-pill px-4 me-2">Reset</a>
            <button type="submit" class="btn btn-primary rounded-pill px-4">
                <i class="fa-solid fa-magnifying-glass me-2"></i> Filter
            </button>
        </div>
    </form>
</div>

<!-- Data Table -->
<div class="card card-premium p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Komoditas</th>
                    <th>Pasar</th>
                    <th>Harga Min</th>
                    <th>Harga Max</th>
                    <th>Harga Rata-Rata</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hargas as $index => $harga)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($harga->tanggal)->format('d/m/Y') }}</td>
                    <td class="fw-bold text-dark">{{ $harga->komoditas->nama ?? '-' }} / {{ $harga->komoditas->satuan ?? 'Kg' }}</td>
                    <td>{{ $harga->pasar->nama ?? '-' }}</td>
                    <td class="text-secondary">Rp {{ number_format($harga->harga_min, 0, ',', '.') }}</td>
                    <td class="text-secondary">Rp {{ number_format($harga->harga_max, 0, ',', '.') }}</td>
                    <td class="fw-bold text-success">Rp {{ number_format($harga->harga_avg, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">Tidak ada data harga yang sesuai dengan filter.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
