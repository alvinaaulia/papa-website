<?php

namespace App\Http\Controllers\Api\DataMaster;

use App\Models\MasterDocument;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MasterDocumentController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $masterDocument = MasterDocument::all();

            Log::info('Master document data:', ['count' => $masterDocument->count()]);

            return response()->json([
                'success' => true,
                'data' => $masterDocument,
                'message' => 'Data master document berhasil diambil'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching master document: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data master document',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'document_name' => 'required|string|max:255',
            'status' => 'required|in:required,optional'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $document = MasterDocument::create([
                'document_name' => $request->document_name,
                'status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'data' => $document,
                'message' => 'Dokumen berhasil ditambahkan'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan dokumen: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $masterDocument = MasterDocument::find($id);

            if (!$masterDocument) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data master document tidak ditemukan'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'document_name' => 'required|string|max:255',
                'status' => 'required|in:required,optional'
            ], [
                'document_name.required' => 'Nama document wajib diisi',
                'status.required' => 'Status wajib diisi'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $masterDocument->update([
                'document_name' => $request->document_name,
                'status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'data' => $masterDocument,
                'message' => 'Master document berhasil diupdate'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate master document',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $masterDocument = MasterDocument::find($id);

            if (!$masterDocument) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data master document tidak ditemukan'
                ], 404);
            }

            $masterDocument->delete();

            return response()->json([
                'success' => true,
                'message' => 'Master document berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus master document',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
