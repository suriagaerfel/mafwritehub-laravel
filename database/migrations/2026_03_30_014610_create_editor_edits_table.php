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
        Schema::create('editor_edits', function (Blueprint $table) {
            $table->increments('editor_editId'); // INTEGER PRIMARY KEY AUTO_INCREMENT

            $table->timestamp('editor_timestamp')->useCurrent();     // DEFAULT CURRENT_TIMESTAMP
            $table->string('editor_writerArticleId', 64);
            $table->string('editor_userId', 64);
            $table->longText('editor_comment');
            $table->timestamp('editor_updateDate')->useCurrent();    // DEFAULT CURRENT_TIMESTAMP
            $table->string('editor_lastEditor', 64);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('editor_edits');
    }
};
