<div class="sidebar" data-color="green" data-background-color="white" data-image="../assets/img/sidebar-1.jpg">
  <!--
    Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

    Tip 2: you can also add an image using data-image tag
-->
  <div class="logo"><a href="#" class="simple-text logo-normal">
    <img src="{{asset('AdminLTE')}}/dist/img/hikari_logo2.png" alt="Hikari Bridge Logo" class="brand-image" style="width: auto; height: 70px;">
    </a>
  </div>

  <div class="sidebar-wrapper">
    <ul class="nav">
      <li class="nav-item @yield('dashboard2_select')">
        <a class="nav-link" href="/">
          <i class="material-icons">dashboard</i>
          <p>Dashboard</p>
        </a>
      </li>
      <li class="nav-item @yield('profil_select')">
        <a class="nav-link" href="{{url('profil')}}">
          <i class="material-icons">person</i>
          <p>Profile</p>
        </a>
      </li>
      @if(Session::get('role')=='teacher')
      {{-- Tampilan untuk Teacher --}}
      <li class="nav-item ">
        <a class="nav-link" href="./tables.html">
          <i class="fa fa-book"></i>
          <p>Kursus Saya</p>
        </a>
      </li>
      <li class="nav-item @yield('jadwal_kursus_select')">
        <a class="nav-link" href="#" onclick="submitJadwalKursus()">
            <i class="fa fa-list"></i>
            <p>Jadwal Kursus</p>
        </a>
        <form id="jadwal-kursus-form" action="{{ url('jadwal-kursus-teacher') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="email" value="{{ Auth::user()->email }}">
        </form>
      </li> 
      <li class="nav-item ">
        <a class="nav-link" href="./tables.html">
          <i class="material-icons">content_paste</i>
          <p>Presensi Siswa</p>
        </a>
      </li>
      <li class="nav-item @yield('riwayat_presensi_select')">
        <a class="nav-link" href="#" onclick="submitRiwayatPresensi()">
            <i class="fa fa-calendar"></i>
            <p>Riwayat Presensi</p>
        </a>
        <form id="riwayat-presensi-form" action="{{ url('riwayat_presensi-teacher') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="email" value="{{ Auth::user()->email }}">
        </form>
      </li>
      <li class="nav-item ">
        <a class="nav-link" href="./tables.html">
          <i class="fa fa-dollar"></i>
          <p>Gaji Saya</p>
        </a>
      </li>
      @else
      {{-- Tampilan untuk selain Teacher (misal: Customer/Admin) --}}
      <li class="nav-item @yield('daftar_hkc_select')">
        <a class="nav-link" href="{{ route('daftar_hkc.index') }}">
          <i class="material-icons">person</i>
          <p>Daftar Program Hikari Kidz</p>
        </a>
      </li>
      <li class="nav-item @yield('riwayat_select')">
        <a class="nav-link" href="{{ url('riwayatpendaftaran') }}">
          <i class="material-icons">library_books</i>
          <p>Riwayat Pendaftaran</p>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#pembayaran" aria-expanded="false">
          <i class="fa fa-clipboard"></i>
          <p>Pembayaran
            <b class="caret"></b>
          </p>
        </a>
        <div class="collapse" id="pembayaran">
          <ul class="nav pl-4">
            <li class="nav-item @yield('tagihan_select')">
              <a class="nav-link" href="{{ url('tagihanpembayaran') }}">
                <i class="material-icons">money</i>
                <p>Daftar Baru</p>
              </a>
            </li>
           <li class="nav-item">
                <a href="{{ route('spp.customer.index') }}" class="nav-link @yield('pembayaran_spp_select')">
                  <i class="far fa-circle nav-icon"></i>
                  <p>SPP Bulanan</p>
                </a>
              </li>
         <li class="nav-item">
          <a href="{{ route('overtime.customer.index') }}" class="nav-link @yield('overtime_bill_customer_select')">
            <i class="nav-icon fas fa-hourglass-half"></i>
            <p>Tagihan Overtime</p>
          </a>
        </li>
        <li class="nav-item"> {{-- Tambahkan ini --}}
          <a href="{{ route('meal.customer.index') }}" class="nav-link @yield('meal_bill_customer_select')">
            <i class="nav-icon fas fa-utensils"></i>
            <p>Tagihan Uang Makan</p>
          </a>
        </li>
          </ul>
        </div>
      </li>

      <li class="nav-item @yield('riwayat_pembayaran_select')">
        <a class="nav-link" href="{{ route('paymenthkc.index') }}">
          <i class="material-icons">library_books</i>
          <p>Riwayat Pembayaran</p>
        </a>
      </li>

      <!-- <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#daycare" aria-expanded="false">
          <i class="fa fa-clipboard"></i>
          <p>Daycare
            <b class="caret"></b>
          </p>
        </a>
        <div class="collapse" id="daycare">
          <ul class="nav pl-4"> -->
            <li class="nav-item @yield('jadwal_makan_daycare_user_select')">
              <a class="nav-link" href="{{ route('jadwal_makan_daycare_user') }}">
                  <i class="material-icons">library_books</i>
                  <p>Jadwal Makan Daycare</p>
              </a>
            </li>
            <!-- <li class="nav-item">
              <a href="{{ url('laporan_kegiatan_daycare.blade') }}" class="nav-link @yield('laporan_kegiatan_daycare_select')">
                <i class="far fa-circle nav-icon"></i>
                <p>Laporan Kegiatan</p>
              </a>
            </li>
          </ul>
        </div>
      </li> -->

      <!-- <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#kidzclub" aria-expanded="false">
          <i class="fa fa-clipboard"></i>
          <p>HKC
            <b class="caret"></b>
          </p>
        </a>
        <div class="collapse" id="kidzclub">
          <ul class="nav pl-4"> -->
            <li class="nav-item @yield('jadwal_hkc_user_select')">
              <a class="nav-link" href="{{ route('jadwal_hkc_user') }}">
                  <i class="material-icons">library_books</i>
                  <p>Jadwal Kegiatan HKC</p>
              </a>
            </li>
            <!-- <li class="nav-item">
              <a href="{{ url('laporan_kegiatan_hkc.blade') }}" class="nav-link @yield('laporan_kegiatan_hkc_select')">
                <i class="far fa-circle nav-icon"></i>
                <p>Laporan Kegiatan</p>
              </a>
            </li>
          </ul>
        </div>
      </li> -->

      <!-- <li class="nav-item ">
        <a class="nav-link" href="./notifications.html">
          <i class="material-icons">library_books</i>
          <p>E-Learning</p>
        </a>
      </li> -->
      @endif
    </ul>
  </div>
</div>
<script>
  function submitJadwalKursus() {
      document.getElementById('jadwal-kursus-form').submit();
  }
</script>
<script>
  function submitRiwayatPresensi() {
      document.getElementById('riwayat-presensi-form').submit();
  }
</script>
