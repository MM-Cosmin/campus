<?php

namespace Modules\Saas\Http\Controllers;

use Illuminate\Support\Facades\Schema;
use Paystack;
use Stripe;
use App\User;
use App\SmStaff;
use App\SmSchool;
use Carbon\Carbon;
use PayPal\Api\Item;
use PayPal\Api\Payer;
use PayPal\Api\Amount;
use App\SmEmailSetting;
use PayPal\Api\Payment;
use PayPal\Api\ItemList;

use App\SmGeneralSettings;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use Illuminate\Http\Request;
use PayPal\Api\RedirectUrls;
use App\SmPaymentGatewaySetting;
use PayPal\Api\PaymentExecution;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Hash;
use Modules\Saas\Entities\VerifyUser;
use PayPal\Auth\OAuthTokenCredential;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Modules\Saas\Events\InstituteRegistration;
use Modules\Saas\Entities\SmSaasPaymentGatewaySetting;




class RegistrationController extends Controller
{

    private $_api_context;
    private $mode;
    private $client_id;
    private $secret;


    public function institution_register_new()
    {
        if(isSubscriptionEnabled()){
            $packages = \Modules\Saas\Entities\SmPackagePlan::where('active_status', 1)->get();
            $setting = \Modules\Saas\Entities\SmSaasSubscriptionSetting::find(1);
            $payment_setting = \Modules\Saas\Entities\SmSaasPaymentGatewaySetting::where('gateway_name', 'Stripe')->first();
            $payment_methods = \Modules\Saas\Entities\SmSaasPaymentMethod::where('active_status', 1)->get();
            $array_payment_methods = [];
            foreach($payment_methods as $payment_method){
                $array_payment_methods[] = $payment_method->id;
            }
            $bank_details = \Modules\Saas\Entities\SmSaasPaymentGatewaySetting::where('gateway_name', 'Bank')->first();
            $cheque_details = \Modules\Saas\Entities\SmSaasPaymentGatewaySetting::where('gateway_name', 'Cheque')->first();
            $account_detail['bank'] = $bank_details->bank_details;
            $account_detail['cheque'] = $cheque_details->cheque_details;
        }else{
            $packages = [];
            $setting = [];
            $payment_setting = [];
            $array_payment_methods = [];
            $account_detail = [];
        }

        if(Session::get('school_id') != ''){
            SmSchool::destroy(Session::get('school_id'));
        }
        
        return view('saas::systemSettings.new_registration', compact('packages', 'setting', 'payment_setting', 'array_payment_methods', 'account_detail'));
    }
    public function institutionNewStore(Request $request)
    {  
        $rules = [
            'school_name' => 'required|max:255|',
            'school_email' => 'required|string|email|max:255|',
            'password' => 'min:6|same:confirm_password',
            'confirm_password' => 'min:6',
            'domain' => 'required|string|min:1|max:191|unique:sm_schools,domain'
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
                $s->email = $request->school_email;
                $s->address = $request->address;
                $s->school_code = $request->school_code;
                $s->domain = $request->domain;
                $s->phone = $request->phone;
                $s->package_id = $request->package;

               

                if (isSubscriptionEnabled()) {
                    $subscription_saas = \Modules\Saas\Entities\SmSaasSubscriptionSetting::find(1);
                    if ($subscription_saas->is_auto_approve == 0) {
                        $s->active_status = 0;
                    }
                }

                // return $s;
                $s->save();
                event(new InstituteRegistration($s));
                $school_id = $s->id;

                try {
                    $user = new User();
                    $user->role_id = 1;
                    $user->school_id = $school_id;
                    $user->full_name = 'Admin';
                    $user->email = $request->school_email;
                    $user->username = $request->school_email;
                    $user->access_status = 1;
                    $user->verified = 1;
                    $user->active_status = 1;
                    $user->is_registered = 1;
                    $user->password = Hash::make($request->password);
                    $user->save();
                    $last_inserted_id = $user->id;

                    try {
                        $staff_number = SmStaff::count();
                        DB::table('sm_staffs')->insert([
                            [
                                'user_id' => $last_inserted_id,
                                'school_id' => $school_id,
                                'role_id' => 1,
                                'staff_no' => $staff_number + 1,
                                'designation_id' => 1,
                                'department_id' => 1,
                                'first_name' => 'System',
                                'last_name' => 'Admin',
                                'full_name' => 'System Admin',
                                'gender_id' => 1,
                                'email' => $request->school_email,
                                'staff_photo' => 'public/uploads/staff/staff.jpg',
                            ]
                        ]);


                        $data['email'] = $request->school_email;
                        $data['password'] = $request->password;

                        if (!isSubscriptionEnabled()) {

                            try {
                                $reciver_email = $request->school_email;
                                $receiver_name =  $request->school_name;
                                $subject= "school_login_access";
                                $login_url = url($request->domain.'.'.config('app.short_url').'/login');
                                $compact = array(
                                    'email' =>  $request->school_email, 
                                    'password' => $request->password, 
                                    'institute_name' => $request->school_name, 
                                    'application_name' => config('app.name'), 
                                    'login_url' => '<a href="'.$login_url.'">'.$login_url.'</a>'
                                );
                                @send_mail($reciver_email, $receiver_name, $subject ,$compact);

                            
                            } catch (\Exception $e) {
                            
                                Log::info($e->getMessage());
                            }
                        }


                        // Start Subscription
                        if (isSubscriptionEnabled()) {

                            if ($request->payment_type == "paid") {
                                $package_detail = \Modules\Saas\Entities\SmPackagePlan::find($request->package);
                                $start_date = date('Y-m-d');
                                $end_date = date('Y-m-d', strtotime($start_date . ' + ' . @$package_detail->duration_days . ' days'));

                                if ($request->relationButton == 'cash') {
                                    $payment = new \Modules\Saas\Entities\SmSubscriptionPayment();
                                    $payment->package_id = $request->package;
                                    $payment->amount = $request->amount_tax;
                                    $payment->payment_type = 'paid';
                                    $payment->approve_status = 'pending';
                                    $payment->payment_date = date('Y-m-d');
                                    $payment->payment_method = $request->relationButton;
                                    $payment->school_id = $school_id;

                                    // $payment->start_date = $start_date;
                                    // $payment->end_date = $end_date;
                                    $payment->buy_type = 'instantly';

                                    $payment->save();
                                } elseif ($request->relationButton == 'cheque') {
                                    $fileName = "";
                                    if ($request->file('cheque_photo') != "") {
                                        $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                                        $file = $request->file('cheque_photo');
                                        $fileSize =  filesize($file);
                                        $fileSizeKb = ($fileSize / 1000000);
                                        if($fileSizeKb >= $maxFileSize){
                                            Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                                            return redirect()->back();
                                        }
                                        $file = $request->file('cheque_photo');
                                        $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                                        $file->move('Modules/Saas/public/uploads/', $fileName);
                                        $fileName = 'Modules/Saas/public/uploads/' . $fileName;
                                    }

                                    $payment = new \Modules\Saas\Entities\SmSubscriptionPayment();
                                    $payment->package_id = $request->package;
                                    $payment->amount = $request->amount_tax;
                                    $payment->bank_name = $request->bank_name_cheque;
                                    $payment->account_holder = $request->account_holder_cheque;
                                    $payment->payment_type = 'paid';
                                    $payment->approve_status = 'pending';
                                    $payment->payment_date = date('Y-m-d');
                                    $payment->payment_method = $request->relationButton;
                                    $payment->school_id = $school_id;

                                    // $payment->start_date = $start_date;
                                    // $payment->end_date = $end_date;
                                    $payment->buy_type = 'instantly';

                                    $payment->file = $fileName;
                                    $payment->save();

                                } elseif ($request->relationButton == 'bank') {

                                    $fileName = "";
                                    if ($request->file('bank_photo') != "") {
                                        $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                                        $file = $request->file('bank_photo');
                                        $fileSize =  filesize($file);
                                        $fileSizeKb = ($fileSize / 1000000);
                                        if($fileSizeKb >= $maxFileSize){
                                            Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                                            return redirect()->back();
                                        }
                                        $file = $request->file('bank_photo');
                                        $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                                        $file->move('Modules/Saas/public/uploads/', $fileName);
                                        $fileName = 'Modules/Saas/public/uploads/' . $fileName;
                                    }


                                    $payment = new \Modules\Saas\Entities\SmSubscriptionPayment();
                                    $payment->package_id = $request->package;
                                    $payment->amount = $request->amount_tax;
                                    $payment->bank_name = $request->bank_name_bank;
                                    $payment->account_holder = $request->account_holder_bank;
                                    $payment->payment_type = 'paid';
                                    $payment->approve_status = 'pending';
                                    $payment->payment_date = date('Y-m-d');
                                    $payment->payment_method = $request->relationButton;
                                    $payment->school_id = $school_id;

                                    // $payment->start_date = $start_date;
                                    // $payment->end_date = $end_date;
                                    $payment->buy_type = 'instantly';

                                    $payment->file = $fileName;
                                    $payment->save();


                                } elseif ($request->relationButton == 'stripe') {

                                    $payment_setting = \Modules\Saas\Entities\SmSaasPaymentGatewaySetting::where('gateway_name', 'Stripe')->first();

                                    Stripe\Stripe::setApiKey($payment_setting->gateway_secret_key);

                                    Stripe\Charge::create([
                                        "amount" => $request->amount_tax * 100,
                                        "currency" => "usd",
                                        "source" => $request->stripeToken,
                                        "description" => "Test payment from InfixEdu."
                                    ]);

                                    $payment = new \Modules\Saas\Entities\SmSubscriptionPayment();
                                    $payment->package_id = $request->package;
                                    $payment->amount = $request->amount_tax;
                                    $payment->bank_name = $request->bank_name_bank;
                                    $payment->account_holder = $request->account_holder_bank;
                                    $payment->payment_type = 'paid';
                                    $payment->approve_status = 'approved';
                                    $payment->payment_date = date('Y-m-d');
                                    $payment->payment_method = $request->relationButton;
                                    $payment->school_id = $school_id;

                                    $payment->start_date = $start_date;
                                    $payment->end_date = $end_date;
                                    $payment->buy_type = 'instantly';

                                    $payment->save();

                                } elseif ($request->relationButton == 'paystack') {

                                    $payment = new \Modules\Saas\Entities\SmSubscriptionPayment();
                                    $payment->package_id = $request->package;
                                    $payment->amount = $request->amount_tax;
                                    $payment->bank_name = $request->bank_name_bank;
                                    $payment->account_holder = $request->account_holder_bank;
                                    $payment->payment_type = 'paid';
                                    $payment->approve_status = 'pending';
                                    $payment->payment_date = date('Y-m-d');
                                    $payment->payment_method = $request->relationButton;
                                    $payment->school_id = $school_id;
                                    $payment->start_date = $start_date;
                                    $payment->end_date = $end_date;
                                    $payment->buy_type = 'instantly';

                                    $payment->save();

                                    Session::put('payment_id', $payment->id);
                                    Session::put('payment_type', 'Saas');

                                    DB::commit();

                                    return Paystack::getAuthorizationUrl()->redirectNow();

                                }

                                elseif ($request->relationButton == 'paypal') {
                                    $data = [];
                                    $serviceCharge = 0;
                                    $gateway_setting = SmPaymentGatewaySetting::where('gateway_name',"PayPal")->where('school_id',Auth::user()->school_id)->first();
                                    if($gateway_setting){
                                        $serviceCharge = chargeAmount("PayPal", $request->amount_tax);
                                    }
                                    $user = Auth::user();
                                    $paypal_payment = new \Modules\Saas\Entities\SmSubscriptionPayment();
                                    $paypal_payment->package_id = $request->package;
                                    $paypal_payment->amount = $request->amount_tax;
                                    $paypal_payment->bank_name = $request->bank_name_bank;
                                    $paypal_payment->account_holder = $request->account_holder_bank;
                                    $paypal_payment->payment_type = 'pending';
                                    $paypal_payment->approve_status = 'pending';
                                    $paypal_payment->payment_date = date('Y-m-d');
                                    $paypal_payment->payment_method = $request->relationButton;
                                    $paypal_payment->school_id = $school_id;
                                    $paypal_payment->buy_type = 'instantly';
                                    $paypal_payment->save();
                                    DB::commit();
                                    $data['request_amount'] = $request->amount_tax ;
                                    $data['amount'] = $request->amount_tax + $serviceCharge;
                                    $data['type'] = "saas_school_reg";
                                    $data['method'] = "PayPal";
                                    $data['description'] = "Saas Subscription Package";
                                    $data['subs_payment_id'] = $paypal_payment->id;
                                    $classMap = config('paymentGateway.'.$data['method']);
                                    $make_payment = new $classMap();
                                    return $make_payment->handle($data);
                                }

                                if ($request->relationButton != "paystack") {
                                    try {

                                        $systemSetting = SmGeneralSettings::select('school_name', 'email')->where('id', 1)->first();
                                        $systemEmail = SmEmailSetting::find(1);
                                        $system_email = $systemEmail->from_email;
                                        $school_name = $systemSetting->school_name;
                                        $data = $request->package;
                                        $to_email = $request->school_email;

                                        $settings = SmEmailSetting::first();
                                        $reciver_email = $to_email;
                                        $receiver_name =  $school_name;
                                        $subject= "Invoice";
                                        $view ="saas::school.send_mail";
                                        $compact['package'] =  $data; 
                                        @send_mail($reciver_email, $receiver_name, $subject , $view ,$compact);

                                    } catch (\Exception $e) {
                                       
                                    }
                                }

                            } else {

                                $package_detail = \Modules\Saas\Entities\SmPackagePlan::find($request->package);
                                $start_date = date('Y-m-d');
                                $end_date = date('Y-m-d', strtotime($start_date . ' + ' . @$package_detail->trial_days . ' days'));

                                // start trial payment
                                $payment = new \Modules\Saas\Entities\SmSubscriptionPayment();
                                $payment->package_id = $request->package;
                                $payment->payment_type = 'trial';
                                $payment->approve_status = 'pending';
                                $payment->payment_date = date('Y-m-d');
                                $payment->school_id = $school_id;

                                $payment->start_date = $start_date;
                                $payment->end_date = $end_date;

                                $payment->save();
                                // end trial payment
                            }

                        }
                        // end subscription

                        DB::commit();

                        $default_tables = getVar('defaults');
                        foreach($default_tables as $t){
                            $function = 'after_commit_callback';
                            if(function_exists($function) && Schema::hasTable($t)){
                                $function($s, $t);
                            }
                        }

                        Toastr::success('School has been registration successfully.', 'Success');
                        //Log::info($e->getMessage());
                        return redirect('//'.$request->domain.'.'.config('app.short_url').'/home');
                    } catch (\Exception $e) {
                        DB::rollback();
                        Log::info($e->getMessage());
        
                        Toastr::error('Cannot add Additional admin info for Admin registration', 'Failed');
                        return redirect()->back();
                    }
                } catch (\Exception $e) {
   
                    DB::rollback();
                
                    Log::info($e->getMessage());
                    Toastr::error('Cannot add Admin login credentials.', 'Failed');
                    return redirect()->back();
                }
            } catch (\Exception $e) {
       
                DB::rollback();
            
                Log::info($e->getMessage());
                Toastr::error('Ops Sorry! Something went wrong, please try again.', 'Failed');
                return redirect()->back();
            }
            
            return view('saas::institute.institution_register', compact('data'));

        
    }
    public function getPaymentStatus(Request $request)
    {
        try {
            $payment_id = Session::get('paypal_payment_id');
        Session::forget('paypal_payment_id');
        if (empty($request->input('PayerID')) || empty($request->input('token'))) {
            \Session::put('error','Payment failed');
            return Redirect::route('/login');
        }
        $payment = Payment::get($payment_id, $this->_api_context);        
        $execution = new PaymentExecution();
        $execution->setPayerId($request->input('PayerID'));        
        $result = $payment->execute($execution, $this->_api_context);
        
        if ($result->getState() == 'approved') { 
            $paypal_fees_paymentId = Session::get('paypal_fees_paymentId');
            if(!is_null($paypal_fees_paymentId)){

                $payment = \Modules\Saas\Entities\SmSubscriptionPayment::find($paypal_fees_paymentId);
                if($payment){
                    $package_detail = \Modules\Saas\Entities\SmPackagePlan::find($payment->package_id );
                    $start_date = date('Y-m-d');
                    $end_date = date('Y-m-d', strtotime($start_date . ' + ' . @$package_detail->duration_days . ' days'));
                    $payment->start_date = $start_date;
                    $payment->end_date = $end_date;
                    $payment->payment_type = 'paid';
                    $payment->approve_status = 'approved';
                    $payment->save();
                    Session::put('success', 'Payment success');
                    Toastr::success('Operation successful', 'Success');
                    return redirect()->back();
                }


            }
            else{
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }  
        }
        
           
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function userVerify(Request $request, $token)
    {

        $verify_user = VerifyUser::where('token', '=', $token)->first();


        if ($verify_user != "") {

            $now = Carbon::now();
            $created_at = Carbon::parse($verify_user->created_at);
            $diffHuman = $created_at->diffForHumans($now);  // 3 Months ago
            $diffHours = $created_at->diffInHours($now);  // 3
            $diffMinutes = $created_at->diffInMinutes($now);

            // return  $diffMinutes;
            if ($diffMinutes <= 160) {
                DB::beginTransaction();

                try {
                    $new_user = User::find($verify_user->user_id);
                    $new_user->access_status = 1;
                    $new_user->active_status = 1;
                    $new_user->verified = 1;
                    $new_user->save();

                    $verify_user->delete();
                    DB::commit();

                    Toastr::success('Email Verification Done Successfully', 'Success');
                    return redirect('/login');
                } catch (\Exception $e) {
                    DB::rollback();
                    Toastr::error('Email Verification Not Done', 'Failed');
                    return redirect('institution-register')->with('message-danger', 'Email Verification Not Done');
                }
            } else {
                Toastr::error('Link Validity Is Expired', 'Failed');
                return redirect('institution-register')->with('message-danger', 'Link Validity Is Expired');
            }
        } else {
            Toastr::error('You have clicked on a invalid link', 'Failed');
            return redirect('institution-register')->with('message-danger', 'You have clicked on a invalid link, please try again');
        }
    }

    public function validate(Request $request){
        $request->validate([
            'domain' => 'required|string|min:1|max:191|unique:sm_schools,domain'
        ]);

        return response()->json(true);
    }
}
