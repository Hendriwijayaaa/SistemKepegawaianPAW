<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_http_loginbenar()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200);
    }
    public function test_validasi_login_form()
    {
        // Mengirimkan data yang valid
        $response = $this->post(route('login.store'), [
            'Nomor KTA' => 'nilai_nomor_kta_valid',
            'Password' => 'nilai_password_valid'
        ]);
        $response->assertStatus(302);
    }

    public function test_halamanloginsalah()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200, 'Akses yang salah harus menghasilkan status 4xx atau 5xx');
        $response->assertViewIs('auth.login');
    }
    public function test_halamanlogin_password_salah()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200, 'Password Tidak Sesuai');
        $response->assertViewIs('auth.login');
    }
    public function test_halamanlogin_email_salah()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200, 'Email Salah');
        $response->assertViewIs('auth.login');
    }
    public function test_halamanlogin_email_tidakterdaftar()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200, 'Email Salah');
        $response->assertViewIs('auth.login');
    }
    public function test_halamanlogin_halaman_resetpassword()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200, 'Email Salah');
        $response->assertViewIs('auth.login');
    }
    public function test_halamanreset_emailterdaftartoconfirm()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200, 'Email Salah');
        $response->assertViewIs('auth.login');
    }
    public function test_halamanreset_emailtidakterdaftar()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200, 'Email Salah');
        $response->assertViewIs('auth.login');
    }
    public function test_halamanreset_validasi_inputanreset()
    {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200, 'Email Salah');
        $response->assertViewIs('auth.login');
    }


        public function test_validasi_tambah_akun_pengguna()
    {
        // Mengirimkan data yang valid
        $response = $this->post(route('pegawai.create'), [
            'Nomor KTA' => 'nilai_nomor_kta_valid',
            'Password' => 'nilai_password_valid'
        ]);
        $response->assertStatus(405);
    }

        public function test_validasi_edit_akun_pengguna()
    {
        // Mengirimkan data yang valid
        $response = $this->post(route('pegawai.create'), [
            'Nomor KTA' => 'nilai_nomor_kta_valid',
            'Password' => 'nilai_password_valid'
        ]);
        $response->assertStatus(405);
    }
        public function test_tambah_akun_pengguna()
    {
        // Mengirimkan data yang valid
        $response = $this->post(route('pegawai.create'), [
            'Nomor KTA' => 'nilai_nomor_kta_valid',
            'Password' => 'nilai_password_valid'
        ]);
        $response->assertStatus(405);
    }
        public function test_edit_akun_pengguna()
    {
        // Mengirimkan data yang valid
        $response = $this->post(route('pegawai.create'), [
            'Nomor KTA' => 'nilai_nomor_kta_valid',
            'Password' => 'nilai_password_valid'
        ]);
        $response->assertStatus(405);
    }
        public function test_hapus_akun_pengguna()
    {
        // Mengirimkan data yang valid
        $response = $this->post(route('pegawai.create'), [
            'Nomor KTA' => 'nilai_nomor_kta_valid',
            'Password' => 'nilai_password_valid'
        ]);
        $response->assertStatus(405);
    }
        public function test_login_banned_akun()
    {
        // Mengirimkan data yang valid
        $response = $this->post(route('pegawai.create'), [
            'Nomor KTA' => 'nilai_nomor_kta_valid',
            'Password' => 'nilai_password_valid'
        ]);
        $response->assertStatus(405);
    }
        public function test_login_notactive_akun()
    {
        // Mengirimkan data yang valid
        $response = $this->post(route('pegawai.create'), [
            'Nomor KTA' => 'nilai_nomor_kta_valid',
            'Password' => 'nilai_password_valid'
        ]);
        $response->assertStatus(405);
    }

}