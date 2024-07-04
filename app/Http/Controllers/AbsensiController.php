<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use App\Models\Absensi;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function test()
    {
        date_default_timezone_set('Asia/Jakarta');

        $now = Carbon::now('Asia/Jakarta');
        $jamMulaiMasuk = strtotime("06:15");
        $jamMulaiPulang = strtotime("13:00");
        $jamBatasAbsenPulang = strtotime("17:30");
        $jamBatasAbsenMasuk = strtotime("12:55");
        // $jamAbsen = strtotime($now->format('H:i'));
        $jamAbsen = strtotime("16:08");

        if($jamAbsen >= $jamMulaiMasuk && $jamAbsen <= $jamBatasAbsenMasuk){

        $currentDate = Carbon::now()->toDateString();

        // 2. Ambil semua data pegawai dari tabel `pegawai`.
        $getPegawai = Pegawai::all();

        // 3. Ambil semua data absensi dari tabel `absensi` untuk tanggal yang sama dengan tanggal saat ini.
        $getDataAbsen = Absensi::whereDate('tanggal', $currentDate)
            ->whereIn('jenis_absen', ['masuk', 'masuk-pulang'])
            ->pluck('pegawai_id')->all();


        // 4. Buat larik yang berisi ID pegawai yang sudah masuk absensi.
        $pegawaiSudahAbsen = array_unique($getDataAbsen);

        $userPegawaiIds = User::where('role', '!=', 'admin')->pluck('pegawai_id')->all();
        $pegawaiBelumAbsen = [];

        foreach ($getPegawai as $pegawai) {
             $user = User::where('pegawai_id', $pegawai->id)->where('role', '!=', 'admin')->first();
            if (!in_array($pegawai->id, $pegawaiSudahAbsen) && in_array($pegawai->id, $userPegawaiIds)) {
                $pegawaiData = $pegawai->toArray();
                $pegawaiData['user_id'] = $user->id;
                $pegawaiBelumAbsen[] = $pegawaiData;
            }
        }

        foreach($pegawaiBelumAbsen as $pegawai){

                    $push_data_absen = new Absensi();
                    $push_data_absen->pegawai_id = $pegawai['id'];
                    $push_data_absen->user_id = $pegawai['user_id'];
                    $push_data_absen->tanggal = Carbon::now()->format('Y-m-d');
                    $push_data_absen->jenis_absen = "masuk";
                    $push_data_absen->jam_absen = $now->format('H:i');
                    $push_data_absen->keterangan = "tidak hadir";
                    $push_data_absen->save();
        }


    }elseif($jamAbsen >= $jamMulaiPulang && $jamAbsen <= $jamBatasAbsenPulang){
        $currentDate = Carbon::now()->toDateString();

                // 2. Ambil semua data pegawai dari tabel `pegawai`.
        $getPegawai = Pegawai::all();

                // 3. Ambil semua data absensi dari tabel `absensi` untuk tanggal yang sama dengan tanggal saat ini.
        $getDataAbsen = Absensi::whereDate('tanggal', $currentDate)
                    ->whereIn('jenis_absen', ['pulang', 'masuk-pulang'])
                    ->pluck('pegawai_id')->all();

                // 4. Buat larik yang berisi ID pegawai yang sudah masuk absensi.
        $pegawaiSudahAbsen = array_unique($getDataAbsen);

        $userPegawaiIds = User::where('role', '!=', 'admin')->pluck('pegawai_id')->all();
        $pegawaiBelumAbsen = [];

        foreach ($getPegawai as $pegawai) {
                    $user = User::where('pegawai_id', $pegawai->id)->where('role', '!=', 'admin')->first();
                    if (!in_array($pegawai->id, $pegawaiSudahAbsen) && in_array($pegawai->id, $userPegawaiIds)) {
                        $pegawaiData = $pegawai->toArray();
                        $pegawaiData['user_id'] = $user->id;
                        $pegawaiBelumAbsen[] = $pegawaiData;
                    }
        }

        foreach($pegawaiBelumAbsen as $pegawai){
                $push_data_absen = new Absensi();
                $push_data_absen->pegawai_id = $pegawai['id'];
                $push_data_absen->user_id = $pegawai['user_id'];
                $push_data_absen->tanggal = Carbon::now()->format('Y-m-d');
                $push_data_absen->jenis_absen = "pulang";
                $push_data_absen->jam_absen = $now->format('H:i');
                $push_data_absen->keterangan = "tidak hadir";
                $push_data_absen->save();
        }
    }

    }


    public function index()
    {
        $userId = Auth::id();
        date_default_timezone_set('Asia/Jakarta');
        $today = Carbon::now()->toDateString();
        $now = Carbon::now('Asia/Jakarta');
        $jamMasuk = strtotime("08:00");
        $jamMulaiMasuk = strtotime("06:15");
        $jamMulaiPulang = strtotime("13:00");
        $jamPulang = strtotime("16:00");
        $jamBatasAbsenPulang = strtotime("17:30");
        $jamBatasAbsenMasuk = strtotime("12:55");
        $jamAbsen = strtotime($now->format('H:i'));
        $absenHariIni = Absensi::where('user_id', $userId)
                ->whereDate('tanggal', $today)->exists();
        // $jamAbsen = strtotime("17:00");
        return view("pages.absensi.index", compact("jamMasuk", "jamMulaiMasuk", "jamMulaiPulang",
        "jamPulang", "jamBatasAbsenPulang", "jamBatasAbsenMasuk", "jamAbsen", 'absenHariIni'));
    }

    public function detailkehadiran()
    {
        $pengguna = Auth::user();

        if($pengguna->role === 'pegawai' || $pengguna->role === 'tu'){
            date_default_timezone_set('Asia/Jakarta');

            // Mendapatkan tanggal saat ini
            $now = Carbon::now('Asia/Jakarta');

            // Mendapatkan pengguna yang sedang login
            $pengguna = Auth::user();

            // Mengambil data absensi pengguna
            $getData = Absensi::where('user_id', $pengguna->id)->get();
            // dd($getData);

            // Mendapatkan semua tanggal dalam bulan saat ini
            $startOfMonth = $now->copy()->startOfMonth();
            $endOfMonth = $now->copy()->endOfMonth();

            $dates = [];
            $dataAbsenMasuk = [];
            $dataAbsenPulang = [];
            $workingDays = 0;  // Menyimpan jumlah hari kerja
            $attendedDays = 0; // Menyimpan jumlah hari hadir

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
                    $absensiHariIni = $getData->filter(function ($absensi) use ($formattedDate) {
                        return $absensi->tanggal == $formattedDate;
                    });

                    $absensiMasuk = $absensiHariIni->firstWhere('jenis_absen', 'masuk');
                    $absensiPulang = $absensiHariIni->firstWhere('jenis_absen', 'pulang');
                    $absensiMasukPulang = $absensiHariIni->firstWhere('jenis_absen', 'masuk-pulang');

                    // Definisikan simbol berdasarkan keterangan
                    if ($absensiMasukPulang) {
                        $keterangan = $this->convertKeterangan($absensiMasukPulang->keterangan);
                        $dataAbsenMasuk[$formattedDate] = $keterangan;
                        $dataAbsenPulang[$formattedDate] = $keterangan;

                        if ($keterangan == 'H') {
                            $attendedDays++;
                        }
                    } else {
                        $keteranganMasuk = $absensiMasuk ? $this->convertKeterangan($absensiMasuk->keterangan) : 'A';
                        $keteranganPulang = $absensiPulang ? $this->convertKeterangan($absensiPulang->keterangan) : 'A';

                        $dataAbsenMasuk[$formattedDate] = $keteranganMasuk;
                        $dataAbsenPulang[$formattedDate] = $keteranganPulang;

                        if ($keteranganMasuk == 'H' && $keteranganPulang == 'H') {
                            $attendedDays++;
                        }
                    }
                } else {
                    // Weekend
                    $dataAbsenMasuk[$formattedDate] = 'L';
                    $dataAbsenPulang[$formattedDate] = 'L';
                }
            }

            // Hitung persentase kehadiran
            $attendancePercentage = $workingDays > 0 ? ($attendedDays / $workingDays) * 100 : 0;

            // Mengirim data ke view
            return view('pages.absensi.detail', compact('getData', 'now', 'dates', 'pengguna', 'dataAbsenMasuk', 'dataAbsenPulang', "attendancePercentage"));
        }elseif($pengguna->role === 'admin'){
            date_default_timezone_set('Asia/Jakarta');

            $now = Carbon::now('Asia/Jakarta');

            // Mendapatkan semua tanggal dalam bulan saat ini
            $startOfMonth = $now->copy()->startOfMonth();
            $endOfMonth = $now->copy()->endOfMonth();

            $pegawais = Pegawai::all();
            $pegawaiIds = $pegawais->pluck('id')->toArray();

            $dates = [];
            $dataAbsenMasuk = [];
            $dataAbsenPulang = [];
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

                // Initialize with default values
                foreach ($pegawaiIds as $pegawaiId) {
                    $dataAbsenMasuk[$pegawaiId][$formattedDate] = '-';
                    $dataAbsenPulang[$pegawaiId][$formattedDate] = '-';
                }

                // Hanya hitung hari kerja
                if (!$dates[count($dates) - 1]['isWeekend']) {
                    $workingDays++;

                    // Cek kehadiran pada hari kerja
                    foreach ($pegawaiIds as $pegawaiId) {
                        $absensiHariIni = Absensi::where('pegawai_id', $pegawaiId)->where('tanggal', $formattedDate)->get();
                        $absenMasuk = $absensiHariIni->where('jenis_absen', 'masuk')->first();
                        $absenPulang = $absensiHariIni->where('jenis_absen', 'pulang')->first();
                        $absenMasukPulang = $absensiHariIni->where('jenis_absen', 'masuk-pulang')->first();

                        // Definisikan simbol berdasarkan keterangan
                        if ($absenMasukPulang) {
                            $keterangan = $this->convertKeterangan($absenMasukPulang->keterangan);
                            $dataAbsenMasuk[$pegawaiId][$formattedDate] = $keterangan;
                            $dataAbsenPulang[$pegawaiId][$formattedDate] = $keterangan;

                            if ($keterangan == 'H') {
                                $attendedDays[$pegawaiId]++;
                            }
                        } else {
                            $keteranganMasuk = $absenMasuk ? $this->convertKeterangan($absenMasuk->keterangan) : 'A';
                            $keteranganPulang = $absenPulang ? $this->convertKeterangan($absenPulang->keterangan) : 'A';

                            $dataAbsenMasuk[$pegawaiId][$formattedDate] = $keteranganMasuk;
                            $dataAbsenPulang[$pegawaiId][$formattedDate] = $keteranganPulang;

                            if ($keteranganMasuk == 'H' && $keteranganPulang == 'H') {
                                $attendedDays[$pegawaiId]++;
                            }
                        }
                    }
                } else {
                    // Weekend
                    foreach ($pegawaiIds as $pegawaiId) {
                        $dataAbsenMasuk[$pegawaiId][$formattedDate] = 'L';
                        $dataAbsenPulang[$pegawaiId][$formattedDate] = 'L';
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

            $getData = Absensi::whereIn('id', $uniquePegawaiIds)->get();
            $getData->transform(function ($item) use ($pegawaiIds) {
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

            return view('pages.absensi.detail', compact('getData', 'dates', 'dataAbsenMasuk', 'dataAbsenPulang'));

        }
    }

    public function detailkehadiranSearch(Request $request)
    {
        $pengguna = Auth::user();

        if($pengguna->role === 'tu' || $pengguna->role === 'pegawai'){
             date_default_timezone_set('Asia/Jakarta');
            // Mendapatkan bulan yang dipilih dari input request atau bulan saat ini
            $bulan = $request->input('bulan', Carbon::now('Asia/Jakarta')->month);
            $now = Carbon::now('Asia/Jakarta');
            $pengguna = Auth::user();

            // Mendapatkan tanggal awal dan akhir bulan yang dipilih
            $startOfMonth = Carbon::createFromDate($now->year, $bulan, 1)->startOfMonth();
            $endOfMonth = $startOfMonth->copy()->endOfMonth();

            // Mengambil data absensi pengguna dalam bulan yang dipilih
            $getData = Absensi::where('user_id', $pengguna->id)
                ->whereBetween('tanggal', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')])
                ->get();

            // Jika tidak ada data absensi, kembalikan pesan error
            if ($getData->isEmpty()) {
                return back()->with('error', "Data absensi untuk bulan yang dipilih tidak ditemukan.");
            }

            $dates = [];
            $dataAbsenMasuk = [];
            $dataAbsenPulang = [];
            $workingDays = 0;  // Menyimpan jumlah hari kerja
            $attendedDays = 0; // Menyimpan jumlah hari hadir

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
                    $absensiHariIni = $getData->filter(function ($absensi) use ($formattedDate) {
                        return $absensi->tanggal == $formattedDate;
                    });

                    $absensiMasuk = $absensiHariIni->firstWhere('jenis_absen', 'masuk');
                    $absensiPulang = $absensiHariIni->firstWhere('jenis_absen', 'pulang');
                    $absensiMasukPulang = $absensiHariIni->firstWhere('jenis_absen', 'masuk-pulang');

                    // Definisikan simbol berdasarkan keterangan
                    if ($absensiMasukPulang) {
                        $keterangan = $this->convertKeterangan($absensiMasukPulang->keterangan);
                        $dataAbsenMasuk[$formattedDate] = $keterangan;
                        $dataAbsenPulang[$formattedDate] = $keterangan;

                        if ($keterangan == 'H') {
                            $attendedDays++;
                        }
                    } else {
                        $keteranganMasuk = $absensiMasuk ? $this->convertKeterangan($absensiMasuk->keterangan) : 'A';
                        $keteranganPulang = $absensiPulang ? $this->convertKeterangan($absensiPulang->keterangan) : 'A';

                        $dataAbsenMasuk[$formattedDate] = $keteranganMasuk;
                        $dataAbsenPulang[$formattedDate] = $keteranganPulang;

                        if ($keteranganMasuk == 'H' && $keteranganPulang == 'H') {
                            $attendedDays++;
                        }
                    }
                } else {
                    // Weekend
                    $dataAbsenMasuk[$formattedDate] = 'L';
                    $dataAbsenPulang[$formattedDate] = 'L';
                }
            }
            $attendancePercentage = $workingDays > 0 ? ($attendedDays / $workingDays) * 100 : 0;
            return view('pages.absensi.detail', compact('getData', 'now', 'dates', 'pengguna', 'dataAbsenMasuk', 'dataAbsenPulang', 'attendancePercentage'));
        }elseif($pengguna->role === 'admin') {

            try {
                date_default_timezone_set('Asia/Jakarta');

                $bulan = $request->input('bulan', Carbon::now('Asia/Jakarta')->month);
                $now = Carbon::now('Asia/Jakarta');
                $pengguna = Auth::user();

                // Mendapatkan tanggal awal dan akhir bulan yang dipilih
                $startOfMonth = Carbon::createFromDate($now->year, $bulan, 1)->startOfMonth();
                $endOfMonth = $startOfMonth->copy()->endOfMonth();

                $pegawais = Pegawai::all();
                $pegawaiIds = $pegawais->pluck('id')->toArray();

                $dates = [];
                $dataAbsenMasuk = [];
                $dataAbsenPulang = [];
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

                    // Initialize with default values
                    foreach ($pegawaiIds as $pegawaiId) {
                        $dataAbsenMasuk[$pegawaiId][$formattedDate] = '-';
                        $dataAbsenPulang[$pegawaiId][$formattedDate] = '-';
                    }

                    // Hanya hitung hari kerja
                    if (!$dates[count($dates) - 1]['isWeekend']) {
                        $workingDays++;

                        // Cek kehadiran pada hari kerja
                        foreach ($pegawaiIds as $pegawaiId) {
                            $absensiHariIni = Absensi::where('pegawai_id', $pegawaiId)->where('tanggal', $formattedDate)->get();
                            $absenMasuk = $absensiHariIni->where('jenis_absen', 'masuk')->first();
                            $absenPulang = $absensiHariIni->where('jenis_absen', 'pulang')->first();
                            $absenMasukPulang = $absensiHariIni->where('jenis_absen', 'masuk-pulang')->first();

                            // Definisikan simbol berdasarkan keterangan
                            if ($absenMasukPulang) {
                                $keterangan = $this->convertKeterangan($absenMasukPulang->keterangan);
                                $dataAbsenMasuk[$pegawaiId][$formattedDate] = $keterangan;
                                $dataAbsenPulang[$pegawaiId][$formattedDate] = $keterangan;

                                if ($keterangan == 'H') {
                                    $attendedDays[$pegawaiId]++;
                                }
                            } else {
                                $keteranganMasuk = $absenMasuk ? $this->convertKeterangan($absenMasuk->keterangan) : 'A';
                                $keteranganPulang = $absenPulang ? $this->convertKeterangan($absenPulang->keterangan) : 'A';

                                $dataAbsenMasuk[$pegawaiId][$formattedDate] = $keteranganMasuk;
                                $dataAbsenPulang[$pegawaiId][$formattedDate] = $keteranganPulang;

                                if ($keteranganMasuk == 'H' && $keteranganPulang == 'H') {
                                    $attendedDays[$pegawaiId]++;
                                }
                            }
                        }
                    } else {
                        // Weekend
                        foreach ($pegawaiIds as $pegawaiId) {
                            $dataAbsenMasuk[$pegawaiId][$formattedDate] = 'L';
                            $dataAbsenPulang[$pegawaiId][$formattedDate] = 'L';
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

                $getData = Absensi::whereIn('id', $uniquePegawaiIds)->whereMonth('tanggal', $bulan)->get();

                if ($getData->isEmpty()) {
                    return back()->with('error', "Data absensi untuk bulan yang dipilih tidak ditemukan.");
                }

                $getData->transform(function ($item) use ($pegawaiIds) {
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


                return view('pages.absensi.detail', compact('getData', 'dates', 'dataAbsenMasuk', 'dataAbsenPulang'));
            }catch(\Exception $e){
                return back()->with('error', $e->getMessage());
            }
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
    public function absensi(Request $request)
    {
        $pengguna = Auth::user();
        date_default_timezone_set('Asia/Jakarta');
        $now = Carbon::now('Asia/Jakarta');


        $jamMulaiMasuk = strtotime("06:15");
        $jamMulaiPulang = strtotime("13:00");
        $jamBatasAbsenPulang = strtotime("17:30");
        $jamBatasAbsenMasuk = strtotime("12:55");
        $jamAbsen = strtotime($now->format('H:i'));
        // $jamAbsen = strtotime("08:08");


        if($now->isWeekday()){
            if($jamAbsen >= $jamMulaiMasuk && $jamAbsen <= $jamBatasAbsenMasuk){
                DB::beginTransaction();
                try {
                    $push_data_absen = new Absensi();
                    $push_data_absen->pegawai_id = $pengguna->pegawai_id;
                    $push_data_absen->user_id = $pengguna->id;
                    $push_data_absen->tanggal = Carbon::now()->format('Y-m-d');
                    $push_data_absen->jenis_absen = "masuk";
                    $push_data_absen->jam_absen = $now->format('H:i');
                    $push_data_absen->keterangan = "hadir";
                    $push_data_absen->save();
                    DB::commit();
                    return redirect()->route("absensi.detailkehadiran")->with('success', "Absen Masuk Berhasil, Semangat Bekerja !");
                }catch(\Exception $e){
                    DB::rollBack();
                    return back()->with('error', $e->getMessage());
                }
            }elseif($jamAbsen >= $jamMulaiPulang && $jamAbsen <= $jamBatasAbsenPulang){
                DB::beginTransaction();
                try {
                    $push_data_absen = new Absensi();
                    $push_data_absen->pegawai_id = $pengguna->pegawai_id;
                    $push_data_absen->user_id = $pengguna->id;
                    $push_data_absen->tanggal = Carbon::now()->format('Y-m-d');
                    $push_data_absen->jenis_absen = "pulang";
                    $push_data_absen->jam_absen = $now->format('H:i');
                    $push_data_absen->keterangan = "hadir";
                    $push_data_absen->save();
                    DB::commit();
                return redirect()->route("absensi.detailkehadiran")->with('success', "Absen Pulang Berhasil, Hati-Hati Pulang !");
                }catch(\Exception $e){
                    DB::rollBack();
                    return back()->with('error', $e->getMessage());
                }
            }else {
                return back()->with('error', "Belum Bisa Absen, Absen Masuk Dimulai Dari Jam 06:15 Dan Jam Pulang Dimulai Dari Jam 13:00. Terimakasih!");
            }
        }else {
            return back()->with('error', "Terimakasih Atas Royalitas Dan Teladannya, Namun Hari Ini Libur. Hari Kerja Yaitu Senin-Jumat");
        }
    }
    public function absenketeranganlainnya(Request $request)
    {
        $pengguna = Auth::user();
        date_default_timezone_set('Asia/Jakarta');
        $now = Carbon::now('Asia/Jakarta');

        if($now->isWeekday()){
                    if($request->jenis_absen === "izin"){
                $currentMonth = Carbon::now()->month;
                $currentYear = Carbon::now()->year;
                $izinCount = Absensi::where('pegawai_id', $pengguna->pegawai_id)
                            ->where('keterangan', 'izin')
                            ->whereMonth('tanggal', $currentMonth)
                            ->whereYear('tanggal', $currentYear)
                            ->count();

                    if ($izinCount >= 1) {
                        return back()->with('error', "Pengajuan Izin dalam bulan ini sudah mencapai batas maksimal.");
                    }

                    DB::beginTransaction();
                    try {
                        $push_data_absen = new Absensi();
                        $push_data_absen->pegawai_id = 1;
                        $push_data_absen->user_id = 1;
                        $push_data_absen->tanggal = Carbon::now()->format('Y-m-d');
                        $push_data_absen->jenis_absen = "masuk-pulang";
                        $push_data_absen->jam_absen = Carbon::now()->format('H:i');
                        $push_data_absen->keterangan = "izin";
                        $push_data_absen->save();
                        DB::commit();
                        return redirect()->route("absensi.detailkehadiran")->with('success', "Konfirmasi Izin Telah Di Kirimkan");
                    }catch(\Exception $e){
                        DB::rollBack();
                        return back()->with('error', $e->getMessage());
                    }
                }elseif($request->jenis_absen === "sakit"){

                    $currentMonth = Carbon::now()->month;
                    $currentYear = Carbon::now()->year;
                    $sickCount = Absensi::where('pegawai_id', $pengguna->pegawai_id)
                            ->where('keterangan', 'sakit')
                            ->whereMonth('tanggal', $currentMonth)
                            ->whereYear('tanggal', $currentYear)
                            ->count();


                    if(empty($request->file_berkas)){
                                return back()->with('error', "Harap Upload Surat");
                    }

                    if ($sickCount >= 2) {
                        return back()->with('error', "Pengajuan sakit dalam bulan ini sudah mencapai batas maksimal.");
                    }

                    $file = $request->file('file_berkas');

                    $fileName = null;

                    if($file != null){
                            $originalExtension = $file->getClientOriginalExtension();
                            $originalName = $file->getClientOriginalName();

                            $allowedExtension = ['pdf'];
                            if(!in_array($originalExtension, $allowedExtension)){
                                return back()->with('error', 'Tidak Sesuai Format');
                            }

                            $fileName = time() . '_' . $originalName;

                    }else {
                        return redirect()->back()->with('error', 'Berkas Tidak Boleh Kosong');
                    }

                    DB::beginTransaction();
                    try {
                        $push_data_absen = new Absensi();
                        $push_data_absen->pegawai_id = 1;
                        $push_data_absen->user_id = 1;
                        $push_data_absen->tanggal = Carbon::now()->format('Y-m-d');
                        $push_data_absen->jenis_absen = "masuk-pulang";
                        $push_data_absen->jam_absen = Carbon::now()->format('H:i');
                        $push_data_absen->keterangan = "sakit";
                        $push_data_absen->file_berkas = $fileName;
                        if ($fileName) {
                            $file->storeAs('public/berkas/', $fileName);
                        }
                        $push_data_absen->save();
                        DB::commit();
                        return redirect()->route("absensi.detailkehadiran")->with('success', "Berhasil Mengirimkan Konfirmasi, Dan Cepat Sembuh");
                    }catch(\Exception $e){
                        DB::rollBack();
                        return back()->with('error', $e->getMessage());
                    }
                }
        }else {
            return back()->with('error', "Terimakasih Atas Royalitas Dan Teladannya, Namun Hari Ini Libur. Hari Kerja Yaitu Senin-Jumat");
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Absensi  $absensi
     * @return \Illuminate\Http\Response
     */
    public function show(Absensi $absensi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Absensi  $absensi
     * @return \Illuminate\Http\Response
     */
    public function edit(Absensi $absensi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Absensi  $absensi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Absensi $absensi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Absensi  $absensi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Absensi $absensi)
    {
        //
    }
}