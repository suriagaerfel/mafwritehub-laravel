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
       Schema::create('from_no_admin_users_messages', function (Blueprint $table) {
            $table->increments('from_no_admin_users_messageId'); // INTEGER PRIMARY KEY AUTO_INCREMENT

            $table->integer('from_no_admin_users_messageRegistrantId')->unsigned();
            $table->longText('from_no_admin_users_messageContent');
            $table->timestamp('from_no_admin_users_messageTimestamp')->useCurrent(); // DEFAULT CURRENT_TIMESTAMP
            $table->string('from_no_admin_users_messageStatus', 64)->default('Unread');
            $table->string('from_no_admin_users_messageStatusSender', 64)->default('Read');
            $table->string('from_no_admin_users_messageStatusRecipient', 64)->default('Unread');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('from_no_admin_users_messages');
    }
};
