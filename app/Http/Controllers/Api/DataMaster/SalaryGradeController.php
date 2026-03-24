<?php

namespace App\Http\Controllers\Api\DataMaster;

use App\Http\Controllers\Controller;
use App\Models\SalaryGrade;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SalaryGradeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $query = SalaryGrade::query();

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $grades = $query->orderBy('min_score')->get();

            return response()->json([
                'success' => true,
                'data' => $grades,
                'message' => 'Data tier/grade gaji berhasil diambil',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching salary grades: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data tier/grade gaji',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'grade_code' => 'required|string|max:30|unique:salary_grades,grade_code',
            'grade_name' => 'required|string|max:100',
            'min_score' => 'required|integer|min:0|max:100',
            'max_score' => 'required|integer|min:0|max:100|gte:min_score',
            'base_salary' => 'required|numeric|between:0,999999999999.99',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        if (
            $request->status === 'active' &&
            $this->hasOverlappingRange((int) $request->min_score, (int) $request->max_score)
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Rentang skor bertabrakan dengan tier/grade aktif lainnya',
            ], 422);
        }

        try {
            $grade = SalaryGrade::create([
                'grade_code' => strtoupper(trim((string) $request->grade_code)),
                'grade_name' => trim((string) $request->grade_name),
                'min_score' => $request->min_score,
                'max_score' => $request->max_score,
                'base_salary' => $request->base_salary,
                'status' => $request->status,
                'description' => $request->description,
            ]);

            return response()->json([
                'success' => true,
                'data' => $grade,
                'message' => 'Tier/grade gaji berhasil ditambahkan',
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating salary grade: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan tier/grade gaji',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        $grade = SalaryGrade::find($id);

        if (!$grade) {
            return response()->json([
                'success' => false,
                'message' => 'Tier/grade gaji tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'grade_code' => 'required|string|max:30|unique:salary_grades,grade_code,' . $grade->id_salary_grade . ',id_salary_grade',
            'grade_name' => 'required|string|max:100',
            'min_score' => 'required|integer|min:0|max:100',
            'max_score' => 'required|integer|min:0|max:100|gte:min_score',
            'base_salary' => 'required|numeric|between:0,999999999999.99',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        if (
            $request->status === 'active' &&
            $this->hasOverlappingRange(
                (int) $request->min_score,
                (int) $request->max_score,
                (int) $grade->id_salary_grade
            )
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Rentang skor bertabrakan dengan tier/grade aktif lainnya',
            ], 422);
        }

        try {
            $grade->update([
                'grade_code' => strtoupper(trim((string) $request->grade_code)),
                'grade_name' => trim((string) $request->grade_name),
                'min_score' => $request->min_score,
                'max_score' => $request->max_score,
                'base_salary' => $request->base_salary,
                'status' => $request->status,
                'description' => $request->description,
            ]);

            return response()->json([
                'success' => true,
                'data' => $grade,
                'message' => 'Tier/grade gaji berhasil diperbarui',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error updating salary grade: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui tier/grade gaji',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $grade = SalaryGrade::find($id);

            if (!$grade) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tier/grade gaji tidak ditemukan',
                ], 404);
            }

            $grade->delete();

            return response()->json([
                'success' => true,
                'message' => 'Tier/grade gaji berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting salary grade: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus tier/grade gaji',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function resolve(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'score' => 'required|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $score = (float) $request->score;

        $grade = SalaryGrade::where('status', 'active')
            ->where('min_score', '<=', $score)
            ->where('max_score', '>=', $score)
            ->orderBy('min_score')
            ->first();

        if (!$grade) {
            return response()->json([
                'success' => false,
                'message' => 'Belum ada tier/grade aktif yang cocok dengan skor tersebut',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $grade,
            'message' => 'Tier/grade berhasil ditentukan',
        ], 200);
    }

    private function hasOverlappingRange(int $minScore, int $maxScore, ?int $ignoreId = null): bool
    {
        $query = SalaryGrade::where('status', 'active')
            ->where(function ($q) use ($minScore, $maxScore) {
                $q->whereBetween('min_score', [$minScore, $maxScore])
                    ->orWhereBetween('max_score', [$minScore, $maxScore])
                    ->orWhere(function ($inner) use ($minScore, $maxScore) {
                        $inner->where('min_score', '<=', $minScore)
                            ->where('max_score', '>=', $maxScore);
                    });
            });

        if ($ignoreId) {
            $query->where('id_salary_grade', '!=', $ignoreId);
        }

        return $query->exists();
    }
}
