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
        Schema::create('content_categories', function (Blueprint $table) {
            $table->increments('content_categoryId'); // INTEGER PRIMARY KEY AUTO_INCREMENT

            $table->string('content_categoryType', 100);
            $table->string('content_categoryName', 100);
            $table->timestamp('content_categoryCreated')->useCurrent(); // DEFAULT CURRENT_TIMESTAMP
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_categories');
    }
};
