<?php

namespace App\Http\Controllers;

use App\Models\LaporanKinerja;
use App\Models\User;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use App\Models\EvaluasiLaporan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EvaluasiLaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pengguna = Auth::user();
        if($pengguna->role === 'tu'){
            $data_get = EvaluasiLaporan::all();
            return view("pages.evaluasi-kinerja.index", compact("data_get"));
        }elseif($pengguna->role === 'pegawai'){
            // dd();
            $data_get = $pengguna ? EvaluasiLaporan::where('user_id', $pengguna->id)->get() : collect();
            return view("pages.evaluasi-kinerja.index", compact("data_get"));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
        public function store(Request $request, $id)
        {
            $pengguna = Auth::user();

           if($pengguna->role === "tu"){
                if(empty($request->nilai_pegawai)){
                    return back()->with('error', "Nilai Pegawai Tidak Boleh Kosong !");
                }

                if(empty($request->nomor_kta)){
                    return back()->with('error', "Nilai Pegawai Tidak Boleh Kosong !");
                }

                if(empty($request->password)){
                    return back()->with('error', "Nilai Pegawai Tidak Boleh Kosong !");
                }

                $kta = $request->nomor_kta;
                $pass = $request->password;

                $getDataKTA = Pegawai::where('no_kta', $kta)->first();

                if (!$getDataKTA) {
                    return back()->with('error', 'Anda Tidak Di Kenali Dan tidak terdaftar.');
                }

                $checkedEmail = User::where('pegawai_id', $getDataKTA->id)->first();

                if (!$checkedEmail) {
                    return back()->with('error', 'Anda Tidak Di Kenali Dan tidak terdaftar.');
                }

                if (!Hash::check($pass, $checkedEmail->password)) {
                    return back()->with('error', 'Password Anda Salah');
                }


                $dataIdAllPegawai = [];
                $idUser = null;
                $getDataLaporanKinerja = LaporanKinerja::where('pegawai_id', $id)->get();
            // dd($getDataLaporanKinerja);
                foreach($getDataLaporanKinerja as $index => $data){
                $idUser = $data->user_id;
                // dd($idUser);
                    $dataIdAllPegawai[$index] =[
                        'id' => $data->id,
                        'id_pegawai' => $data->pegawai_id,
                        'id_user' => $data->user_id,
                    ];
                }

            // dd($idUser,$dataIdAllPegawai);

                DB::beginTransaction();
                try {
                    $push_data = new EvaluasiLaporan();
                    $push_data->semua_id = json_encode($dataIdAllPegawai);
                    $push_data->nilai_pegawai = $request->nilai_pegawai;
                    $push_data->user_id = $idUser;
                    $push_data->save();
                    foreach ($getDataLaporanKinerja as $value){
                        $value->is_nilai = true;
                        $value->save();
                    }
                    DB::commit();
                    return redirect()->route('evaluation.index')->with('success', "Data Berhasil Di Simpan");
                }catch(\Exception $e){
                    DB::rollBack();
                return back()->with('error', $e->getMessage());
                }
           }else {
            return back() - with('error', "Anda Tidak Di Kenali");
           }
        }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EvaluasiLaporan  $evaluasiLaporan
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pengguna = Auth::user();

        if($pengguna->role === 'tu' || $pengguna->role === 'pegawai'){
             $data_get = EvaluasiLaporan::where('id', $id)->first();
            // dd($data_get);
             return view("pages.evaluasi-kinerja.detail", compact("data_get"));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EvaluasiLaporan  $evaluasiLaporan
     * @return \Illuminate\Http\Response
     */
    public function edit(EvaluasiLaporan $evaluasiLaporan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EvaluasiLaporan  $evaluasiLaporan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EvaluasiLaporan $evaluasiLaporan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EvaluasiLaporan  $evaluasiLaporan
     * @return \Illuminate\Http\Response
     */
    public function destroy(EvaluasiLaporan $evaluasiLaporan)
    {
        //
    }
}
