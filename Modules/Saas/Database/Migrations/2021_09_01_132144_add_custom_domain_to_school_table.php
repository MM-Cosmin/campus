<?php

use App\SmLanguagePhrase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomDomainToSchoolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sm_schools', function (Blueprint $table) {
            $table->string('custom_domain')->nullable()->after('domain');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sm_schools', function (Blueprint $table) {
            $table->dropColumn('custom_domain');
        });
    }
}
