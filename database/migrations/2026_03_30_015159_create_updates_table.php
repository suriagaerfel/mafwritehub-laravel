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
        Schema::create('updates', function (Blueprint $table) {
            $table->id('updateId');  // int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY
            
            $table->string('updateType', 100);  // varchar(100) NOT NULL (e.g., 'Subscription')
            $table->longText('updateTitle');    // longtext NOT NULL
            $table->string('updateStatus', 64)->default('Draft');  // varchar(64) NOT NULL DEFAULT 'Draft'
            $table->timestamp('updateTimestamp')->useCurrent();    // timestamp NOT NULL DEFAULT current_timestamp()
            $table->unsignedInteger('updateRegistrantId');  // int(11) NOT NULL (author)
            $table->longText('updateViewers');  // longtext NOT NULL (comma-separated viewer IDs)
            $table->longText('updateContent');  // longtext NOT NULL (rich content)
            $table->longText('updateSlug');     // longtext NOT NULL (SEO-friendly URL)
            $table->dateTime('updatePubDate');  // datetime NOT NULL
            $table->dateTime('updateUpdateDate')->useCurrentOnUpdate();  // datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
            
            $table->timestamps();  // Laravel standard created_at/updated_at
            
            $table->index(['updateRegistrantId', 'updateStatus']);
            $table->index('updatePubDate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('updates');
    }
};
