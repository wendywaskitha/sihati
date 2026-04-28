@extends('layouts.auth')

@section('title', 'Daftar Akun')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card glass-card p-4 p-md-5">
            <div class="text-center mb-4">
                <i class="fa-solid fa-user-plus text-primary fa-3x mb-3"></i>
                <h3 class="fw-bold text-dark">Daftar Akun</h3>
                <p class="text-muted">Bergabung dengan Aplikasi Harga Pasar</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label text-secondary fw-medium">Nama Lengkap</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-user text-muted"></i></span>
                        <input type="text" class="form-control border-start-0" id="name" name="name" value="{{ old('name') }}" required placeholder="John Doe">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label text-secondary fw-medium">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-envelope text-muted"></i></span>
                        <input type="email" class="form-control border-start-0" id="email" name="email" value="{{ old('email') }}" required placeholder="name@example.com">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label text-secondary fw-medium">Role</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-user-tag text-muted"></i></span>
                        <select class="form-select border-start-0" id="role" name="role" required>
                            <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>Petugas Lapangan</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label text-secondary fw-medium">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-lock text-muted"></i></span>
                        <input type="password" class="form-control border-start-0" id="password" name="password" required placeholder="••••••••">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label text-secondary fw-medium">Konfirmasi Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-shield text-muted"></i></span>
                        <input type="password" class="form-control border-start-0" id="password_confirmation" name="password_confirmation" required placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="btn btn-premium w-100 py-3 fw-bold rounded-pill mb-3">
                    Daftar <i class="fa-solid fa-arrow-right ms-2"></i>
                </button>

                <div class="text-center">
                    <p class="text-secondary mb-0">Sudah punya akun? <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Masuk</a></p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
