<?php

use App\SmGeneralSettings;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillingInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $config = SmGeneralSettings::find(1);
       
        Schema::create('billing__information', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('full_name');
            $table->string('company');
            $table->string('billing_email');
            $table->string('address');
            $table->string('country');
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->string('payment_status');
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
        
        $name = "is_saas";

        if (!Schema::hasColumn('users', $name)) {
            Schema::table('users', function ($table) use ($name) {
                $table->boolean($name)->default(0);
            });
         }
        
        if (!Schema::hasColumn('infix_roles', $name)) {
           Schema::table('infix_roles', function ($table) use ($name) {
               $table->boolean($name)->default(0);
           });
        }
        if (!Schema::hasColumn('sm_designations', $name)) {
            Schema::table('sm_designations', function ($table) use ($name) {
                $table->boolean($name)->default(0);
            });
         }
         

        if (!Schema::hasColumn('infix_module_infos', $name)) {
            Schema::table('infix_module_infos', function ($table) use ($name) {
                $table->boolean($name)->default(0);
            });
          }
          
          if (!Schema::hasColumn('sm_staffs', $name)) {
            Schema::table('sm_staffs', function ($table) use ($name) {
                $table->boolean($name)->default(0);
            });
          }

          if (!Schema::hasColumn('sm_designations', $name)) {
            Schema::table('sm_designations', function ($table) use ($name) {
                $table->boolean($name)->default(0);
            });
          }

          if (!Schema::hasColumn('sm_human_departments', $name)) {
            Schema::table('sm_human_departments', function ($table) use ($name) {
                $table->boolean($name)->default(0);
            });
          }

           $name2 = "saas_schools";
           if (!Schema::hasColumn('infix_permission_assigns', $name2)) {
              Schema::table('infix_permission_assigns', function ($table) use ($name2) {
                  $table->text($name2)->nullable();
              });
            }

             if (!Schema::hasColumn('infix_roles', $name2)) {
                Schema::table('infix_roles', function ($table) use ($name2) {
                    $table->string($name2)->nullable();
                });
             }

             $name4 = 'file_size';

             if (!Schema::hasColumn('sm_general_settings', $name4)) {
                Schema::table('sm_general_settings', function ($table) use ($name4) {
                    $table->string($name4)->default('100000');
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
        Schema::dropIfExists('billing__information');
    }
}