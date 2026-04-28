@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card glass-card p-4 p-md-5">
            <div class="text-center mb-4">
                <i class="fa-solid fa-seedling text-success fa-3x mb-3"></i>
                <h3 class="fw-bold text-dark">Sihati</h3>
                <p class="text-muted">Aplikasi Harga Pasar Komoditi Pertanian</p>
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

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label text-secondary fw-medium">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-envelope text-muted"></i></span>
                        <input type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="name@example.com">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label text-secondary fw-medium">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-lock text-muted"></i></span>
                        <input type="password" class="form-control border-start-0" id="password" name="password" required placeholder="••••••••">
                    </div>
                </div>

                <div class="mb-4 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label text-secondary" for="remember">Ingat Saya</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-premium w-100 py-3 fw-bold rounded-pill mb-3">
                    Masuk <i class="fa-solid fa-arrow-right ms-2"></i>
                </button>

                <div class="text-center">
                    <p class="text-secondary mb-0">Belum punya akun? <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Daftar Sekarang</a></p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
