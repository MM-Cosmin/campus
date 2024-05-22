<?php

namespace Modules\Saas\Http\Controllers;



use Mail;
use Twilio;
use App\Role;
use App\User;
use Validator;
use App\SmClass;
use App\SmStaff;
use App\SmParent;
use App\SmSchool;
use LaravelMsg91;
use App\SmStudent;
use Clickatell\Rest;
use App\SmSmsGateway;
use App\ApiBaseMethod;
use App\SmEmailSmsLog;
use App\SmNoticeBoard;
use App\SmEmailSetting;
use App\SmNotification;
use App\SmGeneralSettings;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Clickatell\ClickatellException;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use PhpMyAdmin\MoTranslator\ReaderException;
use Modules\Saas\Entities\SmAdministratorNotice;

class SaasCommunicateController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
        // $this->middleware('TimeZone');
    }


    public function sendMessage(Request $request)
    {
       $roles = Role::where('active_status', '=', 1)
            ->whereIn('school_id', array(1, Auth::user()->school_id))
            ->where('id', '!=', 1)
            ->where('id', '!=', 5)
            ->orderBy('id', 'desc')
            ->get();

        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            return ApiBaseMethod::sendResponse($roles, null);
        }
        return view('saas::communicate.sendMessage', compact('roles'));
    }

    public function saveNoticeData(Request $request)
    {
        $input = $request->all();
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $validator = Validator::make($input, [
                'notice_title' => "required",
                'notice_date' => "required",
                'publish_on' => "required",
                'login_id' => "required"
            ]);
        } else {
            $validator = Validator::make($input, [
                'notice_title' => "required",
                'notice_date' => "required",
                'publish_on' => "required",
            ]);
        }

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $roles_array = array();
        if (empty($request->role)) {
            $roles_array = '';
        } else {
            $roles_array = implode(',', $request->role);
        }

        $user = Auth()->user();

        if ($user) {
            $login_id = $user->id;
        } else {
            $login_id = $request->login_id;
        }

        $noticeData = new SmNoticeBoard();
        if (isset($request->is_published)) {
            $noticeData->is_published = $request->is_published;
        }
        $noticeData->notice_title = $request->notice_title;
        $noticeData->notice_message = $request->notice_message;
        $noticeData->notice_date = date('Y-m-d', strtotime($request->notice_date));
        $noticeData->publish_on = date('Y-m-d', strtotime($request->publish_on));
        $noticeData->inform_to = $roles_array;
        $noticeData->created_by = $login_id;
        $noticeData->school_id =  Auth::user()->school_id;
        $noticeData->updated_by = Auth::user()->id;
        $results = $noticeData->save();

        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            if ($results) {
                return ApiBaseMethod::sendResponse(null, 'Class Room has been created successfully');
            } else {
                return ApiBaseMethod::sendError('Something went wrong, please try again.');
            }
        } else {
            if ($results) {
                return redirect('notice-list')->with('message-success', 'New Notice has been added successfully');
            } else {
                return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
            }
        }
    }

    public function noticeList(Request $request)
    {
        $allNotices = SmNoticeBoard::where('active_status', 1)
            ->where('school_id', '=', Auth::user()->school_id)
            ->orderBy('id', 'DESC')
            ->get();

        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            return ApiBaseMethod::sendResponse($allNotices, null);
        }

        return view('saas::communicate.noticeList', compact('allNotices'));
    }

    public function editNotice(Request $request, $notice_id)
    {
       $roles = Role::where('active_status', '=', 1)
            ->whereIn('school_id', array(1, Auth::user()->school_id))
            ->where('id', '!=', 1)
            ->where('id', '!=', 5)
            ->orderBy('id', 'desc')
            ->get();
        $noticeDataDetails = SmNoticeBoard::find($notice_id);

        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $data = [];
            $data['roles'] = $roles->toArray();
            $data['noticeDataDetails'] = $noticeDataDetails->toArray();
            return ApiBaseMethod::sendResponse($data, null);
        }

        return view('saas::communicate.editSendMessage', compact('noticeDataDetails', 'roles'));
    }

    public function updateNoticeData(Request $request)
    {
        $input = $request->all();
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $validator = Validator::make($input, [
                'notice_title' => "required",
                'notice_date' => "required",
                'publish_on' => "required",
                'login_id' => "required"
            ]);
        } else {
            $validator = Validator::make($input, [
                'notice_title' => "required",
                'notice_date' => "required",
                'publish_on' => "required",
            ]);
        }

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $roles_array = array();
        if (empty($request->role)) {
            $roles_array = '';
        } else {
            $roles_array = implode(',', $request->role);
        }

        $user = Auth()->user();

        if ($user) {
            $login_id = $user->id;
        } else {
            $login_id = $request->login_id;
        }

        $noticeData = SmNoticeBoard::find($request->notice_id);
        if (isset($request->is_published)) {
            $noticeData->is_published = $request->is_published;
        }
        $noticeData->notice_title = $request->notice_title;
        $noticeData->notice_message = $request->notice_message;
        $noticeData->notice_date = date('Y-m-d', strtotime($request->notice_date));
        $noticeData->publish_on = date('Y-m-d', strtotime($request->publish_on));
        $noticeData->inform_to = $roles_array;
        $noticeData->updated_by = $login_id;
        $results = $noticeData->update();

        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            if ($results) {
                return ApiBaseMethod::sendResponse(null, 'Notice has been updated successfully');
            } else {
                return ApiBaseMethod::sendError('Something went wrong, please try again');
            }
        } else {
            if ($results) {
                return redirect('notice-list')->with('message-success', 'Notice has been updated successfully');
            } else {
                return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
            }
        }
    }

    public function deleteNoticeView(Request $request, $id)
    {
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            return ApiBaseMethod::sendResponse($id, null);
        }
        return view('saas::communicate.deleteNoticeView', compact('id'));
    }

    public function deleteNotice(Request $request, $id)
    {
        $result = SmNoticeBoard::destroy($id);

        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            if ($result) {
                return ApiBaseMethod::sendResponse(null, 'Notice has been deleted successfully');
            } else {
                return ApiBaseMethod::sendError('Something went wrong, please try again.');
            }
        } else {
            if ($result) {
                return redirect()->back()->with('message-success-delete', 'Notice has been deleted successfully');
            } else {
                return redirect()->back()->with('message-danger-delete', 'Something went wrong, please try again');
            }
        }
    }

    public function sendEmailSmsView(Request $request)
    {
       $roles = Role::where('active_status', '=', 1)
            ->whereIn('school_id', array(1, Auth::user()->school_id))
            ->where('id', '!=', 1)
            ->where('id', '!=', 5)
            ->orderBy('id', 'desc')
            ->get();
        $classes = SmClass:: where('school_id', '=', Auth::user()->school_id)->get();

        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $data = [];
            $data['roles'] = $roles->toArray();
            $data['classes'] = $classes->toArray();
            return ApiBaseMethod::sendResponse($data, null);
        }

        return view('saas::communicate.sendEmailSms', compact('roles', 'classes'));
    }







    public function sendEmailFromComunicate($data, $to_name, $to_email, $email_sms_title)
    {
        $systemSetting = SmGeneralSettings::where('school_id', '=', Auth::user()->school_id)->first();

        $emailSetting = SmEmailSetting::where('school_id', '=', Auth::user()->school_id)->first();



        
        config(['mail.driver' => $emailSetting->mail_driver]);
        config(['mail.host' => $emailSetting->mail_host]);
        config(['mail.post' => $emailSetting->mail_port]);
        config(['mail.username' => $emailSetting->mail_username]);
        config(['mail.password' => $emailSetting->mail_password]);
        config(['mail.encryption' => $emailSetting->mail_encryption]);



        $system_email = $systemSetting->email;
        $school_name = $systemSetting->school_name;
        if (!empty($system_email)) {

           
            $reciver_email = $to_email;
            $receiver_name =  $to_name;
            $subject= $email_sms_title;
            $view ="saas::emails.mail";
            $compact =  $data; 
            @send_mail($reciver_email, $receiver_name, $subject , $view ,$compact);

            // $result = Mail::send('saas::emails.mail', ["result" => $data], function ($message) use ($to_name, $to_email, $email_sms_title, $system_email, $school_name, $emailSetting) {
            //     $message->to($to_email, $to_name)->subject($email_sms_title);
            //     $message->from($emailSetting->from_email, $school_name);
            // });
            $error_data = [];
            return true;
        } else {
            $error_data[0] = 'success';
            $error_data[1] = 'Operation Failed, Please Updated System Mail';
            return false;
        }
    }



    // public function abcedf(){
    //     config(['clickatell.api_key' => "3MZLpdSBQriDxMwy0317Qw=="]); //set a variale in config file(clickatell.php)

    //     $clickatell = new \Clickatell\Rest();
    //     $result = $clickatell->sendMessage(['to' => ['+8801611774547'],  'content' => 'fdgdf']);


    //     return 'success';


    // }




    public function sendSMSFromComunicate($to_mobile, $sms)
    {
        $activeSmsGateway = SmSmsGateway::where('active_status', '=', 1)->where('school_id', '=', Auth::user()->school_id)->first();



        if ($activeSmsGateway->gateway_name == 'Twilio') {

            config(['TWILIO.SID' => $activeSmsGateway->twilio_account_sid]);
            config(['TWILIO.TOKEN' => $activeSmsGateway->twilio_authentication_token]);
            config(['TWILIO.FROM' => $activeSmsGateway->twilio_registered_no]);



            $account_id         = $activeSmsGateway->twilio_account_sid; // Your Account SID from www.twilio.com/console
            $auth_token         = $activeSmsGateway->twilio_authentication_token; // Your Auth Token from www.twilio.com/console
            $from_phone_number  = $activeSmsGateway->twilio_registered_no;

            $client = new Twilio\Rest\Client($account_id, $auth_token);
            if (!empty($to_mobile)) {
                $result = $message = $client->messages->create('+880 1611-774547', array('from' => $from_phone_number,  'body' => $sms));
            }
        } //end Twilio
        elseif ($activeSmsGateway->gateway_name == 'Clickatell') {
            // this is tested it's working fine.


            config(['clickatell.api_key' => $activeSmsGateway->clickatell_api_id]); //set a variale in config file(clickatell.php)
            $clickatell = new \Clickatell\Rest();
            $result = $clickatell->sendMessage(['to' => [$to_mobile],  'content' => $sms]);
        } //end Clickatell

        elseif ($activeSmsGateway->gateway_name == 'Msg91') {

            config(['MSG91.KEY' => $activeSmsGateway->msg91_authentication_key_sid]);
            config(['MSG91.SENDER_ID' => $activeSmsGateway->msg91_sender_id]);
            config(['MSG91.COUNTRY' => $activeSmsGateway->msg91_country_code]);
            config(['MSG91.ROUTE' => $activeSmsGateway->msg91_route]);



            $msg91_authentication_key_sid   = $activeSmsGateway->msg91_authentication_key_sid;
            $msg91_sender_id                = $activeSmsGateway->msg91_sender_id;
            $msg91_route                    = $activeSmsGateway->msg91_route;
            $msg91_country_code             = $activeSmsGateway->msg91_country_code;

            $curl = curl_init();



            $url = "https://api.msg91.com/api/sendhttp.php?mobiles=" . "+8801611774547" . "&authkey=" . $msg91_authentication_key_sid . "&route=" . $msg91_route . "&sender=" . $msg91_sender_id . "&message=" . $sms . "&country=". $msg91_country_code;

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => "", CURLOPT_MAXREDIRS => 10, CURLOPT_TIMEOUT => 30, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => "GET", CURLOPT_SSL_VERIFYHOST => 0, CURLOPT_SSL_VERIFYPEER => 0,
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                $result =  "cURL Error #:" . $err;
            } else {
                $result =  $response;
            }
        } //end Msg91

        return $result;
    }

    public function sendEmailSms(Request $request)
    {

        $request->validate([
            'email_sms_title' => "required",
            'send_through' => "required",
            'description' => "required",
        ]);

        if($request->send_through == "E"){
            $send_through = "Email";
        }else{
            $send_through = "SMS";
        }

        // try{

        $email_sms_title = $request->email_sms_title;
        // save data in email sms log
        $saveEmailSmsLogData = new SmEmailSmsLog();
        $saveEmailSmsLogData->saveEmailSmsLogData($request);

        if (empty($request->selectTab) or $request->selectTab == 'G') {


            if (empty($request->role)) {
                return redirect()->back()->with('message-danger', 'Please select whom you want to send');
            } else {
                $email_sms_title = $request->email_sms_title;
                $description = $request->description;
                $message_to = implode(',', $request->role);

                $to_name = '';
                $to_email = '';
                $to_mobile = '';
                $receiverDetails = '';
                foreach ($request->role as $role_id) {

                    if ($role_id == 2) {
                        $receiverDetails = SmStudent::select('email', 'full_name', 'mobile')->where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
                    } elseif ($role_id == 3) {
                        $receiverDetails = SmParent::select('guardians_email', 'fathers_name', 'fathers_mobile')->where('school_id', Auth::user()->school_id)->get();
                    } else {
                        $receiverDetails = SmStaff::select('email', 'full_name', 'mobile')->where('role_id', $role_id)->where('active_status', 1)->where('school_id', Auth::user()->school_id)->get();
                    }



                    foreach ($receiverDetails as $receiverDetail) {
                        $to_name    = $receiverDetail->full_name;
                        $to_email   = $receiverDetail->email;
                        $to_mobile  = $receiverDetail->mobile;

                        // send dynamic content in $data
                        $data = array('name' => $to_name, 'email_sms_title' => $request->email_sms_title, 'description' => $request->description);
                        if ($request->send_through == 'E') {
                            if (!empty($receiverDetail->full_name) && !empty($receiverDetail->email)) {
                                $flag = $this->sendEmailFromComunicate($data, $to_name, $to_email, $email_sms_title);
                                if (!$flag) {
                                    return redirect()->back()->with('message-danger', 'Operation Failed.');
                                }
                            }
                        } else {
                            $sms = $request->description;
                            $this->sendSMSFromComunicate($to_mobile, $sms);
                        } //end else
                    } //end loop

                } //end role loop
            } //end else Please select whom you want to send

        } //end select tab G
        else if ($request->selectTab == 'I') {
            if (empty($request->message_to_individual)) {
                return redirect()->back()->with('message-danger', 'Please select whom you want to send');
            } else {
                $message_to_individual = $request->message_to_individual;

                foreach ($message_to_individual as $key => $value) {
                    $receiver_full_name_email = explode('-', $value);
                    $receiver_full_name = $receiver_full_name_email[0];
                    $receiver_email = $receiver_full_name_email[1];
                    $receiver_mobile = $receiver_full_name_email[2];

                    $to_name = $receiver_full_name;
                    $to_email = $receiver_email;

                    $to_mobile = $receiver_mobile;
                    // send dynamic content in $data
                    $data = array('name' => $to_name, 'email_sms_title' => $request->email_sms_title, 'description' => $request->description);
                    // If checked Email
                    if ($request->send_through == 'E') {
                        if (!empty($receiverDetail->full_name) && !empty($receiverDetail->email)) {
                            $flag = $this->sendEmailFromComunicate($data, $to_name, $to_email, $email_sms_title);
                            if (!$flag) {
                                return redirect()->back()->with('message-danger', 'Operation Failed');
                            }
                        }
                    }
                    // if checked Sms 
                    else {
                        $sms = $request->description;
                        $this->sendSMSFromComunicate($to_mobile, $sms);
                    } //end else

                }
            } //end else
            return redirect()->back()->with('message-success', 'Successfully Sent');
        } else {
            //  start send email/sms to class section
            if (empty($request->message_to_section)) {
                return redirect()->back()->with('message-danger', 'Please select whom you want to send');
            } else {

                $class_id = $request->class_id;
                $selectedSections = $request->message_to_section;
                foreach ($selectedSections as $key => $value) {
                    $students = SmStudent::select('email', 'full_name', 'mobile')->where('class_id', $class_id)->where('section_id', $value)->where('active_status', 1)->get();

                    foreach ($students as $student) {
                        $to_name = $student->full_name;
                        $to_email = $student->email;
                        $to_mobile = $student->mobile;
                        // send dynamic content in $data
                        $data = array(
                            'name' => $student->full_name,
                            'email_sms_title' => $request->email_sms_title,
                            'description' => $request->description,

                        );


                        if ($request->send_through == 'E') {
                            if (!empty($receiverDetail->full_name) && !empty($receiverDetail->email)) {
                                $flag = $this->sendEmailFromComunicate($data, $to_name, $to_email, $email_sms_title);
                                if (!$flag) {
                                    return redirect()->back()->with('message-danger', 'Operation Failed.');
                                }
                            }
                        } //send email template 
                        else {
                            $sms = $request->description;
                            $this->sendSMSFromComunicate($to_mobile, $sms);
                        } //end else
                    } //end student loop
                } //end selectedSections loop
            } //end else

            
        } //end else 

        return redirect()->back()->with('message-success', 'Successfully Sent');



        // } catch (\Exception $e) {
        //     // Toastr::error('Operation Failed', 'Failed');
        //     return redirect()->back()->with('message-danger', 'Something went wrong, please check '.$send_through.' settings & try again');
        // }


    } // end function sendEmailSms 





    public function studStaffByRole(Request $request)
    {

        if ($request->id == 2) {
            $allStudents = SmStudent::where('active_status', '=', 1)->where('school_id', Auth::user()->school_id)->get();
            $students = [];
            foreach ($allStudents as $allStudent) {
                $students[] = SmStudent::find($allStudent->id);
            }

            return response()->json([$students]);
        }

        if ($request->id == 3) {
            $allParents = SmParent::where('school_id', Auth::user()->school_id)->get();
            $parents = [];
            foreach ($allParents as $allParent) {
                $parents[] = SmParent::find($allParent->id);
            }

            return response()->json([$parents]);
        }

        if ($request->id != 2 and $request->id != 3) {
            $allStaffs = SmStaff::whereRole($request->id)->where('active_status', '=', 1)->where('school_id', Auth::user()->school_id)->get();
            $staffs = [];
            foreach ($allStaffs as $staffsvalue) {
                $staffs[] = SmStaff::find($staffsvalue->id);
            }

            return response()->json([$staffs]);
        }
    }

    public function emailSmsLog()
    {
        $emailSmsLogs = SmEmailSmsLog::orderBy('id', 'DESC')->where('school_id', '=', Auth::user()->school_id)->get();
        return view('saas::communicate.emailSmsLog', compact('emailSmsLogs'));
    }

    public function sendMail()
    {
        $institutions = SmSchool::where('id', '!=', 1)->get();
        return view('saas::superadminReport.sendEmail', compact('institutions'));
    }


    public function sendMailPost(Request $request){
        $request->validate([
            'email_title' => 'required',
            'description' => 'required',
            'school_id' => 'required_without:select_all',
            'select_all' => 'required_without:school_id',
        ]);
        $data['email_title'] = $request->email_title;
        $data['description'] = $request->description;
           try{
            if (isset($request->select_all)) {
                $institutions = SmSchool::where('id', '!=', 1)->get();

                foreach ($institutions as $institution) {
                    $reciver_email =$institution->email;
                    $receiver_name =  $institution->school_name;
                    $subject= $data['email_title'];
                    $view ="saas::superadminReport.sendEmailView";
                    $compact['compact'] =  $data; 
                    @send_mail_without_template($reciver_email, $receiver_name, $subject , $view ,$compact);
                }
            } else {

                $institution = SmSchool::find($request->school_id);
                $reciver_email = $institution->email;
                $receiver_name =  $institution->school_name;
                $subject= $data['email_title'];
                $view ="saas::superadminReport.sendEmailView";
                $compact['compact'] =  $data; 
                @send_mail_without_template($reciver_email, $receiver_name, $subject , $view ,$compact);
            }
            return redirect()->back()->with('message-success', 'Message has been sent successfully');
           } catch (\Exception $e) {
            return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
        }
    }

    public function sendSms(){
        $institutions = SmSchool::where('id', '!=', 1)->get();
        return view('saas::superadminReport.sendSms', compact('institutions'));
    }


    public function sendNotice()
    {
        $allNotices = SmAdministratorNotice::orderBy('id', 'DESC')->get();
        return view('saas::superadminReport.noticeList', compact('allNotices'));
    }

    public function addNotice(){
        try{
            $institutions = SmSchool::where('active_status', 1)->where('id','!=',1)->get();
            return view('saas::superadminReport.addNotice', compact('institutions'));
        }catch (\Throwable $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function saveNotice(Request $request){

        $request->validate([
                'notice_title' => 'required',
                'notice_date' => 'required',
                'publish_on' => 'required',
                'institution' => 'required|array',
            ],
            [
                'institution.required' => 'At least one school required!'
            ]
        );

        try{
            // $institution_array = implode(',', $request->institution);

            foreach($request->institution as $school){
                $administrator = new SmAdministratorNotice();
                $administrator->notice_title = $request->notice_title;
                $administrator->notice_date = date('Y-m-d', strtotime($request->notice_date));
                $administrator->publish_on = date('Y-m-d', strtotime($request->publish_on));
                $administrator->notice_message = $request->notice_message;
                $administrator->inform_to = $school;
                $result = $administrator->save();
            }
    
            foreach($request->institution as $school){
                
                $user = User::where('school_id', $school)->where('role_id', 1)->first();
                $notification = new SmNotification;
                $notification->user_id = $user->id;
                $notification->role_id = 1;
                $notification->date = date('Y-m-d');
                $notification->message = $request->notice_title;
                $notification->school_id = $school;
                $notification->save();
            }
            if ($result) {
                Toastr::success('Notice has been added successfully', 'Success');
                return redirect()->route('administrator/send-notice');
            } else {
                Toastr::error('Something went wrong', 'error');
                return redirect()->route('administrator/send-notice');
            }
        }catch(\Throwable $e){
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function editAdministratorNotice($id){
        try{
            $notice = SmAdministratorNotice::find($id);
            $institutionArray = explode(',', $notice->inform_to);
    
            $institutions = SmSchool::where('id', '!=', 1)->get();
            return view('saas::superadminReport.addNotice', compact('institutions', 'notice', 'institutionArray'));
        }catch(\Throwable $e){
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
   }


    public function updateAdministratorNotice(Request $request)
    {
        $request->validate(
            [
                'notice_title' => 'required',
                'notice_date' => 'required',
                'publish_on' => 'required',
                'institution' => 'required|array',
            ],
            [
                'institution.required' => 'At least one school required!'
            ]
        );

        try{
            $institution_array = implode(',', $request->institution);
            $administrator = SmAdministratorNotice::find($request->id);
            $administrator->notice_title = $request->notice_title;
            $administrator->notice_date = date('Y-m-d', strtotime($request->notice_date));
            $administrator->publish_on = date('Y-m-d', strtotime($request->publish_on));
            $administrator->notice_message = $request->notice_message;
            $administrator->inform_to = $institution_array;
            $result = $administrator->save();

            if ($result) {
                return redirect('administrator/send-notice')->with('message-success', 'Notice has been updated successfully');
            } else {
                return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
            }
        }catch(\Throwable $e){
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function deleteAdministratorNotice($id){
        try{
            $notice = SmAdministratorNotice::destroy($id);
            if ($notice) {
                Toastr::success('Notice has been deleted successfully', 'Success');
                return redirect('administrator/send-notice')->with('message-success', 'Notice has been deleted successfully');
            } else {
                return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
            }
        }catch(\Throwable $e){
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
}