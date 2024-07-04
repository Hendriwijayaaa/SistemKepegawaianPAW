<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaporanKinerjasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laporan_kinerjas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pegawai_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('tanggal_kegiatan')->nullable();
            $table->string('jam_mulai')->nullable();
            $table->string('jam_berakhir')->nullable();
            $table->text('keterangan_kegiatan')->nullable();
            $table->string('bukti_kegiatan')->nullable();
            $table->integer('is_verify')->default(0);
            $table->boolean('is_nilai')->default(false);
            $table->foreign('pegawai_id')->references('id')->on('pegawais')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('laporan_kinerjas');
    }
}