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
            <li class="nav-item {{ $type_menu === 'dashboard-director' ? 'active' : '' }}">
                <a href="{{ route('dashboard-director') }}" class="nav-link"><i
                        class="fas fa-home"></i><span>Dashboard</span></a>
            </li>
            <li class="menu-header">Utama</li>

            <li class="{{ Request::is('director/presence') ? 'active' : '' }}">
                <a href="{{ route('presence-director') }}" class="nav-link">
                    <i class="fas fa-pen" aria-hidden="true"></i><span>Absensi</span>
                </a>
            </li>

            <li class="{{ Request::is('director/leave-list-director') ? 'active' : '' }}">
                <a href="{{ route('leave-list-director') }}" class="nav-link">
                    <i class="fas fa-clipboard" aria-hidden="true"></i><span>Cuti</span>
                </a>
            </li>

            <li class="{{ Request::is('director/leave-approval-director') ? 'active' : '' }}">
                <a href="{{ route('leave-approval-director') }}" class="nav-link">
                    <i class="fas fa-newspaper" aria-hidden="true"></i><span>Persetujuan Cuti</span>
                </a>
            </li>

            <li class="{{ Request::is('director/daily-activity-list') ? 'active' : '' }}">
                <a href="{{ route('daily-activity-list') }}" class="nav-link">
                    <i class="fas fa-book"></i><span> Kegiatan Harian (PM) </span>
                </a>
            </li>

            <li class="{{ Request::is('director/add-project') ? 'active' : '' }}">
                <a href="{{ route('add-project-director') }}" class="nav-link">
                    <i class="fas fa-book"></i><span> Kegiatan Harian Project </span>
                </a>
            </li>

            <li class="{{ Request::is('director/overtime-list-director') ? 'active' : '' }}">
                <a href="{{ route('overtime-list-director') }}" class="nav-link">
                    <i class="fas fa-business-time"></i><span> Lembur </span>
                </a>
            </li>

            <li class="{{ Request::is('director/overtime-approval-director') ? 'active' : '' }}">
                <a href="{{ route('overtime-approval-director') }}" class="nav-link">
                    <i class="fas fa-calendar"></i><span> Persetujuan Lembur </span>
                </a>
            </li>

            <li class="nav-item dropdown {{ $type_menu === 'employment-contract' ? 'active' : '' }}">
                <a href="" class="nav-link has-dropdown" data-toggle="dropdown"><i
                        class="fas fa-handshake"></i><span>Kontrak Kerja</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('director/employment-contract-approval') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('employment-contract-approval') }}">Persetujuan Kontrak Kerja</a>
                    </li>
                    <li class="{{ Request::is('director/employment-contract') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('employment-contract') }}">Riwayat Kontrak Kerja</a>
                    </li>
                </ul>
            </li>

            <li class="menu-header">Laporan</li>
            <li class="{{ Request::is('director/presence-recap') ? 'active' : '' }}">
                <a href="{{ route('presence-recap-director') }}" class="nav-link">
                    <i class="fas fa-file-circle-check"></i><span>Laporan Rekap Absensi</span>
                </a>
            </li>

            <li class="{{ Request::is('director/daily-activity-report') ? 'active' : '' }}">
                <a href="{{ route('daily-activity-report-director') }}" class="nav-link">
                    <i class="fas fa-file"></i><span> Laporan Kegiatan Harian </span>
                </a>
            </li>

            <li class="{{ Request::is('director/performance-report') ? 'active' : '' }}">
                <a href="{{ route('performance-report-director') }}" class="nav-link">
                    <i class="fas fa-chart-line"></i><span> Laporan Kinerja </span>
                </a>
            </li>

            <li class="{{ Request::is('director/payslip') ? 'active' : '' }}">
                <a href="{{ route('payslip-director') }}" class="nav-link">
                    <i class="fas fa-square-poll-horizontal"></i> <span> Slip Gaji </span>
                </a>
            </li>

            <li class="menu-header">Master Data</li>
            <li class="{{ Request::is('director/daily-activity-types') ? 'active' : '' }}">
                <a href="{{ route('daily-activity-types') }}" class="nav-link">
                    <i class="fas fa-book"></i><span> Jenis Kegiatan Harian </span>
                </a>
            </li>


            <li class="{{ Request::is('director/data-employees-director') ? 'active' : '' }}">
                <a href="{{ route('data-employees-director') }}" class="nav-link">
                    <i class="fa fa-users"></i><span> Data Master Karyawan </span>
                </a>
            </li>

            <li class="{{ Request::is('director/data-master-payslip') ? 'active' : '' }}">
                <a href="{{ route('data-master-payslip') }}" class="nav-link">
                    <i class="fa fa-wallet"></i><span> Data Master Gaji </span>
                </a>
            </li>

            <li class="{{ Request::is('director/data-master-position') ? 'active' : '' }}">
                <a href="{{ route('data-master-position') }}" class="nav-link">
                    <i class="fa fa-wallet"></i><span> Data Master Jabatan </span>
                </a>
            </li>

            <li class="{{ Request::is('director/leave-type') ? 'active' : '' }}">
                <a href="{{ route('leave-type') }}" class="nav-link">
                    <i class="fa fa-calendar-days"></i><span> Data Master Jenis Cuti </span>
                </a>
            </li>

            <li class="{{ Request::is('director/data-master-document') ? 'active' : '' }}">
                <a href="{{ route('data-master-document') }}" class="nav-link">
                    <i class="fa fa-wallet"></i><span> Data Master Jenis Dokumen </span>
                </a>
            </li>

            <li class="{{ Request::is('director/profile-director') ? 'active' : '' }}">
                <a href="{{ route('profile-director') }}" class="nav-link">
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
