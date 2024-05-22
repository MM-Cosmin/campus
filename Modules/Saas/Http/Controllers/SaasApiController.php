<?php

namespace Modules\Saas\Http\Controllers;

use DB;
use Hash;
use Mail;
use App\User;
use App\SmClass;
use App\SmStaff;
use App\SmParent;
use App\SmSchool;
use App\SmSection;
use App\SmStudent;
use App\YearCheck;
use App\SmItemSell;
use App\SmAddIncome;
use App\SmAddExpense;
use App\ApiBaseMethod;
use App\SmFeesPayment;
use App\SmItemReceive;
use App\SmAcademicYear;
use App\SmClassSection;
use App\SmEmailSetting;
use App\SmNotification;
use App\SmGeneralSettings;
use App\SmHrPayrollGenerate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Saas\Entities\Ticket;
use Illuminate\Routing\Controller;
use Modules\Saas\Entities\Comment;
use Modules\Saas\Entities\Category;
use Modules\Saas\Entities\Priority;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Modules\Saas\Entities\SmAdministratorNotice;
use Modules\ParentRegistration\Entities\SmRegistrationSetting;
use Modules\ParentRegistration\Entities\SmStudentRegistration;

class SaasApiController extends Controller
{

    public function schoolList(Request $request)
    {
        try {
            $school_list = SmSchool::where('active_status', 1)->orderBy('school_name', 'asc')->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($school_list, null);
            }
        } catch (\Exception $e) {
            return ApiBaseMethod::sendError('Something went wrong, please try again.');
        }
    }


    public function schoolAcademicYear(Request $request, $school_id)
    {
        try {
            $academic_year_list = SmAcademicYear::where('school_id', $school_id)->where('active_status', 1)->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($academic_year_list, null);
            }
        } catch (\Exception $e) {
            return ApiBaseMethod::sendError('Something went wrong, please try again.');
        }
    }
    public function schoolAcademicYearClass(Request $request, $school_id, $academic_year)
    {
        try {
            $class_list = SmClass::where('school_id', $school_id)->where('created_at', 'LIKE', '%' . $academic_year . '%')->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($class_list, null);
            }
        } catch (\Exception $e) {
            return ApiBaseMethod::sendError('Something went wrong, please try again.');
        }
    }
    public function schoolSections(Request $request, $class_id)
    {
        try {
            $section_list = SmClassSection::where('class_id', $class_id)
                ->join('sm_sections', 'sm_sections.id', 'sm_class_sections.section_id')
                ->select('sm_class_sections.*', 'sm_sections.section_name')
                ->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($section_list, null);
            }
        } catch (\Exception $e) {
            return ApiBaseMethod::sendError('Something went wrong, please try again.');
        }
    }
    public function sendNotice(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'notice_title' => 'required',
            'notice_date' => 'required',
            'publish_on' => 'required',
            'school_id' => 'required',
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
        }
        try {
            if ($request->school_id==0) {
                $schools=SmSchool::where('active_status',1)->get();
                foreach ($schools as $key => $school) {
                    $administrator = new SmAdministratorNotice();
                    $administrator->notice_title = $request->notice_title;
                    $administrator->notice_date = date('Y-m-d', strtotime($request->notice_date));
                    $administrator->publish_on = date('Y-m-d', strtotime($request->publish_on));
                    $administrator->notice_message = $request->notice_message;
                    $administrator->inform_to = $school->id;
                    $result = $administrator->save();
                }

                foreach($schools as $school){
                
                    $user = User::where('school_id', $school->id)->where('role_id', 1)->first();
                    $notification = new SmNotification;
                    $notification->user_id = $user->id;
                    $notification->role_id = 1;
                    $notification->date = date('Y-m-d');
                    $notification->message = $request->notice_title;
                    $notification->school_id = $school->id;
                    $notification->save();
        
                }
            } else {
                $administrator = new SmAdministratorNotice();
                $administrator->notice_title = $request->notice_title;
                $administrator->notice_date = date('Y-m-d', strtotime($request->notice_date));
                $administrator->publish_on = date('Y-m-d', strtotime($request->publish_on));
                $administrator->notice_message = $request->notice_message;
                $administrator->inform_to = $request->school_id;
                $result = $administrator->save();

                    $user = User::where('school_id', $request->school_id)->where('role_id', 1)->first();
                    $notification = new SmNotification;
                    $notification->user_id = $user->id;
                    $notification->role_id = 1;
                    $notification->date = date('Y-m-d');
                    $notification->message = $request->notice_title;
                    $notification->school_id = $request->school_id;
                    $notification->save();
            }
            
               



            
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse(null, 'Notice added successfully');
            }
        } catch (\Exception $e) {
            return ApiBaseMethod::sendError('Something went wrong, please try again.');
        }
    }
    public function dashboardData(Request $request)
    {
        try {

            $total_inistitutions = SmSchool::all()->count();

            $students = SmStudent::all()->count();
            $data['teachers'] = SmStaff::where('role_id', 4)->count();
            $data['staffs'] = SmStaff::where('role_id', '!=', 4)->count();


            $m_add_incomes = SmAddIncome::where('active_status', 1)->where('date', 'like', date('Y-m-') . '%')->sum('amount');
            $m_fees_payments = SmFeesPayment::where('active_status', 1)->where('payment_date', 'like', date('Y-m-') . '%')->sum('amount');
            $m_item_sells = SmItemSell::where('active_status', 1)->where('sell_date', 'like', date('Y-m-') . '%')->sum('total_paid');

            $m_total_income = $m_add_incomes + $m_fees_payments + $m_item_sells;


            $m_add_expenses = SmAddExpense::where('active_status', 1)->where('date', 'like', date('Y-m-') . '%')->sum('amount');
            $m_item_receives = SmItemReceive::where('active_status', 1)->where('receive_date', 'like', date('Y-m-') . '%')->sum('total_paid');
            $m_payroll_payments = SmHrPayrollGenerate::where('active_status', 1)->where('payroll_status', 'P')->where('created_at', 'like', date('Y-m-') . '%')->sum('net_salary');

            $m_total_expense = $m_add_expenses + $m_item_receives + $m_payroll_payments;

            // for current year


            $y_add_incomes = SmAddIncome::where('active_status', 1)->where('date', 'like', date('Y-') . '%')->sum('amount');
            $y_fees_payments = SmFeesPayment::where('active_status', 1)->where('payment_date', 'like', date('Y-') . '%')->sum('amount');
            $y_item_sells = SmItemSell::where('active_status', 1)->where('sell_date', 'like', date('Y-') . '%')->sum('total_paid');

            $y_total_income = $y_add_incomes + $y_fees_payments + $y_item_sells;


            $y_add_expenses = SmAddExpense::where('active_status', 1)->where('date', 'like', date('Y-') . '%')->sum('amount');
            $y_item_receives = SmItemReceive::where('active_status', 1)->where('receive_date', 'like', date('Y-') . '%')->sum('total_paid');
            $y_payroll_payments = SmHrPayrollGenerate::where('active_status', 1)->where('payroll_status', 'P')->where('created_at', 'like', date('Y-') . '%')->sum('net_salary');

            $y_total_expense = $y_add_expenses + $y_item_receives + $y_payroll_payments;


            $sub_institute = $total_inistitutions - 1;

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['total_inistitutions'] = $total_inistitutions;
                $data['students'] = $students;
                $data['m_total_income'] = $m_total_income;
                $data['m_total_expense'] = $m_total_expense;
                $data['y_total_income'] = $y_total_income;
                $data['y_total_expense'] = $y_total_expense;
                $data['teachers'] = SmStaff::where('role_id', 4)->count();
                $data['staffs'] = SmStaff::where('role_id', '!=', 4)->count();
                return ApiBaseMethod::sendResponse($data, null);
            }
        } catch (\Exception $e) {
            return ApiBaseMethod::sendError('Something went wrong, please try again.');
        }
    }

    public function saasStudentListsearch(Request $request)
    {
        if (!Schema::hasTable('sm_student_registrations')) {
            return ApiBaseMethod::sendError('This service not available.');
        }
        try {
            $students = SmStudentRegistration::query();

            if ($request->institution != "") {
                $students->where('school_id', $request->institution);
            }

            if ($request->academic_year != "") {
                $students->where('academic_year', $request->academic_year);
            }

            if ($request->class != "") {
                $students->where('class_id', $request->class);
            }
            if ($request->section != "") {
                $students->where('section_id', $request->section);
            }
            $students = $students->orderBy('id', 'desc');
            $students = $students->get();

            $institutions = SmSchool::orderBy('school_name', 'asc')->get();
            $institution_id = $request->institution;

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['students'] = $students->toArray();
                $data['institutions'] = $institutions->toArray();
                $data['institution_id'] = $institution_id;
                return ApiBaseMethod::sendResponse($data, null);
            }
        } catch (\Throwable $th) {
            return ApiBaseMethod::sendError('Something went wrong, please try again.');
        }
    }

    public function studentApprove(Request $request)
    {

        DB::beginTransaction();
        try {

            $temp_id = $request->id;

            $request = SmStudentRegistration::find($request->id);

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


            $user_stu->username = @$admission_no;


            $user_stu->email = $request->student_email;


            $user_stu->created_at = $created_year;
            $user_stu->school_id = $request->school_id;

            $user_stu->password = Hash::make(123456);
            $user_stu->save();
            $user_stu->toArray();


            // parent user


            $user_parent = new User();
            $user_parent->role_id = 3;

            //$user_parent->username = 'par-'.$get_admission_number;

            if (empty($request->guardian_email)) {

                $user_parent->username  = 'par' . '-' . $request->school_id . '-' . @$admission_no;
            } else {

                $user_parent->username = $request->guardian_email;
            }

            $user_parent->email = $request->guardian_email;
            $user_parent->password = Hash::make(123456);
            $user_parent->created_at = $created_year;
            $user_parent->school_id = $request->school_id;
            $user_parent->save();
            $user_parent->toArray();

            $parent = new SmParent();
            $parent->user_id = $user_parent->id;
            $parent->guardians_name = $request->guardian_name;
            $parent->guardians_mobile = $request->guardian_mobile;
            $parent->guardians_email = $request->guardian_email;
            $parent->relation = $request->guardian_relation;
            if ($request->guardian_relation == 'F') {
                $parent->guardians_relation = 'Father';
            } elseif ($request->guardian_relation == 'M') {

                $parent->guardians_relation = 'Mother';
            } else {
                $parent->guardians_relation = 'Other';
            }
            $parent->created_at = $created_year;
            $parent->school_id = $request->school_id;
            $parent->save();
            $parent->toArray();


            $student = new SmStudent();

            $student->class_id = $request->class_id;
            $student->section_id = $request->section_id;

            $student->admission_date = date('Y-m-d');

            $student->user_id = $user_stu->id;
            $student->parent_id = $parent->id;


            $student->role_id = 2;

            $student->admission_no = @$admission_no;

            $student->roll_no = @$roll_no;


            $student->first_name = $request->first_name;
            $student->last_name = $request->last_name;
            $student->full_name = $request->first_name . ' ' . $request->last_name;

            $student->gender_id = $request->gender_id;

            $student->date_of_birth = date('Y-m-d', strtotime($request->date_of_birth));
            $student->email = $request->student_email;
            $student->mobile = $request->student_mobile;
            $student->created_at = $created_year;

            $student->school_id = $request->school_id;

            $student->session_id = $request->academic_year;


            $student->save();
            $student->toArray();


            SmStudentRegistration::where('id', $temp_id)->delete();

            DB::commit();

            $setting = SmRegistrationSetting::find(1);

            // checking enable or disable
            if (@$setting->approve_after_mail == 1) {


                $user_info = [];

                if ($request->student_email != "") {
                    $user_info[] =  array('email' => $request->student_email, 'id' => $student->id, 'slug' => 'student');
                }


                if ($request->guardian_email != "") {
                    $user_info[] =  array('email' =>  $request->guardian_email, 'id' => $parent->id, 'slug' => 'parent');
                }


                try {


                    foreach ($user_info as $data) {

                        $settings = SmEmailSetting::first();
                        $reciver_email = $data['email'];
                        $receiver_name =  $settings->from_name;
                        $subject= "Login Credentials";
                        $view ="parentregistration::approve_email";
                        $compact['compact'] =  $data; 
                        @send_mail($reciver_email, $receiver_name, $subject , $view ,$compact);

                        // Mail::send('parentregistration::approve_email', compact('data'), function ($message) use ($data) {

                        //     $settings = SmEmailSetting::find(1);
                        //     $email = $settings->from_email;
                        //     $Schoolname = $settings->from_name;

                        //     $message->to($data['email'], $Schoolname)->subject('Login Credentials');
                        //     $message->from($email, $Schoolname);
                        // });
                    }

                    if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                        return ApiBaseMethod::sendResponse(null, 'Operation done successfully');
                    }
                } catch (\Exception $e) {
                    return ApiBaseMethod::sendError('Email Not sent.');
                }
            }



            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse(null, 'Operation done successfully');
            }
        } catch (\Exception $e) {
            return ApiBaseMethod::sendError('Something went wrong, please try again.');
        }
    }
    public function studentDelete(Request $request)
    {
        try {
            SmStudentRegistration::destroy($request->id);
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse(null, 'Operation done successfully');
            }
        } catch (\Exception $e) {
            return ApiBaseMethod::sendError('Something went wrong, please try again.');
        }
    }

    public function institutionEnable(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'status' => 'in:0,1', // DEFAULT or SOCIAL values
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
        }
        try {
            if ($request->status == '1') {
                $status = 'yes';
            } else {
                $status = 'no';
            }
            $school = SmSchool::find($request->id);
            $school->is_enabled = $status;
            $school->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse(null, 'Operation done successfully');
            }
        } catch (\Exception $e) {
            return ApiBaseMethod::sendError('Something went wrong, please try again.');
        }
    }


    public function institutionApprove(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'status' => 'in:0,1', // DEFAULT or SOCIAL values
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
        }
        try {

            $school = SmSchool::find($request->id);
            $school->active_status = $request->status;
            $school->save();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse(null, 'Operation done successfully');
            }
        } catch (\Exception $e) {
            return ApiBaseMethod::sendError('Something went wrong, please try again.');
        }
    }
    public function noticeList(Request $request)
    {
        try {
            $allNotices = SmAdministratorNotice::orderBy('id', 'DESC')->get();

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($allNotices, null);
            }
        } catch (\Exception $e) {
            return ApiBaseMethod::sendError('Something went wrong, please try again.');
        }
    }
    public function administratorNotice(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
        }
        try {

            $allNotices = SmAdministratorNotice::where('inform_to', $request->id)
                ->where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->get();
            // return $allNotices;
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($allNotices, null);
            }
        } catch (\Exception $e) {
            return ApiBaseMethod::sendError('Something went wrong, please try again.');
        }
    }

    public function ticketView(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
        }
        try {
            $user = User::find($request->id);
            if ($user->is_administrator == 'yes') {
                $ticket = Ticket::latest()->get();
            } else {
                $ticket = Ticket::latest()->where('assign_user', $user->id)->orWhere('created_by',  $user->id)->get();
            }


            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse($ticket, null);
            }
        } catch (\Exception $e) {
            return ApiBaseMethod::sendError('Something went wrong, please try again.');
        }
    }
    function comment_store(Request $request)
    {
        
        $input = $request->all();
        $validator = Validator::make($input, [
            'ticket_id' => 'required',
            'comment_by' => 'required',
            'comment' => 'required|string',
            'file'       => 'sometimes|required|mimes:doc,pdf,docx,jpg,jpeg,png,txt',
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
        }
        $ticket = Ticket::findOrFail($request->ticket_id);
        $user = User::findOrFail($request->comment_by);
        if ($ticket) {

            $data = new Comment();
            $data->user_id = $user->id;
            $data->client_id = $ticket->user_id;
            $data->ticket_id = $ticket->id;
            $data->comment = $request->comment;




            $fileName = "";
            if ($request->file('file') != "") {
                $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                $file = $request->file('file');
                $fileSize =  filesize($file);
                $fileSizeKb = ($fileSize / 1000000);
                if($fileSizeKb >= $maxFileSize){
                    Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                    return redirect()->back();
                }
                $file = $request->file('file');
                $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('public/uploads/comment/', $fileName);
                $data->file = 'public/uploads/comment/' . $fileName;
                $data->save();
            }
            $data->school_id =  $user->school_id;
            $data->created_by = $user->id;
            $data->updated_by = $user->id;
            $data->save();

            if ($ticket->created_by != $user->id) {
                $data = new SmNotification();
                $data->message = 'Comment on your ticket';
                $data->url = route('user.ticket_view', $ticket->id);
                $data->user_id = $ticket->created_by;
                $data->school_id =  $user->school_id;
                $data->created_by = $user->id;
                $data->updated_by = $user->id;
                $data->save();
            }

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse(null, 'Comment posted');
            }
        } else {
            return ApiBaseMethod::sendError('Something went wrong, please try again.');
        }
    }
    public function addTicketView(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
        }
        try {
            $category = Category::latest()->get();
            $priority = Priority::latest()->get();
            $user = User::find($request->id);
            if ($user->is_administrator == 'yes') {
                $user_agent = User::where('role_id', '=', 1)->where('is_administrator', '=', 'no')
                    ->join('sm_schools', 'sm_schools.id', '=', 'users.school_id')
                    ->distinct('users.school_id')
                    ->get();
            } else {
                $user_agent = User::where('role_id', '=', 1)->where('is_administrator', '=', 'yes')
                    ->join('sm_schools', 'sm_schools.id', '=', 'users.school_id')
                    ->distinct('users.school_id')
                    ->get();
            }

            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                $data = [];
                $data['category'] = $category->toArray();
                $data['priority'] = $priority->toArray();
                $data['user_agent'] = $user_agent;
                return ApiBaseMethod::sendResponse($data, null);
            }
        } catch (\Throwable $th) {
            return ApiBaseMethod::sendError('Something went wrong, please try again.');
        }
    }

    public function ticket_store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'subject' => 'required|string',
            'description' => 'required|string',
            'category' => 'required|integer',
            'priority' => 'required|integer',
            'user_agent' => 'required|integer',
            'created_by' => 'required|integer'
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
        }
        try {
            $creator_details = User::find($request->created_by);
            $ticket = new Ticket();
            $ticket->assign_user = $request->user_agent;
            $ticket->subject = $request->subject;
            $ticket->description = $request->description;
            $ticket->category_id = $request->category;
            $ticket->priority_id = $request->priority;
            $ticket->created_by = $request->created_by;
            $ticket->save();

            $data = new SmNotification();
            $data->message = 'New ticket created';
            $data->url = route('user.ticket_view', $ticket->id);
            $data->user_id = $request->user_agent;
            $data->school_id =  $creator_details->school_id;
            $data->created_by = $request->created_by;;
            $data->updated_by = $request->created_by;;
            $data->save();


            return ApiBaseMethod::sendResponse(null, 'Ticket Created');
        } catch (\Exception $e) {
            return ApiBaseMethod::sendError('Something went wrong, please try again.');
        }
    }
    public function updateLogo(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'main_school_logo' => 'required|mimes:jpg,jpeg,png',
            'school_id' => 'required',
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
        }

        // for upload School Logo
        if ($request->file('main_school_logo') != "") {
            $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
            $file = $request->file('main_school_logo');
            $fileSize =  filesize($file);
            $fileSizeKb = ($fileSize / 1000000);
            if($fileSizeKb >= $maxFileSize){
                Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                return redirect()->back();
            }
            $main_school_logo = "";
            $file = $request->file('main_school_logo');
            $main_school_logo = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/settings/', $main_school_logo);
            $main_school_logo = 'public/uploads/settings/' . $main_school_logo;

            $generalSettData = SmGeneralSettings::where('school_id', '=', $request->school_id)->first();
            if (!empty($generalSettData)) {
                $generalSettData->logo = $main_school_logo;
                $results = $generalSettData->update();
            } else {
                $generalSettData = new SmGeneralSettings;
                $generalSettData->logo = $main_school_logo;
                $generalSettData->school_id = $request->school_id;
                $results = $generalSettData->save();
            }
        } else {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('No change applied, please try again');
            }
        }
        if ($results) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse(null, 'Logo has been updated successfully');
            }
        } else {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Something went wrong, please try again');
            }
        }
    }
    public function updateFavicon(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($input, [
            'main_school_favicon' => 'required|mimes:jpg,jpeg,png',
            'school_id' => 'required',
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
        }

        if ($request->file('main_school_favicon') != "") {
            $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
            $file = $request->file('main_school_favicon');
            $fileSize =  filesize($file);
            $fileSizeKb = ($fileSize / 1000000);
            if($fileSizeKb >= $maxFileSize){
                Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                return redirect()->back();
            }
            $main_school_favicon = "";
            $file = $request->file('main_school_favicon');
            $main_school_favicon = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $file->move('public/uploads/settings/', $main_school_favicon);
            $main_school_favicon = 'public/uploads/settings/' . $main_school_favicon;
            $generalSettData = SmGeneralSettings::where('school_id', '=', $request->school_id)->first();
            if (!empty($generalSettData)) {
                $generalSettData->favicon = $main_school_favicon;
                $results = $generalSettData->update();
            } else {
                $generalSettData = new SmGeneralSettings;
                $generalSettData->favicon = $main_school_favicon;
                $generalSettData->school_id = $request->school_id;
                $results = $generalSettData->save();
            }
        } else {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('No change applied, please try again');
            }
        }
        if ($results) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse(null, 'Favicon has been updated successfully');
            }
        } else {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Something went wrong, please try again');
            }
        }
    }
    public function updateSchoolSettingsData(Request $request)
    {


        $input = $request->all();
        $validator = Validator::make($input, [
            'school_name' => "required",
            'site_title' => "required",
            'phone' => "required",
            'email' => "required",
            'session_id' => "required",
            'school_id' => "required",
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
        }

        DB::beginTransaction();
        try {
            $generalSettData = SmGeneralSettings::where('school_id', '=', $request->school_id)->first();
            $generalSettData->school_name = $request->school_name;
            $generalSettData->site_title = $request->site_title;
            $generalSettData->school_code = $request->school_code;
            $generalSettData->address = $request->address;
            $generalSettData->phone = $request->phone;
            $generalSettData->email = $request->email;
            $generalSettData->session_id = $request->session_id;
            $generalSettData->promotionSetting = $request->promotionSetting;

            $results = $generalSettData->update();

            $school = SmSchool::find($request->school_id);
            $school->school_name = $request->school_name;
            $school->email = $request->email;
            $school->phone = $request->phone;
            $school->school_code = $request->school_code;
            $school->address = $request->address;
            $school->save();

            DB::commit();

            return ApiBaseMethod::sendResponse(null, 'School settings updated successfully');
        } catch (\Exception $e) {
            return ApiBaseMethod::sendError('Something went wrong, please try again');
        }
    }

    public function teacherClassList(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'id' => "required",

        ]);
        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $teacher = DB::table('sm_staffs')
            ->where('user_id', '=', $request->id)
            ->first();
        $teacher_id = $teacher->id;

        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $data = [];
            if ($teacher->role_id==1) {
                $teacher_classes = DB::table('sm_classes')
                ->where('active_status', 1)
                ->where('academic_id', getAcademicId())
                ->where('school_id',$teacher->school_id )
                ->get();
            } else {
                $teacher_classes = DB::table('sm_assign_subjects')
                ->join('sm_classes', 'sm_classes.id', '=', 'sm_assign_subjects.class_id')
                ->join('sm_sections', 'sm_sections.id', '=', 'sm_assign_subjects.section_id')
                ->distinct('class_id')
                ->select('class_id', 'class_name')
                ->where('teacher_id', $teacher_id)
                ->where('sm_classes.academic_id', getAcademicId())
                ->get();
            }
            
            
            $data['teacher_classes'] = $teacher_classes->toArray();
            return ApiBaseMethod::sendResponse($data, null);
        }
    }

    public function ticket_comment_list(Request $request,$id){

        $comments=Comment::join('users','users.id','=','comments.user_id')->where('ticket_id',$id)->get();
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            return ApiBaseMethod::sendResponse($comments, null);
        }
    }
}
