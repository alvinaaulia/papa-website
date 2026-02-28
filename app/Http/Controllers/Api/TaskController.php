<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $data = Task::with(['user', 'project'])
            ->get()
            ->map(function ($task) {
                return [
                    'id_task' => $task->id_task,
                    'description' => $task->description,
                    'deadline' => $task->deadline,
                    'priority' => $task->priority,
                    'category' => $task->category,
                    'status' => $task->status,
                    'score' => $task->score,
                    'employee' => $task->user ? [
                        'id' => $task->user->id,
                        'name' => $task->user->name,
                    ] : null,
                    'project' => $task->project ? [
                        'id_project' => $task->project->id_project,
                        'name' => $task->project->name,
                    ] : null
                ];
            });

        return response()->json([
            'status' => 'success',
            'message' => 'Tasks data retrieved successfully',
            'data' => $data,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_project' => 'required|uuid|exists:projects,id_project',
            'id_user' => 'required|uuid|exists:users,id',
            'description' => 'required|string',
            'category' => 'required|string',

            'deadline' => 'required|date',
            'score' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $task = Task::create([
            'id_project' => $request->input('id_project'),
            'id_user' => $request->input('id_user'),
            'description' => $request->input('description'),
            'category' => $request->input('category'),
            'priority' => $request->input('priority'),
            'deadline' => $request->input('deadline'),
            'score' => $request->input('score')
        ]);

        $task->load(['user', 'project']);

        return response()->json([
            'status' => 'success',
            'message' => 'Task created successfully',
            'data' => [
                'id_task' => $task->id_task,
                'description' => $task->description,
                'category' => $task->category,
                'priority' => $task->priority,
                'deadline' => $task->deadline,
                'status' => $task->status,
                'score' => $task->score,
                'employee' => $task->user ? [
                    'id' => $task->user->id,
                    'name' => $task->user->name,
                ] : null,
                'project' => $task->project ? [
                    'id_project' => $task->project->id_project,
                    'name' => $task->project->name,
                ] : null
            ],
        ], 201);
    }

    public function getOptions()
    {
        $employees = User::select('id', 'name')->get();
        $projects = Project::select('id_project', 'name')->get();
        $priorities = ['low', 'medium', 'high'];
        $categories = ['Development', 'Design', 'Testing', 'Documentation'];

        return response()->json([
            'status' => 'success',
            'message' => 'Options retrieved successfully',
            'data' => [
                'employees' => $employees,
                'projects' => $projects,
                'priorities' => $priorities,
                'categories' => $categories
            ]
        ], 200);
    }
}
