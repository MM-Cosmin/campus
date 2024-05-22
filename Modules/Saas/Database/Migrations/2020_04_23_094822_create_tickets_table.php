<?php

use App\SmGeneralSettings;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTable extends Migration
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
            Schema::create('tickets', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->nullable()->unsigned();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->string('subject');
                $table->longText('description')->nullable();
                $table->integer('active_status')->default(0);

                $table->integer('assign_user')->nullable()->unsigned();
                $table->foreign('assign_user')->references('id')->on('users')->onDelete('cascade');

                $table->integer('priority_id')->nullable()->unsigned();
                $table->foreign('priority_id')->references('id')->on('priorities')->onDelete('cascade');

                $table->integer('category_id')->nullable()->unsigned();
                $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

                $table->integer('created_by')->nullable()->default(1)->unsigned();
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

                $table->integer('updated_by')->nullable()->default(1)->unsigned();
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');

                $table->integer('school_id')->nullable()->default(1)->unsigned();
                $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');

                $table->integer('academic_id')->nullable()->unsigned();
                $table->foreign('academic_id')->references('id')->on('sm_academic_years')->onDelete('set null');
                $table->timestamps();
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
        Schema::dropIfExists('tickets');
    }
}
