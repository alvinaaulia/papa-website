<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DailyActivityController extends Controller
{
    public function index(Request $request)
    {
        $data = DailyActivity::with(['user'])
            ->get()
            ->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'id_user' => $activity->id_user,
                    'name' => $activity->name,
                    'description' => $activity->description,
                    'created_at' => $activity->created_at,
                    'updated_at' => $activity->updated_at,
                    'user' => $activity->user ? [
                        'id' => $activity->user->id,
                        'name' => $activity->user->name,
                        'email' => $activity->user->email,
                    ] : null
                ];
            });

        return response()->json([
            'status' => 'success',
            'message' => 'Daily activities data retrieved successfully',
            'data' => $data,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_user' => 'required|uuid|exists:users,id',
            'name' => 'required|string|max:150',
            'description' => 'required|string',
            'date' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $activity = DailyActivity::create([
            'id_user' => $request->input('id_user'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'date' => $request->input('date')
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Daily activity created successfully',
            'data' => $activity,
        ], 201);
    }

    public function show($id)
    {
        $activity = DailyActivity::with(['user'])
            ->where('id', $id)
            ->first();

        if (!$activity) {
            return response()->json([
                'status' => 'error',
                'message' => 'Daily activity not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Daily activity data retrieved successfully',
            'data' => [
                'id' => $activity->id,
                'id_user' => $activity->id_user,
                'name' => $activity->name,
                'description' => $activity->description,
                'created_at' => $activity->created_at,
                'updated_at' => $activity->updated_at,
                'user' => $activity->user ? [
                    'id' => $activity->user->id,
                    'name' => $activity->user->name,
                    'email' => $activity->user->email,
                ] : null
            ],
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_user' => 'sometimes|uuid|exists:users,id',
            'name' => 'sometimes|string|max:150',
            'description' => 'sometimes|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $activity = DailyActivity::find($id);

        if (!$activity) {
            return response()->json([
                'status' => 'error',
                'message' => 'Daily activity not found',
            ], 404);
        }

        $activity->update($request->only(['id_user', 'name', 'description']));

        return response()->json([
            'status' => 'success',
            'message' => 'Daily activity updated successfully',
            'data' => $activity,
        ], 200);
    }

    public function destroy($id)
    {
        $activity = DailyActivity::find($id);

        if (!$activity) {
            return response()->json([
                'status' => 'error',
                'message' => 'Daily activity not found',
            ], 404);
        }

        $activity->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Daily activity deleted successfully',
        ], 200);
    }

    //employee daily activities
    public function EmployeeListDaily(Request $request)
    {
        $data = DailyActivity::with(['user'])
            ->get()
            ->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'id_user' => $activity->id_user,
                    'name' => $activity->name,
                    'description' => $activity->description,
                    'created_at' => $activity->created_at,
                    'updated_at' => $activity->updated_at,
                    'user' => $activity->user ? [
                        'id' => $activity->user->id,
                        'name' => $activity->user->name,
                        'email' => $activity->user->email,
                    ] : null
                ];
            });

        return response()->json([
            'status' => 'success',
            'message' => 'Daily activities data retrieved successfully',
            'data' => $data,
        ], 200);
    }

    public function EmployeeDailyDetail($id)
    {
        $activity = DailyActivity::with(['user'])
            ->where('id', $id)
            ->first();

        if (!$activity) {
            return response()->json([
                'status' => 'error',
                'message' => 'Daily activity not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Daily activity data retrieved successfully',
            'data' => [
                'id' => $activity->id,
                'id_user' => $activity->id_user,
                'name' => $activity->name,
                'description' => $activity->description,
                'created_at' => $activity->created_at,
                'updated_at' => $activity->updated_at,
                'user' => $activity->user ? [
                    'id' => $activity->user->id,
                    'name' => $activity->user->name,
                    'email' => $activity->user->email,
                ] : null
            ],
        ], 200);
    }
}
