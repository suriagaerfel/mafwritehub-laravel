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
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');

            $table->longText('title');
            $table->longText('slug');

            $table->string('image', 150)->nullable();
            $table->string('category', 64);
            $table->string('topic', 100);

            $table->integer('writer_id');
            $table->string('writer_name', 256)->nullable();

            $table->dateTime('drafted')->useCurrent();

            $table->dateTime('updated')
                  ->nullable()
                  ->useCurrentOnUpdate()
                  ->useCurrent();

            $table->dateTime('published')->nullable();

            $table->integer('content_version')->default(1);
            $table->string('status', 64)->default('Draft');

            $table->longText('comments')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
