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
            <li class="nav-item {{ $type_menu === 'dashboard-PM' ? 'active' : '' }}">
                <a href="{{ route('dashboard-PM') }}" class="nav-link"><i
                        class="fas fa-home"></i><span>Dashboard</span></a>
            </li>
            <li class="menu-header">Utama</li>

            <li class="{{ Request::is('pm/presence') ? 'active' : '' }}">
                <a href="{{ route('presence-pm') }}" class="nav-link">
                    <i class="fas fa-pen" aria-hidden="true"></i><span>Absensi</span>
                </a>
            </li>

            <li class="nav-item dropdown {{ $type_menu === 'leave-pm' ? 'active' : '' }}">
                <a href="" class="nav-link has-dropdown" data-toggle="dropdown"><i
                        class="fas fa-clipboard"></i><span>Cuti</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('pm/leave-application') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('leave-application-pm') }}">Pengajuan Cuti</a>
                    </li>

                    <li class="{{ Request::is('pm/leave-histories') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('leave-histories-pm') }}">Riwayat Cuti</a>
                    </li>
                </ul>
            </li>

            <li class="{{ Request::is('pm/leave-approval') ? 'active' : '' }}">
                <a href="{{ route('leave-approval-pm') }}" class="nav-link">
                    <i class="fas fa-newspaper"></i><span> Persetujuan Cuti </span>
                </a>
            </li>

            <li class="nav-item dropdown {{ $type_menu === 'daily-activity' ? 'active' : '' }}">
                <a href="" class="nav-link has-dropdown" data-toggle="dropdown"><i
                        class="fas fa-book"></i><span>Kegiatan Harian (PM)</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('pm/add-daily-activities') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('add-daily-activities-pm') }}">Tambah Kegiatan
                            Harian</a>
                    </li>

                    <li class="{{ Request::is('pm/daily-activity-history') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('daily-activity-history-pm') }}">Riwayat Kegiatan Harian</a>
                    </li>
                </ul>
            </li>

            <li class="{{ Request::is('pm/daily-project-list') ? 'active' : '' }}">
                <a href="{{ route('daily-project-list-pm') }}" class="nav-link">
                    <i class="fas fa-book"></i><span> Kegiatan Harian Project </span>
                </a>
            </li>

            <li class="nav-item dropdown {{ $type_menu === 'overtime' ? 'active' : '' }}">
                <a href="" class="nav-link has-dropdown" data-toggle="dropdown"><i
                        class="fas fa-business-time"></i><span>Lembur</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('pm/overtime-application') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('overtime-application-pm') }}">Pengajuan Lembur</a>
                    </li>

                    <li class="{{ Request::is('pm/overtime-histories') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('overtime-histories-pm') }}">Riwayat Lembur</a>
                    </li>
                </ul>
            </li>

            <li class="{{ Request::is('pm/overtime-approval') ? 'active' : '' }}">
                <a href="{{ route('overtime-approval') }}" class="nav-link">
                    <i class="fas fa-calendar"></i><span> Persetujuan Lembur </span>
                </a>
            </li>

            <li class="{{ Request::is('pm/employment-contract') ? 'active' : '' }}">
                <a href="{{ route('employment-contract-pm') }}" class="nav-link">
                    <i class="fas fa-handshake"></i><span>Kontrak Kerja</span>
                </a>
            </li>

            <li class="menu-header">Laporan</li>
            <li class="{{ Request::is('pm/presence-recap') ? 'active' : '' }}">
                <a href="{{ route('presence-recap-pm') }}" class="nav-link">
                    <i class="fas fa-file-circle-check"></i><span>Laporan Rekap Absensi</span>
                </a>
            </li>
            <li class="{{ Request::is('pm/daily-activity-report') ? 'active' : '' }}">
                <a href="{{ route('daily-activity-report-pm') }}" class="nav-link">
                    <i class="fas fa-file"></i><span> Laporan Kegiatan Harian </span>
                </a>
            </li>
            <li class="{{ Request::is('pm/performance-report') ? 'active' : '' }}">
                <a href="{{ route('performance-report-pm') }}" class="nav-link">
                    <i class="fas fa-chart-line"></i><span> Laporan Kinerja </span>
                </a>
            </li>
            <li class="{{ Request::is('pm/payslip') ? 'active' : '' }}">
                <a href="{{ route('payslip-pm') }}" class="nav-link">
                    <i class="fas fa-square-poll-horizontal"></i> <span> Slip Gaji </span>
                </a>
            </li>
            <li class="{{ Request::is('pm/profile-pm') ? 'active' : '' }}">
                <a href="{{ route('profile-pm') }}" class="nav-link">
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
