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
        Schema::create('writer_articles', function (Blueprint $table) {
            $table->id('id');  // int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY
            
            $table->longText('title');        // longtext NOT NULL
            $table->longText('description');  // longtext NOT NULL
            $table->longText('slug');         // longtext NOT NULL
            $table->string('image', 150);     // varchar(150) NOT NULL
            $table->string('category', 64);   // varchar(64) NOT NULL
            $table->string('accessType', 100); // varchar(100) NOT NULL
            $table->longText('sharedWith');   // longtext NOT NULL (comma-separated IDs)
            $table->longText('tags');         // longtext NOT NULL
            $table->unsignedInteger('writer'); // int(11) NOT NULL (writer ID)
            $table->string('writerName', 256); // varchar(256) NOT NULL
            $table->text('editors');          // text NOT NULL (editor IDs)
            $table->dateTime('writeDate')->useCurrent();  // datetime NOT NULL DEFAULT current_timestamp()
            $table->dateTime('updateDate')->useCurrentOnUpdate();  // datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
            $table->dateTime('pubDate');     // datetime NOT NULL
            $table->longText('content');      // longtext NOT NULL
            $table->unsignedInteger('contentVersion')->default(1);  // int(11) NOT NULL DEFAULT 1
            $table->string('status', 64)->default('Draft');  // varchar(64) NOT NULL DEFAULT 'Draft'
            $table->longText('comments');     // longtext NOT NULL
            
            $table->timestamps();  // Laravel standard created_at/updated_at
            
            $table->index(['writer', 'status']);
            $table->index('pubDate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('writer_articles');
    }
};
