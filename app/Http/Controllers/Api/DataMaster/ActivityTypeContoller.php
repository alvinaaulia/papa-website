<?php

namespace App\Http\Controllers\Api\DataMaster;

use App\Http\Controllers\Controller;
use App\Models\ActivityType;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ActivityTypeContoller extends Controller
{
    public function index(Request $request)
    {
        $data = ActivityType::all();

        return response()->json([
            'status' => 'success',
            'message' => 'Activity Type data retrieved successfully',
            'data' => $data,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $activityType = ActivityType::create([
            'name' => $request->input('name'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Activity Type created successfully',
            'data' => $activityType,
        ], 201);

    }

    public function update($activityTypesId)
    {
        $data = ActivityType::where('id', $activityTypesId)->first();

        $validator = Validator::make(request()->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data->update([
            'name' => request()->input('name'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Activity Type updated successfully',
            'data' => $data,
        ], 200);
    }

    public function destroy($activityTypesId)
    {
        $data = ActivityType::where('id', $activityTypesId)->first();

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Activity Type not found',
            ], 404);
        }

        $data->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Activity Type deleted successfully',
        ], 200);
    }
}
