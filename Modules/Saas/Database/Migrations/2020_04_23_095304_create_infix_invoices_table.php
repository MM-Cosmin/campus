<?php

use App\SmGeneralSettings;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfixInvoicesTable extends Migration
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
            Schema::create('infix_invoices', function (Blueprint $table) {
                $table->increments('id');
                $table->bigInteger('customer_id')->nullable();
                $table->string('invoice_number',255)->nullable()->comment('Invoice number Will be Unique');
                $table->date('invoice_date')->nullable();
                $table->date('invoice_due_date')->nullable();
                $table->integer('currency_id')->nullable();
                $table->integer('project_id')->nullable();
                $table->integer('payment_method_id')->nullable();


                $table->enum('recurring_cycle', ['M', 'Q', 'SA', 'A', 'OT']     )->comment('M=Monthly, Q=Quarterly, SA=Semi Annually, A=Annually, OT=Once Time')->nullable();
                $table->tinyInteger('is_recurring_invoice')->default(0)          ->comment('0=No, 1=Yes');
                $table->enum('payment_status',  ['UP', 'P', 'PP', 'PR']         )->comment('UP= UNPAID , P= PAID , PP= PARTIALLY PAID, PR= PROFORMA');
                $table->double('partial_paymemt', 8, 2)->nullable();
                $table->enum('invoice_for', ['P','S','C'])                       ->comment('P=Product, S=Services, C=Customs');

                // $table->string('item_name',255)->nullable();
                // $table->string('description',255)->nullable();
                // $table->double('unit_cost', 8, 2);
                // $table->integer('quantity');

                $table->enum('discount_type', ['P', 'F'])                        ->comment('P=Percentage, F=Fixed')->nullable();
                $table->double('discount_amount', 8, 2)->nullable();

                $table->string('tax_percentage')->nullable();
                $table->string('purchase_order')->nullable();
                $table->text('private_note')->nullable();
                $table->text('public_note')->nullable();
                $table->text('terms_note')->nullable();
                $table->text('footer_note')->nullable();
                $table->string('signature_person')->nullable();
                $table->string('signature_company')->nullable();

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
        Schema::dropIfExists('infix_invoices');
    }
}
