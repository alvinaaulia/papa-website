<?php

namespace App\Http\Controllers\Api\DataMaster;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $data = User::where('role', 'karyawan')->with('profiles')->get();

        $search = $request->input('search.value', '');

        if (!empty($search)) {
            $data->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhereHas('profiles', function ($q) use ($search) {
                        $q->where('phone', 'like', '%' . $search . '%')
                            ->orWhere('address', 'like', '%' . $search . '%');
                    });
            });
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Employee data retrieved successfully',
            'data' => $data,
        ], 200);
    }

    public function show($UserId)
    {
        $data = User::where('id', $UserId)->with('profiles')->first();

        if (!$data) {
            return response()->json([
                'status' => 'error',
                'message' => 'Employee not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Employee data retrieved successfully',
            'data' => $data,
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|in:karyawan,project manager,hrd',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'role' => $request->input('role'),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->password),
        ]);

        Profile::create([
            'id_user' => $user->id,
            'name' => $user->name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Employee created successfully',
            'data' => $user
        ], 201);
    }
}
