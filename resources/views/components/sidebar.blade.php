<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html" style="font-size: 24px">PAPA</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">PAPA</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="nav-item {{ $type_menu === 'dashboard-employee' ? 'active' : '' }}">
                <a href="{{ route('dashboard-employee') }}" class="nav-link"><i
                        class="fas fa-home"></i><span>Dashboard</span></a>
            </li>
            <li class="menu-header">Utama</li>

            <li class="{{ Request::is('karyawan/presence') ? 'active' : '' }}">
                <a href="{{ route('presence') }}" class="nav-link">
                    <i class="fas fa-pen" aria-hidden="true"></i><span>Absensi</span>
                </a>
            </li>
            <li class="nav-item dropdown {{ $type_menu === 'leave' ? 'active' : '' }}">
                <a href="" class="nav-link has-dropdown" data-toggle="dropdown"><i
                        class="fas fa-clipboard"></i><span>Cuti</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('karyawan/leave-application') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('leave-application') }}">Pengajuan Cuti</a>
                    </li>

                    <li class="{{ Request::is('karyawan/leave-histories') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('leave-histories') }}">Riwayat Cuti</a>
                    </li>
                </ul>
            </li>

            <li class="{{ Request::is('karyawan/daily-activities') ? 'active' : '' }}">
                <a href="{{ route('daily-activities') }}" class="nav-link">
                    <i class="fas fa-book"></i><span> Kegiatan Harian </span>
                </a>
            </li>
            <li class="nav-item dropdown {{ $type_menu === 'overtime' ? 'active' : '' }}">
                <a href="" class="nav-link has-dropdown" data-toggle="dropdown"><i
                        class="fas fa-business-time"></i><span>Lembur</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('karyawan/overtime-application') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('overtime-application') }}">Pengajuan Lembur</a>
                    </li>

                    <li class="{{ Request::is('karyawan/overtime-histories') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('overtime-histories') }}">Riwayat Lembur</a>
                    </li>
                </ul>
            </li>
            <li class="{{ Request::is('karyawan/employment-contract') ? 'active' : '' }}">
                <a href="{{ route('employment-contract-karyawan') }}" class="nav-link">
                    <i class="fas fa-handshake"></i><span>Kontrak Kerja</span>
                </a>
            </li>

            <li class="menu-header">Laporan</li>
            <li class="{{ Request::is('karyawan/presence-recap') ? 'active' : '' }}">
                <a href="{{ route('presence-recap') }}" class="nav-link">
                    <i class="fas fa-file-circle-check"></i><span>Laporan Rekap Absensi</span>
                </a>
            </li>
            <li class="{{ Request::is('karyawan/daily-activity-report') ? 'active' : '' }}">
                <a href="{{ route('daily-activity-report') }}" class="nav-link">
                    <i class="fas fa-file"></i><span> Laporan Kegiatan Harian </span>
                </a>
            </li>
            <li class="{{ Request::is('karyawan/performance-report') ? 'active' : '' }}">
                <a href="{{ route('performance-report') }}" class="nav-link">
                    <i class="fas fa-chart-line"></i><span> Laporan Kinerja </span>
                </a>
            </li>
            <li class="{{ Request::is('karyawan/payslip') ? 'active' : '' }}">
                <a href="{{ route('payslip') }}" class="nav-link">
                    <i class="fas fa-square-poll-horizontal"></i> <span> Slip Gaji </span>
                </a>
            </li>
            <li class="{{ Request::is('karyawan/profile') ? 'active' : '' }}">
                <a href="{{ route('profile') }}" class="nav-link">
                    <i class="fas fa-user"></i> <span> Profil </span>
                </a>
            </li>

            <div class="sidebar-mini mt-4 mb-4 p-3">
                <a href="https://getstisla.com/docs" class="btn btn-lg btn-block btn-icon-split"
                    style="background-color: #d51c48; color: white;;">
                    <i class="fas fa-right-from-bracket"></i><span>Logout</span>
                </a>
            </div>
        </ul>
    </aside>
</div>
