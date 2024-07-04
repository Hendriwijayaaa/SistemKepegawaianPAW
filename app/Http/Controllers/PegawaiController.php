<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pengguna = Auth::user();
        if($pengguna->role === "admin"){
            $data_get = Pegawai::all();
            return view("pages.pegawai.index", compact("data_get"));
        }else {
            $data_get = $pengguna ? Pegawai::where('id', $pengguna->pegawai_id)->get() :collect();
            return view("pages.pegawai.index", compact("data_get"));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("pages.pegawai.tambah");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $validateData = $request->validate([
                'nama_lengkap' => 'required',
                'no_kta' => 'required',
                'email' => 'required',
                'tgl_lahir' => 'required',
                'jenis_kelamin' => 'required',
                'tempat_lahir' => 'required',
                'jabatan' => 'required',
                'no_hp' => 'required',
                'tanggal_bergabung' => 'required',
                'alamat' => 'required',
                'status' => 'required',
        ]);

        $checkedKta = Pegawai::where('no_kta', $validateData['no_kta'])->first();

        if($checkedKta){
            return back()->with('error', "Nomor KTA Sudah Terdaftar !");
        }

        $checkedEmail = Pegawai::where('email', $validateData['email'])->first();

        if($checkedEmail){
            return back()->with('error', 'Email Sudah Terdaftar');
        }

        $checkedNoHp = Pegawai::where('no_hp', $validateData['no_hp'])->first();

        if($checkedNoHp){
            return back()->with('error', 'Nomor HP Sudah Terdaftar');
        }

        try {
            DB::beginTransaction();
            $push_data = new Pegawai();
            $push_data->nama_lengkap = $validateData['nama_lengkap'];
            $push_data->tgl_lahir = $validateData['tgl_lahir'];
            $push_data->no_kta = $validateData['no_kta'];
            $push_data->email = $validateData['email'];
            $push_data->tempat_lahir = strtolower($validateData['tempat_lahir']);
            $push_data->jabatan = strtolower($validateData['jabatan']);
            $push_data->tanggal_bergabung = $validateData['tanggal_bergabung'];
            $push_data->alamat = strtolower($validateData['alamat']);
            $push_data->no_hp = $validateData['no_hp'];
            $push_data->jenis_kelamin = $validateData['jenis_kelamin'];
            $push_data->status = $validateData['status'];
            $push_data->save();
            DB::commit();
            return redirect()->route('pegawai.index')->with('success', 'Data Pegawai Dengan Nama ' . $validateData['nama_lengkap'] . ' Berhasil Disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage()); // Kembalikan pesan kesalahan aktual untuk debug
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pegawai  $pegawai
     * @return \Illuminate\Http\Response
     */
    public function show(Pegawai $pegawai)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pegawai  $pegawai
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $get_data = Pegawai::find($id);
        return view("pages.pegawai.edit", compact('get_data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pegawai  $pegawai
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $push_data = Pegawai::find($id);

        // Periksa apakah data pegawai ditemukan
        if (!$push_data) {
            return back()->with('error', 'Data Pegawai tidak ditemukan');
        }

        $validateData = $request->validate([
            'nama_lengkap' => 'required',
                        'no_kta' => 'required',
                        'email' => 'required',
                        'tgl_lahir' => 'required',
                        'jenis_kelamin' => 'required',
                        'tempat_lahir' => 'required',
                        'jabatan' => 'required',
                        'no_hp' => 'required',
                        'tanggal_bergabung' => 'required',
                        'alamat' => 'required',
                        'status' => 'required',
        ]);

        $checkedNoKta = Pegawai::where('no_kta', $validateData['no_kta'])->where('id', '!=', $id)->get();

        if($checkedNoKta->count() > 0){
            return back()->with('error', 'Nomor KTA Sudah Terdaftar');
        }

        $checkedEmail = Pegawai::where('email', $validateData['email'])->where('id', '!=', $id)->get();

        if($checkedEmail->count() > 0){
            return back()->with('error', 'Email Sudah Terdaftar');
        }

        $checkedNoHp = Pegawai::where('no_hp', $validateData['no_hp'])->where('id', '!=', $id)->get();

        if($checkedNoHp->count() > 0){
            return back()->with('error', 'Nomor Handphone Sudah Terdaftar');
        }

        DB::beginTransaction();


        try {
            $push_data->nama_lengkap = $validateData['nama_lengkap'];
            $push_data->tgl_lahir = $validateData['tgl_lahir'];
            $push_data->no_kta = $validateData['no_kta'];
            $push_data->tempat_lahir = strtolower($validateData['tempat_lahir']);
            $push_data->jabatan = strtolower($validateData['jabatan']);
            $push_data->email = $validateData['email'];
            $push_data->tanggal_bergabung = $validateData['tanggal_bergabung'];
            $push_data->alamat = strtolower($validateData['alamat']);
            $push_data->jenis_kelamin = $validateData['jenis_kelamin'];
            $push_data->status = $validateData['status'];
            $push_data->no_hp = $validateData['no_hp'];
            $push_data->save();

            // $pegawai = Pegawai::find($id);
            // dd($pegawai);
            $updateUser = User::where('pegawai_id', $id)->first();
            // dd($updateUser);

            if($updateUser){
                $updateUser->nama = $validateData['nama_lengkap'];
                $updateUser->email = $validateData['email'];
                // dd($updateUser);
                $updateUser->save();
            }

            DB::commit();
            return redirect()->route('pegawai.index')->with('success', 'Data Pegawai Dengan Nama '. $validateData['nama_lengkap'] .' Berhasil Diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pegawai  $pegawai
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $get_data = Pegawai::find($id);
        $get_data->delete();
        return redirect()->route('pegawai.index')->with('success', 'Data Berhasil Dihapus');
    }
}