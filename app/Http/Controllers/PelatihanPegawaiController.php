<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Absensi;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\PelatihanPegawai;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PelatihanPegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            date_default_timezone_set('Asia/Jakarta');

            $now = Carbon::now('Asia/Jakarta');

            $tanggalTerkahirBulan = $now->endOfMonth()->format('d');
            // $tanggalSaatIni = Carbon::now()->format('d');
            $tanggalSaatIni = '31';
            // dd($tanggalTerkahirBulan, $tanggalSaatIni);

            $pegawais = Pegawai::all();
            $pegawaiIds = $pegawais->pluck('id')->toArray();

            // Mendapatkan semua tanggal dalam bulan saat ini
            $startOfMonth = $now->copy()->startOfMonth();
            $endOfMonth = $now->copy()->endOfMonth();

            $dates = [];
            $workingDays = 0;  // Menyimpan jumlah hari kerja
            $attendedDays = []; // Menyimpan jumlah hari hadir per pegawai

            foreach ($pegawaiIds as $pegawaiId) {
                $attendedDays[$pegawaiId] = 0;
            }

            for ($date = $startOfMonth; $date->lte($endOfMonth); $date->addDay()) {
                $dayOfWeek = $date->dayOfWeek;
                $formattedDate = $date->format('Y-m-d');

                // Menyimpan tanggal dalam array
                $dates[] = [
                    'day' => $date->day,
                    'date' => $formattedDate,
                    'isWeekend' => ($dayOfWeek === Carbon::SATURDAY || $dayOfWeek === Carbon::SUNDAY),
                    'dayName' => $dayOfWeek === Carbon::SUNDAY ? 'L' : ($dayOfWeek === Carbon::SATURDAY ? 'L' : null)
                ];

                // Hanya hitung hari kerja
                if (!$dates[count($dates) - 1]['isWeekend']) {
                    $workingDays++;

                    // Cek kehadiran pada hari kerja
                    foreach ($pegawaiIds as $pegawaiId) {
                        $absensiHariIni = Absensi::where('pegawai_id', $pegawaiId)->where('tanggal', $formattedDate)->get();
                        $absenMasuk = $absensiHariIni->where('jenis_absen', 'masuk')->first();
                        $absenPulang = $absensiHariIni->where('jenis_absen', 'pulang')->first();

                        if ($absenMasuk && $absenPulang) {
                            $attendedDays[$pegawaiId]++;
                        }
                    }
                }
            }

                        $lowAttendanceEmployees = [];

            foreach ($attendedDays as $pegawaiId => $days) {
                $attendancePercentage = $workingDays > 0 ? ($days / $workingDays) * 100 : 0;
                if ($attendancePercentage < 80) {
                    $lowAttendanceEmployees[] = [
                        'pegawai_id' => $pegawaiId,
                        'persentase' => $attendancePercentage,
                    ];
                }
            }

            $pegawaiIds = [];
            foreach ($lowAttendanceEmployees as $employee) {
                $pegawaiIds[] = [
                    'pegawai_id' => $employee['pegawai_id'],
                    'persentase_kehadiran' => $employee['persentase']
                ];
            }

            $pegawaiDonExtarctId = array_column($pegawaiIds, 'pegawai_id');

            $getDataUpdate = Absensi::whereIn('pegawai_id', $pegawaiDonExtarctId)->get();

            $pegawaiIdUpdate = $getDataUpdate->pluck('pegawai_id');

            $uniquePegawaiIds = Absensi::whereIn('pegawai_id', $pegawaiIdUpdate)
                ->select(DB::raw('MAX(id) as id'))
                ->groupBy('pegawai_id')
                ->pluck('id');

            $data_get = Absensi::whereIn('id', $uniquePegawaiIds)->get();
            $data_get->transform(function ($item) use ($pegawaiIds) {
                // Cari pegawai berdasarkan pegawai_id
                $user = Pegawai::find($item->pegawai_id);

                // Tambahkan detail pengguna
                $item['nama_pengguna'] = $user ? $user->nama_lengkap : 'Tidak Ditemukan';
                $item['jabatan'] = $user ? $user->jabatan : 'Tidak Ditemukan';

                // Cari persentase kehadiran dari array awal
                $persentase = collect($pegawaiIds)->firstWhere('pegawai_id', $item->pegawai_id)['persentase_kehadiran'];
                $item['persentase_kehadiran'] = $persentase;

                return $item;
            });

            // dd($data_get);

            return view("pages.pelatihan.index", compact("data_get",'tanggalSaatIni','tanggalTerkahirBulan'));
        }catch(\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }


        private function convertKeterangan($keterangan)
    {
        $keterangan = strtolower($keterangan);
        if ($keterangan == 'hadir') {
                return 'H';
        } elseif ($keterangan == 'izin') {
                return 'I';
        } elseif ($keterangan == 'sakit') {
                return 'S';
        } else {
                return 'A';
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllPelatihan()
    {
        $pengguna = Auth::user();

        if($pengguna->role === "tu"){
            $data_get = PelatihanPegawai::all();
            $data_get->transform(function ($item) {
// dd($item);
                $getUser = Absensi::find($item->absen_id);
                $user = $getUser ? Pegawai::find($getUser->pegawai_id) : collect();


                $item['nama_pengguna'] = $user ? $user->nama_lengkap : 'Tidak Ditemukan';
                $item['jabatan'] = $user ? $user->jabatan : 'Tidak Ditemukan';

                return $item;
            });

            return view("pages.pelatihan.detail", compact("data_get"));
        }elseif($pengguna->role === "admin" || $pengguna->role === "pegawai"){
            $getIdAbsensi = Absensi::where('user_id', $pengguna->id)->first();

            $data_get = PelatihanPegawai::where('user_id', $getIdAbsensi->user_id)->get();

            $data_get->transform(function ($item) {
                $getUser = Absensi::find($item->absen_id);
                $user = $getUser ? Pegawai::find($getUser->pegawai_id) : collect();


                $item['nama_pengguna'] = $user ? $user->nama_lengkap : 'Tidak Ditemukan';
                $item['jabatan'] = $user ? $user->jabatan : 'Tidak Ditemukan';

                return $item;
            });

            return view("pages.pelatihan.detail", compact("data_get"));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(empty($request->pelatihan_deksripsi)){
            return back()->with('error', "Deskripsi Pelatihan Tidak Boleh Kosong !");
        }

        DB::beginTransaction();

        try {
            $push_data = new PelatihanPegawai();
            $push_data->absen_id = $request->absen_id;
            $push_data->user_id = $request->user_id;
            $push_data->pelatihan_deksripsi = $request->pelatihan_deksripsi;
            $push_data->save();
            DB::commit();
            return back()->with('success', "Data Berhasil Di Kirimkan");
        }catch(\Exception $e){
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PelatihanPegawai  $pelatihanPegawai
     * @return \Illuminate\Http\Response
     */
    public function show(PelatihanPegawai $pelatihanPegawai)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PelatihanPegawai  $pelatihanPegawai
     * @return \Illuminate\Http\Response
     */
    public function edit(PelatihanPegawai $pelatihanPegawai)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PelatihanPegawai  $pelatihanPegawai
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PelatihanPegawai $pelatihanPegawai)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PelatihanPegawai  $pelatihanPegawai
     * @return \Illuminate\Http\Response
     */
    public function destroy(PelatihanPegawai $pelatihanPegawai)
    {
        //
    }
}