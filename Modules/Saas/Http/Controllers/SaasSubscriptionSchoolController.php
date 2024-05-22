<?php

namespace Modules\Saas\Http\Controllers;

use Mail;
use Stripe;
use App\User;
use Paystack;
use App\SmSchool;
use App\SmStudent;
use PayPal\Api\Item;
use PayPal\Api\Payer;
use PayPal\Api\Amount;
use App\SmEmailSetting;
use PayPal\Api\Payment;
use PayPal\Api\ItemList;
use App\SmGeneralSettings;
use Illuminate\Support\Str;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use Illuminate\Http\Request;
use PayPal\Api\RedirectUrls;
use Illuminate\Http\Response;
use App\SmPaymentGatewaySetting;
use PayPal\Api\PaymentExecution;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use PayPal\Auth\OAuthTokenCredential;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Modules\Saas\Entities\SmPackagePlan;
use Illuminate\Support\Facades\Validator;
use Modules\Saas\Entities\SmSaasPaymentMethod;
use Modules\Saas\Entities\SmPackagePlanFeature;
use Modules\Saas\Entities\SmSubscriptionPayment;
use Modules\Saas\Entities\SmSaasSubscriptionSetting;
use Modules\Saas\Entities\SmSaasPaymentGatewaySetting;



class SaasSubscriptionSchoolController extends Controller
{

    public function index()
    {
        return view('saas::index');
    }

    public function create()
    {
        return view('saas::create');
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        return view('saas::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('saas::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function packageList()
    {

        $module = 'Saas';
        if (User::checkPermission($module) != 100) {
            Toastr::error('Please verify your ' . $module . ' Module', 'Failed');
            return redirect()->route('Moduleverify', $module);
        }

        $packages = SmPackagePlan::where('active_status', 1)->get();

        $purchase_packages = SmSubscriptionPayment::with('package')->where('school_id', Auth::user()->school_id)->get();


        $last_record = SmSubscriptionPayment::with('package')->orderBy('id', 'desc')->where('approve_status', 'approved')->where('school_id', Auth::user()->school_id)->first();

        if ($last_record == '') {
            $last_record = SmSubscriptionPayment::with('package')->orderBy('id', 'desc')->where('payment_type', 'trial')->where('school_id', Auth::user()->school_id)->first();
        }

        $now_time = date('Y-m-d');
        $now_time = date('Y-m-d', strtotime($now_time . ' + 1 days'));

        $last_Active = SmSubscriptionPayment::with('package')->orderBy('id', 'desc')->where('approve_status', 'approved')
            ->where('start_date', '<=', $now_time)->where('end_date', '>=', $now_time)->where('school_id', Auth::user()->school_id)->first();

        return view('saas::school/package_list', compact('packages', 'purchase_packages', 'last_record', 'last_Active'));


    }

    public function buyNow($id, $slug)
    {

        try {

            $active_student = SmStudent::where('school_id', Auth::user()->school_id)->where('active_status', 1)->count();


            $package = SmPackagePlan::find($id);

            if ($active_student > $package->student_quantity) {

                Toastr::error('Your current students more than new student limit, if you want to buy then remove some students.', 'Failed');
                return redirect()->back();
            }

            $settings = SmSaasSubscriptionSetting::find(1);
            $tax = $package->price / 100 * $settings->amount;
            $payment_setting = SmSaasPaymentGatewaySetting::where('gateway_name', 'Stripe')->first();

            $payment_methods = SmSaasPaymentMethod::where('active_status', 1)->get();
            $array_payment_methods = [];
            foreach ($payment_methods as $payment_method) {
                $array_payment_methods[] = $payment_method->id;
            }

            $bank_details = SmSaasPaymentGatewaySetting::where('gateway_name', 'Bank')->first();
            $cheque_details = SmSaasPaymentGatewaySetting::where('gateway_name', 'Cheque')->first();
            $account_detail['bank'] = $bank_details->bank_details;
            $account_detail['cheque'] = $cheque_details->cheque_details;

            return view('saas::school/buy_now', compact('package', 'payment_methods', 'tax', 'payment_setting', 'array_payment_methods', 'account_detail', 'slug'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function makePayment(Request $request)
    {
        $request->validate([
            'cheque_photo' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",
            'bank_photo' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",
        ]);

        try {

            $package_detail = SmPackagePlan::find($request->package_id);
            $last_record = SmSubscriptionPayment::orderBy('id', 'desc')->where('school_id', Auth::user()->school_id)->first();

            if (($last_record && $last_record->payment_type == 'trial')|| $request->buy_type == 'instantly') {

                $Date = date("Y-m-d");
                $start_date = date("Y-m-d");
                $end_date = date('Y-m-d', strtotime($Date . ' + ' . $package_detail->duration_days . ' days'));

            } else {

                $last_record = SmSubscriptionPayment::orderBy('id', 'desc')->where('approve_status', 'approved')->where('school_id', Auth::user()->school_id)->first();

                if ($last_record != "") {

                    $Date = date("Y-m-d");
                    $start_date = date("Y-m-d");
                    if ($Date <= $last_record->end_date) {

                        $start_date = date('Y-m-d', strtotime($last_record->end_date . ' + 1 days'));
                        $end_date = date('Y-m-d', strtotime($start_date . ' + ' . $package_detail->duration_days . ' days'));

                    } else {

                        $start_date = date("Y-m-d");
                        $end_date = date('Y-m-d', strtotime($start_date . ' + ' . $package_detail->duration_days . ' days'));

                    }

                } else {

                    $start_date = date("Y-m-d");
                    $end_date = date('Y-m-d', strtotime($start_date . ' + ' . $package_detail->duration_days . ' days'));

                }

            }

            if ($request->relationButton == 'cash') {
                $payment = new SmSubscriptionPayment();
                $payment->package_id = $request->package_id;
                $payment->amount = $request->amount_tax;
                $payment->payment_type = 'paid';
                $payment->approve_status = 'pending';
                $payment->payment_date = date('Y-m-d');

                // $payment->start_date = $start_date;
                // $payment->end_date = $end_date;

                $payment->buy_type = $request->buy_type;

                $payment->payment_method = $request->relationButton;
                $payment->school_id = Auth::user()->school_id;
                $payment->save();
            } elseif ($request->relationButton == 'cheque') {

                $fileName = "";
                if ($request->file('cheque_photo') != "") {
                    $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                    $file = $request->file('cheque_photo');
                    $fileSize = filesize($file);
                    $fileSizeKb = ($fileSize / 1000000);
                    if ($fileSizeKb >= $maxFileSize) {
                        Toastr::error('Max upload file size ' . $maxFileSize . ' Mb is set in system', 'Failed');
                        return redirect()->back();
                    }

                    $file = $request->file('cheque_photo');
                    $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                    $file->move('Modules/Saas/public/uploads/', $fileName);
                    $fileName = 'Modules/Saas/public/uploads/' . $fileName;
                }

                $payment = new SmSubscriptionPayment();
                $payment->package_id = $request->package_id;
                $payment->amount = $request->amount_tax;
                $payment->bank_name = $request->bank_name_cheque;
                $payment->account_holder = $request->account_holder_cheque;
                $payment->payment_type = 'paid';
                $payment->approve_status = 'pending';
                $payment->payment_date = date('Y-m-d');

                // $payment->start_date = $start_date;
                // $payment->end_date = $end_date;

                $payment->buy_type = $request->buy_type;

                $payment->payment_method = $request->relationButton;
                $payment->school_id = Auth::user()->school_id;
                $payment->file = $fileName;
                $payment->save();

            } elseif ($request->relationButton == 'bank') {

                $fileName = "";
                if ($request->file('bank_photo') != "") {
                    $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
                    $file = $request->file('bank_photo');
                    $fileSize = filesize($file);
                    $fileSizeKb = ($fileSize / 1000000);
                    if ($fileSizeKb >= $maxFileSize) {
                        Toastr::error('Max upload file size ' . $maxFileSize . ' Mb is set in system', 'Failed');
                        return redirect()->back();
                    }
                    $file = $request->file('bank_photo');
                    $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                    $file->move('Modules/Saas/public/uploads/', $fileName);
                    $fileName = 'Modules/Saas/public/uploads/' . $fileName;
                }

                $payment = new SmSubscriptionPayment();
                $payment->package_id = $request->package_id;
                $payment->amount = $request->amount_tax;
                $payment->bank_name = $request->bank_name_bank;
                $payment->account_holder = $request->account_holder_bank;
                $payment->payment_type = 'paid';
                $payment->approve_status = 'pending';
                $payment->payment_date = date('Y-m-d');

                // $payment->start_date = $start_date;
                // $payment->end_date = $end_date;

                $payment->buy_type = $request->buy_type;

                $payment->payment_method = $request->relationButton;
                $payment->school_id = Auth::user()->school_id;
                $payment->file = $fileName;
                $payment->save();

            } elseif ($request->relationButton == 'stripe') {
                $current_currency = Str::lower(@generalSetting()->currency);
                $support_currencys = ['usd', 'aed', 'afn', 'all', 'amd', 'ang', 'aoa', 'ars', 'aud', 'awg', 'azn', 'bam', 'bbd', 'bdt', 'bgn', 'bhd', 'bif', 'bmd', 'bnd', 'bob', 'brl', 'bsd', 'bwp', 'bzd', 'cad', 'cdf', 'chf', 'clp', 'cny', 'cop', 'crc', 'cve', 'czk', 'djf', 'dkk', 'dop', 'dzd', 'egp', 'etb', 'eur', 'fjd', 'fkp', 'gbp', 'gel', 'gip', 'gmd', 'gnf', 'gtq', 'gyd', 'hkd', 'hnl', 'hrk', 'htg', 'huf', 'idr', 'ils', 'inr', 'isk', 'jmd', 'jod', 'jpy', 'kes', 'kgs', 'khr', 'kmf', 'krw', 'kwd', 'kyd', 'kzt', 'lak', 'lbp', 'lkr', 'lrd', 'lsl', 'mad', 'mdl', 'mga', 'mkd', 'mmk', 'mnt', 'mop', 'mro', 'mur', 'mvr', 'mwk', 'mxn', 'myr', 'mzn', 'nad', 'ngn', 'nio', 'nok', 'npr', 'nzd', 'omr', 'pab', 'pen', 'pgk', 'php', 'pkr', 'pln', 'pyg', 'qar', 'ron', 'rsd', 'rub', 'rwf', 'sar', 'sbd', 'scr', 'sek', 'sgd', 'shp', 'sll', 'sos', 'srd', 'std', 'szl', 'thb', 'tjs', 'tnd', 'top', 'try', 'ttd', 'twd', 'tzs', 'uah', 'ugx', 'uyu', 'uzs', 'vnd', 'vuv', 'wst', 'xaf', 'xcd', 'xof', 'xpf', 'yer', 'zar', 'zmw', 'eek', 'lvl', 'svc', 'vef', 'ltl'];
                if (!in_array($current_currency, $support_currencys)) {
                    Toastr::error('This Currency is not supported by Stripe', 'Failed');
                    return redirect()->back();
                }
                $payment_setting = SmSaasPaymentGatewaySetting::where('gateway_name', 'Stripe')->first();

                Stripe\Stripe::setApiKey($payment_setting->gateway_secret_key);

                Stripe\Charge::create([
                    "amount" => $request->amount_tax * 100,
                    "currency" => @generalSetting()->currency,
                    "source" => $request->stripeToken,
                    "description" => "Test payment from InfixEdu."
                ]);

                $payment = new SmSubscriptionPayment();
                $payment->package_id = $request->package_id;
                $payment->amount = $request->amount_tax;
                $payment->bank_name = $request->bank_name_bank;
                $payment->account_holder = $request->account_holder_bank;
                $payment->payment_type = 'paid';
                $payment->approve_status = 'approved';
                $payment->payment_date = date('Y-m-d');

                $payment->start_date = $start_date;
                $payment->end_date = $end_date;
                $payment->buy_type = $request->buy_type;

                $payment->payment_method = $request->relationButton;
                $payment->school_id = Auth::user()->school_id;
                $payment->save();


            } elseif ($request->relationButton == 'paystack') {

                $payment = new \Modules\Saas\Entities\SmSubscriptionPayment();
                $payment->package_id = $request->package_id;
                $payment->amount = $request->amount_tax;
                $payment->bank_name = $request->bank_name_bank;
                $payment->account_holder = $request->account_holder_bank;
                $payment->payment_type = 'paid';
                $payment->approve_status = 'pending';
                $payment->payment_date = date('Y-m-d');
                $payment->payment_method = $request->relationButton;
                $payment->school_id = Auth::user()->school_id;
                $payment->start_date = $start_date;
                $payment->end_date = $end_date;
                $payment->buy_type = $request->buy_type;
                $payment->save();

                $request->merge([
                    'callback_url' => route('payment.success', ['Paystack', 'payment_id' => $payment->id, 'type' => 'saas'])
                ]);

                Session::put('payment_id', $payment->id);
                Session::put('payment_type', 'Saas');

                try {
                    return Paystack::getAuthorizationUrl()->redirectNow();
                } catch (\Exception $e) {
                    Toastr::error('This Currency is not supported by Merchant', 'Failed');
                    return redirect()->back();
                }

            } elseif ($request->relationButton == "paypal") {
                try {
                    $data = [];
                    $serviceCharge = 0;
                    $gateway_setting = SmPaymentGatewaySetting::where('gateway_name',"PayPal")->where('school_id',Auth::user()->school_id)->first();
                    if($gateway_setting){
                        $serviceCharge = chargeAmount("PayPal", $request->amount_tax);
                    }
                    $payment_paypal = new SmSubscriptionPayment();
                    $payment_paypal->package_id = $request->package_id;
                    $payment_paypal->amount = $request->amount_tax;
                    $payment_paypal->bank_name = $request->bank_name_bank;
                    $payment_paypal->account_holder = $request->account_holder_bank;
                    $payment_paypal->payment_type = 'processing';
                    $payment_paypal->approve_status = 'pending';
                    $payment_paypal->payment_date = date('Y-m-d');
                    $payment_paypal->start_date = $start_date;
                    $payment_paypal->end_date = $end_date;
                    $payment_paypal->buy_type = $request->buy_type;
                    $payment_paypal->payment_method = "PayPal";
                    $payment_paypal->school_id = Auth::user()->school_id;
                    $payment_paypal->save();

                    DB::commit();
                    
                    $data['request_amount'] = $request->amount_tax ;
                    $data['amount'] = $request->amount_tax + $serviceCharge;
                    $data['type'] = "saas_school_reg";
                    $data['method'] = "PayPal";
                    $data['description'] = "Saas Subscription Package";
                    $data['subs_payment_id'] = $payment_paypal->id;
                    $classMap = config('paymentGateway.'.$data['method']);
                    $make_payment = new $classMap();
                    return $make_payment->handle($data);
                } catch (\Exception $e) {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            }


            if ($request->relationButton != 'paystack') {

                try {
                    $systemSetting = SmGeneralSettings::select('school_name', 'email')->where('id', 1)->first();

                    $systemEmail = SmEmailSetting::find(1);

                    $system_email = $systemEmail->from_email;
                    $school_name = $systemSetting->school_name;

                    $data = $request->package_id;

                    $to_email = Auth::user()->email;
                    $to_name = Auth::user()->staff->full_name;


                    //return view('saas::school/send_mail', ["package" => $data]);

                    // $result = Mail::send('saas::school/send_mail', ["package" => $data], function ($message) use ($to_email, $system_email, $school_name) {

                    //     $message->to($to_email)->subject('Invoice');
                    //     $message->from($system_email, $school_name);

                    // });

                    $settings = SmEmailSetting::first();
                    $reciver_email = $to_email;
                    $receiver_name = $to_name;
                    $subject = "Invoice";
                    $view = "saas::school.send_mail";
                    $compact['package'] = $data;
                    @send_mail($reciver_email, $receiver_name, $subject, $view, $compact);

                } catch (\Exception $e) {

                }

            }

            Toastr::success('Operation successful', 'Success');
            return redirect('subscription/package-list');

        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function getPaymentStatus(Request $request)
    {
        try {
            $payment_id = Session::get('paypal_payment_id');

            Session::forget('paypal_payment_id');

            if (empty($request->input('PayerID')) || empty($request->input('token'))) {
                Session::put('error', 'Payment failed');
                return redirect()->back('subscription/buy-now', [auth()->user()->school_id, "instantly"]);
            }
            $payment = Payment::get($payment_id, $this->_api_context);
            $execution = new PaymentExecution();
            $execution->setPayerId($request->input('PayerID'));
            $result = $payment->execute($execution, $this->_api_context);

            if ($result->getState() == 'approved') {
                $paymentId = Session::get('paypal_paymentId');
                if (!is_null($paymentId)) {

                    $payment = SmSubscriptionPayment::find($paymentId);
                    $payment->payment_type = 'paid';
                    $payment->approve_status = 'approved';
                    $payment->payment_date = date('Y-m-d');
                    $payment->save();
                    Toastr::success('Payment Successfully Complete', 'Success');
                    $school = SmSchool::find($payment->school_id);
                    return redirect('//' . $school->domain . '.' . config('app.short_url') . '/home');

                } else {
                    Toastr::error('Operation Failed', 'Failed');
                    return redirect()->back();
                }
            }


        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function handleGatewayCallback()
    {

        try {

            $payment = new SmSubscriptionPayment();
            $payment->package_id = Session::get('package_id');
            $payment->amount = Session::get('amount_tax');
            $payment->payment_type = 'paid';
            $payment->approve_status = 'approved';
            $payment->payment_date = date('Y-m-d');

            $payment->start_date = Session::get('start_date');
            $payment->end_date = Session::get('end_date');
            $payment->buy_type = Session::get('buy_type');

            $payment->payment_method = 'paystack';
            $payment->school_id = Session::get('school_id');
            $payment->save();

            try {
                $systemSetting = SmGeneralSettings::select('school_name', 'email')->where('id', 1)->first();
                $systemEmail = SmEmailSetting::find(1);
                $system_email = $systemEmail->from_email;
                $school_name = $systemSetting->school_name;
                $data = Session::get('package_id');
                $school_info = SmSchool::find(Session::get('school_id'));
                $to_email = @$school_info->email;
                //$to_name = Auth::user()->staff->full_name;

                //return view('saas::school/send_mail', ["package" => $data]);

                $result = Mail::send('saas::school/send_mail', ["package" => $data], function ($message) use ($to_email, $system_email, $school_name) {
                    $message->to($to_email)->subject('Invoice');
                    $message->from($system_email, $school_name);
                });
            } catch (\Exception $e) {
                Toastr::error('Invoice Mail Not Send', 'Failed');
                return redirect('subscription/package-list');
            }

            DB::commit();
            Session::put('school_id', '');
            Toastr::success('Operation successful', 'Success');

            if (Session::get('redirect_url') == 'institution-register-new') {
                Session::put('redirect_url', '');
                return redirect('institution-register-new');
            } else {
                return redirect('subscription/package-list');
            }

        } catch (\Exception $e) {
            SmSchool::destroy(Session::get('school_id'));
            Session::put('school_id', '');
            DB::rollback();
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }


    public function paymentHistory()
    {
        try {
            $payments = SmSubscriptionPayment::where('school_id', Auth::user()->school_id)->where('payment_type', 'paid')->get();
            return view('saas::school/payment_history', compact('payments'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function schoolPayments()
    {
        try {
            $payments = SmSubscriptionPayment::where('payment_type', 'paid')->get();
            return view('saas::payment_history', compact('payments'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function updateStatus($id)
    {
        try {
            $payment = SmSubscriptionPayment::find($id);
            return view('saas::update_status', compact('payment'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function updateStatusStore(Request $request)
    {

        try {

            $payment = SmSubscriptionPayment::find($request->id);


            // start date and end date
            if ($payment->buy_type == 'instantly') {

                $Date = date("Y-m-d");
                $start_date = date("Y-m-d");
                $end_date = date('Y-m-d', strtotime($Date . ' + ' . $payment->package->duration_days . ' days'));


            } elseif ($payment->buy_type == 'buy_now') {

                $last_record = SmSubscriptionPayment::orderBy('id', 'desc')->where('approve_status', 'approved')->where('school_id', $payment->school_id)->first();

                if ($last_record != "") {

                    $Date = date("Y-m-d");
                    $start_date = date("Y-m-d");

                    if ($Date <= $last_record->end_date) {

                        $start_date = date('Y-m-d', strtotime($last_record->end_date . ' + 1 days'));
                        $end_date = date('Y-m-d', strtotime($start_date . ' + ' . $payment->package->duration_days . ' days'));

                    } else {

                        $start_date = date("Y-m-d");
                        $end_date = date('Y-m-d', strtotime($start_date . ' + ' . $payment->package->duration_days . ' days'));

                    }

                } else {

                    $start_date = date("Y-m-d");
                    $end_date = date('Y-m-d', strtotime($start_date . ' + ' . $payment->package->duration_days . ' days'));

                }

            } else {
                $start_date = $payment->start_date;
                $end_date = $payment->end_date;

            }
            // start date and end date 


            $payment->start_date = $start_date;
            $payment->end_date = $end_date;

            $payment->approve_status = $request->status;
            $payment->save();

            Toastr::success('Operation successful', 'Success');
            return redirect('subscription/school-payments');

        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function SaasPaymentHistory()
    {

        try {

            $payments = SmSubscriptionPayment::Where('approve_status', 'approved')
                        ->distinct('school_id')
                        ->get();


            return view('saas::payment_history_school', compact('payments'));
        } catch (\Exception $e) {

            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }

    public function singleSchoolPayment($id)
    {
        try {

            $payments = SmSubscriptionPayment::Where('approve_status', 'approved')->where('school_id', $id)->get();

            $school = SmSchool::find($id);

            return view('saas::single_school_payment', compact('payments', 'school'));
        } catch (\Exception $e) {

            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    //
    public function getPackageInfo(Request $request)
    {

        try {

            $package_info = SmPackagePlan::find($request->id);
            $price = number_format($package_info->price, 2);
            $setting = SmSaasSubscriptionSetting::find(1);
            $tax = $package_info->price / 100 * $setting->amount;
            $tax = number_format($tax, 2);

            $total_price = $package_info->price + $tax;
            $total_price = number_format($total_price, 2);

            $general_settings = SmGeneralSettings::find(1);
            $currency = $general_settings->currency_symbol;


            return response()->json([$package_info, $setting, $tax, $total_price, $currency, $price]);

        } catch (\Exception $e) {

            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }


    }
}