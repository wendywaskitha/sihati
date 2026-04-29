<nav class="navbar navbar-expand-lg navbar-dark bg-transparent py-3 mb-4">
    <div class="container px-0">
        <!-- Brand -->
        <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('public.home') }}">
            @if(\App\Models\Pengaturan::getValue('app_logo'))
                <img src="{{ asset(\App\Models\Pengaturan::getValue('app_logo')) }}" alt="Logo" class="me-2" style="height: 35px; max-width: 40px; object-fit: contain;">
            @else
                <i class="fa-solid fa-seedling text-warning fa-lg me-2"></i>
            @endif
            <span class="fs-4">{{ \App\Models\Pengaturan::getValue('app_name', 'Sihati') }}</span>
        </a>
        
        <!-- Toggle Mobile -->
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#publicNav" aria-controls="publicNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Menu & Auth Buttons -->
        <div class="collapse navbar-collapse mt-3 mt-lg-0" id="publicNav">
            <ul class="navbar-nav mx-auto mb-3 mb-lg-0 gap-lg-2">
                <li class="nav-item mb-2 mb-lg-0">
                    <a class="nav-link rounded-pill px-3 py-2 fw-bold text-center {{ request()->routeIs('public.home') ? 'bg-white text-primary shadow-sm' : 'text-white-50' }}" href="{{ route('public.home') }}">
                        <i class="fa-solid fa-house me-1"></i> Beranda
                    </a>
                </li>
                <li class="nav-item mb-2 mb-lg-0">
                    <a class="nav-link rounded-pill px-3 py-2 fw-bold text-center {{ request()->routeIs('public.harga_pasar') ? 'bg-white text-primary shadow-sm' : 'text-white-50' }}" href="{{ route('public.harga_pasar') }}">
                        <i class="fa-solid fa-store me-1"></i> Harga Pasar
                    </a>
                </li>
                <li class="nav-item mb-2 mb-lg-0">
                    <a class="nav-link rounded-pill px-3 py-2 fw-bold text-center {{ request()->routeIs('public.grafik') ? 'bg-white text-primary shadow-sm' : 'text-white-50' }}" href="{{ route('public.grafik') }}">
                        <i class="fa-solid fa-chart-line me-1"></i> Grafik
                    </a>
                </li>
            </ul>
            
            <div class="d-flex flex-column flex-lg-row gap-2">
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-light rounded-pill px-4 fw-bold shadow-sm">
                        <i class="fa-solid fa-user-circle me-1"></i> Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light rounded-pill px-4 fw-bold">
                        <i class="fa-solid fa-right-to-bracket me-1"></i> Masuk
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
