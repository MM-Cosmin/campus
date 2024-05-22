<?php

namespace Modules\Saas\Listeners;

use PDO;
use App\User;
use App\Models\Theme;
use App\SmSmsGateway;
use App\SmAcademicYear;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Lms\Entities\VimeoSetting;
use Modules\Saas\Events\InstituteRegistration;

class InstituteRegisterdListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle(InstituteRegistration $event)
    {
        $school = $event->institute;

        $academic_year = $this->academicYear($school);

        $default_tables = getVar('defaults');
        foreach($default_tables as $t){
            if(Schema::hasTable($t)){
                $last_id = DB::table($t)->max('id');
                $data = DB::table($t)->where('school_id', 1)->when($t == 'assign_permissions', function ($q){
                    $q->where('role_id', '<=', 9);
                })->get()->map(function($v) use($school, $academic_year, $t,$last_id){
                    $v->id = $last_id + $v->id;
                    $v->school_id = $school->id;
                    $v->created_at = now();
                    $v->updated_at = now();
                    if(property_exists($v, 'session_id')){
                        $v->session_id = $academic_year->id;
                    }
                    if(property_exists($v, 'academic_id')){
                        $v->academic_id = $academic_year->id;
                    }
                    $function = 'before_insert_callback';
                    if(function_exists($function)){
                        $v = $function($school, $v, $t);
                    }
                    return (array) $v;
                })->toArray();
                DB::table($t)->insert($data);

                if(\DB::connection()->getPDO()->getAttribute(PDO::ATTR_DRIVER_NAME) == "pgsql"){
                    //Get the max id from that table and add 1 to it
                    $seq = \DB::table($t)->max('id') + 1; 
                    // alter the sequence to now RESTART WITH the new sequence index from above        
                    \DB::select('ALTER SEQUENCE ' . $t . '_id_seq RESTART WITH ' . $seq); 
                }    
            }
        }



        $this->colorTheme($school);

        if (moduleStatusCheck('Lms')) {
            $max_id =  VimeoSetting::max('id');
            $settings = new VimeoSetting;
            $settings->id = $max_id + 2;
            $school_admin = User::with('staff')->where('school_id', $school->id)->where('role_id', 1)->first();
            $settings->created_by = $school_admin ? $school_admin->staff->id : 1;
            $settings->school_id = $school->id;
            $settings->save();
        }


        $max_id = SmSmsGateway::max('id');
        $gateway = new SmSmsGateway();
        $gateway->id = $max_id +1;
        $gateway->gateway_name = 'Twilio';
        $gateway->school_id = $school->id;
        $gateway->save();

        $gateway = new SmSmsGateway();
        $gateway->id = $max_id + 2;
        $gateway->gateway_name = 'Msg91';
        $gateway->school_id = $school->id;
        $gateway->save();

        $gateway = new SmSmsGateway();
        $gateway->id = $max_id + 3;
        $gateway->gateway_name = 'TextLocal';
        $gateway->textlocal_sender = 'TXTLCL';
        $gateway->school_id = $school->id;
        $gateway->save();

        $gateway = new SmSmsGateway();
        $gateway->id = $max_id + 4;
        $gateway->gateway_name = 'AfricaTalking';
        $gateway->africatalking_username = 'sandbox';
        $gateway->school_id = $school->id;
        $gateway->save();

        $gateway = new SmSmsGateway();
        $gateway->id = $max_id + 5;
        $gateway->gateway_name = 'Mobile SMS';
        $gateway->school_id = $school->id;
        $gateway->save();

        if(\DB::connection()->getPDO()->getAttribute(PDO::ATTR_DRIVER_NAME) == "pgsql"){
            //Get the max id from that table and add 1 to it
            $seq = \DB::table('sm_sms_gateways')->max('id') + 1; 
            // alter the sequence to now RESTART WITH the new sequence index from above        
            \DB::select('ALTER SEQUENCE ' . 'sm_sms_gateways' . '_id_seq RESTART WITH ' . $seq); 
        } 


    }


    public function academicYear($school)
    {
        $academic_year = new SmAcademicYear();

        $academic_year->year = date('Y');
        $academic_year->title = ' academic year ' . date('Y');
        $academic_year->school_id = $school->id;
        $academic_year->starting_date = date('Y') . '-01-01';
        $academic_year->ending_date = date('Y') . '-12-31';

        $academic_year->save();
        return $academic_year;
    }

    private function colorTheme(\App\SmSchool $school)
    {
        $default_themes = ['Default', 'Lawn Green'];

        foreach ($default_themes as $key => $item) {
            $theme = Theme::updateOrCreate([
                'title' => $item,
                'school_id' => $school->id
            ]);
            if ($item == 'Lawn Green') {
                $theme->path_main_style = 'lawngreen_version/style.css';
                $theme->path_infix_style = 'lawngreen_version/infix.css';
                $theme->path_infix_style = false;
                $theme->box_shadow = true;
            } else {
                $theme->path_main_style = 'style.css';
                $theme->path_infix_style = 'infix.css';
                $theme->is_default = $key == 0 ? 1 : 0;
            }
            $theme->color_mode = "gradient";
            $theme->background_type = "image";
            $theme->background_image = asset('/public/backEnd/img/body-bg.jpg');
            $theme->is_system = true;
            $theme->school_id = $school->id;
            $theme->save();


        }

        $themes = \App\Models\Theme::withOutGlobalScopes()->where('school_id', $school->id)->get();
        $sql = [];
        foreach ($themes as $theme) {
            if ($theme->title == 'Default') {
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 1, 'value' => "#415094"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 2, 'value' => "#7c32ff"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 3, 'value' => "#c738d8"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 4, 'value' => "#7c32ff"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 5, 'value' => "#828bb2"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 6, 'value' => "#828bb2"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 7, 'value' => "#ffffff"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 8, 'value' => "#ffffff"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 9, 'value' => "#000000"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 10, 'value' => "#000000"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 11, 'value' => "#c738d8"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 12, 'value' => "#ffffff"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 13, 'value' => "#51A351"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 14, 'value' => "#E09079"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 15, 'value' => "#FF6D68"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 16, 'value' => "#415094"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 17, 'value' => "#222222"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 18, 'value' => "#415094"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 19, 'value' => "transparent"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 20, 'value' => "#4c5c9b"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 21, 'value' => "#415094"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 29, 'value' => "#415094"];

            }

            if ($theme->title != 'Default') {
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 1, 'value' => "#415094"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 2, 'value' => "#03e396"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 3, 'value' => "#03e396"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 4, 'value' => "#03e396"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 5, 'value' => "#7e7172"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 6, 'value' => "#828bb2"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 7, 'value' => "#ffffff"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 8, 'value' => "#ffffff"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 9, 'value' => "#000000"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 10, 'value' => "#000000"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 11, 'value' => "#03e396"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 12, 'value' => "#ffffff"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 13, 'value' => "#51A351"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 14, 'value' => "#E09079"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 15, 'value' => "#FF6D68"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 16, 'value' => "#415094"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 17, 'value' => "#222222"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 18, 'value' => "#415094"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 19, 'value' => "#ffffff"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 20, 'value' => "#e7ecff"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 21, 'value' => "#e7ecff"];
                $sql[] = ['theme_id' => $theme->id, 'color_id' => 29, 'value' => "#e7ecff"];
            }
        }

        DB::table('color_theme')->insert($sql);
    }

}
 