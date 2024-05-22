<?php

namespace Modules\Saas\Http\Controllers;

use App\User;
use App\SmStaff;
use App\SmStyle;
use App\SmParent;
use App\SmSchool;
use App\SmStudent;
use App\SmItemSell;
use App\SmLanguage;
use App\SmAddIncome;
use App\SmsTemplate;
use App\SmAddExpense;
use App\SmDateFormat;
use App\SmFeesPayment;
use App\SmItemReceive;
use App\SmAcademicYear;
use App\SmEmailSetting;
use App\SmPaymentMethhod;
use App\SmGeneralSettings;
use App\InfixModuleManager;
use Illuminate\Support\Str;
use App\SmBackgroundSetting;
use App\SmHrPayrollGenerate;
use Illuminate\Http\Request;
use App\Traits\DatabaseTableTrait;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Scopes\StatusAcademicSchoolScope;
use Modules\Saas\Events\InstituteRegistration;
use Modules\University\Entities\UnAcademicYear;
use Modules\RolePermission\Entities\InfixPermissionAssign;
use Modules\Saas\Entities\SaasSchoolModulePermissionAssign;

class InstituteController extends Controller
{
    use DatabaseTableTrait;
    public function institution_List()
    {
        $module = 'Saas';
        if (User::checkPermission($module) != 100) {
            Toastr::error('Please verify your ' . $module . ' Module', 'Failed');
            return redirect()->route('Moduleverify', $module);
        }

        $data = SmSchool::orderBy('school_name', 'asc')->get()->except(1);
        return view('saas::institute.insitutionList', compact('data'));
    }
    public function InstitutionDetails($id)
    {
        $school = SmSchool::find($id);
        $totalStudents = SmStudent::where('active_status', 1)->where('school_id', $id)->get();
        $totalTeachers = SmStaff::where('active_status', 1)->where(function($q)  {
	        $q->where('role_id', 4)->orWhere('previous_role_id', 4);
        })->where('school_id', $id)->get();
        
        $totalParents = SmParent::all()->where('school_id', $id);
        $totalStaffs = SmStaff::where('active_status', 1)->where('role_id', '!=', 1)->where('role_id', '!=', 4)->where('school_id', $id)->get();

        $m_add_incomes = SmAddIncome::where('active_status', 1)->where('date', 'like', date('Y-m-') . '%')->where('school_id', $id)->sum('amount');
        $m_fees_payments = SmFeesPayment::where('active_status', 1)->where('payment_date', 'like', date('Y-m-') . '%')->where('school_id', $id)->sum('amount');
        $m_item_sells = SmItemSell::where('active_status', 1)->where('sell_date', 'like', date('Y-m-') . '%')->where('school_id', $id)->sum('total_paid');

        $m_total_income = $m_add_incomes + $m_fees_payments + $m_item_sells;

        $m_add_expenses = SmAddExpense::where('active_status', 1)->where('date', 'like', date('Y-m-') . '%')->where('school_id', $id)->sum('amount');
        $m_item_receives = SmItemReceive::where('active_status', 1)->where('receive_date', 'like', date('Y-m-') . '%')->where('school_id', $id)->sum('total_paid');
        $m_payroll_payments = SmHrPayrollGenerate::where('active_status', 1)->where('payroll_status', 'P')->where('created_at', 'like', date('Y-m-') . '%')->where('school_id', $id)->sum('net_salary');

        $m_total_expense = $m_add_expenses + $m_item_receives + $m_payroll_payments;

        // for current year

        $y_add_incomes = SmAddIncome::where('active_status', 1)->where('date', 'like', date('Y-') . '%')->where('school_id', $id)->sum('amount');
        $y_fees_payments = SmFeesPayment::where('active_status', 1)->where('payment_date', 'like', date('Y-') . '%')->where('school_id', $id)->sum('amount');
        $y_item_sells = SmItemSell::where('active_status', 1)->where('sell_date', 'like', date('Y-') . '%')->where('school_id', $id)->sum('total_paid');

        $y_total_income = $y_add_incomes + $y_fees_payments + $y_item_sells;

        $y_add_expenses = SmAddExpense::where('active_status', 1)->where('date', 'like', date('Y-') . '%')->where('school_id', $id)->sum('amount');
        $y_item_receives = SmItemReceive::where('active_status', 1)->where('receive_date', 'like', date('Y-') . '%')->where('school_id', $id)->sum('total_paid');
        $y_payroll_payments = SmHrPayrollGenerate::where('active_status', 1)->where('payroll_status', 'P')->where('created_at', 'like', date('Y-') . '%')->where('school_id', $id)->sum('net_salary');

        $y_total_expense = $y_add_expenses + $y_item_receives + $y_payroll_payments;

        return view('saas::institute.institution_details', compact('totalStudents', 'totalTeachers', 'totalParents', 'totalStaffs', 'school', 'm_total_income', 'm_total_expense', 'y_total_income', 'y_total_expense'));
    }

    public function institutionCreate()
    {
        if(isSubscriptionEnabled()){
            $packages = \Modules\Saas\Entities\SmPackagePlan::where('active_status', 1)->get();
        }else{
            $packages = [];
        }
        
        return view('saas::institute.institution_create', compact('packages'));
    }


    public function institutionEdit($id)
    {
        if($id == 1){
            abort(403);
        }
        $school = SmSchool::find($id);

        return view('saas::institute.institution_create', compact('school'));
    }
    
    public function institutionStore(Request $request)
    {
        $rules = [
            'school_name' => 'required|max:255|',
            'email' => 'required|string|email|max:255|',
            'password' => 'min:6|same:confirm_password',
            'confirm_password' => 'min:6',
            'domain' => 'required|max:191|unique:sm_schools,domain',
            'phone' => 'required|numeric'
        ];

        if (isSubscriptionEnabled()) {
            $rules ['package'] ='required';
        }
        
        $request->validate($rules);
        

       // DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::beginTransaction();

        try {

            $s = new SmSchool();
            $s->school_name = $request->school_name;
            $s->email = $request->email;
            $s->address = $request->address;
            $s->school_code = $request->school_code;
            $s->phone = $request->phone;
            $s->domain = $request->domain;
            $s->starting_date = !empty($request->opening_date) ? date('Y-m-d', strtotime($request->opening_date)) : "";
            $s->save();
            $school_id = $s->id;

           event(new InstituteRegistration($s));

            $settings_prefix = Str::lower(str_replace(' ', '_', $s->domain));
            $chat_settings = storage_path('app/chat/' . $settings_prefix . '_settings.json');
            if (!file_exists($chat_settings)) {
                copy(storage_path('app/chat/default_settings.json'), $chat_settings);
            }

            // create current year

            // if subscription module enable
            if(isSubscriptionEnabled()){
                // start trial payment 

                $package_info = \Modules\Saas\Entities\SmPackagePlan::find($request->package);

                $start_date = date('Y-m-d');
                $end_date =  date('Y-m-d', strtotime($start_date. ' + '.$package_info->duration_days.' days'));

                $setting = \Modules\Saas\Entities\SmSaasSubscriptionSetting::first();
                

                $tax = $package_info->price / 100 * $setting->amount;
                $amount = $package_info->price + $tax;

                $payment = new \Modules\Saas\Entities\SmSubscriptionPayment();
                $payment->package_id = $request->package;
                $payment->payment_type = 'paid';
                $payment->approve_status = 'approved';
                $payment->payment_date = date('Y-m-d');
                $payment->amount = $amount;
                $payment->payment_method = 'cash';
                $payment->school_id = $school_id;

                $payment->start_date = $start_date;
                $payment->end_date = $end_date;
                $payment->buy_type = 'instantly';
                $payment->save();
                // end trial payment 
            }
            // if subscription module disable


            try {
                $user = new User();
                $user->role_id = 1;
                $user->school_id = $school_id;
                $user->full_name = 'Admin';
                $user->email = $request->email;
                $user->username = $request->email;
                $user->access_status = 1;
                $user->verified = 1;
                $user->active_status = 1;
                $user->is_registered = 1;
                $user->password = Hash::make($request->password);
                $user->save();
                $last_inserted_id = $user->id;
                \App\Models\Theme::withOutGlobalScopes()->where('school_id', $school_id)->update([
                    'created_by' =>$last_inserted_id
                ]);
                $default_theme = \App\Models\Theme::withOutGlobalScopes()->where('school_id', $school_id)->where('is_default', 1)->first();
                $user->style_id =  $default_theme? $default_theme->id : null;
                $user->save();

                try {
                    $staff_number = SmStaff::count();
                    $new_staff = new SmStaff();
                    $new_staff->user_id = $last_inserted_id ;
                    $new_staff->school_id = $school_id;
                    $new_staff->role_id = 1;
                    $new_staff->staff_no = $staff_number + 1;
                    $new_staff->designation_id = 1;
                    $new_staff->department_id = 1;
                    $new_staff->first_name = 'System';
                    $new_staff->last_name = 'Admin';
                    $new_staff->full_name = 'System Admin';
                    $new_staff->gender_id = 1;
                    $new_staff->email = $request->email;
                    $new_staff->staff_photo = 'public/uploads/staff/staff.jpg';
                    $new_staff->save();
                     
                
                    $data['email'] = $request->email;
                    $data['password'] = $request->password;
                    
                    try {

                        $reciver_email = $request->email;
                        $receiver_name =  $request->school_name;
                        $subject= "school_login_access";
                        $login_url = url($request->domain.'.'.config('app.short_url').'/login');
                        $compact = array(
                            'email' =>  $request->email, 
                            'password' => $request->password, 
                            'institute_name' => $request->school_name, 
                            'application_name' => config('app.name'), 
                            'login_url' => '<a href="'.$login_url.'">'.$login_url.'</a>'
                        );
                        @send_mail($reciver_email, $receiver_name, $subject ,$compact);

                        
                    } catch (\Exception $e) {
                        Toastr::error('Email sending failed, Please setup email', 'Failed');
                    }

                    DB::commit();
                    $default_tables = getVar('defaults');
                    foreach($default_tables as $t){
                        $function = 'after_commit_callback';
                        if(function_exists($function) && Schema::hasTable($t)){
                            $function($s, $t);
                        }
                    }

                    // Mail::to($user->email)->send(new VerifyMail($user));
                    Toastr::success('School has been registration successfully.', 'Success');
                    return redirect('administrator/institution-list');
                } catch (\Exception $e) {
                    DB::rollback();
                    return redirect()->back()->with('message-danger', 'Cannot add Additional admin info for Admin registration');
                }
                
                
            } catch (\Exception $e) {
                DB::rollback();
                return redirect()->back()->with('message-danger', 'Cannot add Admin login credentials');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('message-danger', 'Ops Sorry! Something went wrong, please try again');
        }

    }
    public function institutionUpdate(Request $request)
    {
        if($request->id == 1){
            abort(403);
        }
        $request->validate([
            'school_name' => 'required|max:255',
            'email' => 'required|string|email|max:255',
            'domain' => 'required|max:191|unique:sm_schools,domain,'.$request->id,
            'password' => 'sometimes|nullable|min:6|same:confirm_password',
            'confirm_password' => 'sometimes|nullable|min:6',
            'phone' => 'required|numeric'
        ]);
        
        DB::beginTransaction();
        try {
            $s = SmSchool::find($request->id);
            $previous_domain = $s->domain;
            $s->school_name = $request->school_name;
            $s->email = $request->email;
            $s->address = $request->address;
            $s->school_code = $request->school_code;
            $s->domain = $request->domain;
            $s->starting_date = !empty($request->opening_date) ? date('Y-m-d', strtotime($request->opening_date)) : "";
            $s->save();
            $school_id = $s->id;

            $previous_file = Str::lower(str_replace(' ', '_', $previous_domain));
            $previous = storage_path('app/chat/' . $previous_file . '_settings.json');
            $new_file = Str::lower(str_replace(' ', '_', $request->domain));
            $new = storage_path('app/chat/' . $new_file . '_settings.json');
            if (file_exists($previous) && copy($new, $previous)) {
                unlink($previous);
            }

            $general_setting = SmGeneralSettings::where('school_id', $school_id)->first();
            $general_setting->school_name = $request->school_name;
            $general_setting->site_title = $request->school_name;
            $general_setting->email = $request->email;
            $general_setting->address = $request->address;
            $general_setting->school_code = $request->school_code;
            $general_setting->school_id = $school_id;
            $general_setting->save();



            try {
                $user = User::where('school_id', $school_id)->first();
                $user->email = $request->email;
                $user->username = $request->email;
                if($request->filled('password') && $request->filled('confirm_password')) {
                    $user->password = Hash::make($request->password);
                }
                $user->save();
                $last_inserted_id = $user->id;

                try {
                    $staff = SmStaff::where('user_id', $last_inserted_id)->first();
                    $staff->email = $request->email;
                    $staff->save();

                    DB::commit();


                    //Mail::to($user->email)->send(new VerifyMail($user));
                    Toastr::success('School has been updated successfully.', 'Success');
                    return redirect('administrator/institution-list');
                } catch (\Exception $e) {
                    DB::rollback();
                    return redirect()->back()->with('message-danger', 'Cannot add Additional admin info for Admin registration');
                }
            } catch (\Exception $e) {
                DB::rollback();
                return redirect()->back()->with('message-danger', 'Cannot add Admin login credentials');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('message-danger', 'Ops Sorry! Something went wrong, please try again');
        }


        return view('saas::institute.institution_register', compact('data'));
    }


    // institution approve


    public function institutionDelete($id)
    {

        if($id == 1){
            abort(403);
        }
        try {
            $tables = $this->getAllTables();
            $db = "Tables_in_".env('DB_DATABASE');
            foreach($tables as $table) {
                if(config('database.default') == 'mysql'){
                    $table_name = $table->{$db};
                } else{
                    $table_name = $table->tablename;
                }
                if ((Schema::hasColumns($table_name, ['school_id']))) {
                    DB::table($table_name)->where('school_id',$id)->delete();
                }
            }

            $school = SmSchool::where('id', $id)->first();
            $previous_file = Str::lower(str_replace(' ', '_', $school->domain));
            $previous = storage_path('app/chat/' . $previous_file . '_settings.json');
            if (file_exists($previous) ) {
                unlink($previous);
            }
            $school->delete();
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }
    public function institutionApprove(Request $request)
    {
        if($request->id == 1){
            abort(403);
        }
        if ($request->status == 'on') {
            $status = 1;
        } else {
            $status = 0;
        }


        $school = SmSchool::find($request->id);
        $school->active_status = $status;
        $school->save();

        return response()->json($school);
    }
    // institution


    public function institutionEnable(Request $request)
    {
        if($request->id == 1){
            abort(403);
        }
        if ($request->status == 'on') {
            $status = 'yes';
        } else {
            $status = 'no';
        }
        $school = SmSchool::find($request->id);
        $school->is_enabled = $status;
        $school->save();
        return response()->json($school);
    }

    public function secretLogin(Request $request, $school_id){
        if(!auth()->check() && !$request->code){
            return redirect()->route('login');
        }

        if($school_id == 1){
            abort(404);
        }
        $key = 'mynameistariq';
        $school = SmSchool::findOrFail($school_id);

        if(!$request->code){
            $code = encrypt($key);
            return redirect('//'.$school->domain.'.'.config('app.short_url').'/secret-login/'.$school->id.'?code='.$code);
        }

        try {
           $d_key = decrypt($request->code);
        } catch(\Exception $e){
            $d_key = 'null';
        }

        if( $d_key == $key){
            $user = User::where('school_id', $school->id)->where('role_id', 1)->first();
            if(!$user){
                abort(404);
            }

            Auth::guard()->login($user);
            // System date format save in session
            $date_format_id = generalSetting()->date_format_id;
            $system_date_format = 'jS M, Y';
            if($date_format_id){
                $system_date_format = SmDateFormat::where('id', $date_format_id)->first(['format'])->format;
            }

            session()->put('system_date_format', $system_date_format);

            // System academic session id in session

            $all_modules = [];
            $modules = InfixModuleManager::select('name')->get();
            foreach ($modules as $module) {
                $all_modules[] = $module->name;
            }

            session()->put('all_module', $all_modules);

            //Session put text decoration
            $ttl_rtl = generalSetting()->ttl_rtl;
            session()->put('text_direction', $ttl_rtl);

            $active_style = SmStyle::where('school_id', Auth::user()->school_id)->where('is_active', 1)->first();
            session()->put('active_style', $active_style);

            $all_styles = SmStyle::where('school_id', Auth::user()->school_id)->get();
            session()->put('all_styles', $all_styles);

            //Session put activeLanguage
            $systemLanguage = SmLanguage::where('school_id', Auth::user()->school_id)->get();
            session()->put('systemLanguage', $systemLanguage);
            //session put academic years

            if(moduleStatusCheck('University')){
                $academic_years = Auth::check() ? UnAcademicYear::where('active_status', 1)->where('school_id', Auth::user()->school_id)->get() : '';
            }else{
                $academic_years = Auth::check() ? SmAcademicYear::where('active_status', 1)->where('school_id', Auth::user()->school_id)->get() : '';
            }
            session()->put('academic_years', $academic_years);
            //session put sessions and selected language
            if (Auth::user()->role_id == 2) {
                $profile = SmStudent::where('user_id', Auth::id())->withOutGlobalScopes([StatusAcademicSchoolScope::class])->first();
                session()->put('profile', @$profile->student_photo);
                $session_id = $profile ? $profile->academic_id : generalSetting()->session_id;
            } else {
                $profile = SmStaff::where('user_id', Auth::id())->first();
                if ($profile) {
                    session()->put('profile', $profile->staff_photo);
                }
                $session_id = $profile && $profile->academic_id ? $profile->academic_id : generalSetting()->session_id;
            }

            if(moduleStatusCheck('University')){
                $session_id = generalSetting()->un_academic_id;
                if(!$session_id){
                    $session = UnAcademicYear::where('school_id', Auth::user()->school_id)->where('active_status', 1)->first();
                } else{
                    $session = UnAcademicYear::find($session_id);
                }

                session()->put('sessionId', $session->id);
                session()->put('session', $session);
            }
            else{
                if(!$session_id){
                    $session = SmAcademicYear::where('school_id', Auth::user()->school_id)->where('active_status', 1)->first();
                } else{
                    $session = SmAcademicYear::find($session_id);
                }
                if(!$session){
                    $session = SmAcademicYear::where('school_id', Auth::user()->school_id)->first();
                }

                session()->put('sessionId', $session->id);
                session()->put('session', $session);
            }

            session()->put('school_config', generalSetting());

            $dashboard_background = DB::table('sm_background_settings')->where([['is_default', 1], ['title', 'Dashboard Background']])->first();
            session()->put('dashboard_background', $dashboard_background);

            $email_template = SmsTemplate::where('school_id',Auth::user()->school_id)->first();
            session()->put('email_template', $email_template);

            session(['role_id' => Auth::user()->role_id]);
            return redirect()->intended('/admin-dashboard');
        }

        abort(404);
    }
}