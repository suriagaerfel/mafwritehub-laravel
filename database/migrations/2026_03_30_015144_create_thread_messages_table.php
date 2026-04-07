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
        Schema::create('thread_messages', function (Blueprint $table) {
            $table->bigIncrements('thread_messageId');  // int(20) NOT NULL AUTO_INCREMENT PRIMARY KEY
            
            $table->string('threadCode', 100);  // varchar(100) NOT NULL (unique thread identifier)
            $table->unsignedInteger('registrantId');  // int(11) NOT NULL (sender/recipient ID)
            $table->longText('content');  // longtext NOT NULL (message body)
            $table->timestamp('timestamp')->useCurrent();  // timestamp NOT NULL DEFAULT current_timestamp()
            
            // Message status tracking
            $table->string('status', 64)->default('Unread');  // varchar(64) NOT NULL DEFAULT 'Unread'
            $table->string('statusSender', 64)->default('Read');  // varchar(64) NOT NULL DEFAULT 'Read'
            $table->string('statusRecipient', 64)->default('Unread');  // varchar(64) NOT NULL DEFAULT 'Unread'
            
            $table->timestamps();  // Laravel standard created_at/updated_at
            
            // Performance indexes
            $table->index('threadCode');
            $table->index(['registrantId', 'timestamp']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thread_messages');
    }
};
