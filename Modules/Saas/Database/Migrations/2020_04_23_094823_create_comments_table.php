<?php

use App\SmGeneralSettings;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
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
            Schema::create('comments', function (Blueprint $table) {
                $table->increments('id');
                $table->string('file')->nullable();
                $table->longText('comment');

                $table->integer('comment_id')->nullable()->default(1)->unsigned();
                // $table->foreign('comment_id')->references('id')->on('comments')->onDelete('cascade');

                $table->integer('client_id')->nullable()->default(1)->unsigned();
                $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');

                $table->integer('ticket_id')->nullable()->default(1)->unsigned();
                // $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');

                $table->integer('user_id')->nullable()->default(1)->unsigned();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('comments');
    }
}
