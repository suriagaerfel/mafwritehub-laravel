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
       Schema::create('contents', function (Blueprint $table) {
            $table->increments('contentId'); // INTEGER PRIMARY KEY AUTO_INCREMENT

            $table->string('contentTable', 64);
            $table->integer('contentForeignId')->unsigned();
            $table->integer('contentRegistrantId')->unsigned();
            $table->longText('contentSharedWith');
            $table->string('contentStatus', 64);
            $table->dateTime('contentPubDate'); // DATETIME column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
