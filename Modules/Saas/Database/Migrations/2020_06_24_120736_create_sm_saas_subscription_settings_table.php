<?php

use App\SmGeneralSettings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Saas\Entities\SaasSettings;

class CreateSmSaasSubscriptionSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

            Schema::create('sm_saas_subscription_settings', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('amount')->nullable();
                $table->integer('is_auto_approve')->default(1)->comment('0 no, 1 yes');

                $table->timestamps();
            });

            DB::table('sm_saas_subscription_settings')->insert([
                [
                    'amount' => '10',
                ]
            ]);

            if(Schema::hasTable('saas_settings')){
                if(!SaasSettings::where('lang_name', 'manage_subscription')->first()){
                    $saas_setting=new SaasSettings;
                    $saas_setting->lang_name= 'manage_subscription';
                    $saas_setting->infix_module_id= Null;
                    $saas_setting->save();
                }
            }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_saas_subscription_settings');
    }
}
