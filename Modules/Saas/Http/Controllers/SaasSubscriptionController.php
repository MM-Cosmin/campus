<?php

namespace Modules\Saas\Http\Controllers;

use App\Models\SchoolModule;
use App\User;
use App\SmUserLog;
use App\SmGeneralSettings;
use App\InfixModuleManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;
use Modules\Saas\Entities\SmPackagePlan;
use Modules\Saas\Entities\SmSaasPaymentMethod;
use Modules\Saas\Entities\SmPackagePlanFeature;
use Modules\Saas\Entities\SmSubscriptionPayment;
use Modules\Saas\Entities\SmSaasSubscriptionSetting;
use Modules\Saas\Entities\SmSaasPaymentGatewaySetting;
use App\SmSchool;

class SaasSubscriptionController extends Controller
{
    public function __construct()
    {


    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('saas::index');
    }

    public function TrailInstitution()
    {
        $payments = SmSubscriptionPayment::where('payment_type', 'trial')->get();
        return view('saas::TrailInstitution', compact('payments'));
    }


    public function about()
    {
        $data = \App\InfixModuleManager::where('name', 'Saas')->first();
        return view('saas::about', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('saas::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
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

    public function packages()
    {
        try {
            $packages = SmPackagePlan::all();
            $permissions = planPermissions();
            return view('saas::packages', compact('packages', 'permissions'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function packageStore(Request $request)
    {

        if (config('app.app_sync')) {
            Toastr::error('Restricted in demo mode');
            return back();
        }
        try {

            $input = $request->all();

            $validator = Validator::make(
                $input,
                [
                    'name' => "required|max:200",
                    'duration' => "required",
                    'price' => "required|max:200|min:0",
                    'trial_days' => "numeric|min:0",
                    'student_quantity' => "required|numeric",
                    'staff_quantity' => "required|numeric",
                    'menus' => 'sometimes|nullable|array',
                    'modules' => 'sometimes|nullable|array',
                ]
            );

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();
            $package = new SmPackagePlan();
            $package->name = $request->name;
            $package->duration_days = $request->duration;
            $package->price = $request->price;
            $package->trial_days = $request->trial_days ?? 0;
            $package->active_status = $request->status;
            $package->student_quantity = $request->student_quantity;
            $package->staff_quantity = $request->staff_quantity;
            $package->menus = $request->menus ?? [];
            $package->modules = $request->modules ?? [];
            $package->save();

            if (isset($request->feature)) {
                foreach ($request->feature as $feature_name) {
                    if ($feature_name != "") {
                        $plan_feature = new SmPackagePlanFeature();
                        $plan_feature->feature = $feature_name;
                        $plan_feature->package_plan_id = $package->id;
                        $plan_feature->save();
                    }
                }
            }

            DB::commit();
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function packageEdit($id)
    {
        try {
            $packages = SmPackagePlan::all();
            $package = $packages->where('id', $id)->first();
            $permissions = planPermissions();
            return view('saas::packages', compact('packages', 'package', 'permissions'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function packageView(int $id)
    {
        try {
            $package = SmPackagePlan::where('id', $id)->first();
            $permissions = planPermissions();
            return view('saas::package_view', compact('package', 'permissions'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function packageUpdate(Request $request)
    {
        if (config('app.app_sync')) {
            Toastr::error('Restricted in demo mode');
            return back();
        }
        try {
            $input = $request->all();
            $validator = Validator::make(
                $input,
                [
                    'name' => "required|max:200",
                    'duration' => "required",
                    'price' => "required|max:200",
                    'trial_days' => "numeric|min:0",
                    'student_quantity' => "required|numeric",
                    'staff_quantity' => "required|numeric",
                    'menus' => 'sometimes|nullable|array',
                    'modules' => 'sometimes|nullable|array',
                ]
            );
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            DB::beginTransaction();
            $package = SmPackagePlan::find($request->id);
            $package->name = $request->name;
            $package->duration_days = $request->duration;
            $package->price = $request->price;
            $package->trial_days = $request->trial_days ?? 0;
            $package->active_status = $request->status;
            $package->student_quantity = $request->student_quantity;
            $package->staff_quantity = $request->staff_quantity;
            $package->menus = $request->menus ?? [];
            $package->modules = $request->modules ?? [];
            $package->save();
            SmPackagePlanFeature::where('package_plan_id', $request->id)->delete();

            if (isset($request->feature)) {
                foreach ($request->feature as $feature_name) {
                    if ($feature_name != "") {
                        $plan_feature = new SmPackagePlanFeature();
                        $plan_feature->feature = $feature_name;
                        $plan_feature->package_plan_id = $package->id;
                        $plan_feature->save();
                    }
                }
            }

            DB::commit();
            $schools = SmSchool::all();
            foreach($schools as $school){
                Cache::forget('school_modules' . $school->id);
                Cache::forget('active_package' . $school->id);
            }
            Toastr::success('Operation successful', 'Success');
            return redirect('subscription/packages');
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function packageDelete($id)
    {
        if (config('app.app_sync')) {
            Toastr::error('Restricted in demo mode');
            return back();
        }
        try {
            SmPackagePlan::where('id', $id)->delete();
            Toastr::success('Operation successful', 'Success');
            return redirect('subscription/packages');

        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function packageAssign($id)
    {
        try {

            DB::beginTransaction();

            $schools = SmSchool::where('id', '!=', 1)->get();

            $i = 0;
            foreach ($schools as $school) {
                $subscription = SmSubscriptionPayment::where('school_id', $school->id)->first();

                if ($subscription == "") {

                    $i++;

                    $package_info = SmPackagePlan::find($id);

                    $start_date = date('Y-m-d');
                    $end_date = date('Y-m-d', strtotime($start_date . ' + ' . $package_info->duration_days . ' days'));

                    $setting = SmSaasSubscriptionSetting::find(1);

                    $tax = $package_info->price / 100 * $setting->amount;
                    $amount = $package_info->price + $tax;

                    $payment = new SmSubscriptionPayment();
                    $payment->package_id = $package_info->id;
                    $payment->payment_type = 'paid';
                    $payment->approve_status = 'approved';
                    $payment->payment_date = date('Y-m-d');
                    $payment->amount = $amount;
                    $payment->payment_method = 'cash';
                    $payment->school_id = $school->id;

                    $payment->start_date = $start_date;
                    $payment->end_date = $end_date;
                    $payment->buy_type = 'instantly';


                    $payment->save();

                }
            }

            DB::commit();

            if ($i == 0) {
                Toastr::success('You have already assigned all school.', 'Success');
                return redirect()->back();

            } else {
                Toastr::success('Package has been assigned successfully', 'Success');
                return redirect()->back();
            }


        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }


    public function paymentMethod()
    {
        try {
            $payment_methods = SmSaasPaymentMethod::all();
            return view('saas::paymentMethod', compact('payment_methods'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function paymentMethodStore(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'method' => "required",
        ]);

        $is_duplicate = SmSaasPaymentMethod::where('method', $request->method)->first();
        if ($is_duplicate) {
            Toastr::error('Duplicate name found!', 'Failed');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($validator->fails()) {

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $payment_method = new SmSaasPaymentMethod();
            $payment_method->method = $request->method;
            $result = $payment_method->save();


            Toastr::success('Operation successful', 'Success');
            return redirect()->back();

        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function paymentMethodEdit(Request $request, $id)
    {

        try {
            $payment_methods = SmSaasPaymentMethod::all();

            $payment_method = $payment_methods->where('id', $id)->first();

            return view('saas::paymentMethod', compact('payment_method', 'payment_methods'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function paymentMethodUpdate(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'method' => "required",
        ]);

        $is_duplicate = SmSaasPaymentMethod::where('id', '!=', $request->id)->where('method', $request->method)->first();
        if ($is_duplicate) {
            Toastr::error('Duplicate name found!', 'Failed');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($validator->fails()) {

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $payment_method = SmSaasPaymentMethod::find($request->id);
            $payment_method->method = $request->method;
            $result = $payment_method->save();


            Toastr::success('Operation successful', 'Success');
            return redirect('subscription/payment-method');

        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function paymentMethodDelete($id)
    {

        try {
            $student_group = SmSaasPaymentMethod::destroy($id);


            Toastr::success('Operation successful', 'Success');
            return redirect()->back();


        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function paymentMethodSettings()
    {
        try {
            // $statement                = "SELECT P.id as PID, D.id as DID, P.active_status as IsActive, P.method, D.* FROM sm_saas_payment_methods as P, sm_saas_payment_gateway_settings D WHERE P.gateway_id=D.id";

            // $PaymentMethods           = DB::select($statement);
            $paymeny_gateway = SmSaasPaymentMethod::all();
            $paymeny_gateway_settings = SmSaasPaymentGatewaySetting::all();


            return view('saas::paymentMethodSettings', compact('paymeny_gateway', 'paymeny_gateway_settings'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function isActivePayment(Request $request)
    {
        if (config('app.app_sync')) {
            Toastr::error('Restricted in demo mode');
            return back();
        }
        $request->validate(
            [
                'gateways' => 'required|array',
            ],
            [
                'gateways.required' => 'At least one gateway required!',
            ]
        );


        try {
            $update = SmSaasPaymentMethod::where('active_status', '=', 1)->update(['active_status' => 0]);

            foreach ($request->gateways as $pid => $isChecked) {
                $results = SmSaasPaymentMethod::where('id', '=', $pid)->update(['active_status' => 1]);
            }

            if ($results) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function updatePaymentGateway(Request $request)
    {
        if (config('app.app_sync')) {
            Toastr::error('Restricted in demo mode');
            return back();
        }
        try {
            $paymeny_gateway = [
                'gateway_name', 'gateway_username', 'gateway_password', 'gateway_signature', 'gateway_client_id', 'gateway_mode',
                'gateway_secret_key', 'gateway_secret_word', 'gateway_publisher_key', 'gateway_private_key', 'cheque_details', 'bank_details'
            ];
            $count = 0;
            $gatewayDetails = SmSaasPaymentGatewaySetting::where('gateway_name', $request->gateway_name)->first();

            foreach ($paymeny_gateway as $input_field) {

                if (isset($request->$input_field) && !empty($request->$input_field)) {
                    $gatewayDetails->$input_field = $request->$input_field;
                }

            }
            $results = $gatewayDetails->save();

            /*********** all ********************** */
            $WriteENV = SmSaasPaymentGatewaySetting::all();

            foreach ($WriteENV as $row) {
                switch ($row->gateway_name) {
                    case 'PayPal':

                        $key1 = 'PAYPAL_ENV';
                        $key2 = 'PAYPAL_API_USERNAME';
                        $key3 = 'PAYPAL_API_PASSWORD';
                        $key4 = 'PAYPAL_API_SECRET';

                        $value1 = $row->gateway_mode;
                        $value2 = $row->gateway_username;
                        $value3 = $row->gateway_password;
                        $value4 = $row->gateway_secret_key;

                        $path = base_path() . "/.env";
                        $PAYPAL_ENV = env($key1);
                        $PAYPAL_API_USERNAME = env($key2);
                        $PAYPAL_API_PASSWORD = env($key3);
                        $PAYPAL_API_SECRET = env($key4);

                        if (file_exists($path)) {
                            file_put_contents($path, str_replace(
                                "$key1=" . $PAYPAL_ENV,
                                "$key1=" . $value1,
                                file_get_contents($path)
                            ));
                            file_put_contents($path, str_replace(
                                "$key2=" . $PAYPAL_API_USERNAME,
                                "$key2=" . $value2,
                                file_get_contents($path)
                            ));
                            file_put_contents($path, str_replace(
                                "$key3=" . $PAYPAL_API_PASSWORD,
                                "$key3=" . $value3,
                                file_get_contents($path)
                            ));
                            file_put_contents($path, str_replace(
                                "$key4=" . $PAYPAL_API_SECRET,
                                "$key4=" . $value4,
                                file_get_contents($path)
                            ));
                        }

                        break;
                    case 'Stripe':

                        $key1 = 'STRIPE_KEY';
                        $key2 = 'STRIPE_SECRET';

                        $value1 = $row->gateway_publisher_key;
                        $value2 = $row->gateway_secret_key;

                        $path = base_path() . "/.env";
                        $PUBLISHABLE_KEY = env($key1);
                        $SECRET_KEY = env($key2);

                        if (file_exists($path)) {
                            file_put_contents($path, str_replace(
                                "$key1=" . $PUBLISHABLE_KEY,
                                "$key1=" . $value1,
                                file_get_contents($path)
                            ));
                            file_put_contents($path, str_replace(
                                "$key2=" . $SECRET_KEY,
                                "$key2=" . $value2,
                                file_get_contents($path)
                            ));
                        }

                        break;

                    case 'Paystack':

                        $key1 = 'PAYSTACK_PUBLIC_KEY';
                        $key2 = 'PAYSTACK_SECRET_KEY';
                        $key3 = 'PAYSTACK_PAYMENT_URL';
                        $key4 = 'MERCHANT_EMAIL';

                        $value1 = $row->gateway_publisher_key;
                        $value2 = $row->gateway_secret_key;
                        $value3 = 'https://api.paystack.co';
                        $value4 = $row->gateway_username;

                        $path = base_path() . "/.env";
                        $PAYSTACK_PUBLIC_KEY = env($key1);
                        $PAYSTACK_SECRET_KEY = env($key2);
                        $PAYSTACK_PAYMENT_URL = env($key3);
                        $MERCHANT_EMAIL = env($key4);

                        if (file_exists($path)) {
                            file_put_contents($path, str_replace(
                                "$key1=" . $PAYSTACK_PUBLIC_KEY,
                                "$key1=" . $value1,
                                file_get_contents($path)
                            ));
                            file_put_contents($path, str_replace(
                                "$key2=" . $PAYSTACK_SECRET_KEY,
                                "$key2=" . $value2,
                                file_get_contents($path)
                            ));
                            file_put_contents($path, str_replace(
                                "$key3=" . $PAYSTACK_PAYMENT_URL,
                                "$key3=" . $value3,
                                file_get_contents($path)
                            ));
                            file_put_contents($path, str_replace(
                                "$key4=" . $MERCHANT_EMAIL,
                                "$key4=" . $value4,
                                file_get_contents($path)
                            ));
                        }
                        break;
                    case 'Razorpay':

                        $key1 = 'RAZORPAY_KEY';
                        $key2 = 'RAZORPAY_SECRET';


                        $value1 = $row->gateway_publisher_key;
                        $value2 = $row->gateway_secret_key;

                        $path = base_path() . "/.env";
                        $RAZORPAY_KEY = env($key1);
                        $RAZORPAY_SECRET = env($key2);

                        if (file_exists($path)) {
                            file_put_contents($path, str_replace(
                                "$key1=" . $RAZORPAY_KEY,
                                "$key1=" . $value1,
                                file_get_contents($path)
                            ));
                            file_put_contents($path, str_replace(
                                "$key2=" . $RAZORPAY_SECRET,
                                "$key2=" . $value2,
                                file_get_contents($path)
                            ));
                        }

                        break;
                }
            }

            /*********** all ********************** */

            if ($results) {
                Toastr::success('Operation successful', 'Success');
                return redirect()->back();
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function settings()
    {
        $setting = SmSaasSubscriptionSetting::find(1);
        return view('saas::settings', compact('setting'));
    }

    public function settingsStore(Request $request)
    {

        $request->validate([
            'amount' => 'numeric|min:0'
        ]);


        try {
            $update = SmSaasSubscriptionSetting::find(1);
            $update->amount = $request->amount;
            $update->is_auto_approve = $request->is_auto_approve;
            $update->save();
            
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();

        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    // add payment by saas superadmin
    public function addPayment($id)
    {
        try {
            $packages = SmPackagePlan::all();
            return view('saas::add_payment', compact('packages', 'id'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }

    public function storePayment(Request $request)
    {

        try {

            $setting = SmSaasSubscriptionSetting::find(1);
            $package_info = SmPackagePlan::find($request->package);

            $tax = $package_info->price / 100 * $setting->amount;
            $amount = $package_info->price + $tax;

            $payment = new SmSubscriptionPayment();

            $payment->package_id = $request->package;
            $payment->amount = $amount;
            $payment->payment_type = 'paid';
            $payment->approve_status = 'approved';
            $payment->payment_date = date('Y-m-d');

            $Date = date("Y-m-d");
            $start_date = date("Y-m-d");
            $end_date = date('Y-m-d', strtotime($Date . ' + ' . $payment->package->duration_days . ' days'));

            $payment->start_date = $start_date;
            $payment->end_date = $end_date;

            $payment->buy_type = 'instantly';

            $payment->payment_method = 'cash';
            $payment->school_id = $request->school_id;
            $payment->save();


            Toastr::success('Operation successful', 'Success');
            return redirect()->back();

        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

        return view('saas::add_payment', compact('packages', 'id'));
    }

    // add payment by saas superadmin
    public function addModule($id)
    {
        $permissions = planPermissions();
        $school = SmSchool::findOrFail($id);
        return view('saas::add_modules', compact('permissions', 'id', 'school'));

    }

    public function storeModule(Request $request, $id)
    {

        try {

            $school_module = SchoolModule::where('school_id', $id)->first();

            if(!$school_module){
                $school_module = new SchoolModule();
                $school_module->school_id = $id;
            }

            $school_module->modules = $request->modules ?? [];
            $school_module->menus = $request->menus ?? [];
            $school_module->save();

            Cache::forget('school_modules' . $id);

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();

        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }

    public function purchaseHistory($id)
    {
        try {
            $package = SmPackagePlan::findOrFail($id);
            $purchase_history = SmSubscriptionPayment::where('package_id', $id)
                ->where('approve_status', 'approved')->get();
            return view('saas::purchase_history', compact('package', 'purchase_history'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }

    public function assignModule()
    {
        try {
            $schools = SmSchool::all()->except(1);
            return view('saas::assign_module', compact('schools'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function postAssignModule(Request $request)
    {
        $request->validate([
            'institution' => 'required|integer|exists:sm_schools,id|gt:1'
        ]);
        try {
            $permissions = planPermissions();
            $schools = SmSchool::all()->except(1);
            $school = SmSchool::findOrFail($request->get('institution'));
            return view('saas::assign_module', compact('permissions', 'school', 'schools'));

        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}