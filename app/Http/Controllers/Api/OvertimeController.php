<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Overtime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class OvertimeController extends Controller
{
    /**
     * List overtime history for logged-in employee (karyawan).
     */
    public function employeeIndex(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user || $user->role !== 'karyawan') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $overtimes = Overtime::with('user:id,name')
            ->where('id_user', $user->id)
            ->orderBy('date', 'desc')
            ->get()
            ->map(function ($overtime, $index) {
                return [
                    'no' => $index + 1,
                    'id' => $overtime->id_overtime,
                    'date' => $overtime->date->format('Y-m-d'),
                    'start_overtime' => $overtime->start_overtime,
                    'end_overtime' => $overtime->end_overtime,
                    'total_overtime' => $overtime->total_overtime,
                    'description' => $overtime->description,
                    'status' => $overtime->status,
                    'proof_url' => $overtime->proof ? Storage::url($overtime->proof) : null,
                ];
            });

        return response()->json([
            'status' => 'success',
            'message' => 'Employee overtime history retrieved successfully',
            'data' => $overtimes,
        ], 200);
    }

    /**
     * Submit overtime for logged-in employee (karyawan).
     */
    public function employeeStore(Request $request): JsonResponse
    {
        $user = auth()->user();

        if (!$user || $user->role !== 'karyawan') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        return $this->storeOvertimeForUser($request, $user->id);
    }

    /**
     * List overtime history for logged-in project manager (pm).
     */
    public function pmIndex(Request $request): JsonResponse
    {
        $user = auth()->user();

        if (!$user || $user->role !== 'project manager') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $overtimes = Overtime::with('user:id,name')
            ->where('id_user', $user->id)
            ->orderBy('date', 'desc')
            ->get()
            ->map(function ($overtime, $index) {
                return [
                    'no' => $index + 1,
                    'id' => $overtime->id_overtime,
                    'date' => $overtime->date->format('Y-m-d'),
                    'start_overtime' => $overtime->start_overtime,
                    'end_overtime' => $overtime->end_overtime,
                    'total_overtime' => $overtime->total_overtime,
                    'description' => $overtime->description,
                    'status' => $overtime->status,
                    'proof_url' => $overtime->proof ? Storage::url($overtime->proof) : null,
                ];
            });

        return response()->json([
            'status' => 'success',
            'message' => 'PM overtime history retrieved successfully',
            'data' => $overtimes,
        ], 200);
    }

    /**
     * Submit overtime for logged-in project manager (pm).
     */
    public function pmStore(Request $request): JsonResponse
    {
        $user = auth()->user();

        if (!$user || $user->role !== 'project manager') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        return $this->storeOvertimeForUser($request, $user->id);
    }

    /**
     * HRD: list overtime that need approval (status pending).
     */
    public function hrdApprovalList(Request $request): JsonResponse
    {
        $user = auth()->user();

        if (!$user || $user->role !== 'hrd') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $overtimes = Overtime::with('user:id,name,position,department')
            ->where('status', 'pending')
            ->orderBy('date', 'desc')
            ->get()
            ->map(function ($overtime, $index) {
                return [
                    'no' => $index + 1,
                    'id' => $overtime->id_overtime,
                    'employee_name' => $overtime->user->name ?? null,
                    'employee_position' => $overtime->user->position ?? null,
                    'employee_department' => $overtime->user->department ?? null,
                    'date' => $overtime->date->format('Y-m-d'),
                    'start_overtime' => $overtime->start_overtime,
                    'end_overtime' => $overtime->end_overtime,
                    'total_overtime' => $overtime->total_overtime,
                    'description' => $overtime->description,
                    'status' => $overtime->status,
                    'proof_url' => $overtime->proof ? Storage::url($overtime->proof) : null,
                ];
            });

        return response()->json([
            'status' => 'success',
            'message' => 'HRD overtime approval list retrieved successfully',
            'data' => $overtimes,
        ], 200);
    }

    /**
     * HRD: approve overtime.
     */
    public function hrdApprove(Request $request, string $overtimeId): JsonResponse
    {
        $user = auth()->user();

        if (!$user || $user->role !== 'hrd') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $overtime = Overtime::where('id_overtime', $overtimeId)
            ->where('status', 'pending')
            ->first();

        if (!$overtime) {
            return response()->json([
                'status' => 'error',
                'message' => 'Overtime not found or already processed',
            ], 404);
        }

        $overtime->update(['status' => 'approved']);

        return response()->json([
            'status' => 'success',
            'message' => 'Overtime approved successfully by HRD',
            'data' => $overtime,
        ], 200);
    }

    /**
     * HRD: reject overtime.
     */
    public function hrdReject(Request $request, string $overtimeId): JsonResponse
    {
        $user = auth()->user();

        if (!$user || $user->role !== 'hrd') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $overtime = Overtime::where('id_overtime', $overtimeId)
            ->where('status', 'pending')
            ->first();

        if (!$overtime) {
            return response()->json([
                'status' => 'error',
                'message' => 'Overtime not found or already processed',
            ], 404);
        }

        $overtime->update(['status' => 'rejected']);

        return response()->json([
            'status' => 'success',
            'message' => 'Overtime rejected successfully by HRD',
            'data' => $overtime,
        ], 200);
    }

    /**
     * Director: list overtime that need approval (status pending).
     */
    public function directorApprovalList(Request $request): JsonResponse
    {
        $user = auth()->user();

        if (!$user || $user->role !== 'direktur') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $overtimes = Overtime::with('user:id,name,position,department')
            ->where('status', 'pending')
            ->orderBy('date', 'desc')
            ->get()
            ->map(function ($overtime, $index) {
                return [
                    'no' => $index + 1,
                    'id' => $overtime->id_overtime,
                    'employee_name' => $overtime->user->name ?? null,
                    'employee_position' => $overtime->user->position ?? null,
                    'employee_department' => $overtime->user->department ?? null,
                    'date' => $overtime->date->format('Y-m-d'),
                    'start_overtime' => $overtime->start_overtime,
                    'end_overtime' => $overtime->end_overtime,
                    'total_overtime' => $overtime->total_overtime,
                    'description' => $overtime->description,
                    'status' => $overtime->status,
                    'proof_url' => $overtime->proof ? Storage::url($overtime->proof) : null,
                ];
            });

        return response()->json([
            'status' => 'success',
            'message' => 'Director overtime approval list retrieved successfully',
            'data' => $overtimes,
        ], 200);
    }

    /**
     * Director: approve overtime.
     */
    public function directorApprove(Request $request, string $overtimeId): JsonResponse
    {
        $user = auth()->user();

        if (!$user || $user->role !== 'direktur') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $overtime = Overtime::where('id_overtime', $overtimeId)
            ->where('status', 'pending')
            ->first();

        if (!$overtime) {
            return response()->json([
                'status' => 'error',
                'message' => 'Overtime not found or already processed',
            ], 404);
        }

        $overtime->update(['status' => 'approved']);

        return response()->json([
            'status' => 'success',
            'message' => 'Overtime approved successfully by Director',
            'data' => $overtime,
        ], 200);
    }

    /**
     * Director: reject overtime.
     */
    public function directorReject(Request $request, string $overtimeId): JsonResponse
    {
        $user = auth()->user();

        if (!$user || $user->role !== 'direktur') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $overtime = Overtime::where('id_overtime', $overtimeId)
            ->where('status', 'pending')
            ->first();

        if (!$overtime) {
            return response()->json([
                'status' => 'error',
                'message' => 'Overtime not found or already processed',
            ], 404);
        }

        $overtime->update(['status' => 'rejected']);

        return response()->json([
            'status' => 'success',
            'message' => 'Overtime rejected successfully by Director',
            'data' => $overtime,
        ], 200);
    }

    /**
     * Shared logic for creating overtime for a specific user.
     */
    protected function storeOvertimeForUser(Request $request, string $userId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'start_overtime' => 'required|date_format:H:i',
            'end_overtime' => 'required|date_format:H:i|after:start_overtime',
            'description' => 'nullable|string',
            'proof' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $proofPath = null;
        if ($request->hasFile('proof')) {
            $proofPath = $request->file('proof')->store('overtime_proofs', 'public');
        }

        $start = Carbon::createFromFormat('H:i', $request->input('start_overtime'));
        $end = Carbon::createFromFormat('H:i', $request->input('end_overtime'));
        $totalMinutes = $start->diffInMinutes($end);

        $overtime = Overtime::create([
            'id_user' => $userId,
            'date' => $request->input('date'),
            'start_overtime' => $request->input('start_overtime'),
            'end_overtime' => $request->input('end_overtime'),
            'total_overtime' => $totalMinutes,
            'description' => $request->input('description'),
            'proof' => $proofPath,
            'status' => 'pending',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Overtime submitted successfully',
            'data' => $overtime,
        ], 201);
    }
}
