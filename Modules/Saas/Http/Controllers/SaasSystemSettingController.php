<?php

namespace Modules\Saas\Http\Controllers;

use App\User;
use App\SmExam;
use ZipArchive;
use App\SmClass;
use App\SmStaff;
use App\SmStyle;
use App\Language;
use App\SmBackup;
use App\SmModule;
use App\SmParent;
use App\SmSchool;
use App\SmCountry;
use App\SmSection;
use App\SmSession;
use App\SmStudent;
use App\SmSubject;
use App\SmCurrency;
use App\SmExamType;
use App\SmItemSell;
use App\SmLanguage;
use App\SmTimeZone;
use App\SmAddIncome;
use App\SmAddExpense;
use App\SmCustomLink;
use App\SmDateFormat;
use App\SmModuleLink;
use App\SmSmsGateway;
use App\ApiBaseMethod;
use App\SmFeesPayment;
use App\SmItemReceive;
use GuzzleHttp\Client;
use App\SmAcademicYear;
use App\SmEmailSetting;
use App\Mail\VerifyMail;
use App\SmAssignSubject;
use App\SmSystemVersion;
use App\SmLanguagePhrase;
use App\SmPaymentMethhod;
use App\SmRolePermission;
use App\SmGeneralSettings;
use App\SmHomePageSetting;
use Illuminate\Support\Str;
use App\Billing_Information;
use App\Models\SchoolModule;
use App\SmBackgroundSetting;
use App\SmHrPayrollGenerate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\SmFrontendPersmission;
use App\SmPaymentGatewaySetting;
use App\SmModulePermissionAssign;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Nwidart\Modules\Facades\Module;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\Saas\Entities\VerifyUser;
use Illuminate\Support\Facades\Schema;
use App\SmSchoolModulePermissionAssign;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Validator;
use Modules\Saas\Entities\SmSaasPackages;
use Modules\Saas\Entities\SaasSchoolModulePermissionAssign;
use App\Http\Requests\Admin\GeneralSettings\SmEmailSettingsRequest;

class SaasSystemSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('PM');
        // $this->middleware('TimeZone');
        if (empty(Auth::user()->id)) {
            return redirect('login');
        }
    }
    public function lmsModuleSettings()
    {
        $schools = SmSchool::all();
        foreach ($schools as $school) {
            $exists = SchoolModule::where('module_name', 'lms')->where('school_id', $school->id)->first();
            if (!$exists) {
                $module = new SchoolModule;
                $module->module_name ='lms';
                $module->school_id = $school->id;
                $module->active_status = $school->id == 1 ? 1 : 0;
                $module->updated_by = 1;
                $module->save();
            }
        }
        
        $module_settings = SchoolModule::where('module_name', 'lms')
        ->when(auth()->user()->role_id !=1, function ($query) {
             $query->where('school_id', auth()->user()->school_id);
         })
        ->get();
        return view('saas::lms.lms_settings', compact('module_settings'));
    }

    public function universityModuleSettings()
    {
        $schools = SmSchool::all();
        foreach ($schools as $school) {
            $exists = SchoolModule::where('module_name', 'university')->where('school_id', $school->id)->first();
            if (!$exists) {
                $module = new SchoolModule;
                $module->module_name ='university';
                $module->school_id = $school->id;
                $module->active_status = $school->id == 1 ? 1 : 0;
                $module->updated_by = 1;
                $module->save();
            }
        }
        
        $module_settings = SchoolModule::where('module_name', 'university')
        ->when(auth()->user()->role_id !=1, function ($query) {
             $query->where('school_id', auth()->user()->school_id);
         })
        ->get();
        return view('saas::university.university_setting', compact('module_settings'));
    }

    public function news()
    {
        $exams = SmExam::where('school_id', '=', Auth::user()->school_id)->get();
        $exams_types = SmExamType::where('school_id', '=', Auth::user()->school_id)->get();
        $classes = SmClass::where('active_status', 1)->where('school_id', '=', Auth::user()->school_id)->get();
        $subjects = SmSubject::where('active_status', 1)->where('school_id', '=', Auth::user()->school_id)->get();
        $sections = SmSection::where('active_status', 1)->where('school_id', '=', Auth::user()->school_id)->get();
        return view('frontEnd.home.light_news', compact('exams', 'classes', 'subjects', 'exams_types', 'sections'));
    }

    // tableEmpty
    public function tableEmpty()
    {
        $sms_services = DB::table('migrations')->get();

        $tables = DB::select('SHOW TABLES');
        $table_list = [];
        $table_list_with_count = [];

        $tableString = 'Tables_in_' . env('DB_DATABASE', null);

        foreach ($tables as $table) {
            $table_name = $table->$tableString;
            $table_list[] = $table_name;
            $count = DB::table($table_name)->count();
            $table_list_with_count[] = $table->$tableString . '(' . $count . ')';

        }
        return view('saas::systemSettings.tableEmpty', compact('table_list', 'table_list_with_count'));
    }

    // end tableEmpty

    public function databaseDelete(Request $request)
    {

        $list_of_table = $request->permisions;

        if (empty($list_of_table)) {
            return redirect()->back()->with('message-success', 'Ops Sorry! Please select table name.');
        }
        foreach ($list_of_table as $table) {
            DB::table($table)->truncate();
        }

        $staff = new SmStaff();

        $staff->user_id = Auth::user()->id;
        $staff->role_id = 1;
        $staff->staff_no = 1;
        $staff->designation_id = 1;
        $staff->department_id = 1;
        $staff->first_name = 'Super';
        $staff->last_name = 'Admin';
        $staff->full_name = 'Super Admin';
        $staff->fathers_name = 'NA';
        $staff->mothers_name = 'NA';

        $staff->date_of_birth = '1980-12-26';
        $staff->date_of_joining = '2019-05-26';

        $staff->gender_id = 1;
        $staff->email = Auth::user()->email;
        $staff->mobile = '';
        $staff->emergency_mobile = '';
        $staff->merital_status = '';
        $staff->staff_photo = 'public/uploads/staff/staff1.jpg';

        $staff->current_address = '';
        $staff->permanent_address = '';
        $staff->qualification = '';
        $staff->experience = '';

        $staff->casual_leave = '12';
        $staff->medical_leave = '15';
        $staff->metarnity_leave = '45';

        $staff->driving_license = '';
        $staff->driving_license_ex_date = '2019-02-23';
        $staff->save();

        return redirect()->back()->with('message-success', 'Operation successfully');
    }

    public function databaseRestory(Request $request)
    {
        set_time_limit(900);
        Artisan::call('db:seed');
        return redirect()->back()->with('message-success', 'Operation successfully');
    }

    public function displaySetting()
    {
        $sms_services = SmSmsGateway::all();
        $active_sms_service = SmSmsGateway::select('id')->where('active_status', 1)->first();
        return view('saas::systemSettings.displaySetting', compact('sms_services', 'active_sms_service'));
    }

    public function smsSettings()
    {
        $sms_services = SmSmsGateway::where('school_id', Auth::user()->school_id)->get();
        $active_sms_service = SmSmsGateway::select('id')->where('active_status', 1)->where('school_id', Auth::user()->school_id)->first();
        return view('saas::systemSettings.smsSettings', compact('sms_services', 'active_sms_service'));
    }

    public function languageSettings()
    {
        $sms_languages = SmLanguage::where('school_id', Auth::user()->school_id)->get();
        $all_languages = Language::orderBy('code', 'ASC')->get()->except($sms_languages->pluck('lang_id')->toArray());
        return view('saas::systemSettings.languageSettings', compact('sms_languages', 'all_languages'));
    }

    public function schoolLanguageSettings()
    {
        $sms_languages = SmLanguage::where('school_id', Auth::user()->school_id)->get();
        $all_languages = DB::table('languages')->orderBy('code', 'ASC')->get();

        return view('saas::systemSettings.schoolLanguageSettings', compact('sms_languages', 'all_languages'));
    }

    public function languageEdit($id)
    {
        $selected_languages = SmLanguage::find($id);
        $sms_languages = SmLanguage::where('school_id', Auth::user()->school_id)->get();
        $all_languages = DB::table('languages')->orderBy('code', 'ASC')->get();
        return view('saas::systemSettings.languageSettings', compact('sms_languages', 'all_languages', 'selected_languages'));
    }

    public function languageUpdate(Request $request)
    {
        try {
            $id = $request->id;
            $language_id = $request->language_id;
            $language_details = Language::find($language_id);
            $schools=SmSchool::get();
            if (!empty($language_id)) {
                foreach ($schools as $school) {
                    $sms_languages = SmLanguage::find($id);
                    $sms_languages->language_name = $language_details->name != null ? $language_details->name : '';
                    $sms_languages->language_universal = $language_details->code;
                    $sms_languages->native = $language_details->native;
                    $sms_languages->lang_id = $language_details->id;
                    $sms_languages->school_id = $school->id;
                    $results = $sms_languages->save();
                }
            }
            return redirect('language-settings')->with('message-success', 'Operation successful');
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
    public function ajaxLanguageChange($id)
    {
        try{
            $uni = $id;
            SmLanguage::where('active_status', 1)->update(['active_status' => 0]);

            $updateLang = SmLanguage::where('language_universal', $uni)->first();
            $updateLang->active_status = 1;
            $updateLang->update();

            $values['APP_LOCALE'] = $updateLang->language_universal;
            $envFile = app()->environmentFilePath();
            $str = file_get_contents($envFile);
            if (count($values) > 0) {
                foreach ($values as $envKey => $envValue) {
                    $str .= "\n";
                    $keyPosition = strpos($str, "{$envKey}=");
                    $endOfLinePosition = strpos($str, "\n", $keyPosition);
                    $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
                    if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                        $str .= "{$envKey}={$envValue}\n";
                    } else {
                        $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
                    }
                }
            }
            $str = substr($str, 0, -1);
            $res = file_put_contents($envFile, $str);

            return response()->json([$updateLang]);
        } catch (\Exception $e) {
            return response()->json(" ",404);
        }
    }

    public function ajaxSubjectDropdown(Request $request)
    {
        try{
            $class_id = $request->class;
            $allSubjects = SmAssignSubject::where([['section_id', '=', $request->id], ['class_id', $class_id]])->get();
            $subjectsName = [];
            foreach ($allSubjects as $allSubject) {
                $subjectsName[] = SmSubject::find($allSubject->subject_id);
            }

            return response()->json([$subjectsName]);
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }
    }

    // public function languageAdd(Request $request)
    // {

    //     $request->validate([
    //         'lang_id' => 'required',
    //     ]);

    //     $lang_id = $request->lang_id;

    //     $language_details = DB::table('languages')->where('id', $lang_id)->first();


    //     if (!empty($language_details)) {

    //         $sms_languages = new SmLanguage();
    //         $sms_languages->language_name = $language_details->name;
    //         $sms_languages->language_universal = $language_details->code;
    //         $sms_languages->native = $language_details->native;
    //         $sms_languages->lang_id = $language_details->id;

    //         $results = $sms_languages->save();

    //         if ($results) {

    //             if (Schema::hasColumn('sm_language_phrases', $language_details->code)); {
    //                 return redirect('language-settings')->with('message-success', 'A new Language has been added successfully');
    //             }

    //             if (DB::statement('ALTER TABLE sm_language_phrases ADD ' . $language_details->code . ' text')) {
    //                 $column = $language_details->code;

    //                 $all_translation_terms = SmLanguagePhrase::all();

    //                 $jsonArr = [];
    //                 foreach ($all_translation_terms as $row) {
    //                     $lid = $row->id;
    //                     $english_term = $row->en;
    //                     if (!empty($english_term)) {
    //                         $update_translation_term = SmLanguagePhrase::find($lid);
    //                         $update_translation_term->$column = $english_term;
    //                         $update_translation_term->active_status = 1;
    //                         $update_translation_term->save();
    //                     }

    //                     //$jsonArr[$row->default_phrases]=$row->en;  //Don't Delete
    //                 }
    //                 //$reGenarate = json_encode($jsonArr); //Don't Delete

    //                 $path = base_path() . '/resources/lang/' . $language_details->code;
    //                 if (!file_exists($path)) {
    //                     File::makeDirectory($path, $mode = 0777, true, true);
    //                     $newPath = $path . 'lang.php';
    //                     $page_content = "<?php
    //                 use App\SmLanguagePhrase;
    //                 \$getData = SmLanguagePhrase::where('active_status',1)->get();
    //                 \$LanguageArr=[];
    //                 foreach (\$getData as \$row) {
    //                     \$LanguageArr[\$row->default_phrases]=\$row->" . $language_details->code . ";
    //                 }
    //                 return \$LanguageArr;";

    //                     if (!file_exists($newPath)) {
    //                         File::put($path . '/lang.php', $page_content);
    //                     }
    //                 }

    //                 return redirect('language-settings')->with('message-success', 'A new Language has been added successfully');
    //             } else {
    //                 return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
    //             }
    //         } else {
    //             return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
    //         }
    //     } //not empty language

    // }



    public function languageAdd(Request $request)
    {


        $request->validate([
            'lang_id' => 'required|max:255',
        ]);

        try{
            $lang_id = $request->lang_id;
            $language_details = DB::table('languages')->where('id', $lang_id)->first();

            if (!empty($language_details)) {
                $schools = SmSchool::all();
                foreach($schools as $school){
                    $sms_languages                     = new SmLanguage();
                    $sms_languages->language_name      = $language_details->name;
                    $sms_languages->language_universal = $language_details->code;
                    $sms_languages->native             = $language_details->native;
                    $sms_languages->lang_id            = $language_details->id;
                    $sms_languages->school_id          = $school->id;
                    $sms_languages->active_status      = '0';
                    $sms_languages->save();
                }
                
                if ($language_details->code != 'en') {
                    File::copyDirectory(base_path('/resources/lang/en'), base_path('/resources/lang/' . $language_details->code));
                    $modules = Module::all();
                    foreach ($modules as $module) {
                        File::copyDirectory(module_path($module->getName()) . '/Resources/lang/en', module_path($module->getName()) . '/Resources/lang/' . $language_details->code);
                    }
                }
               
            } //not empty language

            Toastr::success('Operation Successfull', 'success');
           return redirect()->back();
        }catch (\Exception $e) {
           Toastr::error('Operation Failed', 'Failed');
           return redirect()->back();
        }

    }


    public function schoolLanguageAdd(Request $request)
    {

        $request->validate([
            'lang_id' => 'required',
        ]);

        $lang_id = $request->lang_id;
        $language_details = DB::table('languages')->where('id', $lang_id)->first();

        if (!empty($language_details)) {

            $sms_languages = new SmLanguage();
            $sms_languages->language_name = $language_details->name;
            $sms_languages->language_universal = $language_details->code;
            $sms_languages->native = $language_details->native;
            $sms_languages->lang_id = $language_details->id;

            $results = $sms_languages->save();

            if ($results) {

                if (Schema::hasColumn('sm_language_phrases', $language_details->code)); {
                    return redirect('language-settings')->with('message-success', 'A new Language has been added successfully');
                }

                if (DB::statement('ALTER TABLE sm_language_phrases ADD ' . $language_details->code . ' text')) {
                    $column = $language_details->code;

                    $all_translation_terms = SmLanguagePhrase::all();

                    $jsonArr = [];
                    foreach ($all_translation_terms as $row) {
                        $lid = $row->id;
                        $english_term = $row->en;
                        if (!empty($english_term)) {
                            $update_translation_term = SmLanguagePhrase::find($lid);
                            $update_translation_term->$column = $english_term;
                            $update_translation_term->active_status = 1;
                            $update_translation_term->save();
                        }

                        //$jsonArr[$row->default_phrases]=$row->en;  //Don't Delete
                    }
                    //$reGenarate = json_encode($jsonArr); //Don't Delete

                    $path = base_path() . '/resources/lang/' . $language_details->code;
                           
                    if (!file_exists($path)) {
                        File::makeDirectory($path, $mode = 0777, true, true);

                        $getData = SmLanguagePhrase::where('active_status', 1)->pluck($language_details->code, 'default_phrases');
                        $jsonContent = json_encode($getData);
                        $file = base_path() . '/resources/lang/' . $language_details->code . '/'. $language_details->code. ".json";
                        File::put($file, $jsonContent);
                        
                        $name =  '/resources/lang/'. $language_details->code . '/'. $language_details->code. ".json";
                        $nameStr = '"' .$name.'"' ;
                        $newPath      = $path . 'lang.php';
                        $page_content = "<?php
                                            \$jsonFile =  base_path() .". $nameStr . ";
                                            \$array =  json_decode(file_get_contents(\$jsonFile), true);
                                            return \$array;";

                
                        if (!file_exists($newPath)) {
                            File::put($path . '/lang.php', $page_content);
                        }
                    }

                    return redirect('language-settings')->with('message-success', 'A new Language has been added successfully');
                } else {
                    return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
                }
            } else {
                return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
            }
        } //not empty language

    }

    //backupSettings
    public function backupSettings()
    {
        $sms_dbs = SmBackup::orderBy('id', 'DESC')->get();
        return view('saas::systemSettings.backupSettings', compact('sms_dbs'));
    }

    public function BackupStore(Request $request)
    {
        $request->validate([
            'content_file' => 'required|file',
        ]);

        if ($request->file('content_file') != "") {
            $file = $request->file('content_file');
            if ($file->getClientOriginalExtension() == 'sql') {
                $file_name = 'Restore_' . date('d_m_Y_') . $file->getClientOriginalName();
                $file->move('public/databaseBackup/', $file_name);
                $content_file = 'public/databaseBackup/' . $file_name;
            } else {
                return redirect()->back()->with('message-danger', 'Ops! Your file is not sql, please try again');
            }
        }

        if (isset($content_file)) {
            $store = new SmBackup();
            $store->file_name = $file_name;
            $store->source_link = $content_file;
            $store->active_status = 1;
            $store->school_id =  Auth::user()->school_id;
            $store->created_by = Auth::user()->id;
            $store->updated_by = Auth::user()->id;
            $result = $store->save();
        }
        if ($result) {
            return redirect()->back()->with('message-success-delete', 'Database deleted successfully');
        } else {
            return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
        }

        $sms_dbs = SmBackup::orderBy('id', 'DESC')->get();
        return view('saas::systemSettings.backupSettings', compact('sms_dbs'));
    }

    public function languageSetup($language_universal)
    {
        try {
            $lang = 'en';
            $files['base']   = glob(resource_path('lang/' . $lang . '/*.php'));

            $modules = \Module::all();
            foreach ($modules as $module) {
                if (moduleStatusCheck($module->getName())) {
                    $file = glob(module_path($module->getName()) . '/Resources/lang/'.$lang.'/*.php');
                    if ($file) {
                        $files[$module->getLowerName()] = $file;
                    }
                }
            }

            $modules = [];
            foreach($files as $key => $module){
//                $files[] = $key;
                foreach($module as $file){
                    $file = basename($file, '.php');
                    if ($file != 'validation'){
                        $modules[$key][$key.'::'.$file] = $file;
                    }

                }
            }
            return view('saas::systemSettings.languageSetup', compact('language_universal', 'modules'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }


    }

    public function schoolLanguageSetup($language_universal)
    {
        $sms_languages = SmLanguagePhrase::where('active_status', 1)->get();
        $modules = SmModule::all();
        return view('saas::systemSettings.schoolLanguageSetup', compact('language_universal', 'sms_languages', 'modules'));
    }

    public function deleteDatabase($id)
    {
        $source_link = "";
        $data = SmBackup::find($id);
        if (!empty($data)) {
            $source_link = $data->source_link;
            if (file_exists($source_link)) {
                unlink($source_link);
            }
        }
        $result = SmBackup::where('id', $id)->delete();
        if ($result) {
            return redirect()->back()->with('message-success-delete', 'Database deleted successfully');
        } else {
            return redirect()->back()->with('message-danger-delete', 'Something went wrong, please try again');
        }
    }

    //download database from public/databaseBackup
    public function downloadDatabase($id)
    {
        $source_link = "";
        $data = SmBackup::where('id', $id)->first();
        if (!empty($data)) {
            $source_link = $data->source_link;
            if (file_exists($source_link)) {
                unlink($source_link);
            }
        }

        if (file_exists($source_link)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($source_link) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($source_link));
            flush(); // Flush system output buffer
            readfile($source_link);
            return redirect()->back();
        }
    }

    //restore database from public/databaseBackup
    public function restoreDatabase($id)
    {
        $sm_db = SmBackup::where('id', $id)->first();
        if (!empty($sm_db)) {
            $source_link = $sm_db->source_link;
        }

        $DB_HOST = env("DB_HOST", "");
        $DB_DATABASE = env("DB_DATABASE", "");
        $DB_USERNAME = env("DB_USERNAME", "");
        $DB_PASSWORD = env("DB_PASSWORD", "");

        $connection = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);

        if (!file_exists($source_link)) {
            return redirect()->back()->with('message-danger', 'Your file is not found, please try again');
        }
        $handle = fopen($source_link, "r+");
        $contents = fread($handle, filesize($source_link));
        $sql = explode(';', $contents);
        $flag = 0;
        foreach ($sql as $query) {
            $result = mysqli_query($connection, $query);
            if ($result) {
                $flag = 1;
            }
        }
        fclose($handle);

        if ($flag) {
            return redirect()->back()->with('message-success', 'Database Restore successfully');
        } else {
            return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
        }
    }

    //get files Backup #file
    public function getfilesBackup($id)
    {
        set_time_limit(1600);
        if ($id == 1) {
            $files = base_path() . '/public/uploads';
            $created_file_name = 'Backup_' . date('d_m_Y_h:i') . 'Images.zip';
        } else if ($id == 2) {
            $files = base_path() . '/public/uploads/';

            $created_file_name = 'Backup_' . date('d_m_Y_h:i') . 'Projects.zip';
        }
        

         \Zipper::make(public_path($created_file_name))->add($files)->close();

        $store = new SmBackup();
        $store->file_name = $created_file_name;
        $store->source_link = public_path($created_file_name);
        $store->active_status = 1;
        $store->file_type = $id;
        $store->created_by = Auth::user()->id;
        $store->updated_by = Auth::user()->id;
        $store->school_id =  Auth::user()->school_id;
        $result = $store->save();
        if ($id == 2) {
            return response()->download(public_path($created_file_name));
        }

        return redirect()->back()->with('message-success', 'Files Backup successfully');
    }

    // download Files #file
    public function downloadFiles($id)
    {
        $sm_db = SmBackup::where('id', $id)->first();
        $source_link = $sm_db->source_link;
        return response()->download($source_link);
    }

    public function getDatabaseBackup()
    {
        $DB_HOST = env("DB_HOST", "");
        $DB_DATABASE = env("DB_DATABASE", "");
        $DB_USERNAME = env("DB_USERNAME", "");
        $DB_PASSWORD = env("DB_PASSWORD", "");
        $connection = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);

        $tables = array();
        $result = mysqli_query($connection, "SHOW TABLES");
        while ($row = mysqli_fetch_row($result)) {
            $tables[] = $row[0];
        }
        $return = '';
        foreach ($tables as $table) {
            $result = mysqli_query($connection, "SELECT * FROM " . $table);
            $num_fields = mysqli_num_fields($result);

            $return .= 'DROP TABLE ' . $table . ';';
            $row2 = mysqli_fetch_row(mysqli_query($connection, "SHOW CREATE TABLE " . $table));
            $return .= "\n\n" . $row2[1] . ";\n\n";

            for ($i = 0; $i < $num_fields; $i++) {
                while ($row = mysqli_fetch_row($result)) {
                    $return .= "INSERT INTO " . $table . " VALUES(";
                    for ($j = 0; $j < $num_fields; $j++) {
                        $row[$j] = addslashes($row[$j]);
                        if (isset($row[$j])) {
                            $return .= '"' . $row[$j] . '"';
                        } else {
                            $return .= '""';
                        }
                        if ($j < $num_fields - 1) {
                            $return .= ',';
                        }
                    }
                    $return .= ");\n";
                }
            }
            $return .= "\n\n\n";
        }

        if (!file_exists('public/databaseBackup')) {
            mkdir('public/databaseBackup', 0777, true);
        }

        //save file
        $name = 'database_backup_' . date('d_m_Y_h:i') . '.sql';
        $path = 'public/databaseBackup/' . $name;
        $handle = fopen($path, "w+");
        fwrite($handle, $return);
        fclose($handle);

        $get_backup = new SmBackup();
        $get_backup->file_name = $name;
        $get_backup->source_link = $path;
        $get_backup->active_status = 1;
        $get_backup->file_type = 0;
        $get_backup->school_id =  Auth::user()->school_id;
        $get_backup->created_by = Auth::user()->id;
        $get_backup->updated_by = Auth::user()->id;
        $results = $get_backup->save();

        // $sms_dbs = SmBackup::orderBy('id', 'DESC')->get();
        // return view('saas::systemSettings.backupSettings', compact('sms_dbs'));

        if ($results) {
            return redirect()->back()->with('message-success', 'Database Backup successfully');
        } else {
            return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
        }
    }

    public function updateClickatellData()
    {
        $gateway_id = $_POST['gateway_id'];
        $clickatell_username = $_POST['clickatell_username'];
        $clickatell_password = $_POST['clickatell_password'];
        $clickatell_api_id = $_POST['clickatell_api_id'];

        if ($gateway_id) {
            $gatewayDetails = SmSmsGateway::where('id', $gateway_id)->first();
            if (!empty($gatewayDetails)) {

                $gatewayDetailss = SmSmsGateway::find($gatewayDetails->id);
                $gatewayDetailss->clickatell_username = $clickatell_username;
                $gatewayDetailss->clickatell_password = $clickatell_password;
                $gatewayDetailss->clickatell_api_id = $clickatell_api_id;
                $gatewayDetailss->updated_by = Auth::user()->id;
                $results = $gatewayDetailss->update();
            } else {

                $gatewayDetail = new SmSmsGateway();
                $gatewayDetail->clickatell_username = $clickatell_username;
                $gatewayDetail->clickatell_password = $clickatell_password;
                $gatewayDetail->clickatell_api_id = $clickatell_api_id;
                $gatewayDetail->school_id = Auth::user()->school_id;
                $gatewayDetail->created_by = Auth::user()->id;
                $gatewayDetail->updated_by = Auth::user()->id;
                $results = $gatewayDetail->save();
            }
        }

        // $key1 = 'CLICKATELL_API_KEY';

        // $value1 = $clickatell_api_id;

        // $path            = base_path() . "/.env";
        // $CLICKATELL_API_KEY       = env($key1);

        // if (file_exists($path)) {
        //     file_put_contents($path, str_replace(
        //         "$key1=" . $CLICKATELL_API_KEY,
        //         "$key1=" . $value1,
        //         file_get_contents($path)
        //     ));
        // }


        if ($results) {
            echo 'success';
        }
    }

    public function updateTwilioData()
    {

        $gateway_id = $_POST['gateway_id'];
        $twilio_account_sid = $_POST['twilio_account_sid'];
        $twilio_authentication_token = $_POST['twilio_authentication_token'];
        $twilio_registered_no = $_POST['twilio_registered_no'];

        if ($gateway_id) {
            $gatewayDetails = SmSmsGateway::where('id', $gateway_id)->first();
            if (!empty($gatewayDetails)) {

                $gatewayDetailss = SmSmsGateway::find($gatewayDetails->id);
                $gatewayDetailss->twilio_account_sid = $twilio_account_sid;
                $gatewayDetailss->twilio_authentication_token = $twilio_authentication_token;
                $gatewayDetailss->twilio_registered_no = $twilio_registered_no;
                $gatewayDetailss->school_id = Auth::user()->school_id;
                $gatewayDetailss->updated_by = Auth::user()->id;
                $results = $gatewayDetailss->update();
            } else {

                $gatewayDetail = new SmSmsGateway();
                $gatewayDetail->twilio_account_sid = $twilio_account_sid;
                $gatewayDetail->twilio_authentication_token = $twilio_authentication_token;
                $gatewayDetail->twilio_registered_no = $twilio_registered_no;
                $gatewayDetail->school_id = Auth::user()->school_id;
                $gatewayDetail->created_by = Auth::user()->id;
                $gatewayDetail->updated_by = Auth::user()->id;
                $results = $gatewayDetail->save();
            }
        }

        // $key1 = 'TWILIO_SID';
        // $key2 = 'TWILIO_TOKEN';
        // $key3 = 'TWILIO_FROM';

        // $value1 = $twilio_account_sid;
        // $value2 = $twilio_authentication_token;
        // $value3 = $twilio_registered_no;

        // $path            = base_path() . "/.env";
        // $TWILIO_SID       = env($key1);
        // $TWILIO_TOKEN = env($key2);
        // $TWILIO_FROM   = env($key3);

        // if (file_exists($path)) {
        //     file_put_contents($path, str_replace(
        //         "$key1=" . $TWILIO_SID,
        //         "$key1=" . $value1,
        //         file_get_contents($path)
        //     ));
        //     file_put_contents($path, str_replace(
        //         "$key2=" . $TWILIO_TOKEN,
        //         "$key2=" . $value2,
        //         file_get_contents($path)
        //     ));
        //     file_put_contents($path, str_replace(
        //         "$key3=" . $TWILIO_FROM,
        //         "$key3=" . $value3,
        //         file_get_contents($path)
        //     ));
        // }

        if ($results) {
            echo "success";
        }
    }

    public function updateMsg91Data(Request $request)
    {

        $gateway_id = $request->gateway_id;
        $msg91_authentication_key_sid = $request->msg91_authentication_key_sid;
        $msg91_route = $request->msg91_route;
        $msg91_country_code = $request->msg91_country_code;
        $msg91_sender_id = $request->msg91_sender_id;

        // $key1 = 'MSG91_KEY';
        // $key2 = 'MSG91_SENDER_ID';
        // $key3 = 'MSG91_COUNTRY';
        // $key4 = 'MSG91_ROUTE';

        // $value1 = $msg91_authentication_key_sid;
        // $value2 = $msg91_sender_id;
        // $value3 = $msg91_country_code;
        // $value4 = $msg91_route;

        // $path = base_path() . "/.env";
        // $MSG91_KEY = env($key1);
        // $MSG91_SENDER_ID = env($key2);
        // $MSG91_COUNTRY = env($key3);
        // $MSG91_ROUTE = env($key4);

        // if (file_exists($path)) {
        //     file_put_contents($path, str_replace(
        //         "$key1=" . $MSG91_KEY,
        //         "$key1=" . $value1,
        //         file_get_contents($path)
        //     ));
        //     file_put_contents($path, str_replace(
        //         "$key2=" . $MSG91_SENDER_ID,
        //         "$key2=" . $value2,
        //         file_get_contents($path)
        //     ));
        //     file_put_contents($path, str_replace(
        //         "$key3=" . $MSG91_COUNTRY,
        //         "$key3=" . $value3,
        //         file_get_contents($path)
        //     ));
        //     file_put_contents($path, str_replace(
        //         "$key4=" . $MSG91_ROUTE,
        //         "$key4=" . $value4,
        //         file_get_contents($path)
        //     ));
        // }

        if ($gateway_id) {
            $gatewayDetails = SmSmsGateway::where('id', $gateway_id)->where('school_id', Auth::user()->school_id)->first();
            if (!empty($gatewayDetails)) {

                $gatewayDetailss = SmSmsGateway::find($gatewayDetails->id);
                $gatewayDetailss->msg91_authentication_key_sid = $msg91_authentication_key_sid;
                $gatewayDetailss->msg91_sender_id = $msg91_sender_id;
                $gatewayDetailss->msg91_route = $msg91_route;
                $gatewayDetailss->msg91_country_code = $msg91_country_code;
                $gatewayDetailss->school_id = Auth::user()->school_id;
                $gatewayDetailss->updated_by = Auth::user()->id;
                $results = $gatewayDetailss->update();
            } else {

                $gatewayDetail = new SmSmsGateway();

                $gatewayDetail->msg91_authentication_key_sid = $msg91_authentication_key_sid;
                $gatewayDetail->msg91_sender_id = $msg91_sender_id;
                $gatewayDetail->msg91_route = $msg91_route;
                $gatewayDetail->msg91_country_code = $msg91_country_code;
                $gatewayDetail->school_id = Auth::user()->school_id;
                $gatewayDetail->created_by = Auth::user()->id;
                $gatewayDetail->updated_by = Auth::user()->id;
                $results = $gatewayDetail->save();
            }
        }

        if ($results) {
            return redirect()->back()->with('message-success', 'Operation successfully');
        } else {
            return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
        }
    }

    public function activeSmsService()
    {
        $sms_service = $_POST['sms_service'];

        if ($sms_service) {
            $gatewayDetailss = SmSmsGateway::where('active_status', '=', 1)
                ->update(['active_status' => 0, 'school_id' => Auth::user()->school_id]);
        }

        $gatewayDetails = SmSmsGateway::find($sms_service);
        $gatewayDetails->active_status = 1;
        $gatewayDetails->school_id = Auth::user()->school_id;
        $gatewayDetails->updated_by = Auth::user()->id;
        $results = $gatewayDetails->update();

        if ($results) {
            echo "success";
        }
    }

    public function generalSettingsView(Request $request)
    {
        $editData = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
        
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {

            return ApiBaseMethod::sendResponse($editData, null);
        }
        return view('saas::systemSettings.generalSettingsView', compact('editData'));
    }

    public function customDomainSettingsView()
    {
        $setting = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
        
        return view('saas::systemSettings.customDomainSettingsView', compact('setting'));
    }

    public function customDomainSettingsPost (Request $request){
        if (config('app.app_sync')) {
            return response()->json(['message' => 'Restricted in demo mode'], 401);
        }
        $request->validate([
            'allow_custom_domain' => 'required|in:true,false'
        ]);

       
        putEnvConfigration("ALLOW_CUSTOM_DOMAIN", $request->allow_custom_domain);
        Toastr::success('Operation Successfull', 'Success');
        return redirect()->route('administrator/custom-domain-settings');
    }


    public function schoolSettingsView(Request $request)
    {
        try {


            $editData = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();

            $session = SmGeneralSettings::join('sm_academic_years', 'sm_academic_years.id', '=', 'sm_general_settings.session_id')->find(1);
            // return $editData;
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {

                return ApiBaseMethod::sendResponse($editData, null);
            }
            return view('backEnd.systemSettings.generalSettingsView', compact('editData', 'session'));
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function manageCurrency()
    {
        $currencies = SmCurrency::whereOr(['school_id', Auth::user()->school_id], ['school_id', 1])->get();
        return view('backEnd.systemSettings.manageCurrency', compact('currencies'));
    }

    public function storeCurrency(Request $request)
    {
    $request->validate([
    'name' => 'required | max:25',
    'code' => 'required | max:15',
    'symbol' => 'required | max:5',
    ]);


    try {
    $s = new SmCurrency();
    $s->name = $request->name;
    $s->code = $request->code;
    $s->symbol = $request->symbol;
    $s->school_id = Auth::user()->school_id;
    $s->save();
    Toastr::success('Operation successful', 'Success');
    return redirect('manage-currency');

    $currencies = SmCurrency::whereOr(['school_id', Auth::user()->school_id], ['school_id', 1])->get();
    return view('saas::systemSettings.manageCurrency', compact( 'currencies'));

    } catch (\Exception $e) {

    return $e->getMessage();
    }


    }


    public function storeCurrencyUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required | max:25',
            'code' => 'required | max:15',
            'symbol' => 'required | max:5',
        ]);


        try {
            $s =SmCurrency::findOrfail($request->id);
            $s->name = $request->name;
            $s->code = $request->code;
            $s->symbol = $request->symbol;
            $s->school_id = Auth::user()->school_id;
            $s->update();

            Toastr::success('Operation successful', 'Success');
            return redirect('manage-currency');

            $currencies = SmCurrency::whereOr(['school_id', Auth::user()->school_id], ['school_id', 1])->get();
            return view('saas::systemSettings.manageCurrency', compact( 'currencies'));

        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return $e->getMessage();
        }


    }
    public function manageCurrencyEdit($id)
    {

        try {
            $editData = SmCurrency::findOrfail($id);
            $currencies = SmCurrency::whereOr(['school_id', Auth::user()->school_id], ['school_id', 1])->get();

            return view('saas::systemSettings.manageCurrency', compact('editData','currencies'));

        } catch (\Exception $e) {

            return $e->getMessage();
        }


    }
    public function manageCurrencyDelete($id){
        try {
            $currency = SmCurrency::findOrfail($id);
              $currency->delete();
              Toastr::success('Operation successful', 'Success');
              return redirect()->back();
        } catch (\Exception $e) {

            Toastr::error('Operation Failed', 'Failed');
			return redirect()->back();
        }
    }










    public function addGeneralSettings(Request $request)
    {
        $editData = SmGeneralSettings::where('school_id', '=', Auth::user()->school_id)->get();
        $session_ids = SmSession::where('active_status', 1)->where('school_id', '=', Auth::user()->school_id)->get();
        $dateFormats = SmDateFormat::where('active_status', 1)->get();
        $languages = SmLanguage::all();
        $countries = SmCountry::select('currency')->distinct('currency')->get();
        $currencies = SmCurrency::whereOr(['school_id', Auth::user()->school_id], ['school_id', 1])->get();

        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $data = [];
            $data['editData'] = $editData;
            $data['session_ids'] = $session_ids->toArray();
            $data['dateFormats'] = $dateFormats->toArray();
            $data['languages'] = $languages->toArray();
            $data['countries'] = $countries->toArray();
            $data['currencies'] = $currencies->toArray();
            $data['academic_years'] = academicYears()->toArray();
            return ApiBaseMethod::sendResponse($data, 'apply leave');
        }
        return view('saas::systemSettings.addGeneralSettings', compact('editData', 'session_ids', 'dateFormats', 'languages', 'countries', 'currencies'));
    }


    public function updateGeneralSettings(Request $request)
    {
        $editData = SmGeneralSettings::where('school_id', '=', Auth::user()->school_id)->first();
        $session_ids = SmSession::where('active_status', 1)->where('school_id', '=', Auth::user()->school_id)->get();
        $dateFormats = SmDateFormat::where('active_status', 1)->get();
        // $dateFormats = SmDateFormat::where('active_status', 1)->where('school_id', '=', Auth::user()->school_id)->get();
        $languages = SmLanguage::all();
        $countries = SmCountry::select('currency')->distinct('currency')->get();
        $currencies = SmCurrency::whereOr(['school_id', Auth::user()->school_id], ['school_id', 1])->get();
        $time_zones = SmTimeZone::all();

        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $data = [];
            $data['editData'] = $editData;
            $data['session_ids'] = $session_ids->toArray();
            $data['dateFormats'] = $dateFormats->toArray();
            $data['languages'] = $languages->toArray();
            $data['countries'] = $countries->toArray();
            $data['currencies'] = $currencies->toArray();
            $data['academic_years'] = academicYears()->toArray();
            return ApiBaseMethod::sendResponse($data, 'apply leave');
        }
        return view('saas::systemSettings.updateGeneralSettings', compact('time_zones', 'editData', 'session_ids', 'dateFormats', 'languages', 'countries',
            'currencies'));
    }

    public function updateSchoolSettings(Request $request)
    {
        $editData = SmGeneralSettings::where('school_id', '=', Auth::user()->school_id)->first();
        $school = SmSchool::where('id', '=', Auth::user()->school_id)->first();
        $system_settings = SmGeneralSettings::where('school_id', '=', 1)->first();




        $session_ids = SmAcademicYear::where('active_status', 1)->where('school_id', '=', Auth::user()->school_id)->get();

        $dateFormats = SmDateFormat::where('active_status', 1)->get();
        // $dateFormats = SmDateFormat::where('active_status', 1)->where('school_id', '=', Auth::user()->school_id)->get();
        $languages = SmLanguage::all();
        $countries = SmCountry::select('currency')->distinct('currency')->get();
        $currencies = SmCurrency::whereIn('school_id', array(1, Auth::user()->school_id))->get();
        $time_zones = SmTimeZone::all();

// return  $currencies;




        return view('saas::systemSettings.updateSchoolSettings', compact('editData', 'school', 'time_zones', 'currencies', 'countries',
            'languages', 'dateFormats', 'session_ids','system_settings'));
    }


    public function updateSchoolSettingsData(Request $request){
        // $input = $request->all();

        $request->validate([
            'school_name' => "required",
            'site_title' => "required",
            'phone' => "required",
            'email' => "required",
            'session_id' => "required",
            // 'language_id' => "required",
            // 'date_format_id' => "required",
            // 'currency' => "required",
            // 'currency_symbol' => "required",
            // 'time_zone' => "required",
        ]);



        DB::beginTransaction();
        try {

            // $session=SmAcademicYear::find($request->session_id);
            // $session->is_default=1;


            // $session->save();



            $generalSettData = SmGeneralSettings::where('school_id', '=', Auth::user()->school_id)->first();
            $generalSettData->school_name = $request->school_name;
            $generalSettData->site_title = $request->site_title;
            $generalSettData->school_code = $request->school_code;
            $generalSettData->address = $request->address;
            $generalSettData->phone = $request->phone;
            $generalSettData->email = $request->email;
            // $generalSettData->session_year = $session->session;
            $generalSettData->session_id = $request->session_id;
            // $generalSettData->language_id = $request->language_id;
            // $generalSettData->date_format_id = $request->date_format_id;
            // $generalSettData->currency = $request->currency;
            // $generalSettData->currency_symbol = $request->currency_symbol;
            // $generalSettData->copyright_text = $request->copyright_text;
            $generalSettData->promotionSetting = $request->promotionSetting;
            // $generalSettData->time_zone_id = $request->time_zone;

            $results = $generalSettData->update();

            $school = SmSchool::find(Auth::user()->school_id);
            $school->school_name = $request->school_name;
            $school->email = $request->email;
            $school->phone = $request->phone;
            $school->school_code = $request->school_code;
            $school->address = $request->address;
            $school->save();

            DB::commit();

            Toastr::success('General Settings has been updated successfully', 'Success');
            return redirect('school-general-settings');

        } catch (\Exception $e) {
            DB::rollback();
            // return $e;
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }

    }

    public function addGeneralSettingsData(Request $request)
    {

       
        $input = $request->all();

        $validator = Validator::make($input, [
            'school_name' => "required",
            'site_title' => "required",
            'phone' => "required",
            'email' => "required",
            'session_id' => "required",
            'language_id' => "required",
            'date_format_id' => "required",
            'currency' => "required",
            'currency_symbol' => "required",

        ]);

        if ($validator->fails()) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Validation Error.', $validator->errors());
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        $generalSettData = new SmGeneralSettings;
        $generalSettData->school_name = $request->school_name;
        $generalSettData->site_title = $request->site_title;
        $generalSettData->school_code = $request->school_code;
        $generalSettData->address = $request->address;
        $generalSettData->phone = $request->phone;
        $generalSettData->email = $request->email;
        $generalSettData->session_id = $request->session_id;
        $generalSettData->language_id = $request->language_id;
        $generalSettData->date_format_id = $request->date_format_id;
        $generalSettData->currency = $request->currency;
        $generalSettData->currency_symbol = $request->currency_symbol;

        $generalSettData->copyright_text = $request->copyright_text;
        $generalSettData->school_id =  Auth::user()->school_id;

        $results = $generalSettData->save();

        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            if ($results) {
                return ApiBaseMethod::sendResponse(null, 'General Settings has been added successfully');
            } else {
                return ApiBaseMethod::sendError('Something went wrong, please try again');
            }
        } else {
            if ($results) {
                Toastr::success('General Settings has been added successfully', 'Success');
                return redirect('general-settings');
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }
    }
    public function updateGeneralSettingsData(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'school_name' => "required",
            'site_title' => "required",
            'phone' => "required",
            'email' => "required",
            'session_id' => "required",
            'language_id' => "required",
            'date_format_id' => "required",
            'currency' => "required",
            'currency_symbol' => "required",
            'time_zone' => "required",
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        // $id = 1;
        $generalSettData = SmGeneralSettings::where('school_id', '=', Auth::user()->school_id)->first();
        $generalSettData->school_name = $request->school_name;
        $generalSettData->site_title = $request->site_title;
        $generalSettData->school_code = $request->school_code;
        $generalSettData->address = $request->address;
        $generalSettData->phone = $request->phone;
        $generalSettData->email = $request->email;
        $generalSettData->session_id = $request->session_id;
        $generalSettData->language_id = $request->language_id;
        $generalSettData->date_format_id = $request->date_format_id;
        $generalSettData->currency = $request->currency;
        $generalSettData->currency_symbol = $request->currency_symbol;
        $generalSettData->copyright_text = $request->copyright_text;
        $generalSettData->promotionSetting = $request->promotionSetting;
        $generalSettData->time_zone_id = $request->time_zone;
        $results = $generalSettData->update();

        if ($generalSettData->timeZone != "") {
            $value1 = $generalSettData->timeZone->time_zone;
            $key1 = 'APP_TIMEZONE';
            $path            = base_path() . "/.env";
            $APP_TIMEZONE       = env($key1);

            if (file_exists($path)) {
                file_put_contents($path, str_replace(
                    "$key1=" . $APP_TIMEZONE,
                    "$key1=" . $value1,
                    file_get_contents($path)
                ));
            }
        }


        if ($generalSettData->timeZone != "") {
            $value1 = $generalSettData->timeZone->time_zone;
            $key1 = 'APP_TIMEZONE';
            $path            = base_path() . "/.env";
            $APP_TIMEZONE       = env($key1);

            if (file_exists($path)) {
                file_put_contents($path, str_replace(
                    "$key1=" . $APP_TIMEZONE,
                    "$key1=" . $value1,
                    file_get_contents($path)
                ));
            }
        }

        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            if ($results) {
                return ApiBaseMethod::sendResponse(null, 'General Settings has been updated successfully');
            } else {
                return ApiBaseMethod::sendError('Something went wrong, please try again');
            }
        } else {
            if ($results) {
                Toastr::success('General Settings has been updated successfully', 'Success');
                return redirect('administrator/general-settings');
            } else {
                Toastr::error('Operation Failed', 'Failed');
                return redirect()->back();
            }
        }
    }

    public function updateSchoolLogo(Request $request)
    {
        //   uest->file('main_school_logo'));
        $request->validate([
            'main_school_logo' => "sometimes|nullable|mimes:jpg,jpeg,png",
            'main_school_favicon' => "sometimes|nullable|mimes:jpg,jpeg,png",
        ]);
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
            $generalSettData = SmGeneralSettings::where('school_id', '=', Auth::user()->school_id)->first();
            if (!empty($generalSettData)) {
                $generalSettData->logo = $main_school_logo;
                $results = $generalSettData->update();
            } else {
                $generalSettData = new SmGeneralSettings;
                $generalSettData->logo = $main_school_logo;
                $generalSettData->school_id = Auth::user()->school_id;
                $results = $generalSettData->save();
            }
        }
        // for upload School favicon
        else if ($request->file('main_school_favicon') != "") {
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
            $generalSettData = SmGeneralSettings::where('school_id', '=', Auth::user()->school_id)->first();
            if (!empty($generalSettData)) {
                $generalSettData->favicon = $main_school_favicon;
                $results = $generalSettData->update();
            } else {
                $generalSettData = new SmGeneralSettings;
                $generalSettData->favicon = $main_school_favicon;
                $generalSettData->school_id = Auth::user()->school_id;
                $results = $generalSettData->save();
            }
        } else {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('No change applied, please try again');
            }
            Toastr::error('No change applied, please try again', 'Failed');
            return redirect()->back();
        }
        if ($results) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse(null, 'Logo has been updated successfully');
            }

            Toastr::success('Logo has been updated successfully', 'Success');
            return redirect()->back();
        } else {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Something went wrong, please try again');
            }

            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function updateLogo(Request $request)
    {
        $request->validate([
            'main_school_logo' => "sometimes|nullable|mimes:jpg,jpeg,png",
            'main_school_favicon' => "sometimes|nullable|mimes:jpg,jpeg,png",
        ]);

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

            $generalSettData = SmGeneralSettings::where('school_id', '=', Auth::user()->school_id)->first();
            if (!empty($generalSettData)) {
                $generalSettData->logo = $main_school_logo;
                $results = $generalSettData->update();
            } else {
                $generalSettData = new SmGeneralSettings;
                $generalSettData->logo = $main_school_logo;
                $generalSettData->school_id = Auth::user()->school_id;
                $results = $generalSettData->save();
            }
        }
        // for upload School favicon
        else if ($request->file('main_school_favicon') != "") {
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
            $generalSettData = SmGeneralSettings::where('school_id', '=', Auth::user()->school_id)->first();
            if (!empty($generalSettData)) {
                $generalSettData->favicon = $main_school_favicon;
                $results = $generalSettData->update();
            } else {
                $generalSettData = new SmGeneralSettings;
                $generalSettData->favicon = $main_school_favicon;
                $generalSettData->school_id = Auth::user()->school_id;
                $results = $generalSettData->save();
            }
        } else {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('No change applied, please try again');
            }
            Toastr::error('No change applied, please try again', 'Failed');
            return redirect()->back();
        }
        if ($results) {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendResponse(null, 'Logo has been updated successfully');
            }

            Toastr::success('Logo has been updated successfully', 'Success');
            return redirect()->back();
        } else {
            if (ApiBaseMethod::checkUrl($request->fullUrl())) {
                return ApiBaseMethod::sendError('Something went wrong, please try again');
            }

            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }



    public function emailSettings()
    {
        $editData = SmEmailSetting::where('email_engine_type','smtp')->where('school_id',Auth::user()->school_id)->first();
        $editDataPhp = SmEmailSetting::where('email_engine_type','php')->where('school_id',Auth::user()->school_id)->first();
        $active_mail_driver = SmGeneralSettings::where('school_id',app('school')->id)->first('email_driver')->email_driver;
        if (!empty($editData)) {
            return view('saas::systemSettings.emailSettingsView', compact('editData','active_mail_driver', 'editDataPhp'));
        } else {
            return view('saas::systemSettings.addEmailSettings');
        }
    }

    public function SchoolEmailSettings()
    {
        $editData = SmEmailSetting::where('school_id', '=', Auth::user()->school_id)->first();

        if (!empty($editData)) {
            return view('saas::systemSettings.school_emailSettingsView', compact('editData'));
        } else {
            return view('saas::systemSettings.School_addEmailSettings');
        }
    }

    public function addEmailSettingsData(Request $request)
    {
        $request->validate([
            'from_name'         => "required",
            'from_email'        => "required",
        ]);
        if (
            $request->mail_username == ''
            || $request->mail_password == ''
            || $request->mail_encryption == ''
            || $request->mail_port == ''
            || $request->mail_host == '' || $request->mail_driver == ''
        ) {
            Toastr::error('All Field in Smtp Details Must Be filled Up', 'Failed');
            return redirect()->back();
        }


        $key1 = 'MAIL_USERNAME';
        $key2 = 'MAIL_PASSWORD';
        $key3 = 'MAIL_ENCRYPTION';
        $key4 = 'MAIL_PORT';
        $key5 = 'MAIL_HOST';
        $key6 = 'MAIL_DRIVER';

        $value1 = $request->mail_username;
        $value2 = $request->mail_password;
        $value3 = $request->mail_encryption;
        $value4 = $request->mail_port;
        $value5 = $request->mail_host;
        $value6 = $request->mail_driver;

        $path                   = base_path() . "/.env";
        $MAIL_USERNAME          = env($key1);
        $MAIL_PASSWORD          = env($key2);
        $MAIL_ENCRYPTION        = env($key3);
        $MAIL_PORT              = env($key4);
        $MAIL_HOST              = env($key5);
        $MAIL_DRIVER              = env($key6);

        if (file_exists($path)) {
            file_put_contents($path, str_replace(
                "$key1=" . $MAIL_USERNAME,
                "$key1=" . $value1,
                file_get_contents($path)
            ));
            file_put_contents($path, str_replace(
                "$key2=" . $MAIL_PASSWORD,
                "$key2=" . $value2,
                file_get_contents($path)
            ));
            file_put_contents($path, str_replace(
                "$key3=" . $MAIL_ENCRYPTION,
                "$key3=" . $value3,
                file_get_contents($path)
            ));
            file_put_contents($path, str_replace(
                "$key4=" . $MAIL_PORT,
                "$key4=" . $value4,
                file_get_contents($path)
            ));
            file_put_contents($path, str_replace(
                "$key5=" . $MAIL_HOST,
                "$key5=" . $value5,
                file_get_contents($path)
            ));
            file_put_contents($path, str_replace(
                "$key6=" . $MAIL_DRIVER,
                "$key6=" . $value6,
                file_get_contents($path)
            ));
        }

        $emailSettData = new SmEmailSetting;
        $emailSettData->from_name         = $request->from_name;
        $emailSettData->from_email        = $request->from_email;

        $emailSettData->mail_driver     = $request->mail_driver;
        $emailSettData->mail_host     = $request->mail_host;
        $emailSettData->mail_port       = $request->mail_port;
        $emailSettData->mail_username         = $request->mail_username;
        $emailSettData->mail_password     = $request->mail_password;
        $emailSettData->mail_encryption     = $request->mail_encryption;
        $emailSettData->school_id =  Auth::user()->school_id;
        $emailSettData->created_by = Auth::user()->id;
        $emailSettData->updated_by = Auth::user()->id;
        $results = $emailSettData->save();


        if ($results) {
            return redirect()->back()->with('message-success', 'Email Settings has been added successfully');
        } else {
            return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
        }
    }



    public function schoolAddEmailSettingsData(Request $request)
    {

        

        $request->validate([
            'from_name'         => "required",
            'from_email'        => "required",
        ]);

        if (
            $request->mail_username == ''
            || $request->mail_password == ''
            || $request->mail_encryption == ''
            || $request->mail_port == ''
            || $request->mail_host == '' || $request->mail_driver == ''
        ) {
            Toastr::error('All Field in Smtp Details Must Be filled Up', 'Failed');
            return redirect()->back();
        }


        $key1 = 'MAIL_USERNAME';
        $key2 = 'MAIL_PASSWORD';
        $key3 = 'MAIL_ENCRYPTION';
        $key4 = 'MAIL_PORT';
        $key5 = 'MAIL_HOST';
        $key6 = 'MAIL_DRIVER';

        $value1 = $request->mail_username;
        $value2 = $request->mail_password;
        $value3 = $request->mail_encryption;
        $value4 = $request->mail_port;
        $value5 = $request->mail_host;
        $value6 = $request->mail_driver;

        $path                   = base_path() . "/.env";
        $MAIL_USERNAME          = env($key1);
        $MAIL_PASSWORD          = env($key2);
        $MAIL_ENCRYPTION        = env($key3);
        $MAIL_PORT              = env($key4);
        $MAIL_HOST              = env($key5);
        $MAIL_DRIVER              = env($key6);

        if (file_exists($path)) {
            file_put_contents($path, str_replace(
                "$key1=" . $MAIL_USERNAME,
                "$key1=" . $value1,
                file_get_contents($path)
            ));
            file_put_contents($path, str_replace(
                "$key2=" . $MAIL_PASSWORD,
                "$key2=" . $value2,
                file_get_contents($path)
            ));
            file_put_contents($path, str_replace(
                "$key3=" . $MAIL_ENCRYPTION,
                "$key3=" . $value3,
                file_get_contents($path)
            ));
            file_put_contents($path, str_replace(
                "$key4=" . $MAIL_PORT,
                "$key4=" . $value4,
                file_get_contents($path)
            ));
            file_put_contents($path, str_replace(
                "$key5=" . $MAIL_HOST,
                "$key5=" . $value5,
                file_get_contents($path)
            ));
            file_put_contents($path, str_replace(
                "$key6=" . $MAIL_DRIVER,
                "$key6=" . $value6,
                file_get_contents($path)
            ));
        }

        $emailSettData = new SmEmailSetting;
        $emailSettData->from_name         = $request->from_name;
        $emailSettData->from_email        = $request->from_email;

        $emailSettData->mail_driver     = $request->mail_driver;
        $emailSettData->mail_host     = $request->mail_host;
        $emailSettData->mail_port       = $request->mail_port;
        $emailSettData->mail_username         = $request->mail_username;
        $emailSettData->mail_password     = $request->mail_password;
        $emailSettData->mail_encryption     = $request->mail_encryption;
        $emailSettData->school_id     = Auth::user()->school_id;
        $emailSettData->created_by = Auth::user()->id;
        $emailSettData->updated_by = Auth::user()->id;
        $results = $emailSettData->save();


        if ($results) {
            return redirect()->back()->with('message-success', 'Email Settings has been added successfully');
        } else {
            return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
        }
    }
    public function updateEmailSettingsData(SmEmailSettingsRequest $request)
    {
        if ($request->engine_type == "smtp") {
    
            $e = SmEmailSetting::where('email_engine_type', 'smtp')
                ->where('school_id', Auth::user()->school_id)
                ->first();

            if (empty($e)) {
                $e = new SmEmailSetting();
                $e->email_engine_type = 'smtp';
                $e->mail_driver = $request->mail_driver;
                $e->school_id = Auth::user()->school_id;
            }
            $e->from_name = $request->from_name;
            $e->from_email = $request->from_email;
            $e->mail_host = $request->mail_host;
            $e->mail_port = $request->mail_port;
            $e->mail_username = $request->mail_username;
            $e->mail_password = $request->mail_password;
            $e->mail_encryption = $request->mail_encryption;
            $e->active_status = $request->active_status;

            $results = $e->save();

            if ($request->active_status == 1) {

                $gs = SmGeneralSettings::where('school_id',Auth::user()->school_id)->first();

                $gs->email_driver = "smtp";
                $gs->save();
                $phpp = SmEmailSetting::where('email_engine_type', 'php')
                    ->where('school_id', Auth::user()->school_id)
                    ->first();
                if ($phpp) {
                    $phpp->active_status = 0;
                    $phpp->save();
                }

            }
        }

        if ($request->engine_type == "php") {

            $php = SmEmailSetting::where('email_engine_type', 'php')->where('school_id', Auth::user()->school_id)->first();

            if (empty($php)) {
                $php = new SmEmailSetting();
                $php->mail_driver = 'php';
                $php->email_engine_type = 'php';
                $php->school_id = Auth::user()->school_id;
            }
            $php->from_name = $request->from_name;
            $php->from_email = $request->from_email;
            $php->active_status = $request->active_status;
            $results = $php->save();

            if ($request->active_status == 1) {
                $gs = SmGeneralSettings::where('school_id',Auth::user()->school_id)->first();
                $gs->email_driver = "php";
                $gs->save();
                $smtp = SmEmailSetting::where('email_engine_type', 'smtp')->where('school_id', Auth::user()->school_id)->first();
                if ($smtp) {
                    $smtp->active_status = 0;
                    $smtp->save();
                }

            }

        }


        if ($results) {
       

            return redirect()->back()->with('message-success', 'Email Settings has been updated successfully');
        } else {
            return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
        }
    }

    public function schoolUpdateEmailSettingsData(Request $request)
    {
        $request->validate([
            'from_name'         => "required",
            'from_email'        => "required",
        ]);


        if (
            $request->mail_username == ''
            || $request->mail_password == ''
            || $request->mail_encryption == ''
            || $request->mail_port == ''
            || $request->mail_host == '' || $request->mail_driver == ''
        ) {
            Toastr::error('All Field in Smtp Details Must Be filled Up', 'Failed');
            return redirect()->back();
        }


        $key1 = 'MAIL_USERNAME';
        $key2 = 'MAIL_PASSWORD';
        $key3 = 'MAIL_ENCRYPTION';
        $key4 = 'MAIL_PORT';
        $key5 = 'MAIL_HOST';
        $key6 = 'MAIL_DRIVER';

        $value1 = $request->mail_username;
        $value2 = $request->mail_password;
        $value3 = $request->mail_encryption;
        $value4 = $request->mail_port;
        $value5 = $request->mail_host;
        $value6 = $request->mail_driver;

        // $path                   = base_path() . "/.env";
        // $MAIL_USERNAME          = env($key1);
        // $MAIL_PASSWORD          = env($key2);
        // $MAIL_ENCRYPTION        = env($key3);
        // $MAIL_PORT              = env($key4);
        // $MAIL_HOST              = env($key5);
        // $MAIL_DRIVER              = env($key6);

        // if (file_exists($path)) {
        //     file_put_contents($path, str_replace(
        //         "$key1=" . $MAIL_USERNAME,
        //         "$key1=" . $value1,
        //         file_get_contents($path)
        //     ));
        //     file_put_contents($path, str_replace(
        //         "$key2=" . $MAIL_PASSWORD,
        //         "$key2=" . $value2,
        //         file_get_contents($path)
        //     ));
        //     file_put_contents($path, str_replace(
        //         "$key3=" . $MAIL_ENCRYPTION,
        //         "$key3=" . $value3,
        //         file_get_contents($path)
        //     ));
        //     file_put_contents($path, str_replace(
        //         "$key4=" . $MAIL_PORT,
        //         "$key4=" . $value4,
        //         file_get_contents($path)
        //     ));
        //     file_put_contents($path, str_replace(
        //         "$key5=" . $MAIL_HOST,
        //         "$key5=" . $value5,
        //         file_get_contents($path)
        //     ));
        //     file_put_contents($path, str_replace(
        //         "$key6=" . $MAIL_DRIVER,
        //         "$key6=" . $value6,
        //         file_get_contents($path)
        //     ));
        // }




        $emailSettData                    = SmEmailSetting::where('school_id', Auth::user()->school_id)->first();
        $emailSettData->from_name         = $request->from_name;
        $emailSettData->from_email        = $request->from_email;

        $emailSettData->mail_driver     = $request->mail_driver;
        $emailSettData->mail_host     = $request->mail_host;
        $emailSettData->mail_port       = $request->mail_port;
        $emailSettData->mail_username         = $request->mail_username;
        $emailSettData->mail_password     = $request->mail_password;
        $emailSettData->mail_encryption     = $request->mail_encryption;
        $emailSettData->updated_by = Auth::user()->id;
        $results                          = $emailSettData->update();


        if ($results) {
            return redirect()->back()->with('message-success', 'Email Settings has been updated successfully');
        } else {
            return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
        }
    }

    public function paymentMethodSettings()
    {
        $statement = "SELECT P.id as PID, D.id as DID, P.active_status as IsActive, P.method, D.* FROM sm_payment_methhods as P, sm_payment_gateway_settings D WHERE P.gateway_id=D.id AND P.school_id =\"Auth::user()->school_id\"";

        $PaymentMethods = DB::select($statement);

        $paymeny_gateway = SmPaymentMethhod::where('school_id', Auth::user()->school_id)->get();
        $paymeny_gateway_settings = SmPaymentGatewaySetting::where('school_id', Auth::user()->school_id)->get();

        return view('saas::systemSettings.paymentMethodSettings', compact('PaymentMethods', 'paymeny_gateway', 'paymeny_gateway_settings'));
    }

    public function updatePaymentGateway(Request $request)
    {

        $paymeny_gateway = [
            'gateway_name', 'gateway_username', 'gateway_password', 'gateway_signature', 'gateway_client_id', 'gateway_mode',
            'gateway_secret_key', 'gateway_secret_word', 'gateway_publisher_key', 'gateway_private_key'
        ];

        $count = 0;

        $gatewayDetails = SmPaymentGatewaySetting::where('gateway_name', $request->gateway_name)->where('school_id', Auth::user()->school_id)->first();

        foreach ($paymeny_gateway as $input_field) {
            if (isset($request->$input_field) && !empty($request->$input_field)) {
                $gatewayDetails->$input_field = $request->$input_field;
            }
        }
        $gatewayDetails->updated_by = Auth::user()->id;
        $results = $gatewayDetails->save();

        // /*********** all ********************** */
        // $WriteENV = SmPaymentGatewaySetting::all();
        // foreach ($WriteENV as $row) {
        //     switch ($row->gateway_name) {
        //         case 'PayPal':




        //             // $key1 = 'PAYPAL_ENV';
        //             // $key2 = 'PAYPAL_API_USERNAME';
        //             // $key3 = 'PAYPAL_API_PASSWORD';
        //             // $key4 = 'PAYPAL_API_SECRET';

        //             // $value1 = $row->gateway_mode;
        //             // $value2 = $row->gateway_username;
        //             // $value3 = $row->gateway_password;
        //             // $value4 = $row->gateway_secret_key;

        //             // $path = base_path() . "/.env";
        //             // $PAYPAL_ENV = env($key1);
        //             // $PAYPAL_API_USERNAME = env($key2);
        //             // $PAYPAL_API_PASSWORD = env($key3);
        //             // $PAYPAL_API_SECRET = env($key4);

        //             // if (file_exists($path)) {
        //             //     file_put_contents($path, str_replace(
        //             //         "$key1=" . $PAYPAL_ENV,
        //             //         "$key1=" . $value1,
        //             //         file_get_contents($path)
        //             //     ));
        //             //     file_put_contents($path, str_replace(
        //             //         "$key2=" . $PAYPAL_API_USERNAME,
        //             //         "$key2=" . $value2,
        //             //         file_get_contents($path)
        //             //     ));
        //             //     file_put_contents($path, str_replace(
        //             //         "$key3=" . $PAYPAL_API_PASSWORD,
        //             //         "$key3=" . $value3,
        //             //         file_get_contents($path)
        //             //     ));
        //             //     file_put_contents($path, str_replace(
        //             //         "$key4=" . $PAYPAL_API_SECRET,
        //             //         "$key4=" . $value4,
        //             //         file_get_contents($path)
        //             //     ));
        //             // }

        //             break;
        //         case 'Stripe':

        //         'gateway_name'          => 'Stripe',
        //         'gateway_username'      => 'demo@strip.com',
        //         'gateway_password'      => '12334589',
        //         'gateway_client_id'     => '',
        //         'gateway_secret_key'    => 'AVZdghanegaOjiL6DPXd0XwjMGEQ2aXc58z1-isWmBFnw1h2j',
        //         'gateway_secret_word'   => 'AVZdghanegaOjiL6DPXd0XwjMGEQ2aXc58z1'

        //             // $key1 = 'STRIPE_KEY';
        //             // $key2 = 'STRIPE_SECRET';

        //             // $value1 = $row->gateway_publisher_key;
        //             // $value2 = $row->gateway_secret_key;

        //             // $path = base_path() . "/.env";
        //             // $PUBLISHABLE_KEY = env($key1);
        //             // $SECRET_KEY = env($key2);

        //             // if (file_exists($path)) {
        //             //     file_put_contents($path, str_replace(
        //             //         "$key1=" . $PUBLISHABLE_KEY,
        //             //         "$key1=" . $value1,
        //             //         file_get_contents($path)
        //             //     ));
        //             //     file_put_contents($path, str_replace(
        //             //         "$key2=" . $SECRET_KEY,
        //             //         "$key2=" . $value2,
        //             //         file_get_contents($path)
        //             //     ));
        //             // }

        //             break;

        //         case 'Paystack':


        //         'gateway_name'          => 'Paystack',
        //         'gateway_username'      => 'demo@gmail.com',
        //         'gateway_password'      => '12334589',
        //         'gateway_client_id'     => '',
        //         'gateway_secret_key'    => 'sk_live_2679322872013c265e161bc8ea11efc1e822bce1',
        //         'gateway_publisher_key' => 'pk_live_e5738ce9aade963387204f1f19bee599176e7a71',



        //             // $key1 = 'PAYSTACK_PUBLIC_KEY';
        //             // $key2 = 'PAYSTACK_SECRET_KEY';
        //             // $key3 = 'PAYSTACK_PAYMENT_URL';
        //             // $key4 = 'MERCHANT_EMAIL';

        //             // $value1 = $row->gateway_publisher_key;
        //             // $value2 = $row->gateway_secret_key;
        //             // $value3 = 'https://api.paystack.co';
        //             // $value4 = $row->gateway_username;

        //             // $path = base_path() . "/.env";
        //             // $PAYSTACK_PUBLIC_KEY = env($key1);
        //             // $PAYSTACK_SECRET_KEY = env($key2);
        //             // $PAYSTACK_PAYMENT_URL = env($key3);
        //             // $MERCHANT_EMAIL = env($key4);

        //             // if (file_exists($path)) {
        //             //     file_put_contents($path, str_replace(
        //             //         "$key1=" . $PAYSTACK_PUBLIC_KEY,
        //             //         "$key1=" . $value1,
        //             //         file_get_contents($path)
        //             //     ));
        //             //     file_put_contents($path, str_replace(
        //             //         "$key2=" . $PAYSTACK_SECRET_KEY,
        //             //         "$key2=" . $value2,
        //             //         file_get_contents($path)
        //             //     ));
        //             //     file_put_contents($path, str_replace(
        //             //         "$key3=" . $PAYSTACK_PAYMENT_URL,
        //             //         "$key3=" . $value3,
        //             //         file_get_contents($path)
        //             //     ));
        //             //     file_put_contents($path, str_replace(
        //             //         "$key4=" . $MERCHANT_EMAIL,
        //             //         "$key4=" . $value4,
        //             //         file_get_contents($path)
        //             //     ));
        //             // }

        //             break;
        //     }
        // }

        /*********** all ********************** */

        if ($results) {
            return redirect()->back()->with('message-success', 'Operation successfully');
        } else {
            return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
        }
    }

    public function isActivePayment(Request $request)
    {

        $request->validate(
            [
                'gateways' => 'required|array',
            ],
            [
                'gateways.required' => 'At least one gateway required!',
            ]
        );

        $update = SmPaymentMethhod::where('active_status', '=', 1)->update(['active_status' => 0]);

        foreach ($request->gateways as $pid => $isChecked) {
            $results = SmPaymentMethhod::where('id', '=', $pid)->update(['active_status' => 1]);
        }

        if ($results) {
            return redirect()->back()->with('message-success', 'Updated Successfully!');
        } else {

            return redirect()->back()->with('message-danger', 'Ops! Operation Failed!');
        }
    }

    public function updatePaypalData()
    {
        $gateway_id = $_POST['gateway_id'];
        $paypal_username = $_POST['paypal_username'];
        $paypal_password = $_POST['paypal_password'];
        $paypal_signature = $_POST['paypal_signature'];
        $paypal_client_id = $_POST['paypal_client_id'];
        $paypal_secret_id = $_POST['paypal_secret_id'];

        if ($gateway_id) {
            $gatewayDetails = SmPaymentGatewaySetting::where('id', $gateway_id)->first();
            if (!empty($gatewayDetails)) {

                $gatewayDetailss = SmPaymentGatewaySetting::find($gatewayDetails->id);
                $gatewayDetailss->paypal_username = $paypal_username;
                $gatewayDetailss->paypal_password = $paypal_password;
                $gatewayDetailss->paypal_signature = $paypal_signature;
                $gatewayDetailss->paypal_client_id = $paypal_client_id;
                $gatewayDetailss->paypal_secret_id = $paypal_secret_id;
                $gatewayDetailss->updated_by = Auth::user()->id;
                $results = $gatewayDetailss->update();
            } else {

                $gatewayDetail = new SmPaymentGatewaySetting();
                $gatewayDetail->paypal_username = $paypal_username;
                $gatewayDetail->paypal_password = $paypal_password;
                $gatewayDetail->paypal_signature = $paypal_signature;
                $gatewayDetail->paypal_client_id = $paypal_client_id;
                $gatewayDetail->paypal_secret_id = $paypal_secret_id;
                $gatewayDetail->school_id =  Auth::user()->school_id;
                $gatewayDetail->created_by = Auth::user()->id;
                $gatewayDetail->updated_by = Auth::user()->id;
                $results = $gatewayDetail->save();
            }
        }

        if ($results) {
            return redirect()->back()->with('message-success', 'Updated Successfully');
        } else {

            return redirect()->back()->with('message-danger', 'Ops! Operation Failed!');
        }
    }

    public function updateStripeData()
    {
        $gateway_id = $_POST['gateway_id'];
        $stripe_api_secret_key = $_POST['stripe_api_secret_key'];
        $stripe_publisher_key = $_POST['stripe_publisher_key'];

        if ($gateway_id) {
            $gatewayDetails = SmPaymentGatewaySetting::where('id', $gateway_id)->first();
            if (!empty($gatewayDetails)) {

                $gatewayDetailss = SmPaymentGatewaySetting::find($gatewayDetails->id);
                $gatewayDetailss->stripe_api_secret_key = $stripe_api_secret_key;
                $gatewayDetailss->stripe_publisher_key = $stripe_publisher_key;
                $gatewayDetailss->updated_by = Auth::user()->id;
                $results = $gatewayDetailss->update();
            } else {

                $gatewayDetail = new SmPaymentGatewaySetting();
                $gatewayDetail->stripe_api_secret_key = $stripe_api_secret_key;
                $gatewayDetail->stripe_publisher_key = $stripe_publisher_key;
                $gatewayDetail->school_id =  Auth::user()->school_id;
                $gatewayDetail->created_by = Auth::user()->id;
                $gatewayDetail->updated_by = Auth::user()->id;
                $results = $gatewayDetail->save();
            }
        }

        if ($results) {
            echo "success";
        }
    }

    public function updatePayumoneyData()
    {
        $gateway_id = $_POST['gateway_id'];
        $pay_u_money_key = $_POST['pay_u_money_key'];
        $pay_u_money_salt = $_POST['pay_u_money_salt'];

        if ($gateway_id) {
            $gatewayDetails = SmPaymentGatewaySetting::where('id', $gateway_id)->first();
            if (!empty($gatewayDetails)) {

                $gatewayDetailss = SmPaymentGatewaySetting::find($gatewayDetails->id);
                $gatewayDetailss->pay_u_money_key = $pay_u_money_key;
                $gatewayDetailss->pay_u_money_salt = $pay_u_money_salt;
                $gatewayDetailss->updated_by = Auth::user()->id;
                $results = $gatewayDetailss->update();
            } else {

                $gatewayDetail = new SmPaymentGatewaySetting();
                $gatewayDetail->pay_u_money_key = $pay_u_money_key;
                $gatewayDetail->pay_u_money_salt = $pay_u_money_salt;
                $gatewayDetail->school_id =  Auth::user()->school_id;
                $gatewayDetail->created_by = Auth::user()->id;
                $gatewayDetail->updated_by = Auth::user()->id;
                $results = $gatewayDetail->save();
            }
        }

        if ($results) {
            echo "success";
        }
    }

    public function activePaymentGateway()
    {
        $gateway_id = $_POST['gateway_id'];

        if ($gateway_id) {
            $gatewayDetailss = SmPaymentGatewaySetting::where('active_status', '=', 1)
                ->update(['active_status' => 0]);
        }

        $results = SmPaymentGatewaySetting::where('gateway_name', '=', $gateway_id)
            ->update(['active_status' => 1]);

        if ($results) {
            echo "success";
        }
    }

    public function languageDelete(Request $request)
    {

        $delete_directory = SmLanguage::find($request->id);

        try {
            if ($delete_directory) {
                if($delete_directory->language_universal != 'en'){
                    File::deleteDirectory(base_path('/resources/lang/' . $delete_directory->language_universal));
                    $modules = Module::all();
                    foreach ($modules as $module) {
                        File::deleteDirectory(module_path($module->getName()) . '/Resources/lang/' . $delete_directory->language_universal);
                    }
                }
                $result = $delete_directory->delete();
                if ($result) {
                    return redirect()->back()->with('message-success-delete', 'Language has been deleted successfully');
                }
            } else {
                return redirect()->back()->with('message-danger-delete', 'Something went wrong, please try again');
            }

        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    public function changeLocale($locale)
    {
        Session::put('locale', $locale);
        return redirect()->back();
    }

    public function changeLanguage($id)
    {
        try {

            if ($id) {
                $this->setDefaultLanguge((int) $id);
            }

            Toastr::success('Operation Success', 'Success');
            return redirect()->back();

        } catch (\Exception $e) {

            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    private function setDefaultLanguge($id){

        SmLanguage::where('active_status', '=', 1)->where('school_id', Auth::user()->school_id)->update(['active_status' => 0]);
        if(is_integer($id)){
            $language = SmLanguage::where('school_id', Auth::user()->school_id)->findOrFail($id);
        } else{
            $language = SmLanguage::where('school_id', Auth::user()->school_id)->where('language_universal', $id)->firstOrFail();
        }

        $language->active_status = 1;
        $language->save();



        $lang = Language::where('code', $language->language_universal)->first();

        $users = User::where('school_id',Auth::user()->school_id)->get();

        foreach($users as $user){
            $user->language = $lang->code;
            if($lang->rtl == 1){
                $user->rtl_ltl = 1;
                $user->save();
            }else{
                $user->rtl_ltl = 2;
                $user->save();
            }
            $user->save();
        }

        if( $lang->rtl == 1 ){
            session()->put('user_text_direction',1);
        }
        else{
            session()->put('user_text_direction',2);
        }

        session()->put('user_language', $lang->code);
        session()->put('locale', $lang->code);
    }

    public function getTranslationTerms(Request $request)
    {

        try {
            $file = explode('::', $request->id);
            $file_name = gv($file, 1);
            $module = gv($file, 0, 'base');
            if ( $module == 'base'){
                $file = resource_path('lang/'.$request->lu.'/'.$file_name.'.php');
                $en_file = resource_path('lang/en/'.$file_name.'.php');
            } else{
                $file = module_path($module) . '/Resources/lang/'.$request->lu.'/'.$file_name.'.php';
                $en_file = module_path($module) . '/Resources/lang/en/'.$file_name.'.php';
            }

            $terms = [];
            $en_terms = [];

            if (File::exists($file)){
                $terms = include  "{$file}";
            }
            if (File::exists($en_file)){
                $en_terms = include  "{$en_file}";
            }
            return response()->json(['terms' => $terms, 'en_terms' => $en_terms]);
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function translationTermUpdate(Request $request)
    {

        $request->validate(
            [
                'module_id' => 'required',
                'language_universal' => 'required',
            ],
            [
                'module_id.required' => 'Please select at least one module',
            ]
        );
        try {

            $LU = $request->LU;
            $file = explode('::', $request->module_id);
            $file_name = gv($file, 1);
            $module = gv($file, 0, 'base');
            $language_universal = $request->language_universal;

            if ( $module == 'base'){
                $file = resource_path('lang/'.$language_universal.'/'.$file_name.'.php');
            } else{
                $file = module_path($module) . '/Resources/lang/'.$language_universal.'/'.$file_name.'.php';
            }

            if (file_exists($file)) {
                file_put_contents($file, '');
            }
            file_put_contents($file, '<?php return ' . var_export($LU, true) . ';');

            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    //Update System is Availalbe

    public function recurse_copy($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    $this->recurse_copy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    //Update System
    public function UpdateSystem()
    {
        // $client = new Client();
        // $r = $client->request('GET', 'https://infixedu.com/api/getSystemVersion');
        // $info         = $r->getBody()->getContents();
        // $versionInfo  = json_decode($info, true);
        // $version_name = $versionInfo['data']['SystemVersion']['version_name'];
        // $title        = $versionInfo['data']['SystemVersion']['title'];
        // $features     = $versionInfo['data']['SystemVersion']['features'];
        // $existing = SmGeneralSettings::find(1);
        // $existing_version = $existing->system_version;
        if (file_exists('version.php')) {
            $data = file_get_contents('version.php');
        } else {
            $data = 'You are using old version, please upgrade.';
        }
        return view('saas::systemSettings.updateSettings', compact('data'));
    }

    public function admin_UpdateSystem(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'file' => "sometimes|nullable|mimes:pdf,doc,docx,jpg,jpeg,png,txt",

        ]);


        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


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
            $file->move('public/', $fileName);
            $fileName = 'public/' . $fileName;
        }

        if (!file_exists('upgradeFiles')) {
            mkdir('upgradeFiles', 0777, true);
        }

        $zip = new ZipArchive;
        $res = $zip->open($fileName);
        if ($res === TRUE) {
            $zip->extractTo('upgradeFiles/');
            $zip->close();
        } else {
            return 'ops! sorry.';
        }

        $data = SmGeneralSettings::find(1);
        $data->system_version = $request->version_name;
        $data->save();

        return redirect()->back()->with('message-success', 'Upgrade Successfully');
    }

    public function UpgradeSettings(Request $request)
    {
        return redirect()->back()->with('message-success', 'Upgrade Successfully');
    }

    public function ajaxSelectCurrency(Request $request)
    {

        $select_currency_symbol = SmCurrency::select('symbol')->where('code', '=', $request->id)->first();

        $currency_symbol['symbol'] = $select_currency_symbol->symbol;

        return response()->json([$currency_symbol]);
    }

    //ajax theme Style Active
    public function themeStyleActive(Request $request)
    {
        if ($request->id) {
            $modified = SmStyle::where('school_id', '=', Auth::user()->school_id)
            ->where('is_active', 1)
            ->update(array('is_active' => 0));
            $selected = SmStyle::findOrFail($request->id);
            $selected->is_active = 1;
            $selected->updated_by = Auth::user()->id;
            $selected->save();
            return response()->json([$modified]);
        } else {
            return '';
        }
    }


    /* ******************************** homePageBackend ******************************** */
    public function homePageBackend()
    {
        return 'yes';
        $links = SmHomePageSetting::find(1);
        $permisions = SmFrontendPersmission::where('parent_id', 1)->get();
        return view('saas::systemSettings.homePageBackend', compact('links', 'permisions'));
    }

    public function homePageUpdate(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'long_title' => 'required',
            'short_description' => 'required',
            'permisions' => 'required|array',
            'image' => "sometimes|nullable|mimes:jpg,jpeg,png",
        ]);

        $permisionsArray = $request->permisions;
        $permisions = SmFrontendPersmission::where('parent_id', 1)->update(['is_published' => 0]);
        foreach ($permisionsArray as $value) {
            $permisions = SmFrontendPersmission::where('id', $value)->update(['is_published' => 1]);
        }

        $image = "";
        if ($request->file('image') != "") {
            $maxFileSize = SmGeneralSettings::first('file_size')->file_size;
            $file = $request->file('image');
            $fileSize =  filesize($file);
            $fileSizeKb = ($fileSize / 1000000);
            if($fileSizeKb >= $maxFileSize){
                Toastr::error( 'Max upload file size '. $maxFileSize .' Mb is set in system', 'Failed');
                return redirect()->back();
            }
            $file = $request->file('image');
            $image_name = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $path = 'public/uploads/homepage';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $file->move($path . '/', $image_name);
            $image = $path . '/' . $image_name;
        }

        //Update Home Page
        $update = SmHomePageSetting::where('school_id', app('school')->id)->first();
        $update->title = $request->title;
        $update->long_title = $request->long_title;
        $update->short_description = $request->short_description;
        $update->link_label = $request->link_label;
        $update->link_url = $request->link_url;
        $update->school_id = app('school')->id;
        if ($request->file('image') != "") {
            $update->image = $image;
        }
        $result = $update->save();

        if ($result) {
            return redirect()->back()->with('message-success', 'Operation successful');
        } else {
            return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
        }
    }
    /* ******************************** homePageBackend ******************************** */

    /* ******************************** customLinks ******************************** */

    public function customLinks()
    {
        $links = SmCustomLink::find(1);
        return view('saas::systemSettings.customLinks', compact('links'));
    }

    public function customLinksUpdate(Request $request)
    {

        $links = SmCustomLink::find(1);
        $lists = ['title1', 'link_label1', 'link_href1', 'link_label2', 'link_href2', 'link_label3', 'link_href3', 'link_label4', 'title2', 'link_href4', 'link_label5', 'link_href5', 'link_label6', 'link_href6', 'link_label7', 'link_href7', 'link_label8', 'link_href8', 'title3', 'link_label9', 'link_href9', 'link_label10', 'link_href10', 'link_label11', 'link_href11', 'link_label12', 'link_href12', 'title4', 'link_label13', 'link_href13', 'link_label14', 'link_href14', 'link_label15', 'link_href15', 'link_label16', 'link_href16', 'facebook_url', 'twitter_url', 'dribble_url', 'behance_url'];

        foreach ($lists as $list) {
            if (isset($request->$list)) {
                $links->$list = $request->$list;
            }
            $result = $links->save();
        }

        if ($result) {
            return redirect()->back()->with('message-success', 'Operation successful');
        } else {
            return redirect()->back()->with('message-danger', 'Something went wrong, please try again');
        }
    }

    /* ******************************** customLinks ******************************** */

    public function getSystemVersion(Request $request)
    {
        $version = SmSystemVersion::find(1);
        if (ApiBaseMethod::checkUrl($request->fullUrl())) {
            $data['SystemVersion'] = $version;
            return ApiBaseMethod::sendResponse($data, null);
        }
    }

    public function getSystemUpdate(Request $request, $version_upgrade_id = null)
    {
        $data = [];
        if (Schema::hasTable('sm_update_files')) {
            $version = DB::table('sm_update_files')->where('version_name', $version_upgrade_id)->first();
            if (!empty($version->path)) {
                $url = url('/') . '/' . $version->path;
                header("Location: " . $url);
                die();
            } else {
                return redirect()->back();
            }
        }
        return redirect()->back();
    }

    public function apiPermission()
    {

        if (!Schema::hasColumn('sm_general_settings', 'api_url')) {
            Schema::table('sm_general_settings', function ($table) {
                $table->integer('api_url')->default(0);
            });
        }
        $settings = SmGeneralSettings::find(1);

        return view('saas::systemSettings.apiPermission', compact('settings'));
    }


    public function apiPermissionUpdate(Request $request)
    {
        if ($request->status == 'on') {
            $status = 1;
        } else {
            $status = 0;
        }


        $user = SmGeneralSettings::find(1);
        $user->api_url = $status;
        $user->save();

        return response()->json($user);
    }


    /* *************************************************** SASS ********************************************* */
    public function institution_register()
    {
        $additional_services = DB::table('additional_services')->where('active_status', 1)->get();
        $packages = SmSaasPackages::where('active_status', 1)->get();

        $data = SmSchool::orderBy('school_name', 'asc')->get();
        $login_background = SmBackgroundSetting::where([['is_default', 1], ['title', 'Login Background']])->first();

        if (empty($login_background)) {
            $css = "background: url(" . url('public/backEnd/img/login-bg.jpg') . ")  no-repeat center; background-size: cover; ";
        } else {
            if (!empty($login_background->image)) {
            $css = "background: url('" . url($login_background->image) . "')  no-repeat center;  background-size: cover;";
        } else {
            $css = "background:" . $login_background->color;
        }
    }


        return view('saas::systemSettings.institution_register', compact('data', 'packages', 'additional_services','css'));
    }
    public function institution_register_two()
    {
        $additional_services = DB::table('additional_services')->where('active_status', 1)->get();
        $packages = SmSaasPackages::where('active_status', 1)->get();

        $data = SmSchool::orderBy('school_name', 'asc')->get();
        return view('saas::systemSettings.institution_create', compact('data', 'packages', 'additional_services'));
    }

    public function institution_register_new(){
        $packages = SmSaasPackages::where('active_status', 1)->get();
        return view('saas::systemSettings.new_registration',compact('packages'));
    }

    public function institutionCreate()
    {
        return view('saas::systemSettings.institution_create');
    }

    public function institutionEdit($id){
        $school = SmSchool::find($id);
        return view('saas::systemSettings.institution_create', compact('school'));
    }

    // institution 


    // institution

    public function institutionEnable(Request $request){

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


    // institution approve


    public function institutionApprove(Request $request){

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




    // modified by rashed 19 th august
    public function institutionStore(Request $request)
    {
        $request->validate([
            'school_name' => 'required|max:255|',
            'email' => 'required|string|email|max:255|',
            'password' => 'min:6|required_with:cpassword|same:confirm_password',
            'confirm_password' => 'min:6',
        ]);

        DB::beginTransaction();
        try {
            $s = new SmSchool();
            $s->school_name = $request->school_name;
            $s->email = $request->email;
            $s->address = $request->address;
            $s->school_code = $request->school_code;
            $s->phone = $request->phone;
            $s->starting_date = !empty($request->opening_date)? date('Y-m-d', strtotime($request->opening_date)):""; 
            $s->save();
            $school_id = $s->id;


            $general_setting = SmGeneralSettings::where('school_id', $school_id)->first();
            if(empty($general_setting)){
                $general_setting = new SmGeneralSettings();
            }
            // $general_setting = new SmGeneralSettings();
            $general_setting->school_name = $request->school_name;
            $general_setting->email = $request->email;
            $general_setting->address = $request->address;
            $general_setting->school_code = $request->school_code;
            $general_setting->school_id = $school_id;
            $general_setting->phone = $request->phone;
            $general_setting->time_zone_id = 1;
            $general_setting->save();



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

                try {
                    $staff_number = SmStaff::count();
                    DB::table('sm_staffs')->insert([
                        [
                            'user_id' => $last_inserted_id,
                            'school_id' => $school_id,
                            'role_id' => 5,
                            'staff_no' => $staff_number + 1,
                            'designation_id' => 1,
                            'department_id' => 1,
                            'first_name' => 'System',
                            'last_name' => 'Admin',
                            'full_name' => 'System Admin',
                            'gender_id' => 1,
                            'email' => $request->email,
                            'staff_photo' => 'public/uploads/staff/staff.jpg',
                        ]
                    ]);


                    for ($j = 1; $j <= 8; ++$j) {

                        if ($j != 2 && $j != 3) {

                            for ($i = 1; $i <= 21; ++$i) {

                                $assign = new SmModulePermissionAssign();
                                $assign->module_id = $i;
                                $assign->role_id = $j;
                                $assign->created_by = 1;
                                $assign->updated_by = 1;
                                $assign->school_id = $school_id;
                                $assign->save();
                            }
                        }
                    }

                    for ($i = 22; $i <= 35; ++$i) {

                        $assign = new SmModulePermissionAssign();
                        $assign->module_id = $i;
                        $assign->role_id = 2;
                        $assign->created_by = 1;
                        $assign->updated_by = 1;
                        $assign->school_id = $school_id;
                        $assign->save();
                    }

                    for ($i = 36; $i <= 46; ++$i) {

                        $assign = new SmModulePermissionAssign();
                        $assign->module_id = $i;
                        $assign->role_id = 3;
                        $assign->created_by = 1;
                        $assign->updated_by = 1;
                        $assign->school_id = $school_id;
                        $assign->save();
                    }

                    for ($i = 2; $i <= 21; ++$i) {

                        $assign = new SaasSchoolModulePermissionAssign();
                        $assign->module_id = $i;
                        $assign->created_by = 1;
                        $assign->updated_by = 1;
                        $assign->school_id = $school_id;
                        $assign->save();
                    }

                    $module_links = SmModuleLink::where('active_status', 1)->get();

                    foreach($module_links as $module_link){
                        $role_permission = new SmRolePermission();
                        $role_permission->role_id = 5;
                        $role_permission->module_link_id = $module_link->id;
                        // $role_permission->module_id = $module_link->module_id;
                        $role_permission->school_id = $school_id;
                        $role_permission->save();
                    }


                    $payment_methods = ['Cash', 'Cheque', 'Bank', 'Paypal', 'Stripe', 'Paystack'];

                    foreach($payment_methods as $payment_method){
                        $method = new SmPaymentMethhod();
                        $method->method = $payment_method;
                        $method->type = 'System';
                        $method->school_id = $school_id;
                        $method->save();
                    }

                    DB::table('sm_payment_gateway_settings')->insert([
                        [
                            'gateway_name'          => 'PayPal',
                            'gateway_username'      => 'demo@paypal.com',
                            'gateway_password'      => '12334589',
                            'gateway_client_id'     => 'AVZdghanegaOjiL6DPXd0XwjMGEQ2aXc58z1-qNwv_Wz9mI_6MKSW5dS9uPAha3rd7eB82ToOCQLp31c',
                            'gateway_secret_key'    => 'EMgxBzeJ9By7D0xvkSUblDd_GW99WvK0DDNyvkGn7rBikvjPw46xz9Plozp4jl7AOsx-isWmBFnw1h2j',
                            'school_id'    => $school_id
                        ]
                    ]);

                    DB::table('sm_payment_gateway_settings')->insert([
                        [
                            'gateway_name'          => 'Stripe',
                            'gateway_username'      => 'demo@strip.com',
                            'gateway_password'      => '12334589',
                            'gateway_client_id'     => '',
                            'gateway_secret_key'    => 'AVZdghanegaOjiL6DPXd0XwjMGEQ2aXc58z1-isWmBFnw1h2j',
                            'gateway_secret_word'   => 'AVZdghanegaOjiL6DPXd0XwjMGEQ2aXc58z1',
                            'school_id'    => $school_id
                        ]
                    ]);


                    DB::table('sm_payment_gateway_settings')->insert([
                        [
                            'gateway_name'          => 'Paystack',
                            'gateway_username'      => 'demo@gmail.com',
                            'gateway_password'      => '12334589',
                            'gateway_client_id'     => '',
                            'gateway_secret_key'    => 'sk_live_2679322872013c265e161bc8ea11efc1e822bce1',
                            'gateway_publisher_key' => 'pk_live_e5738ce9aade963387204f1f19bee599176e7a71',
                            'school_id'    => $school_id
                        ],

                    ]);

                    DB::table('sm_sms_gateways')->insert([
                        [
                            'gateway_name' => 'Clickatell',
                            'clickatell_username'=>'demo1',
                            'clickatell_password'=>'122334',
                            'school_id'    => $school_id
                        ],
                        [
                            'gateway_name' => 'Twilio',
                            'clickatell_username'=>'demo2',
                            'clickatell_password'=>'12336',
                            'school_id'    => $school_id
                        ],
                        [
                            'gateway_name' => 'Msg91',
                            'clickatell_username'=>'demo3',
                            'clickatell_password'=>'23445',
                            'school_id'    => $school_id
                        ]
                    ]);

                    DB::table('sm_background_settings')->insert([
                        [
                            'title'         => 'Dashboard Background',
                            'type'          => 'image',
                            'image'         => 'public/backEnd/img/body-bg.jpg',
                            'color'         => '',
                            'school_id'         => $school_id,
                            'is_default'    => 1,

                        ]

                    ]);

                    $s = new SmStyle();
                    $s->style_name = 'Default';
                    $s->path_main_style = 'style.css';
                    $s->path_infix_style = 'infix.css';
                    $s->primary_color = '#415094';
                    $s->primary_color2 = '#7c32ff';
                    $s->title_color = '#222222';
                    $s->text_color = '#828bb2';
                    $s->white = '#ffffff';
                    $s->black = '#000000';
                    $s->sidebar_bg = '#e7ecff';
                    $s->barchart1 = '#8a33f8';
                    $s->barchart2 = '#f25278';
                    $s->barcharttextcolor = '#415094';
                    $s->barcharttextfamily = '"poppins", sans-serif';
                    $s->areachartlinecolor1 = 'rgba(124, 50, 255, 0.5)';
                    $s->areachartlinecolor2 = 'rgba(242, 82, 120, 0.5)';
                    $s->areachartlinecolor2 = 'rgba(242, 82, 120, 0.5)';
                    $s->school_id = $school_id;
                    $s->is_active = 1;
                    $s->is_active = 1;
                    $s->save();


                    $s = new  SmStyle();
                    $s->style_name = 'Lawn Green';
                    $s->path_main_style = 'lawngreen_version/style.css';
                    $s->path_infix_style = 'lawngreen_version/infix.css';
                    $s->primary_color = '#415094';
                    $s->primary_color2 = '#03e396';
                    $s->title_color = '#222222';
                    $s->text_color = '#828bb2';
                    $s->white = '#ffffff';
                    $s->black = '#000000';
                    $s->sidebar_bg = '#e7ecff';

                    $s->barchart1 = '#415094';
                    $s->barchart2 = '#03e396';

                    $s->barcharttextcolor = '#03e396';
                    $s->barcharttextfamily = '"Cerebri Sans", Helvetica, Arial, sans-serif';

                    $s->areachartlinecolor1 = '#415094';
                    $s->areachartlinecolor2 = '#03e396';
                    $s->dashboardbackground = '#e7ecff';
                    $s->school_id = $school_id;
                    $s->save();


                    $data['email'] = $request->email;
                    $data['password'] = $request->password;



                    // Mail::send('saas::systemSettings.institution_login_access', compact('data'), function ($message) use ($request) {
                    //     $settings = SmEmailSetting::find(1);
                    //     $email = $settings->from_email;
                    //     $Schoolname = $settings->from_name;
                    //     $message->to($request->email, $Schoolname)->subject('School login access');
                    //     $message->from($email, $Schoolname);
                    // });



                    DB::commit();


                    //Mail::to($user->email)->send(new VerifyMail($user));
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

        if (empty($login_background)) {
            $css = "background: url(" . url('public/backEnd/img/login-bg.jpg') . ")  no-repeat center; background-size: cover; ";
        } else {
            if (!empty(@$login_background->image)) {
            $css = "background: url('" . url(@$login_background->image) . "')  no-repeat center;  background-size: cover;";
        } else {
            $css = "background:" . @$login_background->color;
        }
    }

        return view('saas::systemSettings.institution_register', compact('data'));

    }

    // modified by rashed 19 th august
    public function institutionNewStore(Request $request)
    {
        $request->validate([
            'school_name' => 'required||max:255',
            'email' => 'required|string|email|max:255|',
            'password' => 'min:6|required_with:cpassword|same:cpassword',
            'cpassword' => 'min:6',
        ]);

        DB::beginTransaction();
        try {
            $s = new SmSchool();
            $s->school_name = $request->school_name;
            $s->email = $request->email;
            $s->address = $request->address;
            $s->school_code = $request->school_code;
            $s->phone = $request->phone;
            $s->starting_date = date('Y-m-d');

            $is_approve = SmGeneralSettings::find(1);

            //check school auto approve option available or not ?
            if($is_approve->school_approve == 1){
                $s->active_status = 1;
            }else{
                $s->active_status = 0;
            } 
            $s->save();




            // create SmGeneralSettings records modified by rashed
            $school_id = $s->id;
            $general_setting = SmGeneralSettings::where('school_id', $school_id)->first();
            if(empty($general_setting)){
                $general_setting = new SmGeneralSettings();
            }
            $general_setting->school_name = $request->school_name;
            $general_setting->email = $request->email;
            $general_setting->address = $request->address;
            $general_setting->school_code = $request->school_code;
            $general_setting->school_id = $school_id;
            $general_setting->phone = $request->phone;
            $general_setting->time_zone_id = 1;
            $general_setting->save();



            try {
                $user = new User();
                $user->role_id = 1;
                $user->school_id = $school_id;
                $user->full_name = 'Admin';
                $user->email = $request->email;
                $user->username = $request->email;
                $user->access_status = 1;
                $user->verified = 0;
                $user->is_registered = 1;
                if($is_approve->school_approve == 1){
                    $user->active_status = 1;
                }else{
                    $user->active_status = 0;
                } 
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
                            'email' => $request->email,
                            'staff_photo' => 'public/uploads/staff/staff.jpg',
                        ]
                    ]);


                    for ($j = 1; $j <= 8; ++$j) {
                        if ($j != 2 && $j != 3) {
                            for ($i = 1; $i <= 21; ++$i) {
                                $assign = new SmModulePermissionAssign();
                                $assign->module_id = $i;
                                $assign->role_id = $j;
                                $assign->created_by = 1;
                                $assign->updated_by = 1;
                                $assign->school_id = $school_id;
                                $assign->save();
                            }
                        }
                    }

                    for ($i = 22; $i <= 35; ++$i) {
                        $assign = new SmModulePermissionAssign();
                        $assign->module_id = $i;
                        $assign->role_id = 2;
                        $assign->created_by = 1;
                        $assign->updated_by = 1;
                        $assign->school_id = $school_id;
                        $assign->save();
                    }

                    for ($i = 36; $i <= 46; ++$i) {
                        $assign = new SmModulePermissionAssign();
                        $assign->module_id = $i;
                        $assign->role_id = 3;
                        $assign->created_by = 1;
                        $assign->updated_by = 1;
                        $assign->school_id = $school_id;
                        $assign->save();
                    }

                    for ($i = 2; $i <= 21; ++$i) { 
                        $assign = new SaasSchoolModulePermissionAssign();
                        $assign->module_id = $i;
                        $assign->created_by = 1;
                        $assign->updated_by = 1;
                        $assign->school_id = $school_id;
                        $assign->save();
                    }

                    $module_links = SmModuleLink::where('active_status', 1)->get();

                    foreach($module_links as $module_link){
                        $role_permission = new SmRolePermission();
                        $role_permission->role_id = 5;
                        $role_permission->module_link_id = $module_link->id; 
                        $role_permission->school_id = $school_id;
                        $role_permission->save();
                    }


                    $payment_methods = ['Cash', 'Cheque', 'Bank', 'Paypal', 'Stripe', 'Paystack'];
                    foreach($payment_methods as $payment_method){
                        $method = new SmPaymentMethhod();
                        $method->method = $payment_method;
                        $method->type = 'System';
                        $method->school_id = $school_id;
                        $method->save();
                    }

                    DB::table('sm_payment_gateway_settings')->insert([
                        [
                            'gateway_name'          => 'PayPal',
                            'gateway_username'      => 'demo@paypal.com',
                            'gateway_password'      => '12334589',
                            'gateway_client_id'     => 'AVZdghanegaOjiL6DPXd0XwjMGEQ2aXc58z1-qNwv_Wz9mI_6MKSW5dS9uPAha3rd7eB82ToOCQLp31c',
                            'gateway_secret_key'    => 'EMgxBzeJ9By7D0xvkSUblDd_GW99WvK0DDNyvkGn7rBikvjPw46xz9Plozp4jl7AOsx-isWmBFnw1h2j',
                            'school_id'    => $school_id
                        ]
                    ]);

                    DB::table('sm_payment_gateway_settings')->insert([
                        [
                            'gateway_name'          => 'Stripe',
                            'gateway_username'      => 'demo@strip.com',
                            'gateway_password'      => '12334589',
                            'gateway_client_id'     => '',
                            'gateway_secret_key'    => 'AVZdghanegaOjiL6DPXd0XwjMGEQ2aXc58z1-isWmBFnw1h2j',
                            'gateway_secret_word'   => 'AVZdghanegaOjiL6DPXd0XwjMGEQ2aXc58z1',
                            'school_id'    => $school_id
                        ]
                    ]);


                    DB::table('sm_payment_gateway_settings')->insert([
                        [
                            'gateway_name'          => 'Paystack',
                            'gateway_username'      => 'demo@gmail.com',
                            'gateway_password'      => '12334589',
                            'gateway_client_id'     => '',
                            'gateway_secret_key'    => 'sk_live_2679322872013c265e161bc8ea11efc1e822bce1',
                            'gateway_publisher_key' => 'pk_live_e5738ce9aade963387204f1f19bee599176e7a71',
                            'school_id'    => $school_id
                        ],

                    ]);

                    DB::table('sm_sms_gateways')->insert([
                        [
                            'gateway_name' => 'Clickatell',
                            'clickatell_username'=>'demo1',
                            'clickatell_password'=>'122334',
                            'school_id'    => $school_id
                        ],
                        [
                            'gateway_name' => 'Twilio',
                            'clickatell_username'=>'demo2',
                            'clickatell_password'=>'12336',
                            'school_id'    => $school_id
                        ],
                        [
                            'gateway_name' => 'Msg91',
                            'clickatell_username'=>'demo3',
                            'clickatell_password'=>'23445',
                            'school_id'    => $school_id
                        ]
                    ]);

                    DB::table('sm_background_settings')->insert([
                        [
                            'title'         => 'Dashboard Background',
                            'type'          => 'image',
                            'image'         => 'public/backEnd/img/body-bg.jpg',
                            'color'         => '',
                            'school_id'         => $school_id,
                            'is_default'    => 1,

                        ]

                    ]);

                    $s = new SmStyle();
                    $s->style_name = 'Default';
                    $s->path_main_style = 'style.css';
                    $s->path_infix_style = 'infix.css';
                    $s->primary_color = '#415094';
                    $s->primary_color2 = '#7c32ff';
                    $s->title_color = '#222222';
                    $s->text_color = '#828bb2';
                    $s->white = '#ffffff';
                    $s->black = '#000000';
                    $s->sidebar_bg = '#e7ecff';
                    $s->barchart1 = '#8a33f8';
                    $s->barchart2 = '#f25278';
                    $s->barcharttextcolor = '#415094';
                    $s->barcharttextfamily = '"poppins", sans-serif';
                    $s->areachartlinecolor1 = 'rgba(124, 50, 255, 0.5)';
                    $s->areachartlinecolor2 = 'rgba(242, 82, 120, 0.5)';
                    $s->areachartlinecolor2 = 'rgba(242, 82, 120, 0.5)';
                    $s->school_id = $school_id;
                    $s->is_default = 1;
                    $s->is_active = 1;
                    $s->save();


                    $s = new  SmStyle();
                    $s->style_name = 'Lawn Green';
                    $s->path_main_style = 'lawngreen_version/style.css';
                    $s->path_infix_style = 'lawngreen_version/infix.css';
                    $s->primary_color = '#415094';
                    $s->primary_color2 = '#03e396';
                    $s->title_color = '#222222';
                    $s->text_color = '#828bb2';
                    $s->white = '#ffffff';
                    $s->black = '#000000';
                    $s->sidebar_bg = '#e7ecff';

                    $s->barchart1 = '#415094';
                    $s->barchart2 = '#03e396';

                    $s->barcharttextcolor = '#03e396';
                    $s->barcharttextfamily = '"Cerebri Sans", Helvetica, Arial, sans-serif';

                    $s->areachartlinecolor1 = '#415094';
                    $s->areachartlinecolor2 = '#03e396';
                    $s->dashboardbackground = '#e7ecff';
                    $s->school_id = $school_id;
                    $s->save();


                    $data['email'] = $request->email;
                    $data['password'] = $request->password;


                    DB::commit();
                    $verifyUser = VerifyUser::create([
                        'user_id' => $user->id,
                        'token' => Str::random(40)

                    ]);
 
                        try{
                            Mail::to($user->email)->send(new VerifyMail($user));
                        } catch (\Exception $e) { 
                            Log::info($e->getMessage());
                        }
                    DB::commit();

                    Toastr::success('We sent you an activation code. Check your email and click on the link to verify. This link will valid for next 3 hours', 'Success');
                    return redirect('login');



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
        if (empty($login_background)) {
            $css = "background: url(" . url('public/backEnd/img/login-bg.jpg') . ")  no-repeat center; background-size: cover; ";
        } else {
            if (!empty(@$login_background->image)) {
            $css = "background: url('" . url(@$login_background->image) . "')  no-repeat center;  background-size: cover;";
        } else {
            $css = "background:" . @$login_background->color;
        }
    }
        return view('saas::systemSettings.institution_register', compact('data','css'));
    }




    public function institutionUpdate(Request $request){
        $request->validate([
            'school_name' => 'required|max:255|',
            'email' => 'required|string|email|max:255|'
        ]);

        DB::beginTransaction();
        try {
            $s = SmSchool::find($request->id);
            $s->school_name = $request->school_name;
            $s->email = $request->email;
            $s->address = $request->address;
            $s->school_code = $request->school_code;
            $s->starting_date = !empty($request->opening_date) ? date('Y-m-d', strtotime($request->opening_date)) : "";
            $s->save();
            $school_id = $s->id;


            $general_setting = SmGeneralSettings::where('school_id', $school_id)->first();
            $general_setting->school_name = $request->school_name;
            $general_setting->email = $request->email;
            $general_setting->address = $request->address;
            $general_setting->school_code = $request->school_code;
            $general_setting->school_id = $school_id;
            $general_setting->save();



            try {
                $user = User::where('school_id', $school_id)->where('role_id',1)->first();
                $user->email = $request->email;
                $user->username = $request->email;
                $user->save();
                $last_inserted_id = $user->id;

                
            } catch (\Exception $e) {
                DB::rollback();
                return redirect()->back()->with('message-danger', 'Cannot add Admin login credentials');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('message-danger', 'Ops Sorry! Something went wrong, please try again');
        }


        return view('saas::systemSettings.institution_register', compact('data'));
    }


    // institution



    //ajax theme Style Active
    public function themeStyleRTL(Request $request)
    {
        if ($request->id) {
            $selected = SmGeneralSettings::where('school_id', Auth::user()->school_id)->first();
            $selected->ttl_rtl = $request->id;
            $selected->save();
            return response()->json([$selected]);
        } else {
            return '';
        }
    }


    public function institution_register_store(Request $request)
    {
        $request->validate([
            'school_name' => 'required||max:255',
            'email' => 'required|string|email|max:255|',
            'password' => 'min:6|required_with:cpassword|same:cpassword',
            'cpassword' => 'min:6',
            'package' => 'required',
            'plans' => 'required' 

        ]);


        DB::beginTransaction();
        try {
            $s = new SmSchool();
            $s->school_name = $request->school_name;
            $s->email = $request->email;
            $s->package_id = $request->package;
            $s->plan_type = $request->plans;
            // return $s;
            $s->save();
            $school_id = $s->id;
            try {
                $user = new User();
                $user->role_id = 1;
                $user->school_id = $school_id;
                $user->full_name = 'Admin';
                $user->email = $request->email;
                $user->username = $request->email;
                $user->access_status = 0;
                $user->verified = 0;
                $user->active_status = 0;
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
                            'email' => $request->email,
                            'staff_photo' => 'public/uploads/staff/staff.jpg',
                        ]
                    ]);

                    DB::commit();
                    $verifyUser = VerifyUser::create([
                        'user_id' => $user->id,
                        'token' => Str::random(40)

                    ]);
                    try {
                        $generalSetting = SmGeneralSettings::where('school_id', $school_id)->first();
                        if(empty($generalSetting)){
                            $generalSetting = new SmGeneralSettings;
                        }
                        $generalSetting->school_name = $request->school_name;
                        $generalSetting->email = $request->email;
                        $generalSetting->school_id = $school_id;
                        $generalSetting->save();


                    } catch (\Exception $e) {
                        DB::rollback();
                        return redirect()->back()->with('message-danger', 'Cannot Create New School Settings');
                    }



                    // $biller_information= new Billing_Information;
                    // $biller_information->user_id = $user->id;
                    // $biller_information->first_name = $request->first_name;
                    // $biller_information->last_name = $request->last_name;
                    // $biller_information->full_name = $request->first_name.' '.$request->last_name;
                    // $biller_information->company = $request->company;
                    // $biller_information->email = $request->billing_email;
                    // $biller_information->address = $request->address;
                    // $biller_information->country = $request->country;
                    // $biller_information->city = $request->city;
                    // $biller_information->state = $request->state;
                    // $biller_information->zip = $request->zip;
                    // $biller_information->payment_status = 0;
                    // $biller_information->save();

                    Mail::to($user->email)->send(new VerifyMail($user));

                    Toastr::success('We sent you an activation code. Check your email and click on the link to verify. This link will valid for next 3 hours', 'Success');
                    return redirect('login');

                } catch (\Exception $e) {
                    DB::rollback();
                    Log::info($e->getMessage()); 
                    return redirect()->back()->with('message-danger', 'Cannot add Additional admin info for Admin registration');
                }
            } catch (\Exception $e) {
                DB::rollback();
                Log::info($e->getMessage()); 
                return redirect()->back()->with('message-danger', 'Cannot add Admin login credentials');
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::info($e->getMessage()); 
            return redirect()->back()->with('message-danger', 'Ops Sorry! Something went wrong, please try again');
        }


        return view('saas::systemSettings.institution_register', compact('data'));
    }

    public function institution_List()
    {
        $data = SmSchool::where('id', '!=', 1)->get();
        return view('saas::systemSettings.insitutionList', compact('data'));
    }

    public function InstitutionDetails($id)
    {
        $school = SmSchool::find($id);
        $totalStudents = SmStudent::where('active_status', 1)->where('school_id', $id)->get();
        $totalTeachers = SmStaff::where('active_status', 1)
        ->where(function($q)  {
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
        $chart_data = "";

        for($i = 1; $i <= date('d'); $i++){

            $i = $i < 10? '0'.$i:$i;
            $income = SmAddIncome::monthlyIncome($i, $school->id);
            $expense = SmAddIncome::monthlyExpense($i, $school->id);


            $chart_data .= "{ day: '" . $i . "', income: " . $income . ", expense:" . $expense . " },";
        }

        $chart_data_yearly = "";

        for($i = 1; $i <= date('m'); $i++){

            $i = $i < 10? '0'.$i:$i;

            $yearlyIncome = SmAddIncome::yearlyIncome($i, $school->id);

            $yearlyExpense = SmAddIncome::yearlyExpense($i, $school->id);

            $chart_data_yearly .= "{ y: '" . $i . "', income: " . $yearlyIncome . ", expense:" . $yearlyExpense . " },";
        }

        return view('saas::systemSettings.institution_details', compact('totalStudents', 'totalTeachers', 'totalParents', 'totalStaffs', 'school', 'm_total_income', 'm_total_expense', 'y_total_income', 'y_total_expense'));
    }
    public function verifyUser($token)
    {
        $verifyUser = VerifyUser::where('token', $token)->first();
        if (isset($verifyUser)) {
            $user = $verifyUser->user;
            if ($user->verified == 0) {
                $verifyUser->user->verified = 1;
                $verifyUser->user->access_status = 1;
                $verifyUser->user->active_status = 1;
                $verifyUser->user->save();
                $status = "Your e-mail is verified. You can now login.";
            } else {
                $status = "Your e-mail is verified. You can now login.";
            }
        } else {
            return redirect('/')->with('message-danger', 'Sorry your email cannot be identified');
        }
        Toastr::success($status, 'Success');
        return redirect('login');
    }


    /*  ****************************** Package Methods * ****************************** */

    public function package_List()
    {
        $data = SmSaasPackages::all();
        return view('saas::systemSettings.package_List', compact('data'));
    }

    public function packageEdit($id)
    {
        $editData = SmSaasPackages::find($id);
        $data = SmSaasPackages::all();
        return view('saas::systemSettings.package_List', compact('data', 'editData'));
    }

    public function packageDelete($id)
    {

        $result = SmSaasPackages::destroy($id);

        if ($result) {
            Toastr::success('Operation successful', 'Success');
            return redirect('administrator/package-list');
        } else {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function packageUpdate(Request $request)
    {

        $request->validate([
            'package_name' => 'required',
            'monthly_price' => 'required|numeric',
            'quarterly_price' => 'required|numeric',
            'yearly_price' => 'required|numeric',
            'lifetime_price' => 'required|numeric',
        ]);
        $s = SmSaasPackages::find($request->id);
        $s->package_name = $request->package_name;
        $s->monthly_price = $request->monthly_price;
        $s->quarterly_price = $request->quarterly_price;
        $s->yearly_price = $request->yearly_price;
        $s->lifetime_price = $request->lifetime_price;
        $s->feature = $request->feature;
        $s->active_status = $request->active_status;
        $result = $s->save();

        if ($result) {
            Toastr::success('Operation successful', 'Success');
            return redirect('administrator/package-list');
        } else {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }

    public function packageStore(Request $request)
    {

        $request->validate([
            'package_name' => 'required|',
            'monthly_price' => 'required|numeric',
            'quarterly_price' => 'required|numeric',
            'yearly_price' => 'required|numeric',
            'lifetime_price' => 'required|numeric',
        ]);

        $s = new SmSaasPackages();
        $s->package_name = $request->package_name;
        $s->monthly_price = $request->monthly_price;
        $s->quarterly_price = $request->quarterly_price;
        $s->yearly_price = $request->yearly_price;
        $s->lifetime_price = $request->lifetime_price;
        $s->feature = $request->feature;
        $result = $s->save();



        if ($result) {
            Toastr::success('Operation successful', 'Success');
            return redirect()->back();
        } else {
            Toastr::error('Operation Failed', 'Failed');
            return redirect()->back();
        }
    }
      //ajax session Active
      public function sessionChange(Request $request)
      {

          try{
              if ($request->id) {
                  $data = SmAcademicYear::find($request->id);
                  $selected = SmGeneralSettings::where('school_id',auth()->user()->school_id)->first();
                  $selected->session_id = $request->id;
                  $selected->session_year = $data->year;
                  $selected->save();
                  return response()->json([$selected]);
              } else {
                  return '';
              }
          }catch (\Exception $e) {
             Toastr::error('Operation Failed', 'Failed');
             return redirect()->back();
          }
      }
}