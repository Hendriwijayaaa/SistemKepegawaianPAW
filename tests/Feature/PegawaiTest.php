<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PegawaiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_http_halamanpegawai()
    {
        $response = $this->get(route('dashboard'));
        $response->assertStatus(302);
    }
    public function test_http_halamanpegawai_not_akses()
    {
        $response = $this->get(route('dashboard'));
        $response->assertStatus(302);
    }
    public function test_http_halamanpegawai_edit()
    {
        $response = $this->get(route('dashboard'));
        $response->assertStatus(302);
    }
    public function test_http_halamanpegawai_all()
    {
        $response = $this->get(route('dashboard'));
        $response->assertStatus(302);
    }
    public function test_http_halamanpegawai_byrole()
    {
        $response = $this->get(route('dashboard'));
        $response->assertStatus(302);
    }

        public function test_validasi_tambah_pegawai()
    {
        // Mengirimkan data yang valid
        $response = $this->post(route('pegawai.create'), [
            'Nomor KTA' => 'nilai_nomor_kta_valid',
            'Password' => 'nilai_password_valid'
        ]);
        $response->assertStatus(405);
    }

        public function test_validasi_edit_pegawai()
    {
        // Mengirimkan data yang valid
        $response = $this->post(route('pegawai.create'), [
            'Nomor KTA' => 'nilai_nomor_kta_valid',
            'Password' => 'nilai_password_valid'
        ]);
        $response->assertStatus(405);
    }
        public function test_tambah_pegawai()
    {
        // Mengirimkan data yang valid
        $response = $this->post(route('pegawai.create'), [
            'Nomor KTA' => 'nilai_nomor_kta_valid',
            'Password' => 'nilai_password_valid'
        ]);
        $response->assertStatus(405);
    }
        public function test_edit_pegawai()
    {
        // Mengirimkan data yang valid
        $response = $this->post(route('pegawai.create'), [
            'Nomor KTA' => 'nilai_nomor_kta_valid',
            'Password' => 'nilai_password_valid'
        ]);
        $response->assertStatus(405);
    }
        public function test_hapus_pegawai()
    {
        // Mengirimkan data yang valid
        $response = $this->post(route('pegawai.create'), [
            'Nomor KTA' => 'nilai_nomor_kta_valid',
            'Password' => 'nilai_password_valid'
        ]);
        $response->assertStatus(405);
    }
}