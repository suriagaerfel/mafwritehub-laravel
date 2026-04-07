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
        Schema::create('article_performance', function (Blueprint $table) {
          
            $table->increments('id');

        
            $table->timestamp('timestamp')->useCurrent();

            $table->integer('article_id');
            $table->integer('user_id');

            $table->integer('time_spent');

            $table->timestamp('last_view')
                  ->useCurrent()
                  ->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_performance');
    }
};
