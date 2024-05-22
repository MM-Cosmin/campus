<?php

use App\SmGeneralSettings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfixInvoiceSettingsTable extends Migration
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
            Schema::create('infix_invoice_settings', function (Blueprint $table) {
                $table->increments('id');
                $table->float('tax', 8, 2)->nullable();
                $table->string('tax_type', 5)->default('AD')->comment('AD = After Discount, BD = Before Discount');
                $table->string('prefix')->nullable();
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

            DB::table('infix_invoice_settings')->insert([
                [
                    'tax' => 0,    //
                    'prefix' => 'infix',    //
                ]

            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('infix_invoice_settings');
    }
}
