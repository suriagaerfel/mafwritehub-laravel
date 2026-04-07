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
        Schema::create('developer_tool_files', function (Blueprint $table) {
            $table->increments('developer_tool_fileId'); // INTEGER PRIMARY KEY AUTO_INCREMENT

            $table->timestamp('developer_tool_fileTimestamp')->useCurrent(); // DEFAULT CURRENT_TIMESTAMP
            $table->integer('developer_tool_fileToolId')->unsigned();
            $table->string('developer_tool_fileLink', 150);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('developer_tool_files');
    }
};
