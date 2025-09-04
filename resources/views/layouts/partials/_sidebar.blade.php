<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light">SIP Pegawai</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=3498DB&color=fff"
                    class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->name }}</a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @if(auth()->user()->role == 'admin')
                {{-- MENU UNTUK ADMIN --}}
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pegawai.index') }}"
                        class="nav-link {{ request()->routeIs('pegawai.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Data Pegawai</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('berkas.index') }}"
                        class="nav-link {{ request()->routeIs('berkas.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Berkas Pegawai</p>
                    </a>
                </li>
                @else
                {{-- MENU UNTUK PEGAWAI --}}
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Profil Saya</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('berkas.index') }}"
                        class="nav-link {{ request()->routeIs('berkas.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Berkas Pegawai</p>
                    </a>
                </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>