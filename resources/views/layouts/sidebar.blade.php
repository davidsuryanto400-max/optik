<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link">
        <span class="brand-text font-weight-light">Optik Rapi</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li
                    class="nav-item {{ request()->is('cabang*', 'gudang*', 'tipe*', 'kategori*', 'produk*', 'pasien*') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->is('cabang*', 'gudang*', 'tipe*', 'kategori*', 'produk*', 'pasien*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-database"></i>
                        <p>
                            Master
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('cabang.index') }}"
                                class="nav-link {{ request()->is('cabang*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Cabang</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('gudang.index') }}"
                                class="nav-link {{ request()->is('gudang*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Gudang</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('tipe.index') }}"
                                class="nav-link {{ request()->is('tipe*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tipe</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('kategori.index') }}"
                                class="nav-link {{ request()->is('kategori*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kategori</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('produk.index') }}"
                                class="nav-link {{ request()->is('produk*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Produk</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('pasien.index') }}"
                                class="nav-link {{ request()->is('pasien*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Pasien</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{ request()->is('transaksi*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('transaksi*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-exchange-alt"></i>
                        <p>
                            Transaksi
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('transaksi.index') }}"
                                class="nav-link {{ request()->is('transaksi*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Penjualan</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item {{ request()->is('report*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('report*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Laporan
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('report.rekapStok') }}"
                                class="nav-link {{ request()->is('report/rekap-stok') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Rekap Stok</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('report.kartuStok') }}"
                                class="nav-link {{ request()->is('report/kartu-stok') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kartu Stok</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('report.penjualan') }}"
                                class="nav-link {{ request()->is('report/penjualan') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Laporan Penjualan</p>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>