<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Carbon\Carbon;
use App\Models\Absensi;
use Illuminate\Http\Request;
use App\Models\LaporanKinerja;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use PDF;

class LaporanKinerjaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pengguna = Auth::user();

        if($pengguna->role === "admin" || $pengguna->role === "tu"){
            $data_get = LaporanKinerja::all();
            return view("pages.laporan-kinerja.index", compact("data_get"));
        }elseif($pengguna->role === "pegawai"){
            $data_get = LaporanKinerja::where('user_id', $pengguna->id)->get();
            return view("pages.laporan-kinerja.index", compact("data_get"));
        }else {
            return back()->with("error", "Akun Tidak Di Kenali");
        }
    }
    public function tuLaporan()
    {
        $pegawai = Pegawai::all();

        // Ambil semua ID pegawai dari data pegawai
        $pegawaiIds = $pegawai->pluck('id');

        $uniquePegawaiIds = LaporanKinerja::whereIn('pegawai_id', $pegawaiIds)
                            ->select(DB::raw('MAX(id) as id'))
                            ->groupBy('pegawai_id')
                            ->pluck('id');

        // Mengambil seluruh data laporan kinerja berdasarkan ID yang unik
        $data_get = LaporanKinerja::whereIn('id', $uniquePegawaiIds)->get();
        // dd($data_get);
        $currentMonth = Carbon::now()->month;

        return view("pages.laporan-kinerja.seluruh-laporan", compact("data_get", 'currentMonth'));
    }
    public function verifikasiLaporan()
    {
       $data_get = LaporanKinerja::where('is_verify', false)->get();
        return view("pages.laporan-kinerja.index", compact("data_get"));
    }
    public function getAllLaporanPegawaiById($id)
    {
        // dd($id);
       $data_get = LaporanKinerja::where('pegawai_id', $id)->get();

        // dd($data_get);
        return view("pages.laporan-kinerja.detail-laporan", compact("data_get", 'id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        date_default_timezone_set('Asia/Jakarta');
        $userId = Auth::id();
        $now = Carbon::now('Asia/Jakarta');
        $today = Carbon::now()->toDateString();
        $jamPulang = $now->format('H:i');


        $absenMasuk = Absensi::where('user_id', $userId)
                            ->whereDate('tanggal', $today)
                            ->where('jenis_absen', 'masuk')
                            ->where('keterangan', 'hadir')
                            ->exists();

         $getJamKehadiran = Absensi::where('user_id', $userId)
                            ->whereDate('tanggal', $today)
                            ->where('jenis_absen', 'masuk')
                            ->where('keterangan', 'hadir')
                            ->first();

        $absenPulang = Absensi::where('user_id', $userId)
                            ->whereDate('tanggal', $today)
                            ->where('jenis_absen', 'pulang')
                            ->exists();

    return view("pages.laporan-kinerja.tambah", compact('absenMasuk', 'absenPulang', 'getJamKehadiran', 'jamPulang'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $pengguna = Auth::user();

        date_default_timezone_set('Asia/Jakarta');
        $now = Carbon::now('Asia/Jakarta');
        $jamPulang = $now->format('H:i');
        $tanggalKegiatan = $now->format('Y-m-d');

        $validateData = $request->validate([
                'keterangan_kegiatan' => 'required',
                'jam_mulai' => 'required',
        ]);


        $file = $request->file('bukti_kegiatan');
        $fileName = null;

        if ($file) {
                $originalExtension = $file->getClientOriginalExtension();
                $originalName = $file->getClientOriginalName();

                $allowedExtensions = ['jpg', 'jpeg', 'png', 'heic'];
                if (!in_array($originalExtension, $allowedExtensions)) {
                    return back()->with('error', 'Tidak Sesuai Format');
                }

                $fileName = time() . '_' . $originalName;
        }

        try {
            $lastReport = LaporanKinerja::where('user_id', $pengguna->id)
                            ->orderBy('created_at', 'desc')
                                ->first();

            if ($lastReport) {
                        $lastReportTime = new Carbon($lastReport->created_at);
                        $diffInMinutes = $now->diffInMinutes($lastReportTime);

                if ($diffInMinutes < 5) {
                            return back()->with('error', 'Anda hanya dapat mengirimkan data setiap 5 menit.');
                }
            }

            DB::beginTransaction();

            $push_data = new LaporanKinerja();
            $push_data->jam_mulai = $validateData['jam_mulai'];
            $push_data->jam_berakhir =  $jamPulang;
            $push_data->user_id =  $pengguna->id;
            $push_data->pegawai_id =  $pengguna->pegawai_id;
            $push_data->tanggal_kegiatan =  $tanggalKegiatan;
            $push_data->keterangan_kegiatan = strtolower($validateData['keterangan_kegiatan']);
            $push_data->bukti_kegiatan = $fileName;
            if ($fileName) {
                $file->storeAs('public/bukti-kegiatan/', $fileName);
            }
            // dd($push_data);
            $push_data->save();
            DB::commit();
            return redirect()->route('laporankinerja.index')->with('success', 'Berhasil Di Ajukan');
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pegawai  $pegawai
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        date_default_timezone_set('Asia/Jakarta');
        $userId = Auth::id();
        $now = Carbon::now('Asia/Jakarta');
        $today = Carbon::now()->toDateString();
        $jamPulang = $now->format('H:i');


        $absenMasuk = Absensi::where('user_id', $userId)
                            ->whereDate('tanggal', $today)
                            ->where('jenis_absen', 'masuk')
                            ->where('keterangan', 'hadir')
                            ->exists();



        $absenPulang = Absensi::where('user_id', $userId)
                            ->whereDate('tanggal', $today)
                            ->where('jenis_absen', 'pulang')
                            ->exists();

        $getData = LaporanKinerja::find($id);

        return view("pages.laporan-kinerja.edit", compact('absenMasuk', 'absenPulang', 'getData', 'jamPulang'));
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

        $pengguna = Auth::user();

        date_default_timezone_set('Asia/Jakarta');
        $now = Carbon::now('Asia/Jakarta');
        $jamPulang = $now->format('H:i');
        $tanggalKegiatan = $now->format('Y-m-d');

        $push_data = LaporanKinerja::find($id);
        if (!$push_data) {
            return back()->with('error', 'Data Laporan Kinerja Harian tidak ditemukan');
        }

        $validateData = $request->validate([
                'keterangan_kegiatan' => 'required',
                'jam_mulai' => 'required',
        ]);

        DB::beginTransaction();

        $file = $request->file('bukti_kegiatan');


        try {
            $currentPhoto = $push_data->bukti_kegiatan;

            if ($file != null) {
                $originalExtension = $file->getClientOriginalExtension();
                $originalName = $file->getClientOriginalName();

                $allowedExtension = ['jpg', 'jpeg', 'png', 'heic'];
                if (!in_array($originalExtension, $allowedExtension)) {
                    DB::rollBack();
                    return back()->with('error', 'Tidak Sesuai Format');
                }

                $fileName = time() . '_' . $originalName;
                $file->storeAs('public/bukti-kegiatan/', $fileName);

                $push_data->bukti_kegiatan = $fileName;

                if ($currentPhoto && Storage::exists('public/bukti-kegiatan/' . $currentPhoto)) {
                    Storage::delete('public/bukti-kegiatan/' . $currentPhoto);
                }
            }

            $push_data->jam_mulai = $validateData['jam_mulai'];
            $push_data->jam_berakhir =  $jamPulang;
            $push_data->user_id =  $pengguna->id;
            $push_data->pegawai_id =  $pengguna->pegawai_id;
            $push_data->tanggal_kegiatan =  $tanggalKegiatan;
            $push_data->keterangan_kegiatan = strtolower($validateData['keterangan_kegiatan']);
            // dd($push_data);
            $push_data->save();
            DB::commit();
            return redirect()->route('laporankinerja.index')->with('success', 'Berhasil Di Ajukan');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage()); // Kembalikan pesan kesalahan aktual untuk debug
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
        $get_data = LaporanKinerja::find($id);
        $get_data->delete();

        $currentfoto = $get_data->bukti_kegiatan;

        if ($currentfoto && Storage::exists('public/bukti-kegiatan/' . $currentfoto)) {
            Storage::delete('public/bukti-kegiatan/' . $currentfoto);
        }

        return redirect()->route('laporankinerja.index')->with('success', 'Data Berhasil Dihapus');
    }


    public function verifikasiAcc($id)
    {
        $push_data = LaporanKinerja::where('is_verify', false)
        ->where('id', $id)
        ->first();

        if (!$push_data) {
            return back()->with('error', 'Data Laporan Kinerja Harian tidak ditemukan');
        }

        DB::beginTransaction();

        try{
            $push_data->is_verify = 2;
            $push_data->update();
            DB::commit();
            return redirect()->route("laporankinerja.index")->with('success', "Konfirmasi Laporan Telah Berhasil Di Kirimkan");
        }catch(\Exception $e){
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }

    }
    public function verifikasiTolak($id)
    {
        $push_data = LaporanKinerja::where('is_verify', false)
        ->where('id', $id)
        ->first();

        if (!$push_data) {
            return back()->with('error', 'Data Laporan Kinerja Harian tidak ditemukan');
        }

        DB::beginTransaction();

        try{
            $push_data->is_verify = 1;
            $push_data->update();
            DB::commit();
            return redirect()->route("laporankinerja.index")->with('success', "Konfirmasi Laporan Telah Berhasil Di Kirimkan");
        }catch(\Exception $e){
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }

    }

        public function exportAllLaporan(Request $request)
    {
        if($request->jenis_print === "seluruh"){

            try {
                  $jenis = $request->jenis_print;
                    $laporanGroupedByPegawai = LaporanKinerja::where('is_verify', '!=', 0)
                        ->with('pegawai')
                        ->get()
                        ->groupBy('pegawai_id');

                    $tahun = Carbon::now()->year;
                    $currentTime = Carbon::now()->format('Ymd_His');
                    $fileName = 'laporan_' . $currentTime . '.pdf';

                    $dompdf = PDF::loadView('pages.print-laporan.all-laporan', compact('laporanGroupedByPegawai', 'tahun', 'jenis'));
                    $dompdf->setPaper('F5', 'landscape');
                    $pdfOutput = $dompdf->output();

                    $headers = [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                    ];

                    return Response::make($pdfOutput, 200, $headers);
                } catch (\Exception $e) {
                    return back()->with(
                        'error', 'Terjadi kesalahan saat mengunduh laporan: ' . $e->getMessage()
                    );
                }
        }elseif($request->jenis_print === "perbulan"){
            // dd($request->bulan, $request->tahun);
            if(empty($request->bulan)){
                return back()->with('error', 'Kolom Bulan Tidak Boleh Kosong, Harap Di Isi');
            }

            if(empty($request->tahun)){
                return back()->with('error', 'Kolom Tahun Tidak Boleh Kosong, Harap Di Isi');
            }


            try {
                $jenis = $request->jenis_print;
                $bulan = $request->bulan;
                $tahun = $request->tahun;

                    $laporanGroupedByPegawai = LaporanKinerja::where('is_verify', '!=', 0)
                        ->whereMonth('tanggal_kegiatan', $bulan)
                        ->whereYear('tanggal_kegiatan', $tahun)
                        ->with('pegawai')
                        ->get()
                        ->groupBy('pegawai_id');

                    if($laporanGroupedByPegawai->isEmpty()){
                        return back()->with('error', "Data Tidak Ada Untuk Bulan Dan Tahun Tersebut");
                    }

                    $tahun = Carbon::now()->year;
                    $currentTime = Carbon::now()->format('Ymd_His');
                    $fileName = $currentTime.'_laporan_'. 'bulan_'. $bulan. '_tahun_'.$tahun. '.pdf';

                    $dompdf = PDF::loadView('pages.print-laporan.all-laporan', compact('laporanGroupedByPegawai', 'tahun', 'jenis', 'bulan', 'tahun'));
                    $dompdf->setPaper('F5', 'landscape');
                    $pdfOutput = $dompdf->output();

                    $headers = [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                    ];

                    return Response::make($pdfOutput, 200, $headers);
            } catch (\Exception $e) {
                    return back()->with(
                        'error', 'Terjadi kesalahan saat mengunduh laporan: ' . $e->getMessage()
                    );
            }

        }else {
            return back()->with('error', 'Keyword Yang Anda Masukkan Tidak Sesuai');
        }

    }
        public function exportAllLaporanById(Request $request, $id)
    {
        if($request->jenis_print === "seluruh"){

            try {
                  $jenis = $request->jenis_print;
                    $laporanGroupedByPegawai = LaporanKinerja::where('pegawai_id', $id)->where('is_verify', 2)->get();
                    $laporanPertama = $laporanGroupedByPegawai->first();
                    $tahun = Carbon::now()->year;
                    $currentTime = Carbon::now()->format('Ymd_His');
                    $fileName = 'laporan_' . $currentTime . '.pdf';

                    $dompdf = PDF::loadView('pages.print-laporan.laporanbypegawaiid', compact('laporanGroupedByPegawai', 'tahun', 'jenis', 'laporanPertama'));
                    $dompdf->setPaper('F5', 'landscape');
                    $pdfOutput = $dompdf->output();

                    $headers = [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                    ];

                    return Response::make($pdfOutput, 200, $headers);
                } catch (\Exception $e) {
                    return back()->with(
                        'error', 'Terjadi kesalahan saat mengunduh laporan: ' . $e->getMessage()
                    );
                }
        }elseif($request->jenis_print === "perbulan"){
            // dd("perbulan");

            // dd($request->bulan, $request->tahun);
                if(empty($request->bulan)){
                    return back()->with('error', 'Kolom Bulan Tidak Boleh Kosong, Harap Di Isi');
                }

                if(empty($request->tahun)){
                    return back()->with('error', 'Kolom Tahun Tidak Boleh Kosong, Harap Di Isi');
                }


                try {
                    $jenis = $request->jenis_print;
                    $bulan = $request->bulan;
                    $tahun = $request->tahun;

                    $laporanGroupedByPegawai =LaporanKinerja::where('pegawai_id', $id)->where('is_verify', 2)->get();
                    $laporanPertama = $laporanGroupedByPegawai->first();

                    if($laporanGroupedByPegawai->isEmpty()){
                            return back()->with('error', "Data Tidak Ada Untuk Bulan Dan Tahun Tersebut");
                    }

                        $tahun = Carbon::now()->year;
                        $currentTime = Carbon::now()->format('Ymd_His');
                        $fileName = $currentTime.'_laporan_'. 'bulan_'. $bulan. '_tahun_'.$tahun. '.pdf';

                        $dompdf = PDF::loadView('pages.print-laporan.laporanbypegawaiid', compact('laporanGroupedByPegawai', 'tahun', 'jenis', 'bulan', 'tahun', 'laporanPertama'));
                        $dompdf->setPaper('F5', 'landscape');
                        $pdfOutput = $dompdf->output();

                        $headers = [
                            'Content-Type' => 'application/pdf',
                            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                        ];

                        return Response::make($pdfOutput, 200, $headers);
                } catch (\Exception $e) {
                        return back()->with(
                            'error', 'Terjadi kesalahan saat mengunduh laporan: ' . $e->getMessage()
                        );
                }

        }else {
                return back()->with('error', 'Keyword Yang Anda Masukkan Tidak Sesuai');
        }
    }

    public function verifikasiSemuaLaporanMenjadiAcc(){
        DB::beginTransaction();
       try {
            $getData = LaporanKinerja::where('is_verify', 0)->get();
            foreach ($getData as $data) {
                $data->is_verify = 2;
                $data->save();
            }
            DB::commit();
            return redirect()->route("laporankinerja.tuall")->with('success', "Berhasil Mengirimkan Konfirmasi");
       }catch(\Exception $e){
            DB::rollBack();
            return back()->with('error', $e->getMessage());
       }
    }

}
