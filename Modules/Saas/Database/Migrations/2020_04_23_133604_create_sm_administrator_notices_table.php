<?php

use App\SmGeneralSettings;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmAdministratorNoticesTable extends Migration
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
            Schema::create('sm_administrator_notices', function (Blueprint $table) {
                $table->increments('id');
                $table->string('notice_title', 100)->nullable();
                $table->text('notice_message')->nullable();
                $table->date('notice_date')->nullable();
                $table->date('publish_on')->nullable();
                $table->string('inform_to', 100)->nullable()->comment('Notice message sent to these schools');
                $table->tinyInteger('active_status')->default(1);
                $table->integer('is_published')->nullable()->default(0);
                $table->timestamps();


                $table->integer('created_by')->nullable()->default(1)->unsigned();
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

                $table->integer('updated_by')->nullable()->default(1)->unsigned();
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');

                $table->integer('school_id')->nullable()->default(1)->unsigned();
                $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');

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
        Schema::dropIfExists('sm_administrator_notices');
    }
}
