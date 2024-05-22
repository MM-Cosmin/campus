<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('home_sliders', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('link')->nullable();
            $table->timestamps();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
        });

        DB::table('home_sliders')->insert([
            [
                'image' => "public/uploads/theme/edulia/home_slider/banner-1.jpg",
            ],
            [
                'image' => "public/uploads/theme/edulia/home_slider/banner-2.jpg",
            ],
            [
                'image' => "public/uploads/theme/edulia/home_slider/banner-3.jpg",
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_sliders');
    }
};
