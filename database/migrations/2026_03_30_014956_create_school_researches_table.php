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
       Schema::create('school_researches', function (Blueprint $table) {
            $table->id('id');  // int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY
            
            $table->longText('title');  // longtext NOT NULL
            $table->longText('slug');   // longtext NOT NULL
            $table->string('category', 64);  // varchar(64) NOT NULL
            $table->longText('tags');   // longtext NOT NULL
            $table->string('accessType', 100)->nullable();  // varchar(100) DEFAULT NULL
            $table->longText('sharedWith');  // longtext NOT NULL
            $table->longText('abstract');    // longtext NOT NULL
            $table->string('image', 100);    // varchar(100) NOT NULL
            $table->string('format', 64);    // varchar(64) NOT NULL
            $table->unsignedInteger('school');  // int(11) NOT NULL
            $table->string('proponents', 64);   // varchar(64) NOT NULL
            $table->dateTime('uploadDate');     // datetime NOT NULL
            $table->date('date');               // date NOT NULL
            $table->dateTime('liveDate');       // datetime NOT NULL
            $table->dateTime('updateDate')->useCurrentOnUpdate();  // datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
            $table->string('status', 64)->default('Draft');  // varchar(64) NOT NULL DEFAULT 'Draft'
            $table->longText('content');        // longtext NOT NULL
            $table->unsignedInteger('contentVersion')->default(1);  // int(11) NOT NULL DEFAULT 1
            
            $table->timestamps();  // Laravel standard created_at/updated_at
            
            $table->index(['school', 'status', 'liveDate']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_researches');
    }
};
