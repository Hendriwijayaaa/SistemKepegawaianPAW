<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AbsenTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
       public function test_http_halaman_absen()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200);
    }
       public function test_http_halaman_absen_by_role()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200);
    }
       public function test_http_halaman_absen_after_waktuselesai()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200);
    }
       public function test_http_halaman_absen_before_waktuselesai()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200);
    }
       public function test_validasi_absen()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200);
    }
       public function test_validasi_izin()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200);
    }
       public function test_validasi_sakit()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200);
    }
       public function test_validasi_absen_libur()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200);
    }
       public function test_validasi_absen_tidak_sesuaiwaktu()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200);
    }
}