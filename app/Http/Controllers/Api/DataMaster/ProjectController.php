<?php

namespace App\Http\Controllers\Api\DataMaster;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $data = Project::withCount(['tasks as total_tasks'])
            ->with([
                'tasks' => function ($query) {
                    $query->select('id_task', 'id_project', 'description', 'priority', 'score', 'deadline');
                }
            ])
            ->get()
            ->map(function ($project) {
                return [
                    'id_project' => $project->id_project,
                    'name' => $project->name,
                    'deadline' => $project->deadline,
                    'project_manager' => $project->project_manager,
                    'status' => $project->status,
                    'total_tasks' => $project->total_tasks,
                    'tasks' => $project->tasks->map(function ($task) {
                        return [
                            'id_task' => $task->id_task,
                            'description' => $task->description,
                            'priority' => $task->priority,
                            'score' => $task->score,
                            'deadline' => $task->deadline,
                        ];
                    })
                ];
            });

        return response()->json([
            'status' => 'success',
            'message' => 'Project data retrieved successfully',
            'data' => $data,
        ], 200);
    }

    public function getDailyActivities(Request $request)
    {
        $data = Project::withCount(['tasks as total_tasks'])
            ->with([
                'tasks' => function ($query) {
                    $query->select('id_task', 'id_project', 'description', 'priority', 'score', 'deadline');
                }
            ])
            ->get()
            ->map(function ($project) {
                return [
                    'id_project' => $project->id_project,
                    'date' => $project->created_at->toDateString(),
                    'tasks' => $project->tasks->map(function ($task) {
                        return [
                            'id_task' => $task->id_task,
                            'description' => $task->description,
                            'priority' => $task->priority,
                            'score' => $task->score,
                            'deadline' => $task->deadline,
                        ];
                    })
                ];
            });

        return response()->json([
            'status' => 'success',
            'message' => 'Project data retrieved successfully',
            'data' => $data,
        ], 200);
    }

    public function DetailDailyActivities($projectId)
    {
        $project = Project::with([
            'tasks' => function ($query) {
                $query->select('id_task', 'id_project', 'id_user', 'description', 'priority', 'score', 'deadline');
            }
        ])
            ->where('id_project', $projectId)
            ->first();

        if (!$project) {
            return response()->json([
                'status' => 'error',
                'message' => 'Project not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Project data retrieved successfully',
            'data' => $project,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'deadline' => 'required|date',
            'project_manager' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $project = Project::create([
            'name' => $request->input('name'),
            'deadline' => $request->input('deadline'),
            'project_manager' => $request->input('project_manager')
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Project created successfully',
            'data' => $project,
        ], 201);
    }

    public function show($projectId)
    {
        $project = Project::with([
            'tasks' => function ($query) {
                $query->select('id_task', 'id_project', 'id_user', 'description', 'priority', 'score', 'deadline');
            }
        ])
            ->where('id_project', $projectId)
            ->first();

        if (!$project) {
            return response()->json([
                'status' => 'error',
                'message' => 'Project not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Project data retrieved successfully',
            'data' => $project,
        ], 200);
    }

    public function dailyActivities(Request $request)
    {
        $data = Project::with([
            'tasks' => function ($query) {
                $query->select('id_task', 'id_project', 'id_user', 'description', 'priority', 'score', 'deadline')
                    ->whereDate('deadline', today());
            }
        ])
            ->whereHas('tasks', function ($query) {
                $query->whereDate('deadline', today());
            })
            ->get()
            ->map(function ($project) {
                return [
                    'id_project' => $project->id_project,
                    'name' => $project->name,
                    'project_manager' => $project->project_manager,
                    'status' => $project->status,
                    'daily_tasks' => $project->tasks->map(function ($task) {
                        return [
                            'id_task' => $task->id_task,
                            'description' => $task->description,
                            'priority' => $task->priority,
                            'score' => $task->score,
                            'deadline' => $task->deadline,
                        ];
                    })
                ];
            });

        return response()->json([
            'status' => 'success',
            'message' => 'Daily project activities retrieved successfully',
            'data' => $data,
        ], 200);
    }
}
