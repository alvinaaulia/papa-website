<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/landing-page', function () {
    return view('landingpage');
});

Route::get('/', function () {
    return view('login-page', ['type_menu' => 'login']);
})->name('login-page');

Route::get('/landing-page', function () {
    return view('landingpage');
});

Route::get('/login', action: function () {
    return view('login-page', ['type_menu' => 'login']);
})->name('login-page');

/* karyawan */
Route::prefix('karyawan')->group(function () {
    Route::get('/dashboard-employee', function () {
        return view('dashboard-employee', ['type_menu' => 'dashboard-employee']);
    })->name('dashboard-employee');

    Route::get('/presence', function () {
        return view('karyawan/presence', ['type_menu' => 'presence']);
    })->name('presence');

    Route::get('/pdf-presence', function () {
        $nama_karyawan = request('nama_karyawan');
        $bulan = request('bulan');
        $tahun = request('tahun');

        return view('karyawan/pdf-presence', [
            'type_menu' => 'pdf-presence-karyawan',
            'nama_karyawan' => $nama_karyawan,
            'bulan' => $bulan,
            'tahun' => $tahun
        ]);
    })->name('pdf-presence-karyawan');

    Route::get('/leave-application', function () {
        return view('karyawan/leave-application', ['type_menu' => 'leave']);
    })->name('leave-application');

    Route::get('/leave-histories', function () {
        return view('karyawan/leave-histories', ['type_menu' => 'leave']);
    })->name('leave-histories');

    Route::get('/edit-leave', function () {
        return view('karyawan/edit-leave', ['type_menu' => 'leave']);
    })->name('edit-leave');

    Route::get('/leave-details', function () {
        return view('karyawan/leave-details', ['type_menu' => 'leave']);
    })->name('leave-details');

    Route::get('/overtime-application', function () {
        return view('karyawan/overtime-application', ['type_menu' => 'overtime']);
    })->name('overtime-application');

    Route::get('/edit-overtime', function () {
        return view('karyawan/edit-overtime', ['type_menu' => 'overtime']);
    })->name('edit-overtime');

    Route::get('/overtime-histories', function () {
        return view('karyawan/overtime-histories', ['type_menu' => 'overtime']);
    })->name('overtime-histories');

    Route::get('/payslip', function () {
        return view('karyawan/payslip', ['type_menu' => 'payslip']);
    })->name('payslip');

    Route::get('/daily-activities', function () {
        return view('karyawan/daily-activities', ['type_menu' => 'daily-activities']);
    })->name('daily-activities');

    Route::get('/daily-activity-details', function () {
        return view('karyawan/daily-activity-details', ['type_menu' => 'daily-activity-details']);
    })->name('daily-activity-details-karyawan');

    Route::get('/pdf-daily-activity-karyawan', function () {
        $from_date = request('from_date');
        $end_date = request('end_date');

        return view('karyawan.pdf-daily-activity', [
            'type_menu' => 'pdf-daily-activity-karyawan',
            'from_date' => $from_date,
            'end_date' => $end_date,

        ]);
    })->name('pdf-daily-activity-karyawan');

    Route::get('/employment-contract', function () {
        return view('karyawan/employment-contract', ['type_menu' => 'employment-contract']);
    })->name('employment-contract-karyawan');

    Route::get('/pdf-employment-contract', function () {
        return view('karyawan.pdf-employment-contract', ['type_menu' => 'pdf-employment-contract-employee']);
    })->name('pdf-employment-contract-employee');

    Route::get('/forgot-password', function () {
        return view('karyawan/forgot-password', ['type_menu' => 'forgot-password']);
    })->name('forgot-password');

    Route::get('/daily-activity-report', function () {
        return view('karyawan/daily-activity-report', ['type_menu' => 'daily-activity-report']);
    })->name('daily-activity-report');

    Route::get('/performance-report', function () {
        return view('karyawan/performance-report', ['type_menu' => 'performance-report']);
    })->name('performance-report');

    Route::get('/presence-recap', function () {
        return view('karyawan/presence-recap', ['type_menu' => 'presence-recap']);
    })->name('presence-recap');

    Route::get('/profile', function () {
        return view('karyawan/profile', ['type_menu' => 'profile']);
    })->name('profile');

    Route::get('/edit-profile', function () {
        return view('karyawan/edit-profile', ['type_menu' => 'edit-profile']);
    })->name('edit-profile');
});


/* pm */
Route::prefix('pm')->group(function () {
    Route::get('/dashboard-PM', function () {
        return view('dashboard-PM', ['type_menu' => 'dashboard-PM']);
    })->name('dashboard-PM');


    Route::get('/presence', function () {
        return view('pm.presence-pm', ['type_menu' => 'presence-pm']);
    })->name('presence-pm');

    Route::get('/pdf-presence', function () {
        $nama_karyawan = request('nama_karyawan');
        $bulan = request('bulan');
        $tahun = request('tahun');

        return view('pm/pdf-presence', [
            'type_menu' => 'pdf-presence-pm',
            'nama_karyawan' => $nama_karyawan,
            'bulan' => $bulan,
            'tahun' => $tahun
        ]);
    })->name('pdf-presence-pm');

    Route::get('/leave-histories', function () {
        return view('pm.leave-histories', ['type_menu' => 'leave-pm']);
    })->name('leave-histories-pm');

    Route::get('/leave-application', function () {
        return view('pm.leave-application', ['type_menu' => 'leave-pm']);
    })->name('leave-application-pm');

    Route::get('/edit-leave', function () {
        return view('pm.edit-leave', ['type_menu' => 'edit-leave-pm']);
    })->name('edit-leave-pm');

    // Route::get('/leave-details', function () {
    //     return view('pm/edit-leave', ['type_menu' => 'leave-pm']);
    // })->name('leave-details-pm');

    Route::get('/leave-approval', function () {
        return view('pm.leave-approval', ['type_menu' => 'leave-approval-pm']);
    })->name('leave-approval-pm');

    Route::get('/approval-details', function () {
        return view('pm.approval-details', ['type_menu' => 'approval-details-pm']);
    })->name('approval-details-pm');

    Route::get('/leave-details', function () {
        return view('pm.leave-details', ['type_menu' => 'leave-pm']);
    })->name('leave-details-pm');

    Route::get('/daily-activity-history', function () {
        return view('pm.daily-activity-history-pm', ['type_menu' => 'daily-activity']);
    })->name('daily-activity-history-pm');

    Route::get('/daily-activity-details', function () {
        return view('pm.daily-activity-details-pm', ['type_menu' => 'daily-activity']);
    })->name('daily-activity-details-pm');

    Route::get('/add-daily-activities', function () {
        return view('pm.add-daily-activities-pm', ['type_menu' => 'daily-activity']);
    })->name('add-daily-activities-pm');

    Route::get('/daily-project-list', function () {
        return view('pm.daily-project-list-pm', ['type_menu' => 'daily-project-list']);
    })->name('daily-project-list-pm');

    Route::get('/project-daily-activity-details', function () {
        return view('pm.project-daily-activity-details', ['type_menu' => 'project-daily-activity-details']);
    })->name('project-daily-activity-details');

    Route::get('/pdf-daily-activity-pm', function () {
        $from_date = request('from_date');
        $end_date = request('end_date');

        return view('pm.pdf-daily-activity', [
            'type_menu' => 'pdf-daily-activity-pm',
            'from_date' => $from_date,
            'end_date' => $end_date,

        ]);
    })->name('pdf-daily-activity-pm');

    Route::get('/overtime-application', function () {
        return view('pm/overtime-application', ['type_menu' => 'overtime']);
    })->name('overtime-application-pm');

    Route::get('/edit-overtime', function () {
        return view('pm/edit-overtime', ['type_menu' => 'overtime']);
    })->name('edit-overtime-pm');

    Route::get('/overtime-histories', function () {
        return view('pm/overtime-histories', ['type_menu' => 'overtime']);
    })->name('overtime-histories-pm');

    Route::get('/overtime-approval', function () {
        return view('pm/overtime-approval', ['type_menu' => 'overtime-approval']);
    })->name('overtime-approval');

    Route::get('/payslip', function () {
        return view('pm/payslip', ['type_menu' => 'payslip-pm']);
    })->name('payslip-pm');

    Route::get('/employment-contract', function () {
        return view('pm/employment-contract-pm', ['type_menu' => 'employment-contract-pm']);
    })->name('employment-contract-pm');

    Route::get('/pdf-employment-contract', function () {
        return view('pm.pdf-employment-contract', ['type_menu' => 'pdf-employment-contract-pm']);
    })->name('pdf-employment-contract-pm');

    Route::get('/daily-activity-report', function () {
        return view('pm.daily-activity-report', ['type_menu' => 'daily-activity-report-pm']);
    })->name('daily-activity-report-pm');

    Route::get('/presence-recap', function () {
        return view('pm.presence-recap-pm', ['type_menu' => 'presence-recap-pm']);
    })->name('presence-recap-pm');

    Route::get('/performance-report', function () {
        return view('pm.performance-report-pm', ['type_menu' => 'performance-report-pm']);
    })->name('performance-report-pm');

    Route::get('/performance-report-pdf', function () {
        return view('pm/performance-report-pdf', ['type_menu' => 'performance-report-pdf-pm']);
    })->name('performance-report-pdf-pm');

    Route::get('/profile', function () {
        return view('pm/profile', ['type_menu' => 'profile-pm']);
    })->name('profile-pm');

    Route::get('/edit-profile', function () {
        return view('pm/edit-profile', ['type_menu' => 'edit-profile-pm']);
    })->name('edit-profile-pm');
});


/* Routing hrd */
Route::prefix('hrd')->group(function () {
    Route::get('/dashboard-hrd', function () {
        return view('dashboard-HRD', ['type_menu' => 'dashboard-hrd']);
    })->name('dashboard-hrd');

    Route::get('/leave-application', function () {
        return view('hrd/leave-application', ['type_menu' => 'leave']);
    })->name('leave-application-hrd');

    Route::get('/leave-histories', function () {
        return view('hrd/leave-histories', ['type_menu' => 'leave']);
    })->name('leave-histories-hrd');

    Route::get('/leave-details', function () {
        return view('hrd/leave-details', ['type_menu' => 'leave']);
    })->name('leave-details-hrd');

    Route::get('/edit-leave', function () {
        return view('hrd/edit-leave', ['type_menu' => 'leave']);
    })->name('edit-leave-hrd');

    Route::get('/leave-approval', function () {
        return view('hrd/leave-approval', ['type_menu' => 'leave']);
    })->name('leave-approval-hrd');

    Route::get('/approval-details', function () {
        return view('hrd/approval-details', ['type_menu' => 'approval-details-hrd']);
    })->name('approval-details-hrd');

    Route::get('/overtime-approval', function () {
        return view('hrd/overtime-approval', ['type_menu' => 'overtime-approval-hrd']);
    })->name('overtime-approval-hrd');

    Route::get('/overtime-list', function () {
        return view('hrd/overtime-list', ['type_menu' => 'overtime-list-hrd']);
    })->name('overtime-list-hrd');

    Route::get('/payslip', function () {
        return view('hrd/payslip', ['type_menu' => 'payslip']);
    })->name('payslip-hrd');

    Route::get('/add-payslip', function () {
        return view('hrd/add-payslip', ['type_menu' => 'payslip']);
    })->name('add-payslip-hrd');

    Route::get('/payslip-details', function (Request $request) {
        $salaryId = $request->query('id');
        return view('hrd/payslip-details', [
            'type_menu' => 'payslip',
            'salary_id' => $salaryId
        ]);
    })->name('payslip-details-hrd');

    Route::get('/edit-payslip', function () {
        return view('hrd/edit-payslip', ['type_menu' => 'payslip']);
    })->name('edit-payslip-hrd');

    Route::get('/data-employees', function () {
        return view('hrd/data-employees', ['type_menu' => 'data-employees']);
    })->name('data-employees-hrd');

    Route::get('/add-employees', function () {
        return view('hrd/add-employees', ['type_menu' => 'data-employees']);
    })->name('add-employees-hrd');

    Route::get('/employee-details', function () {
        return view('hrd/employee-details', ['type_menu' => 'employee-details']);
    })->name('employee-details');

    Route::get('/edit-employee-details', function () {
        return view('hrd/edit-employee-details', ['type_menu' => 'edit-employee-details']);
    })->name('edit-employee-details-hrd');


    Route::get('/presence', function () {
        return view('hrd.presence', ['type_menu' => 'presence']);
    })->name('presence-hrd');

    Route::get('/pdf-presence', function () {
        $nama_karyawan = request('nama_karyawan');
        $bulan = request('bulan');
        $tahun = request('tahun');

        return view('hrd/pdf-presence', [
            'type_menu' => 'pdf-presence-hrd',
            'nama_karyawan' => $nama_karyawan,
            'bulan' => $bulan,
            'tahun' => $tahun
        ]);
    })->name('pdf-presence-hrd');

    Route::get('/presence-recap', function () {
        return view('hrd.presence-recap', ['type_menu' => 'presence-recap-hrd']);
    })->name('presence-recap-hrd');

    Route::get('/project-daily-activity', function () {
        return view('hrd.project-daily-activity-hrd', ['type_menu' => 'project-daily-activity-hrd']);
    })->name('project-daily-activity-hrd');

    Route::get('/project-daily-activity-details', function () {
        return view('hrd.project-daily-activity-details-hrd', ['type_menu' => 'project-daily-activity-details-hrd']);
    })->name('project-daily-activity-details-hrd');

    Route::get('/daily-activity-list', function () {
        return view('hrd.daily-activity-list-hrd', ['type_menu' => 'daily-activity']);
    })->name('daily-activity-list-hrd');

    Route::get('/daily-activity-details', function () {
        return view('hrd.daily-activity-details-hrd', ['type_menu' => 'daily-activity']);
    })->name('daily-activity-details-hrd');

    Route::get('/daily-activity-history', function () {
        return view('hrd.daily-activity-history-hrd', ['type_menu' => 'daily-activity']);
    })->name('daily-activity-history-hrd');

    Route::get('/daily-activity-history-details', function () {
        return view('hrd.daily-activity-history-details', ['type_menu' => 'daily-activity']);
    })->name('daily-activity-history-details-hrd');

    Route::get('/pdf-daily-activity-hrd', function () {
        $from_date = request('from_date');
        $end_date = request('end_date');

        return view('hrd.pdf-daily-activity', [
            'type_menu' => 'pdf-daily-activity-hrd',
            'from_date' => $from_date,
            'end_date' => $end_date,

        ]);
    })->name('pdf-daily-activity-hrd');

    Route::get('/employment-contract-hrd', function () {
        return view('hrd.employment-contract-hrd', ['type_menu' => 'employment-contract-hrd']);
    })->name('employment-contract-hrd');

    Route::get('/edit-employment-contract-hrd', function () {
        return view('hrd.edit-employment-contract', ['type_menu' => 'employment-contract-hrd']);
    })->name('edit-employment-contract-hrd');

    Route::get('/add-employment-contract-hrd', function () {
        return view('hrd.add-employment-contract-hrd', ['type_menu' => 'employment-contract-hrd']);
    })->name('add-employment-contract-hrd');

    Route::get('/profile', function () {
        return view('hrd/profile', ['type_menu' => 'profile-hrd']);
    })->name('profile-hrd');

    Route::get('/edit-profile', function () {
        return view('hrd/edit-profile', ['type_menu' => 'edit-profile-hrd']);
    })->name('edit-profile-hrd');

    Route::get('/daily-activity-report', function () {
        return view('hrd.daily-activity-report', ['type_menu' => 'daily-activity-report']);
    })->name('daily-activity-report-hrd');

    Route::get('/performance-report', function () {
        return view('hrd.performance-report', ['type_menu' => 'performance-report-hrd']);
    })->name('performance-report-hrd');

    Route::get('/pdf-employment-contract', function () {
        return view('hrd.pdf-employment-contract', ['type_menu' => 'pdf-employment-contract-hrd']);
    })->name('pdf-employment-contract-hrd');
});


/* Routing Direktur */
Route::prefix('director')->group(function () {
    Route::get('/dashboard-director', function () {
        return view('dashboard-director', ['type_menu' => 'dashboard-director']);
    })->name('dashboard-director');

    Route::get('/presence', function () {
        return view('direktur/presence', ['type_menu' => 'presence']);
    })->name('presence-director');

    Route::get('/pdf-presence', function () {
        $nama_karyawan = request('nama_karyawan');
        $bulan = request('bulan');
        $tahun = request('tahun');

        return view('direktur/pdf-presence', [
            'type_menu' => 'pdf-presence-director',
            'nama_karyawan' => $nama_karyawan,
            'bulan' => $bulan,
            'tahun' => $tahun
        ]);
    })->name('pdf-presence-director');

    Route::get('/leave-list', function () {
        return view('direktur/leave-list', ['type_menu' => 'leave-director']);
    })->name('leave-list-director');

    Route::get('/leave-details', function () {
        return view('direktur/leave-details', ['type_menu' => 'leave-director']);
    })->name('leave-details-director');

    Route::get('/leave-approval', function () {
        return view('direktur/leave-approval', ['type_menu' => 'leave-approval-director']);
    })->name('leave-approval-director');

    Route::get('/approval-details', function () {
        return view('direktur/approval-details', ['type_menu' => 'approval-details-director']);
    })->name('approval-details-director');

    Route::get('/leave-type', function () {
        return view('direktur/leave-type', ['type_menu' => 'leave-type']);
    })->name('leave-type');

    Route::get('/daily-activity-list', function () {
        return view('direktur/daily-activity-list', ['type_menu' => 'daily-activity']);
    })->name('daily-activity-list');

    Route::get('/daily-activity-details', function () {
        return view('direktur/daily-activity-details', ['type_menu' => 'daily-activity']);
    })->name('daily-activity-details');

    Route::get('/pdf-daily-activity-director', function () {
        $from_date = request('from_date');
        $end_date = request('end_date');

        return view('direktur.pdf-daily-activity', [
            'type_menu' => 'pdf-daily-activity-director',
            'from_date' => $from_date,
            'end_date' => $end_date,

        ]);
    })->name('pdf-daily-activity-director');


    Route::get('/daily-activity-types', function () {
        return view('direktur/daily-activity-types', ['type_menu' => 'daily-activity']);
    })->name('daily-activity-types');

    Route::get('/add-project', function () {
        return view('direktur/add-project', ['type_menu' => 'add-project-director']);
    })->name('add-project-director');

    Route::get('/project-daily-activity-details', function () {
        return view('direktur/project-daily-activity-details', ['type_menu' => 'project-daily-activity']);
    })->name('project-daily-activity-details-director');

    Route::get('/overtime-list', function () {
        return view('direktur/overtime-list', ['type_menu' => 'overtime-list-director']);
    })->name('overtime-list-director');

    Route::get('/overtime-approval', function () {
        return view('direktur/overtime-approval', ['type_menu' => 'overtime-approval-director']);
    })->name('overtime-approval-director');

    Route::get('/employment-contract', function () {
        return view('direktur/employment-contract', ['type_menu' => 'employment-contract']);
    })->name('employment-contract');

    Route::get('/employment-contract-approval', function () {
        return view('direktur/employment-contract-approval', ['type_menu' => 'employment-contract']);
    })->name('employment-contract-approval');

    Route::get('/pdf-employment-contract', function () {
        return view('direktur.pdf-employment-contract', ['type_menu' => 'pdf-employment-contract-director']);
    })->name('pdf-employment-contract-director');

    Route::get('/view-pdf-employment-contract', function () {
        return view('direktur.view-pdf-employment-contract', ['type_menu' => 'view-pdf-employment-contract-director']);
    })->name('view-pdf-employment-contract-director');

    Route::get('/presence-recap', function () {
        return view('direktur/presence-recap', ['type_menu' => 'presence-recap-director']);
    })->name('presence-recap-director');

    Route::get('/daily-activity-report', function () {
        return view('direktur/daily-activity-report', ['type_menu' => 'daily-activity-report-director']);
    })->name('daily-activity-report-director');

    Route::get('/performance-report', function () {
        return view('direktur/performance-report', ['type_menu' => 'performance-report-director']);
    })->name('performance-report-director');

    Route::get('/profile', function () {
        return view('direktur/profile', ['type_menu' => 'profile-director']);
    })->name('profile-director');

    Route::get('/edit-profile', function () {
        return view('direktur/edit-profile', ['type_menu' => 'edit-profile-director']);
    })->name('edit-profile-director');

    Route::get('/data-employees', function () {
        return view('direktur/data-employees', ['type_menu' => 'data-employees-director']);
    })->name('data-employees-director');

    Route::get('/employee-details', function () {
        return view('direktur/employee-details', ['type_menu' => 'employee-details-director']);
    })->name('employee-details-director');

    Route::get('/edit-employee', function () {
        return view('direktur/edit-employee-details', ['type_menu' => 'edit-employee-director']);
    })->name('edit-employee-director');

    Route::get('/payslip', function () {
        return view('direktur/payslip', ['type_menu' => 'payslip-director']);
    })->name('payslip-director');

    Route::get('/data-master-payslip', function () {
        return view('direktur/master-payslip', ['type_menu' => 'data-master-payslip']);
    })->name('data-master-payslip');

    Route::get('/edit-data-payslip', function () {
        return view('direktur/edit-data-payslip', ['type_menu' => 'edit-data-payslip']);
    })->name('edit-data-payslip');

    Route::get('/data-master-position', function () {
        return view('direktur/master-position', ['type_menu' => 'data-master-position']);
    })->name('data-master-position');

    Route::get('/data-master-document', function () {
        return view('direktur/master-document', ['type_menu' => 'data-master-document']);
    })->name('data-master-document');
});




// with auth
Route::get('/login', action: function () {
    return view('auth.login', ['type_menu' => 'login']);
})->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');


Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        $user = auth()->user();

        switch ($user->role) {
            case 'direktur':
                return redirect()->route('dashboard-director');
            case 'PSDM':
                return redirect()->route('dashboard-hrd');
            case 'project manager':
                return redirect()->route('dashboard-PM');
            case 'karyawan':
                return redirect()->route('dashboard-employee');
            default:
                return redirect('/');
        }
    })->name('dashboard');

    Route::middleware(['role:direktur'])->prefix('direktur')->name('dashboard.')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard-director');
        })->name('dashboard-director');
    });

    Route::middleware(['role:PSDM'])->prefix('hrd')->name('dashboard.')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard-HRD');
        })->name('hrd');
    });

    Route::middleware(['role:project manager'])->prefix('manajer-proyek')->name('dashboard.')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard-PM');
        })->name('PM');
    });

    Route::middleware(['role:karyawan'])->prefix('karyawan')->name('dashboard.')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard-employee');
        })->name('karyawan');
    });
});

Route::get('/unauthorized', function () {
    return view('errors.403');
})->name('unauthorized');
