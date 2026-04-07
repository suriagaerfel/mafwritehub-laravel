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
        Schema::create('article_content_versions', function (Blueprint $table) {
        
            $table->increments('id');
            $table->timestamp('timestamp')->useCurrent();

            $table->integer('article_id');
            $table->integer('content_version');

            $table->longText('version_content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_content_versions');
    }
};
