<?php

use App\SmGeneralSettings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfixInvoiceCategoryLinksTable extends Migration
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
            Schema::create('infix_invoice_category_links', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->nullable();
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

            DB::table('infix_invoice_category_links')->insert([
                [
                    'name' => 'Payment Method',    //
                ],
                [
                    'name' => 'Discount Amount',    //
                ],
                [
                    'name' => 'Discount Type',    //
                ],
                [
                    'name' => 'TAX/GST/VAT',    //
                ],
                [
                    'name' => 'Customer',    //
                ],
                [
                    'name' => 'Project',    //
                ],
                [
                    'name' => 'Client',    //
                ],
                [
                    'name' => 'Currency',    //
                ],
                [
                    'name' => 'Recurring Invoice',    //
                ],
                [
                    'name' => 'Invoice Number',    //
                ],
                [
                    'name' => 'Invoice Date',    //
                ],
                [
                    'name' => 'Due Date',    //
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
        Schema::dropIfExists('infix_invoice_category_links');
    }
}
