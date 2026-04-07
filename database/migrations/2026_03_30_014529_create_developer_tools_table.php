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
            Schema::create('developer_tools', function (Blueprint $table) {
            $table->increments('id'); // INTEGER PRIMARY KEY AUTO_INCREMENT

            $table->longText('title');
            $table->string('category', 64);
            $table->longText('tags');
            $table->longText('description');
            $table->longText('image');
            $table->string('developer', 64);

            $table->timestamp('createdDate')->useCurrent(); // DEFAULT CURRENT_TIMESTAMP
            $table->dateTime('pubDate');
            $table->timestamp('ppdateDate')
                ->useCurrent()
                ->useCurrentOnUpdate(); // DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP

            $table->integer('contentVersion')->default(1);
            $table->string('status', 64)->default('Draft');
            $table->string('accessType', 100)->default('Free Access');
            $table->integer('amount')->default(0);
            $table->longText('sharedWith');
            $table->longText('slug');
            $table->longText('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('developer_tools');
    }
};
