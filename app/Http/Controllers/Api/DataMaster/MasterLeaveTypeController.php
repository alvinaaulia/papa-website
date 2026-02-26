<?php

namespace App\Http\Controllers\Api\DataMaster;

use App\Http\Controllers\Controller;
use App\Models\MasterLeaveType;
use Illuminate\Http\JsonResponse;

class MasterLeaveTypeController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $masterLeaveType = MasterLeaveType::all();

            return response()->json([
                'success' => true,
                'data' => $masterLeaveType,
                'message' => 'Data master leave berhasil diambil'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data master leave',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function showView()
    {
        try {
            $leaveTypes = MasterLeaveType::all();
            return view('direktur.leave-type', compact('leaveTypes'));
        } catch (\Exception $e) {
            return view('direktur.leave-type')->with('error', 'Gagal memuat data jenis cuti');
        }
    }
}