@extends('layouts.admin')

@section('title', 'Validasi Harga')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-dark mb-0"><i class="fa-solid fa-clipboard-check text-success me-2"></i> Validasi Harga Komoditas</h4>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4">
        {{ session('success') }}
    </div>
@endif

<form action="{{ route('admin.hargas.index') }}" method="GET" class="row g-3 mb-4">
    <div class="col-md-3">
        <select name="pasar_id" class="form-select rounded-pill">
            <option value="">-- Semua Pasar --</option>
            @foreach($pasars as $pasar)
                <option value="{{ $pasar->id }}" {{ request('pasar_id') == $pasar->id ? 'selected' : '' }}>{{ $pasar->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <select name="komoditas_id" class="form-select rounded-pill">
            <option value="">-- Semua Komoditas --</option>
            @foreach($komoditas as $com)
                <option value="{{ $com->id }}" {{ request('komoditas_id') == $com->id ? 'selected' : '' }}>{{ $com->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <select name="status" class="form-select rounded-pill">
            <option value="">-- Semua Status --</option>
            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
        </select>
    </div>
    <div class="col-md-3">
        <button type="submit" class="btn btn-light text-primary rounded-pill px-4 fw-bold w-100">
            <i class="fa-solid fa-filter me-2"></i> Filter
        </button>
    </div>
</form>

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
                    <th>Harga Avg</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
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
                    <td>
                        @if($harga->status == 'approved')
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Approved</span>
                        @else
                            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3">Draft</span>
                        @endif
                    </td>
                    <td class="text-end">
                        @if($harga->status == 'draft')
                        <form action="{{ route('admin.hargas.approve', $harga->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 me-2">
                                <i class="fa-solid fa-check"></i> Approve
                            </button>
                        </form>
                        @else
                        <form action="{{ route('admin.hargas.reject', $harga->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-warning rounded-pill px-3 me-2">
                                <i class="fa-solid fa-xmark"></i> Unapprove
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">Belum ada data agregasi harga.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $hargas->links() }}
    </div>
</div>
@endsection
