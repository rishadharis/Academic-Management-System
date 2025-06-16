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
            <li class="{{Request::routeIs('dashboard.admin') ? 'active' : ''}}">
                <a class="nav-link" href="{{route('dashboard.admin')}}"><i class="fas fa-fire"></i> <span>Dashboard</span></a>
            </li>
            <li class="menu-header">Informasi</li>
            <li class="{{Request::routeIs('annoncement.admin*') ? 'active' : ''}}">
                <a class="nav-link" href="{{route('annoncement.admin')}}"><i class="fas fa-bullhorn"></i> <span>Pengumuman</span></a>
            </li>
            <li class="menu-header">Master Data</li>
            <li class="{{Request::routeIs('dosen.admin*') ? 'active' : ''}}">
                <a class="nav-link" href="{{route('dosen.admin')}}"><i class="fas fa-user"></i> <span>Data Dosen</span></a>
            </li>
            <li class="{{Request::routeIs('mahasiswa.admin*') ? 'active' : ''}}">
                <a class="nav-link" href="{{route('mahasiswa.admin')}}"><i class="fas fa-users"></i> <span>Data Mahasiswa</span></a>
            </li>
            <li class="{{Request::routeIs('matkul.admin*') ? 'active' : ''}}">
                <a class="nav-link" href="{{route('matkul.admin')}}"><i class="fas fa-book-open"></i> <span>Data Mata Kuliah</span></a>
            </li>
            <li class="{{Request::routeIs('tahun.akademik.admin*') ? 'active' : ''}}">
                <a class="nav-link" href="{{route('tahun.akademik.admin')}}"><i class="fas fa-list"></i> <span>Data Tahun Akademik</span></a>
            </li>
            <li class="{{Request::routeIs('kelas.admin*') ? 'active' : ''}}">
                <a class="nav-link" href="{{route('kelas.admin')}}"><i class="fas fa-home"></i> <span>Data Kelas</span></a>
            </li>
            <li class="{{Request::routeIs('jadwal*') ? 'active' : ''}}">
                <a class="nav-link" href="{{route('jadwal')}}"><i class="fas fa-chalkboard"></i> <span>Data Jadwal</span></a>
            </li>
        @endrole
        @role("Dosen")
            <li class="menu-header">Dashboard</li>
            <li class="{{Request::routeIs('dashboard.dosen') ? 'active' : ''}}">
                <a class="nav-link" href="{{route('dashboard.dosen')}}"><i class="fas fa-fire"></i> <span>Dashboard</span></a>
            </li>
            <li class="menu-header">Menu</li>
            <li class="{{Request::routeIs('kelas.dosen*') ? 'active' : ''}}">
                <a class="nav-link" href="{{route('kelas.dosen')}}"><i class="fas fa-home"></i> <span>Kelas</span></a>
            </li>
            <li class="{{Request::routeIs('jadwal*') ? 'active' : ''}}">
                <a class="nav-link" href="{{route('jadwal')}}"><i class="fas fa-chalkboard"></i> <span>Jadwal</span></a>
            </li>
            <li class="{{Request::routeIs('penilaian*') ? 'active' : ''}}">
                <a class="nav-link" href="{{route('penilaian.dosen')}}"><i class="fas fa-marker"></i> <span>Penilaian UAS/UTS</span></a>
            </li>
        @endrole
        @role("Mahasiswa")
            <li class="menu-header">Dashboard</li>
            <li class="{{Request::routeIs('dashboard.mahasiswa') ? 'active' : ''}}">
                <a class="nav-link" href="{{route('dashboard.mahasiswa')}}"><i class="fas fa-fire"></i> <span>Dashboard</span></a>
            </li>
            <li class="menu-header">Informasi</li>
            <li class="{{Request::routeIs('mahasiswa.annoncement*') ? 'active' : ''}}">
                <a class="nav-link" href="{{route('mahasiswa.annoncement')}}"><i class="fas fa-bullhorn"></i> <span>Pengumuman</span></a>
            </li>
            <li class="menu-header">Menu</li>
            <li class="{{Request::routeIs('jadwal.mahasiswa') ? 'active' : ''}}">
                <a class="nav-link" href="{{route('jadwal.mahasiswa')}}"><i class="fas fa-chalkboard"></i> <span>Jadwal</span></a>
            </li>
            <li class="{{Request::routeIs('assigment.mahasiswa*') ? 'active' : ''}}">
                <a class="nav-link" href="{{route('assigment.mahasiswa')}}"><i class="fas fa-file-pdf"></i> <span>Assigment</span></a>
            </li>
        @endrole
    </ul>
</aside>