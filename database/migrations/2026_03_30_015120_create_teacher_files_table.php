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
        Schema::create('teacher_files', function (Blueprint $table) {
            $table->id('id');  // int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY
            
            $table->longText('title');      // longtext NOT NULL
            $table->longText('slug');       // longtext NOT NULL
            $table->string('category', 64); // varchar(64) NOT NULL
            $table->longText('tags');       // longtext NOT NULL
            $table->string('accessType', 64)->default('Free');  // varchar(64) NOT NULL DEFAULT 'Free'
            $table->string('sharedWith', 300);  // varchar(300) NOT NULL (comma-separated IDs)
            $table->longText('description');    // longtext NOT NULL
            $table->unsignedInteger('contentVersion');  // int(11) NOT NULL
            $table->string('image', 100);       // varchar(100) NOT NULL
            $table->string('format', 64);       // varchar(64) NOT NULL
            $table->string('teacher', 64);      // varchar(64) NOT NULL
            $table->dateTime('uploadDate');     // datetime NOT NULL
            $table->dateTime('pubDate');        // datetime NOT NULL
            $table->dateTime('updateDate')->useCurrentOnUpdate();  // datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
            $table->string('status', 64)->default('Draft');  // varchar(64) NOT NULL DEFAULT 'Draft'
            $table->string('forSale', 64)->default('Not for Sale');  // varchar(64) NOT NULL DEFAULT 'Not for Sale'
            $table->unsignedInteger('amount', false, 7);  // int(7) NOT NULL (price)
            $table->longText('content');        // longtext NOT NULL (file path)
            
            $table->timestamps();  // Laravel standard created_at/updated_at
            
            $table->index(['teacher', 'status', 'pubDate']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_files');
    }
};
