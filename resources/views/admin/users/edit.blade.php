@extends('layouts.admin')

@section('title', 'Edit Pengguna')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-dark mb-0"><i class="fa-solid fa-user-pen text-primary me-2"></i> Edit Pengguna</h4>
    
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">
        <i class="fa-solid fa-arrow-left me-2"></i> Kembali
    </a>
</div>

<div class="card card-premium p-4" style="max-width: 600px;">
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-3">
            <label for="name" class="form-label text-secondary fw-medium">Nama Lengkap</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label text-secondary fw-medium">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label text-secondary fw-medium">Password Baru</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
            <small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="role" class="form-label text-secondary fw-medium">Role / Hak Akses</label>
            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                <option value="petugas" {{ old('role', $user->role) == 'petugas' ? 'selected' : '' }}>Petugas Lapangan</option>
                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrator</option>
            </select>
            @error('role')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary rounded-pill px-4">
                <i class="fa-solid fa-floppy-disk me-2"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
