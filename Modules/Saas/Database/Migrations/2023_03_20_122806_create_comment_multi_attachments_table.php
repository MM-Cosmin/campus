<?php

use Modules\Saas\Entities\Comment;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Saas\Entities\CommentMultiAttachment;

class CreateCommentMultiAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_multi_attachments', function (Blueprint $table) {
            $table->id();
            $table->integer('comment_id')->nullable()->unsigned();
            $table->foreign('comment_id')->references('id')->on('comments')->onDelete('cascade');
            $table->text('file')->nullable();
            $table->timestamps();
        });
        $comments = Comment::whereNotNull('file')->get();
        foreach($comments as $item) {
            $attachment = new CommentMultiAttachment();
            $attachment->comment_id  = $item->id;
            $attachment->file  = $item->file;
            $attachment->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commnet_multi_attachments');
    }
}
