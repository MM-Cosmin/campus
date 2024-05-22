<?php

namespace Modules\Saas\Http\Controllers;

use App\SmRolePermission;
use App\User;
use App\SmToDo;
use App\SmStaff;
use App\SmParent;
use App\SmSchool;
use App\SmHoliday;
use App\SmStudent;
use App\SmItemSell;
use App\SmAddIncome;
use App\SmAddExpense;
use App\SmFeesPayment;
use App\SmItemReceive;
use App\SmNoticeBoard;
use App\SmGeneralSettings;
use App\InfixModuleManager;
use App\SmAcademicYear;
use App\SmHrPayrollGenerate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\SmModulePermissionAssign;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Modules\Saas\Entities\SmSaasPackages;
use Modules\Saas\Entities\SmAdministratorNotice;

class SaasController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('PM');
        // $this->middleware('TimeZone');
    }

    /**
     * Show the application dashboard.
     *
     */

    public function about()
    {

        $module = 'Saas';
        if (User::checkPermission($module) != 100) {
            Toastr::error('Please verify your ' . $module . ' Module', 'Failed');
            return redirect()->route('Moduleverify', $module);
        }

        try {
            if (date('d') <= 15) {
                $client = new \GuzzleHttp\Client();
                $s = $client->post(User::$api, array('form_params' => array('TYPE' => $this->TYPE, 'User' => $this->User, 'SmGeneralSettings' => $this->SmGeneralSettings, 'SmUserLog' => $this->SmUserLog, 'InfixModuleManager' => $this->InfixModuleManager, 'URL' => $this->URL)));
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }
        try {
            $data = \App\InfixModuleManager::where('name', $module)->first();
            return view('saas::index', compact('data'));
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function dashboard()
    {

        $module = 'Saas';
        if (User::checkPermission($module) != 100) {
            Toastr::error('Please verify your ' . $module . ' Module', 'Failed');
            return redirect()->route('Moduleverify', $module);
        }

        $role_id = Session::get('role_id');
        if ($role_id == 2) {
            return redirect('student-dashboard');
        } elseif ($role_id == 3) {
            return redirect('parent-dashboard');
        } elseif ($role_id == 10) {
            return redirect('customer-dashboard');
        } elseif ($role_id == "") {
            return redirect('login');
        } else {
            return redirect('admin-dashboard');
        }
    }


    // for display dashboard

    public function index()
    {
        $module_links = [];
        $permissions = SmRolePermission::where('role_id', Auth::user()->role_id)->get();
        $modules = [];
        $chart_data = "";
        $chart_data =" ";
        $day_incomes =  SmAddIncome::where('academic_id', getAcademicId())
                        ->where('name', '!=', 'Fund Transfer')
                        ->where('active_status', 1)
                        ->where('date', '>=', date('Y').'-01-01')
                        ->where('date', '<=', date('Y-m-d'))
                        ->get(['amount','date']);

        $day_expenses =   SmAddExpense::where('academic_id', getAcademicId())
                            ->where('name', '!=', 'Fund Transfer')
                            ->where('active_status', 1)
                            ->where('date', '>=', date('Y').'-01-01')
                            ->where('date', '<=', date('Y-m-d'))
                            ->get(['amount','date']);

        for($i = 1; $i <= date('d'); $i++){
            $i = $i < 10? '0'.$i:$i;
            $income = $day_incomes->where('date', 'like', date('Y-m-') . $i)->sum('amount');
            $expense =  $day_expenses->where('date', 'like', date('Y-m-') . $i)->sum('amount');
            $chart_data .= "{ day: '" . $i . "', income: " . @$income . ", expense:" . @$expense . " },";
        }

        $chart_data_yearly = "";
            for($i = 1; $i <= date('m'); $i++){
                $i = $i < 10? '0'.$i:$i;
                $yearlyIncome = $day_incomes->where('date', 'like', date('Y-' . $i) . '%')->sum('amount');
                $yearlyExpense = $day_expenses->where('date', 'like', date('Y-' . $i) . '%')->sum('amount');
                $chart_data_yearly .= "{ y: '" . $i . "', income: " . @$yearlyIncome . ", expense:" . @$yearlyExpense . " },";
            }

        if (Auth::user()->is_administrator == "yes") {
            if(getAcademicId()){
               $academic_year = SmAcademicYear::where('id', getAcademicId())->first()->year;
            }
            else{
                $academic_year = SmAcademicYear::where('school_id', 1)->first()->year;
            }
            $all_students = SmStudent::withoutGlobalScopes()->whereYear('created_at', $academic_year)->get();
            $total_inistitutions = SmSchool::all()->count() - 1;
            $students = $all_students->count();
            $data['teachers'] = SmStaff::withoutGlobalScopes()->whereYear('created_at', $academic_year)->where(function($q)  {
	            $q->where('role_id', 4)->orWhere('previous_role_id', 4);
            })->count();
            $data['staffs'] = SmStaff::withoutGlobalScopes()->whereYear('created_at', $academic_year)->where('role_id', '!=', 4)->count();
            $data['inactiveStu'] =  $all_students->where('active_status', 0)->count();
            $data['teachers'] = SmStaff::withoutGlobalScopes()->whereYear('created_at', $academic_year)->where(function($q)  {
	            $q->where('role_id', 4)->orWhere('previous_role_id', 4);
            })->count();
            $data['saasStaffs'] = SmStaff::withoutGlobalScopes()->whereYear('created_at', $academic_year)->where('is_saas', 1)->count();
            $data['schoolAdmin'] = User::whereYear('created_at', $academic_year)->wherenotNull('school_id')->where('is_saas', 0)->where('role_id', 1)->count();
            $all_fees_payment = SmFeesPayment::where('active_status', 1)->get();
            $m_add_incomes = $day_expenses->where('date', 'like', date('Y-m-') . '%')->sum('amount');
            $m_fees_payments = $all_fees_payment->where('payment_date', 'like', date('Y-m-') . '%')->sum('amount');
            $m_item_sells = SmItemSell::withoutGlobalScopes()->where('active_status', 1)->where('sell_date', 'like', date('Y-m-') . '%')->sum('total_paid');
            $m_total_income = $m_add_incomes + $m_fees_payments + $m_item_sells;
            $m_add_expenses = $day_expenses->where('date', 'like', date('Y-m-') . '%')->sum('amount');
            $m_item_receives = SmItemReceive::withoutGlobalScopes()->where('active_status', 1)->where('receive_date', 'like', date('Y-m-') . '%')->sum('total_paid');
            $m_payroll_payments = SmHrPayrollGenerate::where('active_status', 1)->where('payroll_status', 'P')->where('created_at', 'like', date('Y-m-') . '%')->sum('net_salary');

            $m_total_expense = $m_add_expenses + $m_item_receives + $m_payroll_payments;

            // for current year


            $y_add_incomes = $day_expenses->where('date', 'like', date('Y-') . '%')->sum('amount');
            $y_fees_payments =$all_fees_payment->where('payment_date', 'like', date('Y-') . '%')->sum('amount');
            $y_item_sells = SmItemSell::withoutGlobalScopes()->where('active_status', 1)->where('sell_date', 'like', date('Y-') . '%')->sum('total_paid');

            $y_total_income = $y_add_incomes + $y_fees_payments + $y_item_sells;


            $y_add_expenses = $day_expenses->where('date', 'like', date('Y-') . '%')->sum('amount');
            $y_item_receives = SmItemReceive::withoutGlobalScopes()->where('active_status', 1)->where('receive_date', 'like', date('Y-') . '%')->sum('total_paid');
            $y_payroll_payments = SmHrPayrollGenerate::where('active_status', 1)->where('payroll_status', 'P')->where('created_at', 'like', date('Y-') . '%')->sum('net_salary');

            $y_total_expense = $y_add_expenses + $y_item_receives + $y_payroll_payments;
            $sub_institute = $total_inistitutions - 1;
            return view('saas::dashboard2', compact('total_inistitutions', 'chart_data', 'chart_data_yearly', 'module_links', 'students', 'data', 'm_total_income', 'm_total_expense', 'y_total_income', 'y_total_expense'));
        } else {
            $user_id = Auth()->user()->id;
            $school_id = Auth()->user()->school_id;

            $totalStudents = SmStudent::where('active_status', 1)->where('school_id', $school_id)->get();
            $totalTeachers = SmStaff::where('active_status', 1)->where(function($q)  {
                $q->where('role_id', 4)->orWhere('previous_role_id', 4);
            })->where('school_id', $school_id)->get();
            $totalParents = SmParent::all()->where('school_id', $school_id);
            $totalStaffs = SmStaff::where('active_status', 1)->where('role_id', '!=', 1)->where('role_id', '!=', 4)->where('school_id', $school_id)->get();
            $toDoLists = SmToDo::where('complete_status', 'P')->where('created_by', $user_id)->where('school_id', $school_id)->get();
            $toDoListsCompleteds = SmToDo::where('complete_status', 'C')->where('created_by', $user_id)->where('school_id', $school_id)->get();

            $notices = SmNoticeBoard::select('*')->where('active_status', 1)->where('school_id', $school_id)->get();
            $administrator_notices = SmAdministratorNotice::all();

            // for current month

            $m_add_incomes = SmAddIncome::where('active_status', 1)->where('date', 'like', date('Y-m-') . '%')->where('school_id', $school_id)->sum('amount');
            $m_fees_payments = SmFeesPayment::where('active_status', 1)->where('payment_date', 'like', date('Y-m-') . '%')->where('school_id', $school_id)->sum('amount');
            $m_item_sells = SmItemSell::where('active_status', 1)->where('sell_date', 'like', date('Y-m-') . '%')->where('school_id', $school_id)->sum('total_paid');

            $m_total_income = $m_add_incomes + $m_fees_payments + $m_item_sells;


            $m_add_expenses = SmAddExpense::withoutGlobalScopes()->where('active_status', 1)->where('date', 'like', date('Y-m-') . '%')->where('school_id', $school_id)->sum('amount');
            $m_item_receives = SmItemReceive::withoutGlobalScopes()->where('active_status', 1)->where('receive_date', 'like', date('Y-m-') . '%')->where('school_id', $school_id)->sum('total_paid');
            $m_payroll_payments = SmHrPayrollGenerate::where('active_status', 1)->where('payroll_status', 'P')->where('created_at', 'like', date('Y-m-') . '%')->where('school_id', $school_id)->sum('net_salary');

            $m_total_expense = $m_add_expenses + $m_item_receives + $m_payroll_payments;

            // for current year


            $y_add_incomes = SmAddIncome::withoutGlobalScopes()->where('active_status', 1)->where('date', 'like', date('Y-') . '%')->where('school_id', $school_id)->sum('amount');
            $y_fees_payments = SmFeesPayment::where('active_status', 1)->where('payment_date', 'like', date('Y-') . '%')->where('school_id', $school_id)->sum('amount');
            $y_item_sells = SmItemSell::withoutGlobalScopes()->where('active_status', 1)->where('sell_date', 'like', date('Y-') . '%')->where('school_id', $school_id)->sum('total_paid');

            $y_total_income = $y_add_incomes + $y_fees_payments + $y_item_sells;


            $y_add_expenses = SmAddExpense::withoutGlobalScopes()->where('active_status', 1)->where('date', 'like', date('Y-') . '%')->where('school_id', $school_id)->sum('amount');
            $y_item_receives = SmItemReceive::withoutGlobalScopes()->where('active_status', 1)->where('receive_date', 'like', date('Y-') . '%')->where('school_id', $school_id)->sum('total_paid');
            $y_payroll_payments = SmHrPayrollGenerate::where('active_status', 1)->where('payroll_status', 'P')->where('created_at', 'like', date('Y-') . '%')->where('school_id', $school_id)->sum('net_salary');

            $y_total_expense = $y_add_expenses + $y_item_receives + $y_payroll_payments;


            $holidays = SmHoliday::where('active_status', 1)->where('school_id', $school_id)->get();
            $events = array();
            foreach ($holidays as $k => $holiday) {
                $events[$k]['title'] = $holiday->holiday_title;
                $events[$k]['start'] = date('D M Y', strtotime($holiday->from_date));
                $events[$k]['end'] = date('D M Y', strtotime($holiday->to_date));
            }
            return view('saas::dashboard', compact('totalStudents', 'totalTeachers', 'totalParents', 'totalStaffs', 'toDoLists', 'notices',
                'toDoListsCompleteds', 'm_total_income', 'm_total_expense', 'y_total_income', 'y_total_expense', 'holidays', 'school_id', 'administrator_notices','module_links','chart_data','chart_data_yearly','events'));
        }
    }

    public function saveToDoData(Request $request)
    {
        $toDolists = new SmToDo();
        $toDolists->todo_title = $request->todo_title;
        $toDolists->date = date('Y-m-d', strtotime($request->date));
        $toDolists->created_by = Auth()->user()->id;
        $toDolists->updated_by = Auth::user()->id;
        $toDolists->school_id = Auth::user()->school_id;
        $results = $toDolists->save();

        if ($results) {
            return redirect()->back()->with('message-success', 'To Do Data added successfully');
        } else {
            return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
        }
    }

    public function viewToDo($id)
    {
        $toDolists = SmToDo::where('id', $id)->first();
        return view('backEnd.dashboard.viewToDo', compact('toDolists'));
    }

    public function editToDo($id)
    {
        $editData = SmToDo::find($id);
        return view('backEnd.dashboard.editToDo', compact('editData', 'id'));
    }

    public function updateToDo(Request $request)
    {
        $to_do_id = $request->to_do_id;

        $toDolists = SmToDo::find($to_do_id);
        $toDolists->todo_title = $request->todo_title;
        $toDolists->date = date('Y-m-d', strtotime($request->date));
        $toDolists->complete_status = $request->complete_status;
        $toDolists->updated_by = Auth()->user()->id;
        $results = $toDolists->update();

        if ($results) {
            return redirect()->back()->with('message-success', 'To Do Data updated successfully');
        } else {
            return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
        }
    }

    public function removeToDo(Request $request)
    {

        $to_do = SmToDo::find($request->id);
        $to_do->complete_status = "C";
        $to_do->save();
        $html = "";
        return response()->json('html');
    }

    public function getToDoList(Request $request)
    {
        $to_do_list = SmToDo::where('complete_status', 'C')->get();
        $datas = [];
        foreach ($to_do_list as $to_do) {
            $datas[] = array(
                'title' => $to_do->todo_title,
                'date' => date('jS M, Y', strtotime($to_do->date))
            );
        }

        return response()->json($datas);
    }

    public function viewNotice($id)
    {
        $notice = SmNoticeBoard::find($id);
        return view('backEnd.dashboard.view_notice', compact('notice'));
    }

    public function viewAdminNotice($id)
    {
        $notice = SmAdministratorNotice::find($id);
        return view('backEnd.dashboard.view_notice', compact('notice'));
    }


    public function updatePassowrd()
    {
        return view('backEnd.update_password');
    }


    public function updatePassowrdStore(Request $request)
    {
        $request->validate([
            'current_password' => "required",
            'new_password' => "required|same:confirm_password|min:6|different:current_password",
            'confirm_password' => 'required|min:6'
        ]);
        $user = Auth::user();


        if (Hash::check($request->current_password, $user->password)) {

            $user->password = Hash::make($request->new_password);
            $result = $user->save();

            if ($result) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } else {
            Toastr::error('Current password not match!', 'Failed');
                return redirect()->back();
        }
    }
}