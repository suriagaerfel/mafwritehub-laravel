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
        Schema::create('message_threads', function (Blueprint $table) {
            $table->increments('message_threadId'); // INTEGER PRIMARY KEY AUTO_INCREMENT

            $table->string('message_threadType', 64);
            $table->longText('message_threadCode');
            $table->timestamp('message_threadTimestamp')->useCurrent(); // DEFAULT CURRENT_TIMESTAMP
            $table->timestamp('message_threadUpdateDate')->nullable(); // NULL DEFAULT NULL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_threads');
    }
};
