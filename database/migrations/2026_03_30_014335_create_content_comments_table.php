<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('content_comments', function (Blueprint $table) {
            $table->increments('content_commentId'); // INTEGER PRIMARY KEY AUTO_INCREMENT

            $table->string('content_commentContentType', 100);
            $table->integer('content_commentContentId')->unsigned();
            $table->integer('content_commentRegistrant')->unsigned();
            $table->string('content_commentRole', 100);
            $table->longText('content_commentContent');
            $table->timestamp('content_commentTimestamp')->useCurrent(); // DEFAULT CURRENT_TIMESTAMP
            $table->string('content_commentReaction', 100);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_comments');
    }
};
