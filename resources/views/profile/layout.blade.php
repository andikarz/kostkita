<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Dashboard Pemilik')</title>

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Icon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    {{-- Custom Pemilik Style --}}
    <link rel="stylesheet" href="{{ asset('css/pemilik.css') }}">

    @stack('head')
</head>

<body>

    <div class="pemilik-wrapper">

        {{-- SIDEBAR --}}
        <aside class="pemilik-sidebar">
            <div class="sidebar-brand">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('img/logo kostkita.png') }}" alt="Logo" class="brand-logo">
                </a>
            </div>


            <div class="sidebar-section-title">Menu List</div>

            {{-- Daftar kost milik user (route: kost.index) --}}
            <a href="{{ route('profile.show') }}"
                class="sidebar-link {{ request()->routeIs('profile.user.profile') ? 'active' : '' }}">
                <span>Profile</span>
            </a>

            {{-- Riwayat Pemesanan --}}
            <a href="{{ route('profile.riwayat') }}"
                class="sidebar-link {{ request()->routeIs('profile.riwayat') ? 'active' : '' }}">
                <span>Riwayat Pemesanan</span>
            </a>

            {{-- Syarat & Ketentuan --}}
            <a href="{{ route('profile.syarat') }}"
                class="sidebar-link {{ request()->routeIs('profile.syarat') ? 'active' : '' }}">
                <span>Syarat dan Ketentuan</span>
            </a>

            @if(Auth::user()->role === 'owner')
                <a href="{{ route('profile.dashboard') }}"
                    class="sidebar-link {{ request()->routeIs('profile.dashboard') ? 'active' : '' }}">
                    <span>Dashboard Pemilik </span>
                </a>

                <a href="{{ route('profile.create') }}"
                    class="sidebar-link {{ request()->routeIs('profile.create') ? 'active' : '' }}">
                    <span>Tambah Kost </span>
                </a>

                <a href="{{ route('profile.edit') }}"
                    class="sidebar-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <span>Edit Kost </span>
                </a>
            @endif

            {{-- Logout --}}
            <a href="#" class="sidebar-link logout-link"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-arrow-right-from-bracket me-2"></i>
                <span>Logout</span>
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </aside>
        {{-- END SIDEBAR --}}

        {{-- MAIN AREA --}}
        <div class="pemilik-main">

            {{-- TOPBAR --}}
            <header class="pemilik-topbar">
                <div class="topbar-spacer"></div>

                <div class="topbar-user">
                    <span class="topbar-username">
                        {{ auth()->check() ? auth()->user()->name : 'Pengguna' }}
                    </span>
                    <div class="topbar-avatar">
                        {{ strtoupper(substr(auth()->check() ? auth()->user()->name : 'P', 0, 1)) }}
                    </div>
                </div>
            </header>
            {{-- END TOPBAR --}}

            {{-- CONTENT --}}
            <main class="pemilik-content">
                @yield('content')
            </main>

            {{-- FOOTER --}}
            <footer class="pemilik-footer">
                Copyright © Kost Kita {{ now()->year }}
            </footer>
        </div>
        {{-- END MAIN AREA --}}

    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

</body>

</html>