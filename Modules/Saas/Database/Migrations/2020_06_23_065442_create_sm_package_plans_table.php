<?php

use App\SmLanguagePhrase;
use App\SmGeneralSettings;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Saas\Entities\SmPackagePlan;

class CreateSmPackagePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

            Schema::create('sm_package_plans', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->integer('duration_days')->nullable();
                $table->double('price', 16, 2)->nullable();
                $table->integer('trial_days')->nullable();

                $table->tinyInteger('active_status')->default(1)->comment('1 active, 0 inactive');
                $table->text('features')->nullable();
                $table->integer('student_quantity')->nullable();
                $table->integer('staff_quantity')->nullable();
                $table->longText('modules')->nullable();
                $table->longText('menus')->nullable();
                
                $table->timestamps();
            }); 

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_package_plans');
    }
}