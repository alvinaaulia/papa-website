<?php

namespace App\Http\Controllers\Api\DataMaster;

use App\Models\MasterPosition;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MasterPositionController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $masterPositions = MasterPosition::all();

            Log::info('Master positions data:', ['count' => $masterPositions->count()]);

            return response()->json([
                'success' => true,
                'data' => $masterPositions,
                'message' => 'Data master position berhasil diambil'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching master positions: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data master position',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'position_name' => 'required|string|max:100',
            'status' => 'required|in:onsite,online,hybrid'
        ], [
            'position_name.required' => 'Nama jabatan wajib diisi',
            'position_name.max' => 'Nama jabatan maksimal 100 karakter',
            'status.required' => 'Status wajib diisi',
            'status.in' => 'Status harus onsite, online, atau hybrid'
        ]);

        $validator->after(function ($validator) use ($request) {
            $existingPosition = MasterPosition::where('position_name', $request->position_name)
                ->where('status', $request->status)
                ->first();

            if ($existingPosition) {
                $statusText = $this->getStatusText($request->status);
                $validator->errors()->add(
                    'position_name',
                    "Jabatan '{$request->position_name}' dengan status {$statusText} sudah ada"
                );
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $masterPosition = MasterPosition::create([
                'position_name' => $request->position_name,
                'status' => $request->status
            ]);

            DB::commit();

            Log::info('Master position created:', ['id' => $masterPosition->id_master_position]);

            return response()->json([
                'success' => true,
                'data' => $masterPosition,
                'message' => 'Jabatan berhasil ditambahkan'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating master position: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan jabatan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $masterPosition = MasterPosition::find($id);

            if (!$masterPosition) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data jabatan tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $masterPosition,
                'message' => 'Data jabatan berhasil diambil'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data jabatan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $masterPosition = MasterPosition::find($id);

            if (!$masterPosition) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data jabatan tidak ditemukan'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'position_name' => 'required|string|max:100',
                'status' => 'required|in:onsite,online,hybrid'
            ], [
                'position_name.required' => 'Nama jabatan wajib diisi',
                'position_name.max' => 'Nama jabatan maksimal 100 karakter',
                'status.required' => 'Status wajib diisi',
                'status.in' => 'Status harus onsite, online, atau hybrid'
            ]);

            $validator->after(function ($validator) use ($request, $id) {
                $existingPosition = MasterPosition::where('position_name', $request->position_name)
                    ->where('status', $request->status)
                    ->where('id_master_position', '!=', $id)
                    ->first();

                if ($existingPosition) {
                    $statusText = $this->getStatusText($request->status);
                    $validator->errors()->add(
                        'position_name',
                        "Jabatan '{$request->position_name}' dengan status {$statusText} sudah ada"
                    );
                }
            });

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $masterPosition->update([
                'position_name' => $request->position_name,
                'status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'data' => $masterPosition,
                'message' => 'Jabatan berhasil diupdate'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate jabatan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $masterPosition = MasterPosition::find($id);

            if (!$masterPosition) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data jabatan tidak ditemukan'
                ], 404);
            }

            if ($masterPosition->positions()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus jabatan karena masih digunakan di data position'
                ], 422);
            }

            $masterPosition->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jabatan berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus jabatan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper method untuk mendapatkan text status
     */
    private function getStatusText($status): string
    {
        $statusMap = [
            'onsite' => 'On Site',
            'online' => 'Online',
            'hybrid' => 'Hybrid'
        ];

        return $statusMap[$status] ?? $status;
    }
}
