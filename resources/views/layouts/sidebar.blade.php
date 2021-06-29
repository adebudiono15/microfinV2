<!-- Sidebar -->
<div class="sidebar sidebar-style-2">			
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <div class="user">
                <div class="avatar-sm float-left mr-2">
                    <img src="assets/img/profile.jpg" alt="..." class="avatar-img rounded-circle">
                </div>
                <div class="info">
                    <a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
                        <span>
                            {{-- {{ Auth::user()->name }} --}} Nama
                            <span class="user-level">
                                Role User
                                {{-- {{ Auth::user()->nip }} --}}
                            </span>
                            <span class="caret"></span>
                        </span>
                    </a>
                    <div class="clearfix"></div>

                    <div class="collapse in" id="collapseExample">
                        <ul class="nav">
                            <li>
                                <a href="#profile">
                                    <span class="link-collapse">My Profile</span>
                                </a>
                            </li>
                            <li>
                                <a href="#edit">
                                    <span class="link-collapse">Edit Profile</span>
                                </a>
                            </li>
                            <li>
                                <a href="#settings">
                                    <span class="link-collapse">Settings</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                <span class="link-collapse">Logout</span>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <ul class="nav nav-primary">
                {{-- Dashboard --}}
                <li class="nav-item {{ Request::is('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                {{--  Transaksi  --}}
                <li class="nav-item {{ Request::is('dashboard-penjualan','dashboard-pembelian') ? 'active' : '' }}">
                    <a data-toggle="collapse" href="#transaksi">
                        <i class="fas fa-layer-group"></i>
                        <p>Transaksi</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ Request::is('dashboard-penjualan','dashboard-pembelian') ? 'show' : '' }}" id="transaksi">
                        <ul class="nav nav-collapse">
                            <li class="{{ Request::is('dashboard-penjualan') ? 'active' : '' }}">
                                <a href="{{ route('dashboard-penjualan') }}">
                                    <span class="sub-item">Penjualan</span>
                                </a>
                            </li>
                            <li class="{{ Request::is('dashboard-pembelian') ? 'active' : '' }}">
                                <a href="{{ url('dashboard-pembelian') }}">
                                    <span class="sub-item">Pembelian</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                {{-- Migrasi --}}
                <li class="nav-item {{ Request::is('migrasi') ? 'active' : '' }}">
                    <a href="{{ route('migrasi') }}">
                        <i class="flaticon-delivery-truck"></i>
                        <p>Migrasi</p>
                    </a>
                </li>

                {{--  Piutang  --}}
                <li class="nav-item {{ Request::is('hutangpiutang') ? 'active' : '' }}">
                    <a data-toggle="collapse" href="#hutangpiutang">
                        <i class="fas fa-dollar-sign"></i>
                        <p>Keuangan</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ Request::is('pendapatan','pengeluaran','piutang','hutang') ? 'show' : '' }}" id="hutangpiutang">
                        <ul class="nav nav-collapse">
                            <li class="{{ Request::is('piutang') ? 'active' : '' }}">
                                <a href="{{ route('piutang') }}">
                                    <span class="sub-item">Piutang</span>
                                </a>
                            </li>
                            {{--  Hutang  --}}
                            <li class="{{ Request::is('hutang') ? 'active' : '' }}">
                                <a href="{{ route('hutang') }}">
                                    <span class="sub-item">Hutang</span>
                                </a>
                            </li>

                            {{--  Pendapatan  --}}
                            <li class="{{ Request::is('pendapatan') ? 'active' : '' }}">
                                <a href="{{ route('pendapatan') }}">
                                    <span class="sub-item">Pendapatan</span>
                                </a>
                            </li>
                            {{-- Pengeluaran  --}}
                            <li class="{{ Request::is('pengeluaran') ? 'active' : '' }}">
                                <a href="{{ route('pengeluaran') }}">
                                    <span class="sub-item">Pengeluaran</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                {{--  Master  --}}
                <li class="nav-item {{ Request::is('barang','satuan','kategori','supplier','customer') ? 'active' : '' }}">
                    <a data-toggle="collapse" href="#master">
                        <i class="fas fa-warehouse"></i>
                        <p>Master</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ Request::is('barang','satuan','kategori','supplier','customer') ? 'show' : '' }}" id="master">
                        <ul class="nav nav-collapse">
                            <li class="{{ Request::is('barang') ? 'active' : '' }}">
                                <a href="{{ url('barang') }}">
                                    <span class="sub-item">Barang</span>
                                </a>
                            </li>
                            <li class="{{ Request::is('customer') ? 'active' : '' }}">
                                <a href="{{ url('customer') }}">
                                    <span class="sub-item">Customer</span>
                                </a>
                            </li>
                            <li class="{{ Request::is('satuan') ? 'active' : '' }}">
                                <a href="{{ url('satuan') }}">
                                    <span class="sub-item">Satuan</span>
                                </a>
                            </li>
                            <li class="{{ Request::is('supplier') ? 'active' : '' }}">
                                <a href="{{ url('supplier') }}">
                                    <span class="sub-item">Supplier</span>
                                </a>
                            </li>
                            <li class="{{ Request::is('kategori') ? 'active' : '' }}">
                                <a href="{{ url('kategori') }}">
                                    <span class="sub-item">Kategori</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->