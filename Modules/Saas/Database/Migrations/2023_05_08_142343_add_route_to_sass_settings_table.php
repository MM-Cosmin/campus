<?php

use Illuminate\Support\Facades\Schema;
use Modules\Saas\Entities\SaasSettings;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRouteToSassSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('saas_settings', function (Blueprint $table) {
            $table->string('route')->nullable()->after('saas_status');
        });
        Schema::table('tickets', function (Blueprint $table) {
            if(Schema::hasColumn('tickets', 'active_status')) {
                $table->integer('active_status')->nullable()->change();
            }            
        });
        $data=[
            405=>'general-settings',
            410=>'email-settings',
            444=>'sms-settings',
            401=>'manage-currency',
            428=>'base_setup',
            456=>'backup-settings',
            463=>'button-disable-enable',
            480=>'templatesettings.sms-template',
            710=>'templatesettings.email-template',
        ];
        foreach($data as $key=>$d){
            $saas_setting= SaasSettings::where('infix_module_id', $key)->first();
            if($saas_setting) {
                $saas_setting->route = $d;
                $saas_setting->save();
            }
        }

        $saasPermissionList = include('./resources/var/permission/saas_route_formated.php');
         foreach($saasPermissionList as $item){           
            storePermissionData($item);
         }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sass_settings', function (Blueprint $table) {

        });
    }
}
