<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyActivityProject;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DailyActivityProjectController extends Controller
{
    public function index(Request $request)
    {
        $data = DailyActivityProject::with(['user'])
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

        $activity = DailyActivityProject::create([
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
        $activity = DailyActivityProject::with(['user'])
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

        $activity = DailyActivityProject::find($id);

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

    public function generatePDF(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'employee_id' => 'sometimes|uuid|exists:users,id',
                'project_id' => 'sometimes|uuid|exists:projects,id_project'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Query data daily activities dengan filter
            $query = DailyActivityProject::with(['user', 'project']);

            // Filter by date range
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $query->whereBetween('date', [$startDate, $endDate]);

            // Filter by employee
            if ($request->has('employee_id')) {
                $query->where('id_user', $request->input('employee_id'));
            }

            // Filter by project
            if ($request->has('project_id') && method_exists($query->getModel(), 'project')) {
                $query->where('id_project', $request->input('project_id'));
            }

            // Get data dengan urutan tanggal
            $activities = $query->orderBy('date', 'asc')->get();

            // Format data untuk PDF
            $reportData = [
                'activities' => $activities,
                'from_date' => Carbon::parse($startDate)->format('d/m/Y'),
                'end_date' => Carbon::parse($endDate)->format('d/m/Y'),
                'employee_name' => $activities->first()->user->name ?? 'Anonymous',
                'employee_nip' => $activities->first()->user->profiles->nip ?? '111111111',
                'project_name' => $activities->first()->project->name ?? 'Bentuyun',
                'project_manager' => $activities->first()->project->project_manager ?? 'Septian Iqbal Pratama',
                'generated_at' => Carbon::now()->format('d F Y'),
            ];

            return view('direktur.pdf-daily-activity', $reportData);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to generate PDF report',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getReportData(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'employee_id' => 'sometimes|uuid|exists:users,id',
                'project_id' => 'sometimes|uuid|exists:projects,id_project'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $query = DailyActivityProject::with(['user', 'project']);

            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $query->whereBetween('date', [$startDate, $endDate]);

            // Filter by employee
            if ($request->has('employee_id')) {
                $query->where('id_user', $request->input('employee_id'));
            }

            // Filter by project
            if ($request->has('project_id') && method_exists($query->getModel(), 'project')) {
                $query->where('id_project', $request->input('project_id'));
            }

            // Get data dengan urutan tanggal
            $activities = $query->orderBy('date', 'asc')->get();

            // Format response
            $formattedActivities = $activities->map(function ($activity, $index) {
                return [
                    'no' => $index + 1,
                    'date' => Carbon::parse($activity->date)->format('d-m-Y'),
                    'description' => $activity->description,
                    'employee_name' => $activity->user->name ?? 'Unknown',
                    'project_name' => $activity->project->name ?? 'No Project'
                ];
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Daily activities report data retrieved successfully',
                'data' => [
                    'activities' => $formattedActivities,
                    'total_activities' => $activities->count(),
                    'date_range' => [
                        'start_date' => Carbon::parse($startDate)->format('d/m/Y'),
                        'end_date' => Carbon::parse($endDate)->format('d/m/Y')
                    ],
                    'summary' => [
                        'employee_name' => $activities->first()->user->name ?? 'Anonymous',
                        'project_name' => $activities->first()->project->name ?? 'Bentuyun'
                    ]
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve report data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
