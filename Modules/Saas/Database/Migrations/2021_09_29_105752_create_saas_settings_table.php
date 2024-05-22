<?php

use App\SmLanguagePhrase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Modules\Saas\Entities\SaasSettings;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saas_settings', function (Blueprint $table) {
            $table->id();
            $table->string('lang_name');
            $table->tinyInteger('active_status')->default(1);
            $table->tinyInteger('saas_status')->default(1);
            $table->integer('infix_module_id')->nullable();            
            $table->integer('user_id')->nullable()->unsigned(); 
            $table->timestamps();
        });

        try {
            //data key infix_module_infos id
            $data=[
                405=>'general_settings',
                410=>'email_settings',
                444=>'sms_settings',
                401=>'manage_currency',
                428=>'base_setup',
                456=>'backup',
                463=>'button_manage',
                480=>'email_template',
                710=>'sms_template',
            ];
            foreach($data as $key=>$d){
                $saas_setting=new SaasSettings;
                $saas_setting->lang_name=$d;
                $saas_setting->infix_module_id=$key;        
                $saas_setting->save();
            }

            if(!SaasSettings::where('lang_name', 'manage_subscription')->first()){
                $saas_setting=new SaasSettings;
                $saas_setting->lang_name= 'manage_subscription';
                $saas_setting->infix_module_id= Null;
                $saas_setting->save();
            }


        } catch (\Throwable $th) {
            Log::info($th);
            //throw $th;
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saas_settings');
    }
}
