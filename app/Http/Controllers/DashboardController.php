<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\LaporanKinerja;
use App\Models\Pegawai;
use App\Models\PelatihanPegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pengguna = Auth::user();

        if($pengguna->role === 'pegawai'){
            $totalIzin = Absensi::where('user_id', $pengguna->id)->where('keterangan', 'izin')->count();
            $totalSakit = Absensi::where('user_id', $pengguna->id)->where('keterangan', 'sakit')->count();
            $totalPelatihan = PelatihanPegawai::where('user_id', $pengguna->id)->count();
            return view("pages.dashboard.index", compact('totalPelatihan', 'totalIzin', 'totalSakit'));
        }elseif($pengguna->role === 'admin'){
            $totalPegawai = Pegawai::all()->count();
            $totalAkun = User::all()->count();
            $totalSemuaLaporan = LaporanKinerja::all()->count();
            return view("pages.dashboard.index", compact('totalSemuaLaporan', 'totalPegawai', 'totalAkun'));
        }elseif($pengguna->role === 'tu'){
            $laporanBelumDiVerifikasi = LaporanKinerja::where('is_verify', 0)->get()->count();
            $laporanAcc = LaporanKinerja::where('is_verify', 2)->get()->count();
            $laporanTolak = LaporanKinerja::where('is_verify', 2)->get()->count();
            $totalSemuaLaporan = LaporanKinerja::all()->count();
            return view("pages.dashboard.index", compact('totalSemuaLaporan', 'laporanBelumDiVerifikasi', 'laporanAcc', 'laporanTolak'));
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function doc()
    {
        return view("pages.doc");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
