<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pegawai_id')->nullable();
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role');
            $table->string('status')->default('aktif');
            $table->boolean('is_verify')->default(false);
            $table->foreign('pegawai_id')->references('id')->on('pegawais')->onDelete('cascade')->onUpdate('cascade');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}