<?php

namespace Modules\Saas\Http\Controllers;

use App\Role;
use Validator;
use App\SmClass;
use App\SmStaff;
use App\SmSchool;
use App\SmStudent;
use App\SmWeekend;
use Carbon\Carbon;
use App\SmItemSell;
use App\SmAddIncome;
use App\SmBaseSetup;
use App\SmClassTime;
use App\SmAddExpense;
use App\SmFeesMaster;
use App\ApiBaseMethod;
use App\SmFeesPayment;
use App\SmItemReceive;
use App\SmStaffAttendence;
use App\SmStudentCategory;
use App\SmHrPayrollGenerate;
use App\SmStudentAttendance;
use Illuminate\Http\Request;
use App\Models\StudentRecord;
use App\SmClassRoutineUpdate;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Scopes\AcademicSchoolScope;
use App\Scopes\GlobalAcademicScope;
use Illuminate\Support\Facades\Auth;
use App\Scopes\StatusAcademicSchoolScope;

class SaasSuperadminReportController extends Controller
{
    public function incomeExpense()
    {
        $institutions = SmSchool::orderBy('school_name', 'asc')->get();
        return view('saas::superadminReport.incomeExpense', compact('institutions'));
    }

    public function incomeExpenseSearch(Request $request)
    {
        $request->validate([
            'institution' => 'required',
            'type' => 'required'
        ]);

        $date_from = date('Y-m-d', strtotime($request->date_from));
        $date_to = date('Y-m-d', strtotime($request->date_to));

        $date_time_from = date('Y-m-d H:i:s', strtotime($request->date_from));
        $date_time_to = date('Y-m-d H:i:s', strtotime($request->date_to . ' ' . '23:59:00'));


        $type_id = $request->type;

        $from_date = $request->date_from;

        $to_date = $request->date_to;

        $institutions = SmSchool::orderBy('school_name', 'asc')->get();



        if ($request->type == "In") {
            if ($request->filtering_income == "all") {
                $dormitory = 0;
                $transport = 0;
                $add_incomes = SmAddIncome::where('date', '>=', $date_from)->where('date', '<=', $date_to)->where('active_status', 1)->where('school_id', $request->institution)->get();

                $fees_payments = SmFeesPayment::where('updated_at', '>=', $date_time_from)->where('updated_at', '<=', $date_time_to)->where('active_status', 1)->where('school_id', $request->institution)->sum('amount');

                $item_sells = SmItemSell::where('updated_at', '>=', $date_time_from)->where('updated_at', '<=', $date_time_to)->where('active_status', 1)->where('school_id', $request->institution)->sum('total_paid');
            } elseif ($request->filtering_income == "sell") {
                $dormitory = 0;
                $transport = 0;
                $add_incomes = [];
                $fees_payments = '';

                $item_sells = SmItemSell::where('updated_at', '>=', $date_time_from)->where('updated_at', '<=', $date_time_to)->where('active_status', 1)->where('school_id', $request->institution)->sum('total_paid');
            } elseif ($request->filtering_income == "fees") {
                $dormitory = 0;
                $add_incomes = [];
                $transport = 0;
                $item_sells = '';

                $fees_payments = SmFeesPayment::where('updated_at', '>=', $date_time_from)->where('updated_at', '<=', $date_time_to)->where('active_status', 1)->where('school_id', $request->institution)->sum('amount');
            } elseif ($request->filtering_income == "dormitory") {
                $add_incomes = [];
                $fees_payments = '';
                $item_sells = '';
                $transport = 0;

                $fees_masters = SmFeesMaster::select('fees_type_id')->Where('fees_group_id', 2)->where('school_id', $request->institution)->get();
                $dormitory = 0;
                foreach ($fees_masters as $fees_master) {
                    $dormitory = $dormitory + SmFeesPayment::where('fees_type_id', $fees_master->fees_type_id)->where('updated_at', '>=', $date_time_from)->where('school_id', $request->institution)->where('updated_at', '<=', $date_time_to)->where('active_status', 1)->sum('amount');
                }
            } else {
                $add_incomes = [];
                $fees_payments = '';
                $item_sells = '';
                $dormitory = 0;

                $fees_masters = SmFeesMaster::select('fees_type_id')->Where('fees_group_id', 1)->get();
                $transport = 0;
                foreach ($fees_masters as $fees_master) {
                    $transport = $transport + SmFeesPayment::where('fees_type_id', $fees_master->fees_type_id)->where('updated_at', '>=', $date_time_from)->where('updated_at', '<=', $date_time_to)->where('active_status', 1)->where('school_id', $request->institution)->sum('amount');
                }
            }

            return view('saas::superadminReport.incomeExpense', compact('institutions', 'add_incomes', 'fees_payments', 'item_sells', 'dormitory', 'transport', 'type_id', 'from_date', 'to_date'));
        } else {
            if ($request->filtering_expense == "all") {

                $add_expenses = SmAddExpense::where('date', '>=', $date_from)->where('date', '<=', $date_to)->where('active_status', 1)->where('school_id', $request->institution)->get();

                $item_receives = SmItemReceive::where('updated_at', '>=', $date_time_from)->where('updated_at', '<=', $date_time_to)->where('active_status', 1)->where('school_id', $request->institution)->sum('total_paid');


                $payroll_payments = SmHrPayrollGenerate::where('updated_at', '>=', $date_time_from)->where('updated_at', '<=', $date_time_to)->where('active_status', 1)->where('school_id', $request->institution)->where('payroll_status', 'P')->sum('net_salary');
            } elseif ($request->filtering_expense == "receive") {
                $add_expenses = [];

                $item_receives = SmItemReceive::where('updated_at', '>=', $date_time_from)->where('updated_at', '<=', $date_time_to)->where('active_status', 1)->where('school_id', $request->institution)->sum('total_paid');


                $payroll_payments = '';
            } else {
                $add_expenses = [];

                $item_receives = '';


                $payroll_payments = SmItemReceive::where('updated_at', '>=', $date_time_from)->where('updated_at', '<=', $date_time_to)->where('active_status', 1)->where('school_id', $request->institution)->sum('total_paid');
            }





            return view('saas::superadminReport.incomeExpense', compact('institutions', 'add_expenses', 'item_receives', 'payroll_payments', 'type_id', 'from_date', 'to_date'));
        }
    }

    public function studentList()
    {
        $institutions = SmSchool::orderBy('school_name', 'asc')->get();
        return view('saas::superadminReport.stduentList', compact('institutions'));
    }

    public function studentListSearch(Request $request)
    {
        try{
            $records = StudentRecord::query();
            $records->where('is_promote',0);
            if ($request->institution != "") {
                $records->where('school_id', $request->institution);
            }
            if ($request->class != "") {
                $records->where('class_id', $request->class);
            }
            if ($request->section != "") {
                $records->where('section_id', $request->section);
            }
            if ($request->roll_no != "") {
                $records->where('roll_no', 'like', '%' . $request->roll_no . '%');
            }
            $records->whereHas('student',function($q){
                $q->where('active_status',1);
            });
            $student_records = $records->with('student')->get();

            $classes = SmClass::where('active_status', 1)->get();
            $institutions = SmSchool::orderBy('school_name', 'asc')->get();
            $types = SmStudentCategory::all();
            $genders = SmBaseSetup::where('active_status', '=', '1')->where('base_group_id', '=', '1')->where('school_id', Auth::user()->school_id)->get();


            $institution_id = $request->institution;
            $class_id = $request->class;
            $name = $request->name;
            $roll_no = $request->roll_no;
            return view('saas::superadminReport.stduentList', compact('student_records', 'classes', 'class_id', 'name', 'roll_no', 'institution_id', 'institutions', 'types', 'genders'));

        }
        catch(\Exception $e){
             ;
        }
    }


    public function teacherList()
    {
        $institutions = SmSchool::orderBy('school_name', 'asc')->get();

        return view('saas::superadminReport.teacherList', compact('institutions'));
    }

    public function teacherListSearch(Request $request)
    {
        $teachers = SmStaff::query();
        $teachers->where('active_status', 1);
        $teachers->where(function($q) {
            $q->where('role_id', 4)->orWhere('previous_role_id', 4);
        });

        if ($request->institution != "") {
            $teachers->where('school_id', $request->institution);
        }


        if ($request->staff_no != "") {
            $teachers->where('staff_no', $request->staff_no);
        }

        if ($request->staff_name != "") {
            $teachers->where('full_name', 'like', '%' . $request->staff_name . '%');
        }
        $teachers = $teachers->get();
        $institutions = SmSchool::orderBy('school_name', 'asc')->get();


        return view('saas::superadminReport.teacherList', compact('institutions', 'teachers'));
    }


    public function classList()
    {
        $institutions = SmSchool::orderBy('school_name', 'asc')->get();
        return view('saas::superadminReport.classList', compact('institutions'));
    }

    public function classListSearch(Request $request)
    {
        
        $classes = SmClass::query();
        if ($request->institution != "") {
            $classes->where('school_id', $request->institution);
        }
        $classes = $classes->withOutGlobalScope(StatusAcademicSchoolScope::class)->get();
        $institutions = SmSchool::orderBy('school_name', 'asc')->get();
        return view('saas::superadminReport.classList', compact('institutions', 'classes'));
    }


    public function classRoutine()
    {
        $institutions = SmSchool::orderBy('school_name', 'asc')->get();

        return view('saas::superadminReport.classRoutine', compact('institutions'));
    }

    public function classRoutineSearch(Request $request)
    {
        $request->validate([
            'institution' => 'required',
            'class' => 'required',
            'section' => 'required',
        ]);

        $class_id = $request->class;
        $section_id = $request->section;
        $institutions = SmSchool::orderBy('school_name', 'asc')->get();
        $school = $institutions->where('id', $request->institution)->first();
        $class_times = $school->classTimes;
        $sm_weekends = $school->weekends;
        return view('saas::superadminReport.classRoutine', compact('class_id', 'section_id', 'sm_weekends', 'institutions'));
    }

    public function ajaxGetClass(Request $request)
    {
        $classes = SmClass::withoutGlobalScope(GlobalAcademicScope::class)->withoutGlobalScope(StatusAcademicSchoolScope::class)->where('school_id', '=', $request->id)->get();

        return response()->json([$classes]);
    }


    public function staffAttendance()
    {
        $institutions = SmSchool::orderBy('school_name', 'asc')->get();
        $roles = Role::where('active_status', '=', 1)
            ->whereIn('school_id', array(1, Auth::user()->school_id))
            ->where('id', '!=', 2)
            ->where('id', '!=', 1)
            ->where('id', '!=', 3)
            ->orderBy('id', 'desc')
            ->get();
        return view('saas::superadminReport.staffAttendance', compact('institutions', 'roles'));
    }


    public function staffAttendanceSearch(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'institution' => 'required',
            'role' => 'required',
            'month' => 'required',
            'year' => 'required'
        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $year = $request->year;
        $month = $request->month;;
        $role_id = $request->role;;
        $role_id = $request->role;;
        $current_day = date('d');
        $institutions = SmSchool::orderBy('school_name', 'asc')->get();

        $days = cal_days_in_month(CAL_GREGORIAN, $request->month, $request->year);
        $roles = Role::where('active_status', '=', 1)
            ->whereIn('school_id', array(1, Auth::user()->school_id))
            ->where('id', '!=', 1)
            ->where('id', '!=', 5)
            ->orderBy('id', 'desc')
            ->get();

        $staffs = SmStaff::whereRole($request->role)->get();

        $attendances = [];
        foreach ($staffs as $staff) {
            $attendance = SmStaffAttendence::where('staff_id', $staff->id)->where('attendence_date', 'like', $request->year . '-' . $request->month . '%')->get();
            if (count($attendance) != 0) {
                $attendances[] = $attendance;
            }
        }

        return view('saas::superadminReport.staffAttendance', compact('institutions','attendances','staffs', 'days', 'year', 'month', 'current_day', 'roles', 'role_id'));
    }

    public function studentAttendance()
    {
        $institutions = SmSchool::orderBy('school_name', 'asc')->get();
        return view('saas::superadminReport.stduentAttendance', compact('institutions'));
    }


    public function studentAttendanceSearch(Request $request)
    {

        $input = $request->all();
      
        $validator = Validator::make($input, [
            'institution' => 'required',
            'class' => 'required',
            'section' => 'required',
            'month' => 'required',
            'year' => 'required'
        ]);


        if ($validator->fails()) {

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $year = $request->year;
        $month = $request->month;
        $class_id = $request->class;
        $section_id = $request->section;
        $current_day = date('d');
        $institutions = SmSchool::orderBy('school_name', 'asc')->get();

        $days = cal_days_in_month(CAL_GREGORIAN, $request->month, $request->year);
        $students = StudentRecord::where('class_id', $request->class)
                                ->where('section_id', $request->section)
                                ->where('school_id', $request->institution)->get()->sortBy('roll_no');


        $attendances = [];
        foreach ($students as $record) {
            $attendance = SmStudentAttendance::where('student_id', $record->student_id)
            ->where('attendance_date', 'like', $request->year . '-' . $request->month . '%')
            ->where('school_id', $request->institution)
            ->withoutGlobalScope(AcademicSchoolScope::class)
            ->where('student_record_id', $record->id)
            ->get();
            if (count($attendance) != 0) {
                $attendances[] = $attendance;
            }
        }
      

        return view('saas::superadminReport.stduentAttendance', compact('institutions', 'attendances', 'days','students', 'year', 'month', 'current_day', 'class_id', 'section_id'));
    }
}