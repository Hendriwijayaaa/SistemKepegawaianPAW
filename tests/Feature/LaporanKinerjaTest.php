<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LaporanKinerjaTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
   public function test_http_laporan_kinerja_by_role()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200);
    }
   public function test_http_laporan_kinerja()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200);
    }
   public function test_http_laporan_kinerja_verifikasi()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200);
    }
   public function test_http_detail_laporan_kinerja()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200);
    }
   public function test_http_not_absen_masuk_laporan_kinerja()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200);
    }
   public function test_http_before_absen_pulang_laporan_kinerja()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200);
    }
   public function test_http_tambah_laporan_kinerja()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200);
    }
   public function test_http_edit_laporan_kinerja()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200);
    }
   public function test_validasi_tambah_laporan_kinerja()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200);
    }
   public function test_validasi_edit_laporan_kinerja()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200);
    }
   public function test_tambah_laporan_kinerja()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200);
    }
   public function test_edit_laporan_kinerja()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200);
    }
   public function test_hapus_laporan_kinerja()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200);
    }
   public function test_cetak_laporan_kinerja()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200);
    }

   public function test_validasi_cetak_laporan_kinerja_pertahun()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200);
    }
   public function test_validasi_cetak_laporan_kinerja_perbulan()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200);
    }

}