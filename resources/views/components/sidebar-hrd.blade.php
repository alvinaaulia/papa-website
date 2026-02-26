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
            <li class="nav-item {{ $type_menu === 'dashboard-hrd' ? 'active' : '' }}">
                <a href="{{ route('dashboard-hrd') }}" class="nav-link"><i
                        class="fas fa-home"></i><span>Dashboard</span></a>
            </li>
            <li class="menu-header">Utama</li>

            <li class="{{ Request::is('hrd/presence') ? 'active' : '' }}">
                <a href="{{ route('presence-hrd') }}" class="nav-link">
                    <i class="fas fa-pen" aria-hidden="true"></i><span>Absensi</span>
                </a>
            </li>
            <li class="nav-item dropdown {{ $type_menu === 'leave' ? 'active' : '' }}">
                <a href="" class="nav-link has-dropdown" data-toggle="dropdown"><i
                        class="fas fa-clipboard"></i><span>Cuti</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('hrd/leave-application') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('leave-application-hrd') }}">Pengajuan Cuti</a>
                    </li>

                    <li class="{{ Request::is('hrd/leave-histories') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('leave-histories-hrd') }}">Riwayat Cuti</a>
                    </li>
                </ul>
            </li>

            <li class="{{ Request::is('hrd/leave-approval') ? 'active' : '' }}">
                <a href="{{ route('leave-approval-hrd') }}" class="nav-link">
                    <i class="fas fa-newspaper"></i><span> Persetujuan Cuti </span>
                </a>
            </li>

            <li class="{{ Request::is('hrd/daily-activity-list') ? 'active' : '' }}">
                <a href="{{ route('daily-activity-list-hrd') }}" class="nav-link">
                    <i class="fas fa-book"></i><span> Kegiatan Harian (PM) </span>
                </a>
            </li>

            <li class="{{ Request::is('hrd/project-daily-activity') || Request::is('hrd/project-daily-activity-details') ? 'active' : '' }}">
                <a href="{{ route('project-daily-activity-hrd') }}" class="nav-link">
                    <i class="fas fa-book"></i><span> Kegiatan Harian Project </span>
                </a>
            </li>

            <li class="{{ Request::is('hrd/overtime-list') ? 'active' : '' }}">
                <a href="{{ route('overtime-list-hrd') }}" class="nav-link">
                    <i class="fas fa-business-time"></i><span> Lembur </span>
                </a>
            </li>

            <li class="{{ Request::is('hrd/overtime-approval') ? 'active' : '' }}">
                <a href="{{ route('overtime-approval-hrd') }}" class="nav-link">
                    <i class="fas fa-calendar"></i><span> Persetujuan Lembur </span>
                </a>
            </li>

            <li class="nav-item dropdown {{ $type_menu === 'employment-contract-hrd' ? 'active' : '' }}">
                <a href="" class="nav-link has-dropdown" data-toggle="dropdown"><i
                        class="fas fa-book"></i><span>Kontrak Kerja</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('hrd/add-employment-contract-hrd') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('add-employment-contract-hrd') }}">Tambah Kontrak Kerja</a>
                    </li>
                    <li class="{{ Request::is('hrd/employment-contract-hrd') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('employment-contract-hrd') }}">Riwayat Kontrak
                            Kerja</a>
                    </li>

                </ul>
            </li>

            {{-- <li class="{{ Request::is('hrd/employment-contract') ? 'active' : '' }}">
                <a href="{{ route('employment-contract-hrd') }}" class="nav-link">
                    <i class="fas fa-handshake"></i><span>Kontrak Kerja</span>
                </a>
            </li> --}}

            <li class="menu-header">Laporan</li>
            <li class="{{ Request::is('hrd/presence-recap') ? 'active' : '' }}">
                <a href="{{ route('presence-recap-hrd') }}" class="nav-link">
                    <i class="fas fa-file-circle-check"></i><span>Laporan Rekap Absensi</span>
                </a>
            </li>
            <li class="{{ Request::is('hrd/daily-activity-report') ? 'active' : '' }}">
                <a href="{{ route('daily-activity-report-hrd') }}" class="nav-link">
                    <i class="fas fa-file"></i><span> Laporan Kegiatan Harian </span>
                </a>
            </li>
            <li class="{{ Request::is('hrd/performance-report') ? 'active' : '' }}">
                <a href="{{ route('performance-report-hrd') }}" class="nav-link">
                    <i class="fas fa-chart-line"></i><span> Laporan Kinerja </span>
                </a>
            </li>

            <li class="nav-item dropdown {{ $type_menu === 'payslip' ? 'active' : '' }}">
                <a href="" class="nav-link has-dropdown" data-toggle="dropdown"><i
                        class="fas fa-square-poll-horizontal"></i><span>Slip Gaji</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('hrd/payslip') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('payslip-hrd') }}">Riwayat Gaji Karyawan</a>
                    </li>

                    <li class="{{ Request::is('hrd/add-payslip') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('add-payslip-hrd') }}">Tambah Slip Gaji</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item dropdown {{ $type_menu === 'data-employees' ? 'active' : '' }}">
                <a href="" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fa fa-users"
                        aria-hidden="true"></i><span>Data Master Karyawan</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('hrd/data-employees') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('data-employees-hrd') }}">Melihat Data Karyawan</a>
                    </li>

                    <li class="{{ Request::is('hrd/add-employees') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('add-employees-hrd') }}">Menambah Karyawan</a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="{{ route('profile-hrd') }}" class="nav-link">
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
