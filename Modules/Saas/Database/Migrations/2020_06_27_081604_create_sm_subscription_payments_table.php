<?php

use App\SmGeneralSettings;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Saas\Entities\SmSubscriptionPayment;

class CreateSmSubscriptionPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

            Schema::create('sm_subscription_payments', function (Blueprint $table) {
                $table->increments('id');

                $table->integer('package_id')->nullable()->default(1)->unsigned();
                $table->foreign('package_id')->references('id')->on('sm_package_plans')->onDelete('cascade');

                $table->enum('payment_type', ['paid', 'trial'])->nullable()->comment('for frontend registration ');
                $table->enum('approve_status', ['approved','pending', 'cancelled'])->nullable();

                $table->string('bank_name')->nullable();
                $table->string('account_holder')->nullable();

                $table->date('payment_date')->nullable();
                $table->string('payment_method')->nullable();
                $table->string('file')->nullable()->comment('cheque and bank both file here');
                $table->double('amount', 2)->nullable();

                $table->integer('school_id')->nullable()->default(1)->unsigned();
                $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');

                // new fields for

                $table->date('start_date')->nullable()->comment('package start date');
                $table->date('end_date')->nullable()->comment('package end/renewal date');
                $table->string('buy_type')->nullable()->comment('buy_now = after finish current then start, instantly = start from now, nothing for trial');
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
        Schema::dropIfExists('sm_subscription_payments');
    }
}
