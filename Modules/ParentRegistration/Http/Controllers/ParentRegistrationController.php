<?php

namespace Modules\ParentRegistration\Http\Controllers;

use App\User;
use App\SmClass;
use App\SmRoute;
use App\SmStaff;
use App\SmParent;
use App\SmSchool;
use App\SmSection;
use App\SmStudent;
use App\SmVehicle;
use Carbon\Carbon;
use App\SmBaseSetup;
use App\SmAcademicYear;
use App\SmClassSection;
use App\SmEmailSetting;
use App\SmNotification;
use App\SmStudentGroup;
use App\SmDormitoryList;
use App\SmStudentCategory;
use Illuminate\Http\Request;
use App\Models\SmCustomField;
use App\Models\StudentRecord;
use Anhskohbo\NoCaptcha\NoCaptcha;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Scopes\StatusAcademicSchoolScope;
use Modules\ParentRegistration\Entities\SmStudentField;
use Modules\ParentRegistration\Entities\SmRegistrationSetting;
use Modules\ParentRegistration\Entities\SmStudentRegistration;
use Modules\ParentRegistration\Http\Requests\SettingRequestForm;
use App\Http\Controllers\Admin\StudentInfo\SmStudentAdmissionController;
use Modules\ParentRegistration\Http\Requests\SmStudentRegistrationRequest;



class ParentRegistrationController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth');
        $this->middleware('PM');
    }


    public function index()
    {
        try {
            return view('parentregistration::index');
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function about()
    {

        try {
            $data = \App\InfixModuleManager::where('name', 'ParentRegistration')->first();
            return view('parentregistration::index', compact('data'));
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function settings()
    {
        $school_id = Auth::user()->school_id;

        $setting = SmRegistrationSetting::where('school_id', $school_id)->first();

        if (!$setting) {
            $setting = $this->insertSetting($school_id);
        }


        $fields = $this->insertStudentField($school_id);
        // return $fields;

        return view('parentregistration::settings', compact('setting', 'fields'));
    }

    private function insertSetting($school_id)
    {
        $setting = new SmRegistrationSetting();

        $setting->recaptcha = 2;
        $setting->school_id = $school_id;
        $setting->save();
        return $setting->refresh();
    }

    private function insertStudentField($school_id)
    {

        // SmStudentField::where('school_id', $school_id)->delete();
        $db_fields = SmStudentField::where('school_id', $school_id)->get();

        $request_fields = [
            'session', 'class', 'section', 'first_name', 'last_name', 'email_address', 'gender', 'date_of_birth', 'age', 'blood_group', 'religion', 'caste', 'phone_number', 'id_number', 'student_category_id', 'student_group_id', 'height', 'weight', 'photo', 'fathers_name', 'fathers_occupation', 'fathers_phone', 'fathers_photo', 'mothers_name', 'mothers_occupation', 'mothers_phone', 'mothers_photo', 'guardians_name', 'relation', 'guardians_email', 'guardians_photo', 'guardians_phone', 'guardians_occupation', 'guardians_address', 'current_address', 'permanent_address', 'route', 'vehicle', 'dormitory_name', 'room_number', 'national_id_number', 'local_id_number', 'bank_account_number', 'bank_name', 'previous_school_details', 'additional_notes', 'ifsc_code', 'document_file_1', 'document_file_2', 'document_file_3', 'document_file_4', 'custom_field'
        ];

        if (moduleStatusCheck('Lead')) {
            $request_fields[] = 'lead_city';
            $request_fields[] = 'source_id';
        }

        $required = ['session', 'class', 'first_name', 'last_name', 'phone_number', 'relation', 'guardians_phone'];

        $fields = [];
        $db_fields_array = $db_fields->pluck('field_name')->toArray();


        if ($db_fields->count() != count($request_fields)) {

            $db_fields_array = $db_fields->pluck('field_name')->toArray();

            foreach ($request_fields as $key => $value) {
                if (!in_array($value, $db_fields_array)) {

                    $setting = new SmStudentField();
                    $setting->position = $key + 1;
                    $setting->school_id = $school_id;
                    $setting->field_name = $value;
                    $setting->is_required = in_array($value, $required) ? 1 : 0;
                    $setting->save();
                }
            }
        }



        if (count($fields)) {

            DB::table('sm_student_fields')->insert($fields);
            SmStudentField::where('school_id', $school_id)->whereIn('field_name', ['route', 'vehicle'])->update([
                'admin_section' => 'transport'
            ]);
            SmStudentField::where('school_id', $school_id)->whereIn('field_name', ['dormitory_name', 'room_number'])->update([
                'admin_section' => 'dormitory'
            ]);
            SmStudentField::where('school_id', $school_id)->whereIn('field_name', ['custom_field'])->update([
                'admin_section' => 'custom_field'
            ]);
        }

        $un_fields = [
            'un_session_id',
            'un_academic_id',
            'un_faculty_id',
            'un_department_id',
            'un_semester_id',
            'un_semester_label_id',
            'un_section_id',
        ];
        $all_fields = SmStudentField::query();
        if(!moduleStatusCheck('University')){
            $all_fields->whereNotIn('field_name', $un_fields);
        }
        return $all_fields = $all_fields->where('school_id', $school_id)->get()->filter(function ($field) {
            return !$field->admin_section || isMenuAllowToShow($field->admin_section);
        });
    }

    public function create()
    {
        try {
            return view('parentregistration::create');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function show($id)
    {
        try {
            return view('parentregistration::show');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }




    public function edit($id)
    {
        try {
            return view('parentregistration::edit');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }



    // new student registration form view method
    public function registration($page_link)
    {
        $school = app('school');
        $reg_setting = SmRegistrationSetting::where('school_id', $school->id)->first();

        if (!$reg_setting) {
            $reg_setting = $this->insertSetting($school->id);
        }

        if ($reg_setting->url != $page_link) {
            abort(404);
        }

        if (Carbon::parse($reg_setting->start_date) > Carbon::today()) {
            $message = str_replace(['{START_DATE}', '{END_DATE}'], [dateConvert($reg_setting->start_date), dateConvert($reg_setting->end_date)], $reg_setting->before_start_msg ?? 'Registration is not open yet. Please contact with school administration');
            request()->session()->flash('success', $message);
            return view('parentregistration::registration', compact('reg_setting'));
        }

        if (Carbon::parse($reg_setting->end_date) < Carbon::today()) {
            $message = str_replace(['{START_DATE}', '{END_DATE}'], [dateConvert($reg_setting->start_date), dateConvert($reg_setting->end_date)], $reg_setting->after_end_msg);
            request()->session()->flash('success', $message);
            return view('parentregistration::registration', compact('reg_setting'));
        }

        try {
            $data = $this->loadData();
            $this->insertStudentField($school->id);
            $fields = SmStudentField::where('active_status', 1)->where('school_id', $school->id)->get()->filter(function ($field) {
                return !$field->admin_section || isMenuAllowToShow($field->admin_section);
            });

            $active_fields = $fields->pluck('field_name')->toArray();

            $custom_fields = null;

            if (in_array('custom_field', $active_fields)) {
                $custom_fields = SmCustomField::where('form_name', 'student_registration')->where('is_showing', 1)->get();
            }
            $captcha = null;
            if ($reg_setting->recaptcha == 1) {
                $captcha =  new NoCaptcha($reg_setting->nocaptcha_secret, $reg_setting->nocaptcha_sitekey);
            }
            if (generalSetting() &&  generalSetting()->active_theme == 'edulia') {
                return view('parentregistration::theme.' . activeTheme() . '.registration', compact('captcha', 'school', 'custom_fields', 'active_fields', 'fields', 'reg_setting'))->with($data);
            } else {
                return view('parentregistration::registration', compact('captcha', 'school', 'custom_fields', 'active_fields', 'fields', 'reg_setting'))->with($data);
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    // new student registration form view method
    public function registrationOld()
    {
        $school = app('school');
        $reg_setting = SmRegistrationSetting::where('school_id', $school->id)->first();

        if (!$reg_setting) {
            $reg_setting = $this->insertSetting($school->id);
        }

        return redirect()->route('parentregistration/registration', $reg_setting->url);
    }


    // get academic year for parent registration for saas using ajax
    public function getClasAcademicyear(Request $request)
    {
        $classes = [];
        $academic_years = SmAcademicYear::where('school_id', $request->id)->get();
        return response()->json([$classes, $academic_years]);
    }

    // Get section for new registration by ajax
    public function getSection(Request $request)
    {
        try {
            $sectionIds = SmClassSection::withOutGlobalScope(StatusAcademicSchoolScope::class)->where('class_id', '=', $request->id)->get();
            $sections = [];
            foreach ($sectionIds as $sectionId) {
                $sections[] = SmSection::withOutGlobalScope(StatusAcademicSchoolScope::class)->find($sectionId->section_id);
            }
            return response()->json($sections);
        } catch (\Exception $e) {
            return response()->json("", 404);
        }
    }


    // Get class for regular school and saas for new student registration
    public function getClasses(Request $request)
    {
        $academic_year = SmAcademicYear::where('id', $request->id)->first();
        if (isset($request->school_id)) {
            $classes = SmClass::withOutGlobalScope(StatusAcademicSchoolScope::class)->where('active_status', '=', '1')->where('academic_id', $request->id)->where('school_id', $request->school_id)->get();
        } else {
            $school = app('school');
            $classes = SmClass::where('active_status', '=', '1')->where('academic_id', $request->id)->where('school_id', $school->id)->get();
        }
        return response()->json([$classes, $academic_year]);
    }

    // new stduent registration store in temporary table for review
    public function studentStore(SmStudentRegistrationRequest $request)
    {
        $school = app('school');

        $reg_setting = SmRegistrationSetting::where('school_id', $school->id)->first();

        try {
            $student = new SmStudentRegistration();
            $student->first_name = $request->first_name;
            $student->last_name = $request->last_name;
            $student->class_id = $request->class;
            $student->gender_id = $request->gender;
            $student->id_number = $request->id_number;
            $student->academic_id = $request->academic_year;
            $student->student_email = $request->student_email;
            $student->student_mobile = $request->student_mobile;
            $student->guardian_name = $request->guardian_name;
            $student->guardian_relation = $request->relationButton;
            $student->guardian_email = $request->guardian_email;
            $student->guardian_mobile = $request->guardian_mobile;
            $student->how_do_know_us = $request->how_do_know_us;
            $student->date_of_birth = date('Y-m-d', strtotime($request->date_of_birth));
            $student->age = $request->age;
            if (moduleStatusCheck('Lead')) {
                $student->lead_city = $request->lead_city;
                $student->source_id = $request->source_id;
            }
            //online student registartion field -abunayem
            $student->section_id = $request->section;
            $student->caste = $request->caste;


            $student->student_photo = OnlineRegistrationFileUpload('public/uploads/student/', $request->file('photo'));
            $student->bloodgroup_id = $request->blood_group;
            $student->religion_id = $request->religion;
            $student->height = $request->height;
            $student->weight = $request->weight;
            $student->current_address = $request->current_address;
            $student->permanent_address = $request->permanent_address;
            $student->route_list_id = $request->route;
            $student->dormitory_id = $request->dormitory_name;
            $student->room_id = $request->room_number;
            $student->student_group_id = $request->student_group_id;
            $student->student_category_id = $request->student_category_id;

            //$driver_id=SmVehicle::where('id','=',$request->vehicle)->first();
            if (!empty($request->vehicle)) {
                $driver = SmVehicle::where('id', '=', $request->vehicle)
                    ->select('driver_id')
                    ->first();
                if (!empty($driver)) {
                    $student->vechile_id = $request->vehicle;
                    $student->driver_id = $driver->driver_id;
                }
            }
            $student->national_id_no = $request->national_id_number;
            $student->local_id_no = $request->local_id_number;
            $student->bank_account_no = $request->bank_account_number;
            $student->bank_name = $request->bank_name;
            $student->previous_school_details = $request->previous_school_details;
            $student->aditional_notes = $request->additional_notes;
            $student->ifsc_code = $request->ifsc_code;
            $student->document_title_1 = $request->document_title_1;
            $student->document_file_1 =  OnlineRegistrationFileUpload('public/uploads/student/document/', $request->file('document_file_1'));
            $student->document_title_2 = $request->document_title_2;
            $student->document_file_2 =  OnlineRegistrationFileUpload('public/uploads/student/document/', $request->file('document_file_2'));
            $student->document_title_3 = $request->document_title_3;
            $student->document_file_3 = OnlineRegistrationFileUpload('public/uploads/student/document/', $request->file('document_file_3'));
            $student->document_title_4 = $request->document_title_4;
            $student->document_file_4 = OnlineRegistrationFileUpload('public/uploads/student/document/', $request->file('document_file_4'));
            $student->fathers_name = $request->fathers_name;
            $student->fathers_mobile = $request->fathers_phone;
            $student->fathers_occupation = $request->fathers_occupation;
            $student->fathers_photo = OnlineRegistrationFileUpload('public/uploads/student/', $request->file('fathers_photo'));
            $student->mothers_name = $request->mothers_name;
            $student->mothers_mobile = $request->mothers_phone;
            $student->mothers_occupation = $request->mothers_occupation;
            $student->mothers_photo = OnlineRegistrationFileUpload('public/uploads/student/', $request->file('mothers_photo'));

            $student->guardians_occupation = $request->guardians_occupation;
            if ($request->relationButton == 'F') {
                $student->relation = 'Father';
                $student->guardians_photo = $student->fathers_photo;
            }elseif($request->relationButton == 'M'){
                $student->relation = 'Mother';
                $student->guardians_photo = $student->mothers_photo;
            }else{
                $student->relation = 'Other';
                $student->guardians_photo = OnlineRegistrationFileUpload('public/uploads/student/', $request->file('guardians_photo'));
            }
            $student->guardians_address = $request->guardians_address;
            $student->school_id = $school->id;

            if ($request->customF) {
                $dataImage = $request->customF;
                foreach ($dataImage as $label => $field) {
                    if (is_object($field)) {
                        $key = "";
                        if ($field != "") {
                            $maxFileSize = generalSetting()->file_size * 1024;
                            $file = $field;
                            $fileSize =  filesize($file);
                            $fileSizeKb = ($fileSize / 1000000);
                            if ($fileSizeKb >= $maxFileSize) {
                                Toastr::error('Max upload file size ' . $maxFileSize . ' Mb is set in system', 'Failed');
                                return redirect()->back();
                            }
                            $file = $field;
                            $key = $file->getClientOriginalName();
                            $file->move('public/uploads/customFields/', $key);
                            $dataImage[$label] =  'public/uploads/customFields/' . $key;
                        }
                    }
                }

                //Custom Field Start
                $student->custom_field_form_name = "student_registration";
                $student->custom_field = json_encode($dataImage, true);
                //Custom Field End
            }
            //end 

            $student->save();


            $users = User::where('role_id', 1)->where('school_id', $school->id)->get();
            $setting = SmRegistrationSetting::find(1);
            foreach ($users as $user) {
                $notification = new SmNotification();
                $notification->message = "New Parentregistration";
                $notification->is_read = 0;
                $notification->user_id = $user->id;
                $notification->role_id = 1;
                $notification->school_id = $school->id;
                $notification->academic_id = $student->academic_year;
                $notification->date = date('Y-m-d');
                $notification->save();
            }


            $setting = SmRegistrationSetting::find(1);
            $school_admin['email'] =  $school->email;
            $school_admin['school_name'] =  $school->school_name;

            if (@$setting->registration_after_mail == 1) {
                if ($request->student_email != "") {
                    $compact['name'] = $request->first_name . ' ' . $request->last_name;
                    @send_mail($request->student_email, $request->first_name . ' ' . $request->last_name, "student_registration", $compact);
                }

                if ($request->guardian_email != "") {
                    $compact['student_name'] = $request->first_name . ' ' . $request->last_name;
                    $compact['father_name'] = $request->fathers_name;
                    $compact['parent_name'] = $request->fathers_name;
                    $compact['username'] = $request->guardian_email;
                    @send_mail($request->guardian_email, $request->fathers_name, "parent_login_credentials", $compact);
                    @send_sms($request->guardian_mobile, 'student_admission_in_progress', $compact);
                }
            }
            return redirect()->back()->with('success', __('parentregistration::parentRegistration.you_have_successfully_complete_the_registration'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    // Show student list for new registration
    public function studentList()
    {
        $students = SmStudentRegistration::with('class', 'section', 'academicYear', 'gender', 'school')
            ->where('school_id', Auth::user()->school_id)
            ->where('academic_id', getAcademicId())
            ->orderBy('id', 'desc')->get();
        $academic_years = SmAcademicYear::where('school_id', Auth::user()->school_id)->get();
        return view('parentregistration::student_list', compact('students', 'academic_years'));
    }

    // Show student list for new registration
    public function studentListSearch(Request $request)
    {
        $students = SmStudentRegistration::query()->with('class', 'section', 'academicYear', 'gender', 'school');/* add for egar loading ->abunayem */

        $students->where('school_id', Auth::user()->school_id);

        if ($request->academic_year != "") {
            $students->where('academic_id', $request->academic_year);
        }

        if ($request->class != "") {
            $students->where('class_id', $request->class);
        }
        if ($request->section != "") {
            $students->where('section_id', $request->section);
        }
        $students = $students->orderBy('id', 'desc');
        $students = $students->get();


        $academic_years = SmAcademicYear::where('school_id', Auth::user()->school_id)->get();



        return view('parentregistration::student_list', compact('students', 'academic_years'));
    }




    // Show student list for new registration
    public function saasStudentList()
    {
        $institutions  = SmSchool::all();
        return view('parentregistration::saas_student_list', compact('institutions'));
    }


    // Show student list for new registration
    public function saasStudentListsearch(Request $request)
    {
        $students = SmStudentRegistration::query();

        if ($request->institution != "") {
            $students->where('school_id', $request->institution);
        }

        if ($request->academic_year != "") {
            $students->where('academic_id', $request->academic_year);
        }

        if ($request->class != "") {
            $students->where('class_id', $request->class);
        }
        if ($request->section != "") {
            $students->where('section_id', $request->section);
        }
        $students = $students->orderBy('id', 'desc');
        $students = $students->get();

        $institutions = SmSchool::all();
        $institution_id = $request->institution;
        return view('parentregistration::saas_student_list', compact('students', 'institution_id', 'institutions'));
    }


    // Approve method for new student regis., after successfully then the student will delete from tempo. stduent table
    public function studentApprove(Request $request)
    {
        DB::beginTransaction();
        try {

            $temp_id = $request->id;
            $request = SmStudentRegistration::findOrFail($request->id);
            if ($request->section_id == null) {
                Toastr::warning('Please Assign Section ', 'warning');
                return redirect()->back();
            }
            $student_table_detail = SmStudent::where('school_id', $request->school_id)->max('admission_no');

            $student_table_detail_roll = SmStudent::where('class_id', $request->class_id)
                ->where('section_id', $request->section_id)
                ->where('school_id', $request->school_id)
                ->max('roll_no');
            if ($student_table_detail == 0) {
                $admission_no = 1;
            } else {
                $admission_no = $student_table_detail + 1;
            }

            if ($student_table_detail_roll == 0) {
                $roll_no = 1;
            } else {
                $roll_no = $student_table_detail_roll + 1;
            }

            $created_year = $request->academicYear->year . '-01-01 12:00:00';

            // stduent user
            $user_stu = new User();
            $user_stu->role_id = 2;
            $user_stu->full_name = $request->first_name . ' ' . $request->last_name;
            $user_stu->username = $admission_no;
            $user_stu->email = $request->student_email;
            $user_stu->created_at = $created_year;
            $user_stu->school_id = $request->school_id;
            $user_stu->password = Hash::make(123456);
            $user_stu->save();
            $user_stu->toArray();

            $check_parent_email = User::where('email', $request->guardian_email)->where('role_id', 3)->first();
            // parent user
            if (empty($check_parent_email)) { /*  guardian info exit  condition ->abuNayem*/
                $user_parent = new User();
                $user_parent->role_id = 3;
                $user_parent->full_name = $request->guardian_name;

                //$user_parent->username = 'par-'.$get_admission_number;

                if (empty($request->guardian_email)) {

                    $user_parent->username  = 'par' . '-' . $request->school_id . '-' . $admission_no;
                } else {

                    $user_parent->username = $request->guardian_email;
                }

                $user_parent->email = $request->guardian_email;
                $user_parent->password = Hash::make(123456);
                $user_parent->created_at = $created_year;
                if (empty($request->school_id)) {
                    $user_parent->school_id =  1;
                } else {
                    $user_parent->school_id = $request->school_id;
                }

                $user_parent->save();
                $user_parent->toArray();



                $parent = new SmParent();
                $parent->user_id = $user_parent->id;

                $parent->guardians_email = $request->guardian_email;
                $parent->relation = $request->guardian_relation;
                $parent->guardians_relation = 'Other';

                $parent->fathers_name = $request->fathers_name;
                $parent->fathers_mobile = $request->fathers_mobile;
                $parent->fathers_occupation = $request->fathers_occupation;
                $parent->fathers_photo = $request->fathers_photo;

                $parent->mothers_name = $request->mothers_name;
                $parent->mothers_mobile = $request->mothers_mobile;
                $parent->mothers_occupation = $request->mothers_occupation;
                $parent->mothers_photo = $request->mothers_photo;

                $parent->guardians_name = $request->guardian_name;
                $parent->guardians_mobile = $request->guardian_mobile;
                $parent->guardians_email = $request->guardian_email;
                $parent->guardians_occupation = $request->guardians_occupation;
                $parent->guardians_relation = $request->relation;
                $parent->relation = $request->guardian_relation;
                $parent->guardians_address = $request->guardians_address;


                $parent->created_at = $created_year;
                $parent->school_id = $request->school_id;
                $parent->save();
                $parent->toArray();
            }
            $exit_parent_id = $check_parent_email ? SmParent::where('user_id', $check_parent_email->id)->first()->id : '';
            $student = new SmStudent();

            $student->class_id = $request->class_id;
            $student->section_id = $request->section_id;

            $student->admission_date = date('Y-m-d');

            $student->user_id = $check_parent_email == '' ? $user_stu->id : $check_parent_email->id;
            $student->parent_id = $check_parent_email == '' ? $parent->id : $exit_parent_id;


            $student->role_id = 2;
            $student->admission_no = $admission_no;
            $student->roll_no = $request->id_number ? $request->id_number : $roll_no;
            $student->first_name = $request->first_name;
            $student->last_name = $request->last_name;
            $student->full_name = $request->first_name . ' ' . $request->last_name;

            $student->gender_id = $request->gender_id;

            $student->date_of_birth = date('Y-m-d', strtotime($request->date_of_birth));
            $student->age = $request->age;
            $student->email = $request->student_email;
            $student->mobile = $request->student_mobile;
            $student->created_at = $created_year;

            $student->school_id = $request->school_id;

            $student->session_id = $request->academic_id;
            $student->academic_id = $request->academic_id;

            // add online registration field->abunayem
            $student->caste = $request->caste;
            $student->student_photo = $request->student_photo;
            $student->bloodgroup_id = $request->bloodgroup_id;
            $student->religion_id = $request->religion_id;
            $student->height = $request->height;
            $student->weight = $request->weight;
            $student->current_address = $request->current_address;
            $student->permanent_address = $request->permanent_address;
            $student->route_list_id = $request->route_list_id;
            $student->dormitory_id = $request->dormitory_id;
            $student->room_id = $request->room_id;

            //$driver_id=SmVehicle::where('id','=',$request->vehicle)->first();
            if (!empty($request->vehicle)) {
                $driver = SmVehicle::where('id', '=', $request->vechile_id)
                    ->select('driver_id')
                    ->first();
                if (!empty($driver)) {
                    $student->vechile_id = $request->vechile_id;
                    $student->driver_id = $driver->driver_id;
                }
            }
            // $student->driver_name = $request->driver_name;
            // $student->driver_phone_no = $request->driver_phone;
            $student->national_id_no = $request->national_id_no;
            $student->local_id_no = $request->local_id_no;
            $student->bank_account_no = $request->bank_account_no;
            $student->bank_name = $request->bank_name;
            $student->previous_school_details = $request->previous_school_details;
            $student->aditional_notes = $request->aditional_notes;
            $student->ifsc_code = $request->ifsc_code;
            $student->document_title_1 = $request->document_title_1;
            $student->document_file_1 =  $request->document_file_1;
            $student->document_title_2 = $request->document_title_2;
            $student->document_file_2 =  $request->document_file_2;
            $student->document_title_3 = $request->document_title_3;
            $student->document_file_3 = $request->document_file_3;
            $student->document_file_3 = $request->document_title_4;
            $student->document_file_4 = $request->document_file_4;
            $student->student_category_id = $request->student_category_id;
            $student->student_group_id = $request->student_group_id;
            if (moduleStatusCheck('Lead') == true) {
                $student->lead_city = $request->lead_city;
                $student->source_id = $request->source_id;
            }



            //Custom Field Start
            $student->custom_field_form_name = $request->custom_field_form_name;
            $student->custom_field = $request->custom_field;
            //Custom Field End

            $student->save();
            $student->toArray();

            //Add student record start
            $studentRecord = new StudentRecord();
            $studentRecord->class_id = $student->class_id;
            $studentRecord->section_id = $student->section_id;
            $studentRecord->roll_no = $student->role_id;
            $studentRecord->session_id = $student->session_id;
            $studentRecord->school_id = $student->school_id;
            $studentRecord->academic_id = $student->academic_id;
            $studentRecord->student_id = $student->id;
            $studentRecord->save();
            //Add student record end

            SmStudentRegistration::where('id', $temp_id)->delete();

            DB::commit();

            $setting = SmRegistrationSetting::find(1);

            // checking enable or disable
            if ($setting->approve_after_mail == 1) {


                $user_info = [];

                if ($request->student_email != "") {
                    $user_info[] =  array('name' => $student->full_name, 'email' => $request->student_email, 'id' => $student->id, 'slug' => 'student');
                }


                if ($request->guardian_email != "") {
                    $user_info[] =  array('name' => $request->guardian_name, 'email' =>  $request->guardian_email, 'id' => $parent->id, 'slug' => 'parent');
                }


                try {


                    foreach ($user_info as $data) {

                        $settings = SmEmailSetting::first();
                        $reciver_email = $data['email'];
                        $receiver_name =  $settings->from_name;
                        $subject = "Login Credentials";
                        $view = "parentregistration::approve_email";
                        $compact['compact'] =  $data;
                        @send_mail($reciver_email, $receiver_name, $subject, $view, $compact);
                    }

                    Toastr::success('Operation successful', 'Success');
                    return redirect()->route('parentregistration.student-list');
                } catch (\Exception $e) {

                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                }
            }
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function assignSectionStore(Request $request)
    {

        if ($request->section == "") {
            Toastr::error('Please select class name.', 'Failed');
            return redirect()->back();
        }
        try {

            $student = SmStudentRegistration::findOrFail($request->id);
            $student->section_id = $request->section;
            $student->save();

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    // Temporary stduent delete
    public function studentDelete(Request $request)
    {
        try {
            SmStudentRegistration::destroy($request->id);
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    // unique stduent email check by ajax from all school
    public function checkStudentEmail(Request $request)
    {
        $student = User::where('email', $request->id)->where('school_id', $request->school_id)->first();


        $SmStudentRegistration = SmStudentRegistration::where('school_id', $request->school_id)
            ->where(function ($q) use ($request) {
                $q->where('student_email', $request->id)->orWhere('guardian_email', $request->id);
            })->first();

        if ($student != "" || $SmStudentRegistration != "") {
            return response()->json(1);
        } else {
            return response()->json(0);
        }
    }

    // unique stduent mobile check by ajax from all school
    public function checkStudentMobile(Request $request)
    {
        $student = SmStudent::where('mobile', $request->id)->where('school_id', $request->school_id)->first();
        $SmStudentRegistration = SmStudentRegistration::where('student_mobile', $request->id)->where('school_id', $request->school_id)->first();

        if ($student != "" || $SmStudentRegistration != "") {
            return response()->json(1);
        } else {
            return response()->json(0);
        }
    }

    // unique guardian email check by ajax from all school
    public function checkGuardianEmail(Request $request)
    {
        $student = User::where('email', $request->id)->where('school_id', $request->school_id)->first();
        $SmStudentRegistration = SmStudentRegistration::where('school_id', $request->school_id)->where(function ($q) use ($request) {
            $q->where('student_email', $request->id)->orWhere('guardian_email', $request->id);
        })->first();

        if ($student != "" || $SmStudentRegistration != "") {
            return response()->json(1);
        } else {
            return response()->json(0);
        }
    }

    // unique guardian mobile check by ajax from all school
    public function checkGuardianMobile(Request $request)
    {
        $student = SmParent::where('guardians_mobile', $request->id)->where('school_id', $request->school_id)->first();
        $SmStudentRegistration = SmStudentRegistration::where('guardian_mobile', $request->id)->where('school_id', $request->school_id)->first();

        if ($student != "" || $SmStudentRegistration != "") {
            return response()->json(1);
        } else {
            return response()->json(0);
        }
    }

    public function studentView($id)
    {

        $student_detail = SmStudentRegistration::where('id', $id)->firstOrFail();
        if (!empty($student_detail->vechile_id)) {
            $driver_id = SmVehicle::where('id', '=', $student_detail->vechile_id)->first();
            $driver_info = SmStaff::where('id', '=', $driver_id->driver_id)->first();
        } else {
            $driver_id = '';
            $driver_info = '';
        }
        return view("parentregistration::student_view", compact('student_detail', 'driver_id', 'driver_info'));
    }


    // registartion setting for regular school and saas
    public function Updatesettings(SettingRequestForm $request)
    {
        if (config('app.app_sync')) {
            Toastr::error('Restricted in demo mode');
            return back();
        }
        try {

            if (Auth::id() == 1) {
                $key1 = 'NOCAPTCHA_SITEKEY';
                $key2 = 'NOCAPTCHA_SECRET';



                $value1 = $request->nocaptcha_sitekey;
                $value2 = $request->nocaptcha_secret;


                $path                   = base_path() . "/.env";
                $NOCAPTCHA_SITEKEY          = env($key1);
                $NOCAPTCHA_SECRET          = env($key2);


                if (file_exists($path)) {
                    file_put_contents($path, str_replace(
                        "$key1=" . $NOCAPTCHA_SITEKEY,
                        "$key1=" . $value1,
                        file_get_contents($path)
                    ));
                    file_put_contents($path, str_replace(
                        "$key2=" . $NOCAPTCHA_SECRET,
                        "$key2=" . $value2,
                        file_get_contents($path)
                    ));
                }
            }



            $setting = SmRegistrationSetting::where('school_id', Auth::user()->school_id)->first();

            if ($setting == "") {
                $setting = new SmRegistrationSetting();
            }

            if (isset($request->position)) {
                $setting->position = $request->position;
            }

            if (isset($request->registration_permission)) {
                $setting->registration_permission = $request->registration_permission;
            }

            if (isset($request->registration_after_mail)) {
                $setting->registration_after_mail = $request->registration_after_mail;
            }

            if (isset($request->approve_after_mail)) {
                $setting->approve_after_mail = $request->approve_after_mail;
            }

            if (isset($request->recaptcha)) {
                $setting->recaptcha = $request->recaptcha;
            }

            $setting->nocaptcha_sitekey = $request->nocaptcha_sitekey;
            $setting->nocaptcha_secret = $request->nocaptcha_secret;
            //footer note show hide ->abunayem
            $setting->footer_note_status = $request->footer_note_status;
            $setting->footer_note_text = $request->footer_note_text;
            //end

            $setting->start_date = $request->start_date ? Carbon::parse($request->start_date)->format('Y-m-d H:i:s') : null;
            $setting->end_date = $request->end_date ? Carbon::parse($request->end_date)->format('Y-m-d H:i:s') : null;
            $setting->before_start_msg = $request->before_start_msg;
            $setting->after_end_msg = $request->after_end_msg;
            $setting->url = $request->url;
            $setting->save();

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {

            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function assignSection($id)
    {


        try {
            $student = SmStudentRegistration::findOrFail($id);

            $sectionIds = SmClassSection::where('class_id', '=', $student->class_id)->get();
            $sections = [];
            foreach ($sectionIds as $sectionId) {
                $sections[] = SmSection::find($sectionId->section_id);
            }

            return view('parentregistration::assign_section', compact('sections', 'student'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function labelUpdate(Request $request)
    {
        try {
            if (empty($request->label_name)) {
                Toastr::error('Name Field Can Not Be Empty', 'Failed');
                return redirect()->back();
            }
            $label_field = SmStudentField::find($request->id);
            $label_field->label_name = $request->label_name;

            if ($label_field->is_required != 1) {
                $label_field->active_status = $request->status;
            }
            $label_field->save();

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Throwable $th) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function labelStatus(Request $request)
    {

        try {

            $label_field = SmStudentField::find($request->filed_id);
            if ($label_field->is_required != 1) {
                $label_field->active_status = $request->field_status;
            }
            $label_field->save();
            return response(["done"]);
        } catch (\Throwable $th) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function editStudent($id)
    {

        try {
            $data = SmStudentAdmissionController::loadData();
            $data['student'] = SmStudentRegistration::find($id);
            $data['siblings'] = [];
            $data['custom_filed_values'] = json_decode($data['student']->custom_field);
            $data['max_admission_id'] = SmStudent::where('school_id', auth()->user()->school_id)->max('admission_no');

            $email = $data['student']->student_email;
            $phone = $data['student']->student_mobile;
            $data['exitStudent'] = null;
            if ($email || $phone) {
                $x_student = SmStudent::query();
                if ($email && $phone) {
                    $x_student->where('mobile', $phone);
                } else if ($email) {
                    $x_student->where('email', $email);
                } else if ($phone) {
                    $x_student->where('mobile', $phone);
                }
                $data['exitStudent'] = $x_student->first();
            }

            return view('parentregistration::student_approve', $data);
        } catch (\Exception $e) {

            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public static function loadData()
    {

        $school = app('school');

        $data['classes'] = [];
        $data['academic_years'] = SmAcademicYear::where('active_status', 1)->where('school_id', $school->id)->get();
        $data['sessions'] = $data['academic_years'];


        //field showing into online registration -abunayem
        $data['genders'] = SmBaseSetup::where('active_status', '=', '1')->where('base_group_id', '=', '1')->where('school_id', $school->id)->get();
        $data['blood_groups'] = SmBaseSetup::where('active_status', '=', '1')->where('base_group_id', '=', '3')
            ->where('school_id', $school->id)
            ->get(['id', 'base_setup_name']);

        $data['religions'] = SmBaseSetup::where('active_status', '=', '1')->where('base_group_id', '=', '2')
            ->where('school_id', $school->id)
            ->get(['id', 'base_setup_name']);

        $data['categories'] = SmStudentCategory::where('school_id', $school->id)->get(['id', 'category_name']);
        $data['groups'] = SmStudentGroup::where('school_id', $school->id)->get(['id', 'group']);

        $data['route_lists'] = SmRoute::where('active_status', '=', '1')->where('school_id', $school->id)->get();
        $data['vehicles'] = SmVehicle::where('active_status', '=', '1')->where('school_id', $school->id)->get();
        $data['driver_lists'] = SmStaff::where([['active_status', '=', '1'], ['role_id', 9]])->where('school_id', $school->id)->get();
        $data['dormitory_lists'] = SmDormitoryList::where('active_status', '=', '1')->where('school_id', $school->id)->get();

        $data['lead_city'] = [];
        $data['sources'] = [];

        if (moduleStatusCheck('Lead') == true) {
            $data['lead_city'] = \Modules\Lead\Entities\LeadCity::where('school_id',  $school->id)->get(['id', 'city_name']);
            $data['sources'] = \Modules\Lead\Entities\Source::where('school_id',  $school->id)->get(['id', 'source_name']);
        }
        return $data;
    }
}
