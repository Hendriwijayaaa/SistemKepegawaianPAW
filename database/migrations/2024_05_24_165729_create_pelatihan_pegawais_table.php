<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePelatihanPegawaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelatihan_pegawais', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('absen_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('pelatihan_deksripsi')->nullable();
            $table->boolean('is_catat')->default(true);
            $table->foreign('absen_id')->references('id')->on('absensis')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('pelatihan_pegawais');
    }
}