<?php

use App\SmGeneralSettings;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaasSchoolModulePermissionAssignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $config = SmGeneralSettings::find(1);
        if (@$config->Saas == 1) {
        Schema::create('saas_school_module_permission_assigns', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('active_status')->default(1);
            $table->timestamps();


            $table->integer('module_id')->nullable()->unsigned();
            $table->foreign('module_id')->references('id')->on('sm_module_permissions')->onDelete('cascade');

            $table->integer('created_by')->nullable()->default(1)->unsigned();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

            $table->integer('updated_by')->nullable()->default(1)->unsigned();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
        });
    }
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saas_school_module_permission_assigns');
    }
}
