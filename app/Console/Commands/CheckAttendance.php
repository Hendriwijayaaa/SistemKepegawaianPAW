<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Pegawai;
use App\Models\Absensi;
use App\Models\User;

class CheckAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and mark attendance for employees';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        date_default_timezone_set('Asia/Jakarta');

        $now = Carbon::now('Asia/Jakarta');
        $jamMulaiMasuk = strtotime("06:15");
        $jamMulaiPulang = strtotime("13:00");
        $jamBatasAbsenPulang = strtotime("17:30");
        $jamBatasAbsenMasuk = strtotime("12:55");
        $jamAbsen = strtotime($now->format('H:i'));
        // $jamAbsen = strtotime("07:31");

        if ($now->isWeekday()) {
            if ($jamAbsen >= $jamMulaiMasuk && $jamAbsen <= $jamBatasAbsenMasuk) {
                $this->handleAbsen(['masuk', 'masuk-pulang'], 'masuk', 'tidak hadir');
            } elseif ($jamAbsen >= $jamMulaiPulang && $jamAbsen <= $jamBatasAbsenPulang) {
                $this->handleAbsen(['pulang', 'masuk-pulang'], 'pulang', 'tidak hadir');
            }
        }

        return 0;
    }

    protected function handleAbsen($jenisAbsenArray, $jenisAbsen, $keterangan)
    {
        $currentDate = Carbon::now()->toDateString();
        $getPegawai = Pegawai::all();
        $getDataAbsen = Absensi::whereDate('tanggal', $currentDate)
            ->whereIn('jenis_absen', $jenisAbsenArray)
            ->pluck('pegawai_id')->all();

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

        foreach ($pegawaiBelumAbsen as $pegawai) {
            $push_data_absen = new Absensi();
            $push_data_absen->pegawai_id = $pegawai['id'];
            $push_data_absen->user_id = $pegawai['user_id'];
            $push_data_absen->tanggal = Carbon::now()->format('Y-m-d');
            $push_data_absen->jenis_absen = $jenisAbsen;
            $push_data_absen->jam_absen = Carbon::now()->format('H:i');
            $push_data_absen->keterangan = $keterangan;
            $push_data_absen->save();
        }
    }
}