@extends('layouts.admin')

@section('title', 'Kelola Komoditas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-dark mb-0"><i class="fa-solid fa-leaf text-success me-2"></i> Kelola Komoditas</h4>
    <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold" data-bs-toggle="modal" data-bs-target="#createModal">
        <i class="fa-solid fa-plus me-2"></i> Tambah Komoditas
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger border-0 shadow-sm mb-4">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card card-premium p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 80px;">No</th>
                    <th>Nama Komoditas</th>
                    <th>Satuan</th>
                    <th>Kategori</th>
                    <th style="width: 200px;" class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($komoditas as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="fw-medium text-dark">{{ $item->nama }}</td>
                    <td><span class="badge bg-light text-secondary rounded-pill px-3">{{ $item->satuan ?? 'Kg' }}</span></td>
                    <td><span class="badge bg-light text-primary rounded-pill px-3">{{ $item->kategori->nama ?? '-' }}</span></td>
                    <td class="text-end">
                        <button type="button" class="btn btn-sm btn-light text-primary rounded-pill px-3 me-2 edit-btn" 
                                data-id="{{ $item->id }}" 
                                data-nama="{{ $item->nama }}"
                                data-satuan="{{ $item->satuan }}"
                                data-kategori="{{ $item->kategori_id }}">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </button>
                        
                        <form action="{{ route('admin.komoditas.destroy', $item->id) }}" method="POST" id="delete-form-{{ $item->id }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm btn-light text-danger rounded-pill px-3" onclick="confirmDelete({{ $item->id }})">
                                <i class="fa-solid fa-trash-can"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">Belum ada data komoditas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="createModalLabel">Tambah Komoditas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.komoditas.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kategori_id" class="form-label text-secondary fw-medium">Kategori</label>
                        <select class="form-select" id="kategori_id" name="kategori_id" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nama" class="form-label text-secondary fw-medium">Nama Komoditas</label>
                        <input type="text" class="form-control" id="nama" name="nama" required placeholder="Contoh: Beras Kepala">
                    </div>
                    <div class="mb-3">
                        <label for="satuan" class="form-label text-secondary fw-medium">Satuan</label>
                        <input type="text" class="form-control" id="satuan" name="satuan" required placeholder="Contoh: Kg, Liter, Ikat, Buah" value="Kg">
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title fw-bold" id="editModalLabel">Edit Komoditas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_kategori_id" class="form-label text-secondary fw-medium">Kategori</label>
                        <select class="form-select" id="edit_kategori_id" name="kategori_id" required>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_nama" class="form-label text-secondary fw-medium">Nama Komoditas</label>
                        <input type="text" class="form-control" id="edit_nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_satuan" class="form-label text-secondary fw-medium">Satuan</label>
                        <input type="text" class="form-control" id="edit_satuan" name="satuan" required>
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
        const editNama = document.getElementById('edit_nama');
        const editSatuan = document.getElementById('edit_satuan');
        const editKategori = document.getElementById('edit_kategori_id');
        const editModal = new bootstrap.Modal(document.getElementById('editModal'));

        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                const satuan = this.getAttribute('data-satuan');
                const kategori = this.getAttribute('data-kategori');

                editForm.action = `/admin/komoditas/${id}`;
                editNama.value = nama;
                editSatuan.value = satuan;
                editKategori.value = kategori;
                
                editModal.show();
            });
        });
    });
</script>
@endsection
