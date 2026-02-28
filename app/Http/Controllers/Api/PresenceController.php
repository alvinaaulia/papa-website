<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Presence;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PresenceController extends Controller
{
    // direktur
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['status' => 'error', 'message' => 'Unauthenticated'], 401);
        }
        try {
            $query = Presence::with([
                'user' => function ($query) {
                    $query->select('id', 'name', 'email');
                }
            ])->whereHas('user', function ($query) {
                $query->where('role', 'karyawan');
            });

            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('date', [$request->start_date, $request->end_date]);
            }

            // Filter by employees
            if ($request->has('employees')) {
                $query->whereHas('user', function ($q) use ($request) {
                    $q->whereIn('name', $request->employees);
                });
            }

            $attendances = $query->orderBy('date', 'desc')
                ->orderBy('check_in', 'desc')
                ->get()
                ->map(function ($presence) {
                    return [
                        'attendance_id' => $presence->id_presence,
                        'date' => $presence->date,
                        'employee_name' => $presence->user->name,
                        'check_in_time' => $presence->check_in,
                        'check_out_time' => $presence->check_out ?? 'Not Checked Out Yet',
                        'total_hours' => $this->calculateTotalHours($presence->check_in, $presence->check_out, $presence->work_time),
                        'check_in_photo' => $presence->in_photo ? asset('storage/' . $presence->in_photo) : null,
                        'check_out_photo' => $presence->out_photo ? asset('storage/' . $presence->out_photo) : null,
                        'attendance_status' => $this->getAttendanceStatus($presence->check_in, $presence->check_out),
                        'check_in_info' => 'View Photo',
                        'check_out_info' => 'View Photo'
                    ];
                });

            return response()->json([
                'status' => 'success',
                'message' => 'Employee attendance data retrieved successfully',
                'data' => $attendances
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve attendance data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($attendanceId): JsonResponse
    {
        try {
            $attendance = Presence::with([
                'user' => function ($query) {
                    $query->select('id', 'name', 'email', 'position');
                }
            ])
                ->where('id_presence', $attendanceId)
                ->first();

            if (!$attendance) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Attendance record not found'
                ], 404);
            }

            $attendanceData = [
                'attendance_id' => $attendance->id_presence,
                'date' => $attendance->date,
                'employee' => [
                    'name' => $attendance->user->name,
                    'email' => $attendance->user->email,
                    'position' => $attendance->user->position
                ],
                'check_in' => [
                    'time' => $attendance->check_in,
                    'photo' => $attendance->in_photo ? asset('storage/' . $attendance->in_photo) : null,
                    'location' => 'Office'
                ],
                'check_out' => [
                    'time' => $attendance->check_out,
                    'photo' => $attendance->out_photo ? asset('storage/' . $attendance->out_photo) : null,
                    'location' => 'Office'
                ],
                'work_duration' => $this->calculateTotalHours($attendance->check_in, $attendance->check_out, $attendance->work_time),
                'attendance_status' => $this->getAttendanceStatus($attendance->check_in, $attendance->check_out),
                'created_at' => $attendance->created_at,
                'updated_at' => $attendance->updated_at
            ];

            return response()->json([
                'status' => 'success',
                'message' => 'Attendance details retrieved successfully',
                'data' => $attendanceData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve attendance details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function filterByDate(Request $request): JsonResponse
    {
        $validator = Presence::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $attendances = Presence::with([
                'user' => function ($query) {
                    $query->select('id', 'name', 'email');
                }
            ])
                ->whereBetween('date', [$request->start_date, $request->end_date])
                ->orderBy('date', 'desc')
                ->orderBy('check_in', 'desc')
                ->get()
                ->map(function ($presence) {
                    return [
                        'attendance_id' => $presence->id_presence,
                        'date' => $presence->date,
                        'employee_name' => $presence->user->name,
                        'check_in_time' => $presence->check_in,
                        'check_out_time' => $presence->check_out ?? 'Not Checked Out Yet',
                        'total_hours' => $this->calculateTotalHours($presence->check_in, $presence->check_out, $presence->work_time),
                        'check_in_photo' => $presence->in_photo ? asset('storage/' . $presence->in_photo) : null,
                        'check_out_photo' => $presence->out_photo ? asset('storage/' . $presence->out_photo) : null,
                        'attendance_status' => $this->getAttendanceStatus($presence->check_in, $presence->check_out),
                        'check_in_info' => 'View Photo',
                        'check_out_info' => 'View Photo'
                    ];
                });

            return response()->json([
                'status' => 'success',
                'message' => 'Filtered attendance data retrieved successfully',
                'data' => [
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'attendances' => $attendances
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to filter attendance data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function calculateTotalHours($checkIn, $checkOut, $workTime): string
    {
        if (!$checkIn || !$checkOut) {
            return '-';
        }

        if ($workTime) {
            $hours = floor($workTime / 60);
            $minutes = $workTime % 60;
            return sprintf('%dh %02dm', $hours, $minutes);
        }

        $checkInTime = \Carbon\Carbon::parse($checkIn);
        $checkOutTime = \Carbon\Carbon::parse($checkOut);

        $totalMinutes = $checkOutTime->diffInMinutes($checkInTime);
        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;

        return sprintf('%dh %02dm', $hours, $minutes);
    }

    private function getAttendanceStatus($checkIn, $checkOut): string
    {
        if (!$checkIn) {
            return 'Absent';
        }

        if (!$checkOut) {
            return 'Present (Not Checked Out)';
        }

        return 'Present';
    }

    public function getPresenceHRD(Request $request): JsonResponse
    {
        if (! $request->user()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthenticated'], 401);
        }
        try {
            $attendances = Presence::with([
                'user' => function ($query) {
                    $query->select('id', 'name', 'email');
                }
            ])->whereHas('user', function ($query) {
                $query->where('role', 'karyawan');
            })
                ->orderBy('date', 'desc')
                ->orderBy('check_in', 'desc')
                ->get()
                ->map(function ($presence) {
                    return [
                        'attendance_id' => $presence->id_presence,
                        'date' => $presence->date,
                        'employee_name' => $presence->user->name,
                        'check_in_time' => $presence->check_in,
                        'check_out_time' => $presence->check_out ?? 'Not Checked Out Yet',
                        'total_hours' => $this->calculateTotalHours($presence->check_in, $presence->check_out, $presence->work_time),
                        'check_in_photo' => $presence->in_photo ? asset('storage/' . $presence->in_photo) : null,
                        'check_out_photo' => $presence->out_photo ? asset('storage/' . $presence->out_photo) : null,
                        'attendance_status' => $this->getAttendanceStatus($presence->check_in, $presence->check_out),
                        'check_in_info' => 'View Photo',
                        'check_out_info' => 'View Photo'
                    ];
                });

            return response()->json([
                'status' => 'success',
                'message' => 'Employee attendance data retrieved successfully',
                'data' => $attendances
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve attendance data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // pm — data presensi PM yang login
    public function getPresencePM(Request $request)
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }
        $userId = $user->id;
        try {
            $query = Presence::with([
                'user' => function ($query) {
                    $query->select('id', 'name', 'email');
                }
            ])
                ->whereHas('user', function ($query) {
                    $query->whereIn('role', ['pm', 'project manager']);
                })
                ->where('id_user', $userId)
                ->orderBy('date', 'desc');

            // Filter by date range
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('date', [$request->start_date, $request->end_date]);
            }

            // Filter by search
            if ($request->has('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('date', 'like', '%' . $request->search . '%')
                        ->orWhere('check_in', 'like', '%' . $request->search . '%')
                        ->orWhere('check_out', 'like', '%' . $request->search . '%');
                });
            }

            // Pagination
            $perPage = $request->get('per_page', 10);
            $presences = $query->paginate($perPage);

            $formattedPresences = $presences->map(function ($presence) {
                return [
                    'id' => $presence->id,
                    'date' => $presence->date,
                    'check_in' => $presence->check_in,
                    'check_out' => $presence->check_out,
                    'in_photo' => $presence->in_photo,
                    'out_photo' => $presence->out_photo,
                    'status' => $presence->status,
                    'user' => $presence->user,
                    'total_hours' => $this->calculateTotalHours($presence->check_in, $presence->check_out, $presence->work_time),
                    'attendance_status' => $this->getAttendanceStatus($presence->check_in, $presence->check_out),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedPresences,
                'meta' => [
                    'current_page' => $presences->currentPage(),
                    'per_page' => $presences->perPage(),
                    'total' => $presences->total(),
                    'last_page' => $presences->lastPage(),
                ],
                'message' => 'Data presence project manager berhasil diambil'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data presence project manager: ' . $e->getMessage()
            ], 500);
        }
    }


    // Karyawan — data presensi karyawan yang login
    public function getPresenceEmployee(Request $request)
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }
        $userId = $user->id;
        try {
            $query = Presence::with([
                'user' => function ($query) {
                    $query->select('id', 'name', 'email');
                }
            ])
                ->whereHas('user', function ($query) {
                    $query->where('role', 'karyawan');
                })
                ->where('id_user', $userId)
                ->orderBy('date', 'desc');

            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('date', [$request->start_date, $request->end_date]);
            }

            if ($request->has('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('date', 'like', '%' . $request->search . '%')
                        ->orWhere('check_in', 'like', '%' . $request->search . '%')
                        ->orWhere('check_out', 'like', '%' . $request->search . '%');
                });
            }

            $perPage = $request->get('per_page', 10);
            $presences = $query->paginate($perPage);
            $formattedPresences = $presences->map(function ($presence) {
                return [
                    'id' => $presence->id,
                    'date' => $presence->date,
                    'check_in' => $presence->check_in,
                    'check_out' => $presence->check_out,
                    'in_photo' => $presence->in_photo,
                    'out_photo' => $presence->out_photo,
                    'status' => $presence->status,
                    'user' => $presence->user,
                    'total_hours' => $this->calculateTotalHours($presence->check_in, $presence->check_out, $presence->work_time),
                    'attendance_status' => $this->getAttendanceStatus($presence->check_in, $presence->check_out),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedPresences,
                'meta' => [
                    'current_page' => $presences->currentPage(),
                    'per_page' => $presences->perPage(),
                    'total' => $presences->total(),
                    'last_page' => $presences->lastPage(),
                ],
                'message' => 'Data presence karyawan berhasil diambil'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data presence karyawan: ' . $e->getMessage()
            ], 500);
        }
    }
}
