<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Sihati Admin</title>
    @if(\App\Models\Pengaturan::getValue('app_favicon'))
        <link rel="icon" type="image/x-icon" href="{{ asset(\App\Models\Pengaturan::getValue('app_favicon')) }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f8f9fa;
        }
        
        :root {
            --sidebar-width: 260px;
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        /* Sidebar Styles */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: #ffffff;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.05);
            z-index: 1000;
            overflow-y: auto;
        }
        
        #sidebar::-webkit-scrollbar {
            width: 5px;
        }
        #sidebar::-webkit-scrollbar-track {
            background: #f8f9fa;
        }
        #sidebar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #f1f5f9;
        }

        .sidebar-menu {
            padding: 20px 0;
            list-style: none;
            margin: 0;
        }

        .sidebar-menu li a {
            padding: 12px 24px;
            display: flex;
            align-items: center;
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
            border-left: 4px solid transparent;
        }

        .sidebar-menu li a i {
            width: 25px;
            margin-right: 15px;
            font-size: 1.1rem;
        }

        .sidebar-menu li a:hover, .sidebar-menu li.active a {
            color: #667eea;
            background: rgba(102, 126, 234, 0.05);
            border-left-color: #667eea;
        }

        /* Content Styles */
        #content {
            margin-left: var(--sidebar-width);
            padding: 40px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        .navbar-custom {
            background: #ffffff;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.03);
            border-radius: 15px;
            padding: 15px 25px;
            margin-bottom: 30px;
        }

        .card-premium {
            background: #ffffff;
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
            transition: transform 0.2s ease;
        }
        
        .card-premium:hover {
            transform: translateY(-5px);
        }

        @media (max-width: 768px) {
            #sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            #sidebar.active {
                margin-left: 0;
            }
            #content {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div id="sidebar">
        <div class="sidebar-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                @if(\App\Models\Pengaturan::getValue('app_logo'))
                    <img src="{{ asset(\App\Models\Pengaturan::getValue('app_logo')) }}" alt="Logo" class="me-2" style="height: 30px; max-width: 40px; object-fit: contain;">
                @else
                    <i class="fa-solid fa-seedling text-success fa-2x me-2"></i>
                @endif
                <span class="fw-bold fs-4 text-dark">{{ \App\Models\Pengaturan::getValue('app_name', 'Sihati') }}</span>
            </div>
            <button class="btn d-md-none" id="sidebarCollapseBtn">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
        
        <ul class="sidebar-menu">
            <li class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-chart-pie"></i> Dashboard</a>
            </li>
            
            @if(auth()->user()->role == 'admin')
            <li class="px-4 py-2 text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">Master Data</li>
            <li class="{{ request()->routeIs('admin.kategoris.*') ? 'active' : '' }}">
                <a href="{{ route('admin.kategoris.index') }}"><i class="fa-solid fa-tags"></i> Kategori</a>
            </li>
            <li class="{{ request()->routeIs('admin.komoditas.*') ? 'active' : '' }}">
                <a href="{{ route('admin.komoditas.index') }}"><i class="fa-solid fa-leaf"></i> Komoditas</a>
            </li>
            <li class="{{ request()->routeIs('admin.kecamatans.*') ? 'active' : '' }}">
                <a href="{{ route('admin.kecamatans.index') }}"><i class="fa-solid fa-map-location-dot"></i> Kecamatan</a>
            </li>
            <li class="{{ request()->routeIs('admin.desas.*') ? 'active' : '' }}">
                <a href="{{ route('admin.desas.index') }}"><i class="fa-solid fa-house-chimney"></i> Desa</a>
            </li>
            <li class="{{ request()->routeIs('admin.pasars.*') ? 'active' : '' }}">
                <a href="{{ route('admin.pasars.index') }}"><i class="fa-solid fa-store"></i> Pasar</a>
            </li>
            <li class="{{ request()->routeIs('admin.pedagangs.*') ? 'active' : '' }}">
                <a href="{{ route('admin.pedagangs.index') }}"><i class="fa-solid fa-users"></i> Pedagang</a>
            </li>
            @endif
            
            <li class="px-4 py-2 text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">Transaksi</li>
            @if(auth()->user()->role == 'petugas' || auth()->user()->role == 'admin')
            <li class="{{ request()->routeIs('admin.harga_details.*') ? 'active' : '' }}">
                <a href="{{ route('admin.harga_details.index') }}"><i class="fa-solid fa-pen-to-square"></i> Input Harga</a>
            </li>
            @endif
            
            @if(auth()->user()->role == 'admin')
            <li class="{{ request()->routeIs('admin.hargas.*') ? 'active' : '' }}">
                <a href="{{ route('admin.hargas.index') }}"><i class="fa-solid fa-clipboard-check"></i> Validasi Harga</a>
            </li>
            
            <li class="px-4 py-2 text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">Laporan & Sistem</li>
            <li class="{{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                <a href="{{ route('admin.laporan.index') }}"><i class="fa-solid fa-file-invoice-dollar"></i> Laporan</a>
            </li>
            <li class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <a href="{{ route('admin.users.index') }}"><i class="fa-solid fa-users-gear"></i> Pengguna</a>
            </li>
            <li class="{{ request()->routeIs('admin.pengaturan.*') ? 'active' : '' }}">
                <a href="{{ route('admin.pengaturan.index') }}"><i class="fa-solid fa-gears"></i> Pengaturan</a>
            </li>
            <li class="{{ request()->routeIs('admin.backup.*') ? 'active' : '' }}">
                <a href="{{ route('admin.backup.index') }}"><i class="fa-solid fa-database"></i> Backup & Restore</a>
            </li>
            @endif
        </ul>
    </div>

    <!-- Main Content -->
    <div id="content">
        <!-- Navbar -->
        <div class="navbar-custom d-flex justify-content-between align-items-center">
            <button class="btn btn-light d-md-none" id="sidebarToggle">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div class="d-none d-md-block">
                <h5 class="fw-bold mb-0 text-dark">Selamat Datang, {{ auth()->user()->name }}</h5>
                <small class="text-muted text-uppercase fw-medium">{{ auth()->user()->role }}</small>
            </div>
            
            <div class="dropdown">
                <button class="btn btn-light rounded-pill px-3 dropdown-toggle d-flex align-items-center" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-user-circle fs-5 me-2 text-primary"></i>
                    <span>{{ auth()->user()->name }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3 mt-2" aria-labelledby="userMenu">
                    <li>
                        <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger d-flex align-items-center">
                                <i class="fa-solid fa-right-from-bracket me-2"></i> Keluar
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Yield Content -->
        @yield('content')

        <!-- Footer -->
        <footer class="text-center py-4 mt-auto text-secondary small border-top border-light">
            {{ \App\Models\Pengaturan::getValue('app_footer', 'Copyright &copy; ' . date('Y') . ' Sihati. All rights reserved.') }}
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarCollapseBtn = document.getElementById('sidebarCollapseBtn');

            if(sidebarToggle) {
                sidebarToggle.addEventListener('click', function () {
                    sidebar.classList.toggle('active');
                });
            }
            if(sidebarCollapseBtn) {
                sidebarCollapseBtn.addEventListener('click', function () {
                    sidebar.classList.remove('active');
                });
            }
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 2500
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: "{{ session('error') }}",
                });
            @endif
        });

        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
</body>
</html>
