<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-success elevation-4">
  <!-- Brand Logo -->
  <a href="#" class="brand-link bg-light">
    <img src="{{asset('AdminLTE')}}/dist/img/hikari_logo.png" alt="AdminLTE Logo" class="brand-image" style="opacity: .8">
    <span class="brand-text" style="display: flex; justify-content: center; align-items: center;">
      <img src="{{asset('AdminLTE')}}/dist/img/hikari_logo3.png" alt="Hikari Bridge Logo" class="brand-image" style="width: auto; height: 100px;">
    </span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{ url(auth()->user()->foto ?? '') }}" alt="{{ Auth::user()->name }}" class="img-circle elevation-2">
      </div>
      <div class="info">
        <a href="{{url('profil')}}" class="d-block">{{ Auth::user()->name }}</a>
      </div>
    </div>

    <!-- SidebarSearch Form -->
    <div class="form-inline">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <li class="nav-item">
          <a href="/" class="nav-link @yield('dashboard_select')">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <li class="nav-header">PERUSAHAAN</li>

        @if(Session::get('role')=='admin')
        <li class="nav-item">
          <a href="{{ url('users') }}" class="nav-link @yield('users_select')">
            <i class="nav-icon fas fa-user-cog"></i>
            <p>Users Management</p>
          </a>
        </li>
        @endif

        <li class="nav-item">
          <a href="{{ url('staf') }}" class="nav-link @yield('staf_select')">
            <i class="nav-icon fas fa-users"></i>
            <p>Staf</p>
          </a>
        </li>

        <li class="nav-header">KURSUS</li>

        <li class="nav-item">
          <a href="{{ url('teacher') }}" class="nav-link @yield('teacher_select')">
            <i class="nav-icon fas fa-users"></i>
            <p>Tenaga Pengajar</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('peserta_kursus') }}" class="nav-link @yield('peserta_kursus_select')">
            <i class="nav-icon fas fa-list-alt"></i>
            <p>Peserta Kursus</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('kursus') }}" class="nav-link @yield('kursus_select')">
            <i class="nav-icon fas fa-list-alt"></i>
            <p>Data Kursus</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('kursus/detail') }}" class="nav-link @yield('detail_kursus_select')">
            <i class="nav-icon fas fa-list-alt"></i>
            <p>Detail Peserta Kursus</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('jadwal_kursus')}}" class="nav-link @yield('jadwal_kursus_select')">
            <i class="nav-icon far fa-calendar-alt"></i>
            <p>Jadwal Kursus</p>
          </a>
        </li>

        <li class="nav-header">HIKARI KIDZ</li>

        <li class="nav-item">
          <a href="{{ url('paket') }}" class="nav-link @yield('paket_select')">
            <i class="nav-icon fas fa-users"></i>
            <p>Paket Daycare</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('paket_hkc') }}" class="nav-link @yield('paket_hkc_select')">
            <i class="nav-icon fas fa-users"></i>
            <p>Paket HKC</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('kegiatan_tambahan') }}" class="nav-link @yield('kegiatan_tambahan_select')">
            <i class="nav-icon fas fa-tasks"></i>
            <p>Kegiatan Tambahan</p>
          </a>
        </li>

        <li class="nav-header">LAPORAN KEGIATAN</li>
        
        <li class="nav-item">
          <a href="{{ url('tema_hkc')}}" class="nav-link @yield('tema_hkc_select')">
            <i class="nav-icon far fa-calendar-alt"></i>
            <p>Tema Kegiatan HKC</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('jadwal_hkc')}}" class="nav-link @yield('jadwal_hkc_select')">
            <i class="nav-icon far fa-calendar-alt"></i>
            <p>Jadwal Kegiatan HKC</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('jadwal_hikari_kidz')}}" class="nav-link @yield('jadwal_hikari_kidz_select')">
            <i class="nav-icon far fa-calendar-alt"></i>
            <p>Jadwal Kegiatan Daycare</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('jadwal_makan_daycare')}}" class="nav-link @yield('jadwal_makan_daycare_select')">
            <i class="nav-icon far fa-calendar-alt"></i>
            <p>Jadwal Makan Daycare</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('laporan_kegiatan.daycare.index')}}" class="nav-link @yield('laporan_kegiatan_daycare_select')"> 
            <i class="nav-icon far fa-calendar-alt"></i>    
            <p>Laporan Kegiatan Daycare</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('laporan_kegiatan.hkc_list')}}" class="nav-link @yield('laporan_kegiatan_hkc_select')"> 
            <i class="nav-icon far fa-calendar-alt"></i>    
            <p>Laporan Kegiatan HKC</p>
          </a>
        </li>

        <li class="nav-header">PRESENSI</li>
        <li class="nav-item">
          <a href="#" class="nav-link @yield('jam_datang_select') @yield('jam_pulang_select') @yield('riwayat_absensi_select')">
            <i class="nav-icon fas fa-users"></i>
            <p>
              DAYCARE
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('absensi_daycare.store_jam_datang') }}" class="nav-link @yield('jam_datang_select')">
                <i class="nav-icon fas fa-sign-in-alt"></i>
                <p>Jam Datang</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('absensi_daycare.store_jam_pulang') }}" class="nav-link @yield('jam_pulang_select')">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>Jam Pulang</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('absensi_daycare.riwayat_absensi') }}" class="nav-link @yield('riwayat_absensi_select')">
                <i class="nav-icon fas fa-list-alt"></i>
                <p>Riwayat Absensi Daycare</p>
              </a>
            </li>
          </ul>
        </li>
        
        <li class="nav-header">PENCATATAN</li>
        <li class="nav-item">
          <a href="{{ url('peserta_hikari_kidz') }}" class="nav-link @yield('peserta_hikari_kidz_select')">
            <i class="nav-icon fas fa-list-alt"></i>
            <p>Riwayat Peserta Hikari Kidz</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('peserta_hikari_kidz.verifikasi') }}" class="nav-link @yield('peserta_hikari_kidz_verifikasi_select')">
            <i class="nav-icon fas fa-list-alt"></i>
            <p>Data Peserta Hikari Kidz</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('admin.pembayaran.index') }}" class="nav-link @yield('pembayaran_admin_select')">
            <i class="nav-icon fas fa-dollar-sign"></i>
            <p>Verifikasi Pembayaran</p>
          </a>
        </li>
             <li class="nav-item">
    <a href="{{ route('spp.generator.index') }}" class="nav-link @yield('spp_generator_select')">
        <i class="nav-icon fas fa-cogs"></i>
        <p>Generator SPP</p>
    </a>
</li>

        
        <li class="nav-item">
          <a href="{{ url('presensi_staf') }}" class="nav-link @yield('presensi_staf_select')">
            <i class="nav-icon fas fa-table"></i>
            <p>Presensi Staf</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('presensi_teacher') }}" class="nav-link @yield('presensi_teacher_select')">
            <i class="nav-icon fas fa-table"></i>
            <p>Presensi Teacher</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="#" class="nav-link @yield('gaji_staf_select') @yield('gaji_teacher_select')">
            <i class="nav-icon fa fa-dollar-sign"></i>
            <p>
              Gaji
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ url('gaji_staf') }}" class="nav-link @yield('gaji_staf_select')">
                <i class="nav-icon fas fa"></i>
                <p>Gaji Staf</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('gaji_teacher') }}" class="nav-link @yield('gaji_teacher_select')">
                <i class="nav-icon fas fa"></i>
                <p>Gaji Teacher</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-header">BUKU BESAR</li>
        <li class="nav-item">
          <a href="{{ url('coa') }}" class="nav-link @yield('coa_select')">
            <i class="nav-icon fas fa-book"></i>
            <p>COA</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('jurnal/umum') }}" class="nav-link @yield('jurnalumum_select')">
            <i class="nav-icon fas fa-table"></i>
            <p>Jurnal Umum</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('jurnal/bukubesar') }}" class="nav-link @yield('bukubesar_select')">
            <i class="nav-icon fas fa-list-alt"></i>
            <p>Buku Besar</p>
          </a>
        </li>

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
