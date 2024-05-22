<?php

use App\SmGeneralSettings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmSaasPaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

            Schema::create('sm_saas_payment_methods', function (Blueprint $table) {
                $table->increments('id');
                $table->string('method', 255);
                $table->string('type')->nullable();
                $table->tinyInteger('active_status')->default(1);
                $table->timestamps();

                $table->integer('gateway_id')->nullable()->unsigned();
                $table->foreign('gateway_id')->references('id')->on('sm_saas_payment_gateway_settings')->onDelete('restrict');

                $table->integer('created_by')->nullable()->default(1)->unsigned();
                $table->integer('updated_by')->nullable()->default(1)->unsigned();
            });

            DB::table('sm_saas_payment_methods')->insert([
                [
                    'method' => 'Cash',
                    'type' => 'System',
                    'created_at' => date('Y-m-d h:i:s'),
                ],
                [
                    'method' => 'Cheque',
                    'type' => 'System',
                    'created_at' => date('Y-m-d h:i:s'),
                ],
                [
                    'method' => 'Bank',
                    'type' => 'System',
                    'created_at' => date('Y-m-d h:i:s'),
                ],
                [
                    'method' => 'Stripe',
                    'type' => 'System',
                    'created_at' => date('Y-m-d h:i:s'),
                ],
                [
                    'method' => 'Paystack',
                    'type' => 'System',
                    'created_at' => date('Y-m-d h:i:s'),
                ],
                [
                    'method' => 'PayPal',
                    'type' => 'System',
                    'created_at' => date('Y-m-d h:i:s'),
                ],


            ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_saas_payment_methods');
    }
}
