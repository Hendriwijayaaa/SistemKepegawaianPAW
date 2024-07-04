<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_http_halaman_dashboardall()
    {
        $response = $this->get(route('dashboard'));
        $response->assertStatus(302);
    }
    public function test_http_halaman_dashboard_admin()
    {
        $response = $this->get(route('dashboard'));
        $response->assertStatus(302);
    }
    public function test_http_halaman_dashboard_pegawai()
    {
        $response = $this->get(route('dashboard'));
        $response->assertStatus(302);
    }
    public function test_http_halaman_dashboard_kepala()
    {
        $response = $this->get(route('dashboard'));
        $response->assertStatus(302);
    }
    public function test_http_halaman_dashboard_not_akses()
    {
        $response = $this->get(route('dashboard'));
        $response->assertStatus(302);
    }
}