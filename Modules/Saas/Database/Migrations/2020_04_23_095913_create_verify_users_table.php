<?php

use App\SmGeneralSettings;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVerifyUsersTable extends Migration
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
            Schema::create('verify_users', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->string('token');
                $table->timestamps();
                $table->integer('created_by')->nullable()->default(1)->unsigned();
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

                $table->integer('updated_by')->nullable()->default(1)->unsigned();
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');

                $table->integer('academic_id')->nullable()->unsigned();
                $table->foreign('academic_id')->references('id')->on('sm_academic_years')->onDelete('set null');
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
        Schema::dropIfExists('verify_users');
    }
}
