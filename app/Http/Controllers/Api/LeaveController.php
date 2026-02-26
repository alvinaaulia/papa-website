<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeaveController extends Controller
{
    /**
     * Karyawan: riwayat cuti user yang login.
     */
    public function employeeIndex(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthenticated'], 401);
        }
        if (strtolower((string) $user->role) !== 'karyawan') {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $leaves = Leave::with('user:id,name')
            ->where('id_user', $user->id)
            ->orderBy('start_of_leave', 'desc')
            ->get()
            ->map(fn ($leave, $i) => $this->formatLeaveItem($leave, $i + 1));

        return response()->json([
            'status' => 'success',
            'message' => 'Riwayat cuti berhasil diambil',
            'data' => $leaves,
        ], 200);
    }

    /**
     * Karyawan: ajukan cuti.
     */
    public function employeeStore(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user || strtolower((string) $user->role) !== 'karyawan') {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }
        return $this->storeLeave($request, $user->id);
    }

    /**
     * PM: riwayat cuti user yang login.
     */
    public function pmIndex(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthenticated'], 401);
        }
        $role = strtolower((string) $user->role);
        if (! in_array($role, ['pm', 'project manager'], true)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $leaves = Leave::with('user:id,name')
            ->where('id_user', $user->id)
            ->orderBy('start_of_leave', 'desc')
            ->get()
            ->map(fn ($leave, $i) => $this->formatLeaveItem($leave, $i + 1));

        return response()->json([
            'status' => 'success',
            'message' => 'Riwayat cuti berhasil diambil',
            'data' => $leaves,
        ], 200);
    }

    /**
     * PM: ajukan cuti.
     */
    public function pmStore(Request $request): JsonResponse
    {
        $user = $request->user();
        $role = $user ? strtolower((string) $user->role) : '';
        if (! in_array($role, ['pm', 'project manager'], true)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }
        return $this->storeLeave($request, $user->id);
    }

    /**
     * PM: list pengajuan cuti karyawan (untuk disetujui/ditolak).
     */
    public function pmApprovalList(Request $request): JsonResponse
    {
        $user = $request->user();
        $role = $user ? strtolower((string) $user->role) : '';
        if (! in_array($role, ['pm', 'project manager'], true)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $query = Leave::with('user:id,name,email')
            ->whereHas('user', fn ($q) => $q->where('role', 'karyawan'));
        if ($request->has('status_pm')) {
            $query->where('status_pm', $request->status_pm);
        }
        $leaves = $query->orderBy('created_at', 'desc')->get()
            ->map(fn ($leave, $i) => $this->formatLeaveItemForApproval($leave, $i + 1, 'pm'));

        return response()->json([
            'status' => 'success',
            'message' => 'List pengajuan cuti karyawan berhasil diambil',
            'data' => $leaves,
        ], 200);
    }

    /**
     * PM: setujui pengajuan cuti karyawan.
     */
    public function pmApprove(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        $role = $user ? strtolower((string) $user->role) : '';
        if (! in_array($role, ['pm', 'project manager'], true)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }
        $leave = Leave::with('user')->where('id_leave', $id)->first();
        if (! $leave) {
            return response()->json(['status' => 'error', 'message' => 'Pengajuan cuti tidak ditemukan'], 404);
        }
        if (strtolower((string) ($leave->user->role ?? '')) !== 'karyawan') {
            return response()->json(['status' => 'error', 'message' => 'Hanya dapat menyetujui pengajuan cuti karyawan'], 403);
        }
        $leave->update(['status_pm' => 'approved']);
        return response()->json([
            'status' => 'success',
            'message' => 'Pengajuan cuti berhasil disetujui (PM)',
            'data' => $this->formatLeaveItemForApproval($leave->fresh('user'), 0, 'pm'),
        ], 200);
    }

    /**
     * PM: tolak pengajuan cuti karyawan.
     */
    public function pmReject(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        $role = $user ? strtolower((string) $user->role) : '';
        if (! in_array($role, ['pm', 'project manager'], true)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }
        $leave = Leave::with('user')->where('id_leave', $id)->first();
        if (! $leave) {
            return response()->json(['status' => 'error', 'message' => 'Pengajuan cuti tidak ditemukan'], 404);
        }
        if (strtolower((string) ($leave->user->role ?? '')) !== 'karyawan') {
            return response()->json(['status' => 'error', 'message' => 'Hanya dapat menolak pengajuan cuti karyawan'], 403);
        }
        $leave->update(['status_pm' => 'rejected']);
        return response()->json([
            'status' => 'success',
            'message' => 'Pengajuan cuti ditolak (PM)',
            'data' => $this->formatLeaveItemForApproval($leave->fresh('user'), 0, 'pm'),
        ], 200);
    }

    /**
     * HRD: list semua pengajuan cuti (untuk disetujui/ditolak).
     */
    public function hrdList(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user || strtolower((string) $user->role) !== 'hrd') {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $query = Leave::with('user:id,name,email');
        if ($request->has('status_hrd')) {
            $query->where('status_hrd', $request->status_hrd);
        }
        $leaves = $query->orderBy('created_at', 'desc')->get()
            ->map(fn ($leave, $i) => $this->formatLeaveItemForApproval($leave, $i + 1, 'hrd'));

        return response()->json([
            'status' => 'success',
            'message' => 'List pengajuan cuti berhasil diambil',
            'data' => $leaves,
        ], 200);
    }

    /**
     * HRD: setujui pengajuan cuti.
     */
    public function hrdApprove(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        if (! $user || strtolower((string) $user->role) !== 'hrd') {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }
        $leave = Leave::where('id_leave', $id)->first();
        if (! $leave) {
            return response()->json(['status' => 'error', 'message' => 'Pengajuan cuti tidak ditemukan'], 404);
        }
        $leave->update(['status_hrd' => 'approved']);
        return response()->json([
            'status' => 'success',
            'message' => 'Pengajuan cuti berhasil disetujui (HRD)',
            'data' => $this->formatLeaveItemForApproval($leave->fresh('user'), 0, 'hrd'),
        ], 200);
    }

    /**
     * HRD: tolak pengajuan cuti.
     */
    public function hrdReject(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        if (! $user || strtolower((string) $user->role) !== 'hrd') {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }
        $leave = Leave::where('id_leave', $id)->first();
        if (! $leave) {
            return response()->json(['status' => 'error', 'message' => 'Pengajuan cuti tidak ditemukan'], 404);
        }
        $leave->update(['status_hrd' => 'rejected']);
        return response()->json([
            'status' => 'success',
            'message' => 'Pengajuan cuti ditolak (HRD)',
            'data' => $this->formatLeaveItemForApproval($leave->fresh('user'), 0, 'hrd'),
        ], 200);
    }

    /**
     * Direktur: list semua pengajuan cuti (untuk disetujui/ditolak).
     */
    public function directorList(Request $request): JsonResponse
    {
        $user = $request->user();
        $role = $user ? strtolower((string) $user->role) : '';
        if (! in_array($role, ['direktur', 'director'], true)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $query = Leave::with('user:id,name,email');
        if ($request->has('status_director')) {
            $query->where('status_director', $request->status_director);
        }
        $leaves = $query->orderBy('created_at', 'desc')->get()
            ->map(fn ($leave, $i) => $this->formatLeaveItemForApproval($leave, $i + 1, 'director'));

        return response()->json([
            'status' => 'success',
            'message' => 'List pengajuan cuti berhasil diambil',
            'data' => $leaves,
        ], 200);
    }

    /**
     * Direktur: setujui pengajuan cuti.
     */
    public function directorApprove(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        $role = $user ? strtolower((string) $user->role) : '';
        if (! in_array($role, ['direktur', 'director'], true)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }
        $leave = Leave::where('id_leave', $id)->first();
        if (! $leave) {
            return response()->json(['status' => 'error', 'message' => 'Pengajuan cuti tidak ditemukan'], 404);
        }
        $leave->update(['status_director' => 'approved']);
        return response()->json([
            'status' => 'success',
            'message' => 'Pengajuan cuti berhasil disetujui (Direktur)',
            'data' => $this->formatLeaveItemForApproval($leave->fresh('user'), 0, 'director'),
        ], 200);
    }

    /**
     * Direktur: tolak pengajuan cuti.
     */
    public function directorReject(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        $role = $user ? strtolower((string) $user->role) : '';
        if (! in_array($role, ['direktur', 'director'], true)) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }
        $leave = Leave::where('id_leave', $id)->first();
        if (! $leave) {
            return response()->json(['status' => 'error', 'message' => 'Pengajuan cuti tidak ditemukan'], 404);
        }
        $leave->update(['status_director' => 'rejected']);
        return response()->json([
            'status' => 'success',
            'message' => 'Pengajuan cuti ditolak (Direktur)',
            'data' => $this->formatLeaveItemForApproval($leave->fresh('user'), 0, 'director'),
        ], 200);
    }

    /**
     * Detail satu pengajuan cuti (bisa dipakai semua role yang punya akses).
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthenticated'], 401);
        }

        $leave = Leave::with('user:id,name,email')->where('id_leave', $id)->first();
        if (! $leave) {
            return response()->json(['status' => 'error', 'message' => 'Pengajuan cuti tidak ditemukan'], 404);
        }

        $role = strtolower((string) $user->role);
        $canSee = in_array($role, ['karyawan', 'pm', 'project manager'], true) && $leave->id_user === $user->id
            || in_array($role, ['hrd', 'direktur', 'director'], true);
        if (! $canSee) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $item = $this->formatLeaveItem($leave, 1);
        $item['status_pm'] = $leave->status_pm;
        $item['status_hrd'] = $leave->status_hrd;
        $item['status_director'] = $leave->status_director;

        return response()->json([
            'status' => 'success',
            'message' => 'Detail cuti berhasil diambil',
            'data' => $item,
        ], 200);
    }

    private function storeLeave(Request $request, string $userId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'leave_type' => 'required|string|max:255',
            'reason' => 'nullable|string|max:500',
            'start_of_leave' => 'required|date',
            'end_of_leave' => 'required|date|after_or_equal:start_of_leave',
            'notes' => 'nullable|string|max:1000',
            'leave_address' => 'nullable|string|max:500',
            'phone_number' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $start = Carbon::parse($request->start_of_leave);
        $end = Carbon::parse($request->end_of_leave);
        $amountOfLeave = (int) $start->diffInDays($end) + 1;

        $leave = Leave::create([
            'id_user' => $userId,
            'leave_type' => $request->leave_type,
            'reason' => $request->reason,
            'start_of_leave' => $request->start_of_leave,
            'end_of_leave' => $request->end_of_leave,
            'amount_of_leave' => $amountOfLeave,
            'notes' => $request->notes,
            'leave_address' => $request->leave_address,
            'phone_number' => $request->phone_number,
            'status_pm' => 'pending',
            'status_hrd' => 'pending',
            'status_director' => 'pending',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pengajuan cuti berhasil dikirim',
            'data' => $this->formatLeaveItem($leave->load('user'), 1),
        ], 201);
    }

    private function formatLeaveItem(Leave $leave, int $no): array
    {
        return [
            'no' => $no,
            'id' => $leave->id_leave,
            'employee_name' => $leave->user->name ?? null,
            'leave_type' => $leave->leave_type,
            'reason' => $leave->reason,
            'start_of_leave' => $leave->start_of_leave->format('Y-m-d'),
            'end_of_leave' => $leave->end_of_leave->format('Y-m-d'),
            'amount_of_leave' => $leave->amount_of_leave,
            'notes' => $leave->notes,
            'leave_address' => $leave->leave_address,
            'phone_number' => $leave->phone_number,
            'display_status' => $leave->display_status,
            'status_pm' => $leave->status_pm,
            'status_hrd' => $leave->status_hrd,
            'status_director' => $leave->status_director,
            'created_at' => $leave->created_at?->toIso8601String(),
        ];
    }

    private function formatLeaveItemForApproval(Leave $leave, int $no, string $approver): array
    {
        $item = $this->formatLeaveItem($leave, $no);
        $item['approver_status'] = match ($approver) {
            'pm' => $leave->status_pm,
            'hrd' => $leave->status_hrd,
            'director' => $leave->status_director,
            default => null,
        };
        return $item;
    }
}
