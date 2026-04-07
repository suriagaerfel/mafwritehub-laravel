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
       Schema::create('admin_messages', function (Blueprint $table) {
            $table->increments('admin_messageId'); // INTEGER PRIMARY KEY AUTOINCREMENT in SQLite

            $table->string('admin_messageType', 100);
            $table->integer('admin_messageRegistrantId')->unsigned();
            $table->longText('admin_messageRegistrantAccountName');
            $table->longText('admin_messageContent');
            $table->string('admin_messageStatus', 100);
            $table->timestamp('admin_messageTimestamp')->useCurrent(); // DEFAULT CURRENT_TIMESTAMP
            $table->integer('admin_messageViewer')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_messages');
    }
};
