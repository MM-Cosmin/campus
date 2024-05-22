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
        Schema::create('sm_photo_galleries', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('feature_image')->nullable();
            $table->string('gallery_image')->nullable();
            $table->boolean('is_publish')->default(true);
            $table->integer('position')->default(0);
            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
            $table->timestamps();
        });

        DB::table('sm_photo_galleries')->insert([
            [
                'parent_id' => Null,
                'name' => 'Academic Expo For All Role',
                'description' => "Celebrating student achievements and innovation stay with us",
                'feature_image' => "public/uploads/theme/edulia/photo_gallery/gallery-1.jpg",
                'gallery_image' => Null,
                'position' => 1,
            ],
            [
                'parent_id' => Null,
                'name' => 'Arts & Beats Festival For Student',
                'description' => "A vibrant celebration of creativity through art and music",
                'feature_image' => "public/uploads/theme/edulia/photo_gallery/gallery-2.jpg",
                'gallery_image' => Null,
                'position' => 2,
            ],
            [
                'parent_id' => Null,
                'name' => 'Language and Literature Fiesta',
                'description' => "A lively celebration of words, stories, and cultural expressions.",
                'feature_image' => "public/uploads/theme/edulia/photo_gallery/gallery-3.jpg",
                'gallery_image' => Null,
                'position' => 3,
            ],
            [
                'parent_id' => Null,
                'name' => 'Environmental Awareness Day',
                'description' => "Promoting eco-consciousness and sustainable living",
                'feature_image' => "public/uploads/theme/edulia/photo_gallery/gallery-4.jpg",
                'gallery_image' => Null,
                'position' => 4,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sm_photo_galleries');
    }
};
