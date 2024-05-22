<?php

use App\SmGeneralSettings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmSaasPackagesTable extends Migration
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
            Schema::create('sm_saas_packages', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('package_name', 255);
                $table->double('monthly_price', 16, 2)->nullable();
                $table->double('quarterly_price', 16, 2)->nullable();
                $table->double('yearly_price', 16, 2)->nullable();
                $table->double('lifetime_price', 16, 2)->nullable();
                $table->tinyInteger('active_status')->default(1);
                $table->text('feature')->nullable();
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


            DB::table('sm_saas_packages')->insert([
                [
                    'package_name' => 'Free',    //  
                    'monthly_price' => 0,    //  
                    'quarterly_price' => 0,    //  
                    'yearly_price' => 0,    //  
                    'lifetime_price' => 0,    //  
                    'active_status' => 1,    //  
                    'feature' => 'auto genarate features',    //  
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
        Schema::dropIfExists('sm_saas_packages');
    }
}
