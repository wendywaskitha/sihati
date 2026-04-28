@extends('layouts.admin')

@section('title', 'Raw Data Harga')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-dark mb-0"><i class="fa-solid fa-pen-to-square text-primary me-2"></i> Raw Data Harga ({{ \Carbon\Carbon::parse($tanggal)->format('d/m/Y') }})</h4>
    <a href="{{ route('admin.harga_details.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold">
        <i class="fa-solid fa-plus me-2"></i> Input Harga
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4">
        {{ session('success') }}
    </div>
@endif

<form action="{{ route('admin.harga_details.index') }}" method="GET" class="row g-3 mb-4">
    <div class="col-md-3">
        <input type="date" name="tanggal" class="form-control rounded-pill" value="{{ $tanggal }}">
    </div>
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
        <button type="submit" class="btn btn-light text-primary rounded-pill px-4 fw-bold w-100">
            <i class="fa-solid fa-filter me-2"></i> Filter Data
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
                    <th>Pedagang</th>
                    <th>Pasar</th>
                    <th>Harga</th>
                    <th>Petugas</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hargaDetails as $index => $detail)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($detail->tanggal)->format('d/m/Y') }}</td>
                    <td class="fw-bold text-dark">{{ $detail->komoditas->nama ?? '-' }} / {{ $detail->komoditas->satuan ?? 'Kg' }}</td>
                    <td>{{ $detail->pedagang->nama ?? '-' }}</td>
                    <td>{{ $detail->pasar->nama ?? '-' }}</td>
                    <td class="fw-bold text-success">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                    <td><small class="text-muted">{{ $detail->creator->name ?? '-' }}</small></td>
                    <td class="text-end">
                        @if($detail->is_approved)
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 fw-bold" style="font-size: 0.8rem;">
                                <i class="fa-solid fa-lock me-1"></i> Validated
                            </span>
                        @else
                            <button type="button" class="btn btn-sm btn-light text-primary rounded-pill px-3 me-2 edit-btn" 
                                    data-id="{{ $detail->id }}" 
                                    data-harga="{{ $detail->harga }}"
                                    data-komoditas="{{ $detail->komoditas->nama ?? '-' }}"
                                    data-pedagang="{{ $detail->pedagang->nama ?? '-' }}">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            
                            <form action="{{ route('admin.harga_details.destroy', $detail->id) }}" method="POST" id="delete-form-{{ $detail->id }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-light text-danger rounded-pill px-3" onclick="confirmDelete({{ $detail->id }})">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">Belum ada data input harga.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $hargaDetails->links() }}
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="editModalLabel">Edit Harga</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-medium">Komoditas</label>
                        <input type="text" class="form-control bg-light" id="edit_komoditas" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary fw-medium">Pedagang</label>
                        <input type="text" class="form-control bg-light" id="edit_pedagang" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="edit_harga" class="form-label text-secondary fw-medium">Harga (Rp)</label>
                        <input type="number" class="form-control" id="edit_harga" name="harga" required min="0">
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editButtons = document.querySelectorAll('.edit-btn');
        const editForm = document.getElementById('editForm');
        const editHarga = document.getElementById('edit_harga');
        const editKomoditas = document.getElementById('edit_komoditas');
        const editPedagang = document.getElementById('edit_pedagang');
        const editModal = new bootstrap.Modal(document.getElementById('editModal'));

        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const harga = this.getAttribute('data-harga');
                const komoditas = this.getAttribute('data-komoditas');
                const pedagang = this.getAttribute('data-pedagang');

                editForm.action = `/admin/harga_details/${id}`;
                editHarga.value = harga;
                editKomoditas.value = komoditas;
                editPedagang.value = pedagang;
                
                editModal.show();
            });
        });
    });
</script>
@endsection
