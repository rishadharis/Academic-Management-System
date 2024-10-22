<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <a href="#">ACM</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
        <a href="#">ACM</a>
    </div>
    <ul class="sidebar-menu">
        @role('Admin')
            <li class="menu-header">Dashboard</li>
            <li class="{{Request::routeIs('dashboard.admin') ? 'active' : ''}}"><a class="nav-link" href="{{route('dashboard.admin')}}"><i class="fas fa-fire"></i> <span>Dashboard</span></a></li>
            <li class="menu-header">Master Data</li>
            <li class="{{Request::routeIs('dosen.admin*') ? 'active' : ''}}"><a class="nav-link" href="{{route('dosen.admin')}}"><i class="fas fa-user"></i> <span>Data Dosen</span></a></li>
            <li class="{{Request::routeIs('mahasiswa.admin*') ? 'active' : ''}}"><a class="nav-link" href="{{route('mahasiswa.admin')}}"><i class="fas fa-users"></i> <span>Data Mahasiswa</span></a></li>
            <li class="{{Request::routeIs('matkul.admin*') ? 'active' : ''}}"><a class="nav-link" href="{{route('matkul.admin')}}"><i class="fas fa-book-open"></i> <span>Data Mata Kuliah</span></a></li>
            <li class="{{Request::routeIs('tahun.akademik.admin*') ? 'active' : ''}}"><a class="nav-link" href="{{route('tahun.akademik.admin')}}"><i class="fas fa-list"></i> <span>Data Tahun Akademik</span></a></li>
            <li class="{{Request::routeIs('kelas.admin*') ? 'active' : ''}}"><a class="nav-link" href="{{route('kelas.admin')}}"><i class="fas fa-home"></i> <span>Data Kelas</span></a></li>
            <li class="menu-header">Informasi</li>
            <li class=""><a class="nav-link" href=""><i class="fas fa-bullhorn"></i> <span>Pengumuman</span></a></li>
        @endrole
    </ul>
</aside>