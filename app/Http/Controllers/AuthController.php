<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Mail\SendVerifikasiAkun;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Token;
use JWTAuth;
use JWTFactory;
class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function activeMail($email, $token)
    {
        $datas = User::where('email', $email)->where('is_verify', false)->first();

        if (!$datas) {
            return redirect()->route('login.index')->with('error', 'Tautan aktivasi tidak valid atau telah kedaluwarsa.');
        }

        try {
            // Buat instance dari Token menggunakan token yang sudah di-decode
            $jwtToken = new Token($token);
            $payload = JWTAuth::decode($jwtToken);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return redirect()->route('login.index')->with('error', 'Tautan aktivasi tidak valid atau telah kedaluwarsa.');
        }

        $now = \Carbon\Carbon::now();
        $tokenExpiration = \Carbon\Carbon::createFromTimestamp($payload['exp']);

        if ($now->gt($tokenExpiration)) {
            return redirect()->route('login.index')->with('error', 'Tautan aktivasi telah kedaluwarsa.');
        }

        $datas->is_verify = true;
        $datas->update();

        return redirect()->route('login.index')->with('success', 'Akun berhasil diaktifkan.');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
       if (Auth::check()) {
        // Jika pengguna sudah login, alihkan ke halaman yang sesuai
        return redirect()->route('dashboard');
    }
        // Jika belum login, tampilkan halaman login
        return view("auth.login");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function createLogin(Request $request)
    {
        $validateData = $request->validate([
            'no_kta' => 'required',
            'password' => 'required',
        ], [
            'no_kta.required' => 'Nomor KTA tidak boleh kosong!',
            'password.required' => 'Password tidak boleh kosong!',
        ]);

        $getDataKTA = Pegawai::where('no_kta', intval($validateData['no_kta']))->first();

            if (!$getDataKTA) {
                return redirect()->route('login.index')->with('error', 'Anda Tidak Di Kenali Dan tidak terdaftar.');
            }

            // Cari user berdasarkan pegawai_id
            $checkedEmail = User::where('pegawai_id', $getDataKTA->id)->first();

            if (!$checkedEmail) {
                return redirect()->route('login.index')->with('error', 'Anda Tidak Di Kenali Dan tidak terdaftar.');
            }

            // Periksa password
            if (!Hash::check($validateData['password'], $checkedEmail->password)) {
                return redirect()->route('login.index')->with('error', 'Password Anda Salah');
            }
             if ($getDataKTA->status !== 'aktif') {
                return redirect()->route('login.index')->with('error', 'Status pegawai Anda tidak aktif.');
            }

        if ($checkedEmail->is_verify === 1) {
                    if ($checkedEmail->status === 'aktif') {
                        Auth::login($checkedEmail);

                        if (Auth::check()) {
                            // Jika pengguna sudah login, alihkan ke halaman yang sesuai
                            return $this->redirectToAuthenticatedUser($checkedEmail);
                        }
                    } elseif ($checkedEmail->status === 'tidak aktif') {
                        return redirect()->route('login.index')->with('error', 'Akun Anda Di Non Aktifkan. Silahkan Hubungi Pihak Administrator');
                    } elseif ($checkedEmail->status === 'banned') {
                        return redirect()->route('login.index')->with('error', 'Akun Anda Terkena Banned');
                    } else {
                        return redirect()->route('login.index')->with('error', 'Akun Anda Tidak Valid');
                    }
                } else {
                    return redirect()->route('login.index')->with('error', 'Akun Anda Belum Diverifikasi');
                }
    }

    private function redirectToAuthenticatedUser($user)
    {
        if ($user->role === 'admin' ||
            $user->role === 'hrd' ||
            $user->role === 'karyawan') {
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('login.index')->with('error', 'Anda Tidak Termasuk Kedalam Pengelola Data Ini, Silahkan Hubungi Pihak Kami Jika Merasa Memang Anda Termasuk Petugas Pengelola Ini.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login.index');
    }
    public function destroy($id)
    {
        $pengguna = User::find($id);
        $pengguna->delete();
        return redirect()->route('masterakun.index')->with("success", "Data Berhasil Di Hapus");
    }


        public function masterAkun()
    {
        $data_get = User::all();
        return view("pages.masterakun.index", compact('data_get'));
    }
    public function masterAkunCreate()
    {
        $getPegawai = Pegawai::all();
        return view("pages.masterakun.tambah", compact("getPegawai"));
    }
    public function udpateTing($id)
    {
        $data_get = User::find($id);
        $getPegawai = Pegawai::where('no_kta', '!=', $data_get->pegawai->no_kta)->get();
        // dd($getPegawai);
        return view("pages.masterakun.edit", compact("data_get", "getPegawai"));
    }

    public function masterAkunStore(Request $request)
    {
        $validateData = $request->validate([
            'pegawai_id' => 'required|integer',
            'password' => [
                'required',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/'
            ],
            'role' => 'required',
            'status' => 'required',
        ], [
            'password.required' => 'Password Tidak Boleh Kosong!',
            'password.regex' => 'Password harus minimal 8 karakter, mengandung huruf besar, huruf kecil, dan angka.',
            'status.required' => 'Status Akun Tidak Boleh Kosong!',
        ]);

        $checkedEmail = User::where('pegawai_id', intval($validateData['pegawai_id']))->first();

        if($checkedEmail){
            return back()->with('error', 'Akun Sudah Terdaftar');
        }

        $getPegawai = Pegawai::find(intval($validateData['pegawai_id']));

        // dd($getPegawai);

        $register = new User();
        $register->pegawai_id = $getPegawai->id;
        $register->nama = $getPegawai->nama_lengkap ?? null;
        $register->email = $getPegawai->email ?? null;
        $register->role = $validateData['role'];
        $register->status = $validateData['status'];
        $register->password = Hash::make($validateData['password']);
        DB::beginTransaction();

        try {
            $payload = JWTFactory::sub($register->id)->exp(now()->addHours(6)->timestamp)->make();
            $token = JWTAuth::encode($payload)->get();
            Mail::to($getPegawai->email)->send(new SendVerifikasiAkun($getPegawai->email, $token));
            $register->save();
            DB::commit();
            return redirect()->route("masterakun.index")->with('success', 'Untuk Melanjutkan Aktivasi Silahkan Check Email Anda');
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->route("masterakun.index")->with('error', $e->getMessage());
        }

    }
    public function masterAkunUpdateting(Request $request, $id)
    {
         $register = User::find($id);

           $validateData = $request->validate([
            'pegawai_id' => 'required|integer',
            'password' => [
                'required',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/'
            ],
            'role' => 'required',
            'status' => 'required',
        ], [
            'password.required' => 'Password Tidak Boleh Kosong!',
            'password.regex' => 'Password harus minimal 8 karakter, mengandung huruf besar, huruf kecil, dan angka.',
            'status.required' => 'Status Akun Tidak Boleh Kosong!',
        ]);

         $existingPegawai = User::where('pegawai_id', intval($validateData['pegawai_id']))
                        ->where('id', '!=', $register->id)
                        ->first();

        // dd($existingPegawai);

        if ($existingPegawai) {
            return back()->with('error', 'Akun sudah terdaftar untuk pegawai lain.');
        }

        $getPegawai = Pegawai::where('id', intval($validateData['pegawai_id']))->first();
        // dd($getPegawai);

        $register->pegawai_id = $getPegawai->id ?? null;
        $register->nama = $getPegawai->nama_lengkap ?? null;
        $register->email = $getPegawai->email ?? null;
        $register->role = $validateData['role'];
        $register->status = $validateData['status'];
        $register->password = Hash::make($validateData['password']);
        // dd($register);

        try {
            DB::beginTransaction();
            $register->update();
            DB::commit();
            return redirect()->route("masterakun.index")->with('success', 'Berhasil DIupdate');
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->route("masterakun.index")->with('error', $e->getMessage());
        }

    }
}
