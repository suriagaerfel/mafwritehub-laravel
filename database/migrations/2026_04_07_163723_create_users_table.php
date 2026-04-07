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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');

            $table->string('first_name', 256);
            $table->string('middle_name', 256);
            $table->string('last_name', 256);

            $table->string('name', 100);

            $table->longText('description');

            $table->string('type', 64);

            $table->string('profile_picture_link', 100);
            $table->string('cover_photo_link', 100);

            $table->string('email_address', 64);
            $table->string('mobile_number', 64);
            $table->string('username', 100);

            $table->longText('password');

            $table->string('confirmation_code', 64);

            $table->string('verification', 64)->default('Unverified');
            $table->string('status', 64)->default('No Ban');

            $table->dateTime('created')->useCurrent();

            $table->string('reset_token_hash', 64);
            $table->dateTime('reset_token_expiration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
