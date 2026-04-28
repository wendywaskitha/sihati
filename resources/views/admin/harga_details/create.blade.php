@extends('layouts.admin')

@section('title', 'Input Harga')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<div class="mb-4">
    <a href="{{ route('admin.harga_details.index') }}" class="text-decoration-none text-muted fw-bold">
        <i class="fa-solid fa-arrow-left me-2"></i> Kembali
    </a>
    <h4 class="fw-bold text-dark mt-2"><i class="fa-solid fa-pen-to-square text-primary me-2"></i> Input Harga Harian</h4>
</div>

@if($errors->any())
    <div class="alert alert-danger border-0 shadow-sm mb-4">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('admin.harga_details.store') }}" method="POST">
    @csrf
    
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card card-premium p-4 h-100">
                <h5 class="fw-bold text-dark mb-3">Informasi Umum</h5>
                
                <div class="mb-3">
                    <label for="tanggal" class="form-label text-secondary fw-medium">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="mb-3">
                    <label for="pasar_id" class="form-label text-secondary fw-medium">Pasar</label>
                    <select class="form-select" id="pasar_id" name="pasar_id" required>
                        <option value="">-- Pilih Pasar --</option>
                        @foreach($pasars as $pasar)
                            <option value="{{ $pasar->id }}">{{ $pasar->nama }} ({{ $pasar->kecamatan->nama ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="pedagang_id" class="form-label text-secondary fw-medium">Pedagang</label>
                    <select class="form-select" id="pedagang_id" name="pedagang_id" required>
                        <option value="">-- Pilih Pedagang --</option>
                        @foreach($pedagangs as $pedagang)
                            <option value="{{ $pedagang->id }}" data-pasar="{{ $pedagang->pasar_id }}">{{ $pedagang->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card card-premium p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0">Daftar Komoditas & Harga</h5>
                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" id="add-row">
                        <i class="fa-solid fa-plus me-1"></i> Tambah Baris
                    </button>
                </div>

                <div class="row g-3 mb-2 d-none d-md-flex text-secondary fw-medium" style="font-size: 0.9rem;">
                    <div class="col-md-7">Komoditas</div>
                    <div class="col-md-4">Harga (Rp)</div>
                    <div class="col-md-1"></div>
                </div>

                <div id="repeater-container">
                    <div class="repeater-row card bg-light border-0 p-3 mb-3 position-relative shadow-sm">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-7 col-12">
                                <label class="form-label text-secondary fw-medium d-md-none">Komoditas</label>
                                <select class="form-select select2-enable" name="items[0][komoditas_id]" required>
                                    <option value="">-- Pilih Komoditas --</option>
                                    @foreach($komoditas as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama }} per {{ $item->satuan ?? 'Kg' }} ({{ $item->kategori->nama ?? '-' }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 col-10">
                                <label class="form-label text-secondary fw-medium d-md-none">Harga (Rp)</label>
                                <input type="number" class="form-control" name="items[0][harga]" placeholder="Harga (Rp)" required min="0">
                            </div>
                            <div class="col-md-1 col-2 text-end">
                                <button type="button" class="btn btn-sm btn-light text-danger rounded-circle remove-row" style="width: 35px; height: 35px;">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold w-100 d-md-none">
                        <i class="fa-solid fa-cloud-arrow-up me-2"></i> Simpan Data
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold d-none d-md-inline-block">
                        <i class="fa-solid fa-cloud-arrow-up me-2"></i> Simpan Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const pasarSelect = document.getElementById('pasar_id');
        const pedagangSelect = document.getElementById('pedagang_id');
        const originalPedagangs = Array.from(pedagangSelect.options);

        // Initialize Tom Select for Pasar
        const tsPasar = new TomSelect(pasarSelect, {
            create: false,
            sortField: { field: "text", direction: "asc" }
        });

        // Initialize Tom Select for Pedagang
        let tsPedagang = new TomSelect(pedagangSelect, {
            create: true,
            sortField: { field: "text", direction: "asc" }
        });

        // Initialize Tom Select for initial Komoditas
        const initialKomoditas = document.querySelector('.repeater-row select');
        let tsKomoditas = new TomSelect(initialKomoditas, {
            create: false,
            sortField: { field: "text", direction: "asc" }
        });

        // Filter Pedagang based on Pasar
        pasarSelect.addEventListener('change', function () {
            const selectedPasar = this.value;
            
            // Destroy existing Tom Select instance for Pedagang
            if (tsPedagang) {
                tsPedagang.destroy();
            }

            // Clear existing options except the first one
            pedagangSelect.innerHTML = '';
            pedagangSelect.appendChild(originalPedagangs[0]);

            if (selectedPasar) {
                originalPedagangs.forEach(option => {
                    if (option.getAttribute('data-pasar') === selectedPasar) {
                        pedagangSelect.appendChild(option.cloneNode(true));
                    }
                });
            } else {
                for(let i=1; i<originalPedagangs.length; i++) {
                    pedagangSelect.appendChild(originalPedagangs[i].cloneNode(true));
                }
            }

            // Re-initialize Tom Select for Pedagang
            tsPedagang = new TomSelect(pedagangSelect, {
                create: true,
                sortField: { field: "text", direction: "asc" }
            });
        });

        // Repeater Logic
        const addButton = document.getElementById('add-row');
        const repeaterContainer = document.getElementById('repeater-container');
        let rowIndex = 1;

        addButton.addEventListener('click', function () {
            const firstRow = document.querySelector('.repeater-row');
            const newRow = firstRow.cloneNode(true);
            
            // Clean up Tom Select artifacts from cloned row
            const tsWrapper = newRow.querySelector('.ts-wrapper');
            if (tsWrapper) tsWrapper.remove();
            
            const select = newRow.querySelector('select');
            select.style.display = '';
            select.className = 'form-select';
            select.name = `items[${rowIndex}][komoditas_id]`;
            select.value = '';
            
            const input = newRow.querySelector('input');
            input.name = `items[${rowIndex}][harga]`;
            input.value = '';

            repeaterContainer.appendChild(newRow);
            
            // Initialize Tom Select on the new select
            new TomSelect(select, {
                create: false,
                sortField: { field: "text", direction: "asc" }
            });

            rowIndex++;
            attachRemoveEvent(newRow.querySelector('.remove-row'));
        });

        function attachRemoveEvent(button) {
            button.addEventListener('click', function () {
                const rows = document.querySelectorAll('.repeater-row');
                if (rows.length > 1) {
                    this.closest('.repeater-row').remove();
                } else {
                    alert('Minimal harus ada satu baris data.');
                }
            });
        }

        attachRemoveEvent(document.querySelector('.remove-row'));
    });
</script>
@endsection
