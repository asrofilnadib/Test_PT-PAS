<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="{{ route('dashboard') }}" class="app-brand-link">
      <span class="app-brand-logo demo">
        <img src="{{ asset('assets/img/logo/logo pas vertikal.jpg') }}" alt="Logo" width="80">
      </span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
      <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
      <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">

    {{-- Dashboard --}}
    <li class="menu-item {{ Request::routeIs('dashboard') ? 'active open' : '' }}">
      <a href="{{ route('dashboard') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-smart-home"></i>
        <div data-i18n="Dashboards">Dashboard</div>
      </a>
    </li>

    {{-- Item management --}}
    <li class="menu-item {{ Request::routeIs('barang*') || Request::routeIs('transaksi_barang*') ? 'active open' : '' }}">
      <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-settings"></i>
        <div data-i18n="Barang Manajemen">Barang Manajemen</div>
      </a>
      <ul class="menu-sub">
        <li class="menu-item {{ Request::routeIs('barang') ? 'active' : '' }}">
          <a href="{{ route('barang') }}" class="menu-link">
            <div data-i18n="Kelola Barang">Kelola Barang</div>
          </a>
        </li>
        <li class="menu-item {{ Request::routeIs('transaksi_barang') ? 'active' : '' }}">
          <a href="{{ route('transaksi_barang') }}" class="menu-link">
            <div data-i18n="Kelola Transaksi Barang">Kelola Transaksi Barang</div>
          </a>
        </li>
        <li class="menu-item {{ Request::routeIs('reporting_barang') ? 'active' : '' }}">
          <a href="{{ route('report') }}" class="menu-link">
            <div data-i18n="Laporan Barang">Laporan Barang</div>
          </a>
        </li>
      </ul>
    </li>

    {{-- Users management --}}
    <li class="menu-item {{ Request::routeIs('user') ? 'active open' : '' }}">
      <a href="{{ route('user') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-users"></i>
        <div data-i18n="Users">Users</div>
      </a>
    </li>

    {{-- Logout --}}
    <li class="menu-item mt-3">
      <a href="{{ route('logout') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-logout"></i>
        <div data-i18n="Logout">Logout</div>
      </a>
    </li>

  </ul>
</aside>
