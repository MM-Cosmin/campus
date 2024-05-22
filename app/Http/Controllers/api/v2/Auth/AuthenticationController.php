<?php

namespace App\Http\Controllers\api\v2\Auth;

use App\User;
use App\SmStaff;
use App\SmParent;
use App\SmStudent;
use App\SmFeesAssign;
use App\SmMarksGrade;
use App\ApiBaseMethod;
use App\SmExamSchedule;
use App\SmNotification;
use App\SmGeneralSettings;
use App\SmStudentDocument;
use App\SmStudentTimeline;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\SmFeesAssignDiscount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\SmStudentRegistrationField;
use App\SmHomework;
use App\SmVehicle;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\ParentRegistration\Entities\SmStudentRegistration;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'email' => "required",
            'password' => "required",
        ]);


        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $data = [];
                $data['unread_notifications'] = SmNotification::where('user_id', $user->id)->where('is_read', 0)->count();
                $data['user'] = [
                    'id' => $user->id,
                    'full_name' => $user->full_name,
                    'phone_number' => $user->phone_number,
                    'role_id' => $user->role_id,
                    'school_id' => $user->school_id,
                    'is_administrator' => $user->is_administrator,
                    'rtl_ltl' => $user->rtl_ltl,
                ];
                if ($user->role_id == 2) {
                    $student = $user->student;
                    if (!$student) {
                        throw ValidationException::withMessages(['message' => 'Student not found']);
                    }
                    $data['user'] += [
                        'student_id' => $user->student->id
                    ];
                } else if ($user->role_id == 3) {
                    $parent = $user->parent;
                    if (!$parent) {
                        throw ValidationException::withMessages(['message' => 'Student not found']);
                    }
                    $data['user'] += [
                        'parent_id' => $parent->id
                    ];
                } else {
                    $staff = $user->staff;
                    if (!$staff) {
                        throw ValidationException::withMessages(['message' => 'Staff not found']);
                    }
                    $data['user'] += [
                        'staff_id' => $staff->id
                    ];
                }
                $data['TTL_RTL_status'] = '1=RTL,2=TTL';
                $old_token = DB::table('oauth_access_tokens')->where('user_id', $user->id)->delete();
                $accessToken = $user->createToken('AuthToken')->accessToken;
                $token = $accessToken;
                $data['accessToken'] = 'Bearer ' . $token;

                $response = [
                    'success' => true,
                    'data'    => $data,
                    'message' => 'Logged in successfully.',
                ];

                return response()->json($response, 200);
            } else {
                $response = [
                    'success' => false,
                    'data'    => null,
                    'message' => 'These credentials do not match our records.',
                ];
                return response()->json($response, 401);
                // throw ValidationException::withMessages(['data' => $response]);
            }
        } else {
            $response = [
                'success' => false,
                'data'    => null,
                'message' => 'These credentials do not match our records.',
            ];
            return response()->json($response, 401);
            // throw ValidationException::withMessages(['data' => $response]);
            // throw ValidationException::withMessages(['message' => 'These credentials do not match our records']);
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            throw ValidationException::withMessages(['message' => 'These credentials do not match our records']);
        }
        $user->device_token = null;
        $user->save();
        $user->token()->revoke();
        $response = [
            'success' => true,
            'data'    => null,
            'message' => 'Successfully logged out.',
        ];
        return response()->json($response, 200);
        throw ValidationException::withMessages($response);
        
    }

    public function DemoUser(Request $request, $role_id)
    {

        $user = User::where('role_id', $role_id)->first();
        if ($user) {
            Auth::login($user);
            $user = Auth::user();
            $old_token = DB::table('oauth_access_tokens')->where('user_id', $user->id)->delete();
            $accessToken = $user->createToken('AuthToken')->accessToken;
            $token = $accessToken;
            $data['accessToken'] = 'Bearer ' . $token;
            $data['unread_notifications'] = SmNotification::where('user_id', $user->id)->where('is_read', 0)->count();
            $data['user'] = [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'phone_number' => $user->phone_number,
                'role_id' => $user->role_id,
                'school_id' => $user->school_id,
                'is_administrator' => $user->is_administrator,
                'rtl_ltl' => $user->rtl_ltl,
            ];
            if ($user->role_id == 2) {
                $data['user'] += [
                    'student_id' => @$user->student->id
                ];
            } else if ($user->role_id == 3) {
                $data['user'] += [
                    'parent_id' => @$user->parent->id
                ];
            } else {
                $data['user'] += [
                    'staff_id' => @$user->staff->id
                ];
            }
            $data['TTL_RTL_status'] = '1=RTL,2=TTL';

            $response = [
                'success' => true,
                'data'    => $data,
                'message' => 'Logged in successfully.',
            ];

            return response()->json($response, 200);
        } else {
            throw ValidationException::withMessages(['message' => 'These credentials do not match our records']);
        }
    }

    public function emailVerify(Request $request)
    {
        $request->validate([
            'email' => 'required'
        ]);

        $emailCheck = User::select('*')->where('email', $request->email)->first();
        if ($emailCheck == "") {
            throw ValidationException::withMessages(['message' => 'Something went wrong']);
        } else {
            $admissionNumber = '';
            $student = SmStudent::where('user_id', $emailCheck->id)->first();
            if ($student) {
                if ($emailCheck->role_id == 2) {
                    $admissionNumber = $student->admission_number;
                }
            }
            $random = Str::random(32);
            $user = User::where('email', $request->email)->first();
            $user->random_code = $random;
            $user->save();

            $data['user_email'] = $request->email;
            $data['id'] = $emailCheck->id;
            $data['random'] = $user->random_code;
            $data['role_id'] = $user->role_id;
            $data['admission_number'] = $admissionNumber;
            @send_mail($user->email, $user->full_name, "password_reset", $data);
            $response = [
                'success' => true,
                'data'    => $data,
                'message' => 'Please check your email.',
            ];
            return response()->json($response, 200);
        }
    }

    public function studentProfile(Request $request, $id)
    {
        $data['student_detail'] = SmStudent::with(['bloodGroup' => function ($q1) {
            $q1->select('id', 'base_setup_name as bloodgroup_name');
        }, 'religion' => function ($q2) {
            $q2->select('id', 'base_setup_name as religion_name');
        }, 'parents' => function ($q3) {
            $q3->select('id', 'fathers_name', 'fathers_mobile', 'fathers_occupation', 'fathers_photo', 'mothers_name', 'mothers_mobile', 'mothers_occupation', 'mothers_photo', 'guardians_name', 'guardians_mobile', 'guardians_email', 'guardians_occupation', 'guardians_relation', 'guardians_photo');
        }, 'route' => function ($q4) {
            $q4->select('id', 'title');
        }, 'vehicle' => function ($q5) {
            $q5->select('id', 'vehicle_no');
        }, 'dormitory' => function ($q6) {
            $q6->select('id', 'dormitory_name');
        }, 'studentDocument' => function ($q7) {
            $q7->select('id', 'title', 'file');
        }])
            ->select('student_photo','first_name', 'last_name', 'admission_no', 'date_of_birth', 'age', 'mobile', 'email', 'current_address', 'permanent_address', 'bloodgroup_id', 'religion_id', 'parent_id', 'route_list_id', 'vechile_id', 'dormitory_id', 'height', 'weight', 'national_id_no', 'local_id_no', 'bank_name', 'bank_account_no')->findOrFail($id);
        $data['driver'] = SmVehicle::select('sm_staffs.full_name', 'sm_staffs.mobile')->where('sm_vehicles.id', '=', $data['student_detail']->vechile_id)
            ->join('sm_staffs', 'sm_staffs.id', '=', 'sm_vehicles.driver_id')
            ->first();

         $data['show_permission'] = SmStudentRegistrationField::select('field_name', 'is_show')->where('school_id', app('school')->id)->get();

         $response = [
            'success' => true,
            'data'    => $data,
        ];
         return response()->json($response, 200);

    }

    public function generalSettings()
    {
        $data['system_settings'] = SmGeneralSettings::where('school_id', app('school')->id)->first();
        if ($data['system_settings']) {
            $response = [
                'success' => true,
                'data'    => $data,
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'success' => true,
                'data'    => $data,
            ];
            return response()->json($response, 404);
            // throw ValidationException::withMessages(['message' => 'Data not found.']);
        }
    }

    public function studentProfileEdit($id)
    {
        $data['student_detail'] = SmStudent::select('student_photo','first_name', 'last_name', 'admission_no', 'date_of_birth', 'age', 'mobile', 'email', 'current_address')->findOrFail($id);

        $data['edit_permission'] = SmStudentRegistrationField::select('field_name', 'student_edit')->where('school_id', app('school')->id)->get();

        $response = [
            'success' => true,
            'data'    => $data
        ];
        return response()->json($response, 200);
    }

    public function studentProfileUpdate(Request $request)
    {
        $student = SmStudent::find($request->id);
        $student_file_destination = 'public/uploads/student/';
        $parent = SmParent::find($student->parent_id);
        if ($request->filled('first_name')) {
            $student->first_name = $request->first_name;
        }
        if ($request->filled('last_name')) {
            $student->last_name = $request->last_name;
        }
        if ($request->filled('first_name') && $request->filled('last_name')) {
            $student->full_name = $request->first_name . ' ' . $request->last_name;
        }
        if ($request->filled('date_of_birth')) {
            $student->date_of_birth = date('Y-m-d', strtotime($request->date_of_birth));
        }
        if ($request->filled('photo')) {
            $student->student_photo = fileUpdate($parent->student_photo, $request->photo, $student_file_destination);
        }
        if ($request->filled('current_address')) {
            $student->current_address = $request->current_address;
        }
        
        $student->save();

        $response = [
            'success' => true,
            'data'    => $student,
            'message' => 'Profile updated successfully.',
        ];

        return response()->json($response, 200);
    }

}
