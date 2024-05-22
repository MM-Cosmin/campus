<?php

use App\SmLanguagePhrase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\InfixModuleInfo;
use Modules\RolePermission\Entities\InfixPermissionAssign;

class CreateAppSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_sliders', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('slider_image')->nullable();
            $table->text('url')->nullable();
            $table->tinyInteger('active_status')->default(1);
            $table->integer('school_id')->nullable()->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
            $table->timestamps();
        });

        try {
                $d=[
                    [18, 'app_slider', 'App Slider', 'App Slider', 'অ্যাপ স্লাইডার', 'App Slider'],
                    [18, 'app', 'App', 'App', 'অ্যাপ', 'App'],
                    [18, 'slider', 'Slider', 'Slider', 'স্লাইডার', 'Slider'],
                    [18, 'sliders', 'Slider', 'Slider', 'স্লাইডার', 'Slider'],
                    [18, 'sliders_list', 'Slider', 'Slider', 'স্লাইডার', 'Slider'],
                ];

                foreach ($d as $row) {
                    $s = SmLanguagePhrase::where('default_phrases', trim($row[1]))->first();
                    if (empty($s)) {
                        $s = new SmLanguagePhrase();
                    }
                    $s->modules = $row[0];
                    $s->default_phrases = trim($row[1]);
                    $s->en = trim($row[2]);
                    $s->es = trim($row[3]);
                    $s->bn = trim($row[4]);
                    $s->fr = trim($row[5]);
                    $s->save();
                }
        } catch (\Throwable $th) {
            Log::info($th);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_sliders');
    }
}
