<?php

use App\SmGeneralSettings;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmPackagePlanFeaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

            Schema::create('sm_package_plan_features', function (Blueprint $table) {
                $table->increments('id');
                $table->string('feature')->nullable();

                $table->integer('package_plan_id')->nullable()->default(1)->unsigned();
                $table->foreign('package_plan_id')->references('id')->on('sm_package_plans');

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
        Schema::dropIfExists('sm_package_plan_features');
    }
}
