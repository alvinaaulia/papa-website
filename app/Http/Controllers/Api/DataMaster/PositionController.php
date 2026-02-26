<?php

namespace App\Http\Controllers\Api\DataMaster;

use App\Http\Controllers\Controller;
use App\Models\Position;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PositionController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $positions = Position::with('masterPosition', 'user')->get();

            return response()->json([
                'success' => true,
                'data' => $positions,
                'message' => 'Data position berhasil diambil'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data position',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id_master_position' => 'required|exists:master_positions,id_master_position',
            'id_user' => [
                'required',
                'uuid',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->whereNotNull('id');
                })
            ],
            'entry_date' => [
                'required',
                'date',
                // Validasi untuk mencegah duplikasi posisi untuk user yang sama pada tanggal yang sama
                Rule::unique('positions')->where(function ($query) use ($request) {
                    return $query->where('id_user', $request->id_user)
                                 ->where('id_master_position', $request->id_master_position);
                })
            ]
        ], [
            'id_master_position.required' => 'Master position wajib diisi',
            'id_master_position.exists' => 'Master position tidak valid',
            'id_user.required' => 'User wajib diisi',
            'id_user.uuid' => 'Format user ID tidak valid',
            'id_user.exists' => 'User tidak ditemukan',
            'entry_date.required' => 'Tanggal masuk wajib diisi',
            'entry_date.date' => 'Format tanggal tidak valid',
            'entry_date.unique' => 'User sudah memiliki posisi ini pada tanggal tersebut'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $position = Position::create([
                'id_master_position' => $request->id_master_position,
                'id_user' => $request->id_user,
                'entry_date' => $request->entry_date
            ]);

            $position->load(['masterPosition', 'user']);

            return response()->json([
                'success' => true,
                'data' => $position,
                'message' => 'Position berhasil dibuat'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat position',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $position = Position::with(['masterPosition', 'user'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $position,
                'message' => 'Data position berhasil diambil'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data position tidak ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id_master_position' => 'required|exists:master_positions,id_master_position',
            'entry_date' => [
                'required',
                'date',
                // Validasi unique dengan mengabaikan record saat ini
                Rule::unique('positions')->where(function ($query) use ($request) {
                    return $query->where('id_user', $request->id_user)
                                 ->where('id_master_position', $request->id_master_position);
                })->ignore($id, 'id_position')
            ]
        ], [
            'id_master_position.required' => 'Master position wajib diisi',
            'id_master_position.exists' => 'Master position tidak valid',
            'entry_date.required' => 'Tanggal masuk wajib diisi',
            'entry_date.date' => 'Format tanggal tidak valid',
            'entry_date.unique' => 'User sudah memiliki posisi ini pada tanggal tersebut'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $position = Position::findOrFail($id);

            $position->update([
                'id_master_position' => $request->id_master_position,
                'entry_date' => $request->entry_date
            ]);

            $position->load(['masterPosition', 'user']);

            return response()->json([
                'success' => true,
                'data' => $position,
                'message' => 'Position berhasil diupdate'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate position',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $position = Position::findOrFail($id);
            $position->delete();

            return response()->json([
                'success' => true,
                'message' => 'Position berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus position',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
