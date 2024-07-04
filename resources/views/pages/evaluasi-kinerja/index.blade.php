@extends('layouts.master')

@section('title', 'Seluruh Data Evaluasi Kinerja')

@section('content')
    @php
        use Carbon\Carbon;
    @endphp

    @if ($data_get->count() > 0)
        <div class="card w-100 position-relative overflow-hidden">
            <div class="mt-2 mb-3 text-center">
                <h5 class="fw-semibold mb-0 mt-3">Seluruh Data Evaluasi Kinerja</h5>
            </div>
            <div class="container">
                <div class="row g-4 mb-3">
                    <div class="col-sm-auto">
                    </div>
                    <div class="col-sm">
                        <div class="d-flex justify-content-sm-end">
                            <div class="search-box ms-2">
                                <input type="text" class="form-control search" placeholder="Search..." id="search-table">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive rounded-2 mb-4">
                    <table class="table table-bordered border text-nowrap text-md-nowrap mg-b-0 text-center big">
                        <thead class="text-dark">
                            <tr>
                                <th class="big">No</th>
                                <th class="big">Tanggal Dinilai</th>
                                <th class="big">Nama Pegawai</th>
                                <th class="big">Jabatan</th>
                                <th class="big">Nilai Kinerja</th>
                                <th class="big">Tahun</th>
                                <th class="big">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($data_get as $data)
                                @php
                                    $idPegawai = null;
                                    $year = null;
                                    $getDataEvaluasiKinerja = json_decode($data->semua_id, true);
                                    $idPegawai = $getDataEvaluasiKinerja[0]['id_pegawai'];
                                    $getLaporanKinerja = \App\Models\LaporanKinerja::where(
                                        'pegawai_id',
                                        $idPegawai,
                                    )->first();

                                    if ($getLaporanKinerja) {
                                        $tanggal = $getLaporanKinerja->tanggal;
                                        $carbonDate = Carbon::parse($tanggal);
                                        $year = $carbonDate->year;
                                    }
                                @endphp
                                <tr>
                                    <td class="text-capitalize">{{ $no++ }}</td>
                                    <td class="text-capitalize">
                                        {{ optional(\Carbon\Carbon::parse($data->created_at)->locale('id'))->isoFormat('DD MMMM YYYY') ?? '' }}
                                    </td>
                                    <td>
                                        {{ $getLaporanKinerja->pegawai->nama_lengkap ?? '' }}
                                    </td>
                                    <td class="text-capitalize">
                                        {{ $getLaporanKinerja->pegawai->jabatan ?? '' }}
                                    </td>
                                    <td class="text-capitalize">
                                        {{ $data->nilai_pegawai ?? '' }}
                                    </td>
                                    <td class="text-capitalize">
                                        {{ $year ?? '' }}
                                    </td>
                                    <td>
                                        <div class="remove">
                                            <a class="btn btn-sm btn-outline-primary remove-item-btn"
                                                data-bs-effect="effect-fall" data-bs-toggle="modal"
                                                href="#modaldemo8{{ $data->id }}">Info</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @foreach ($data_get as $data)
                        @php
                            $namaBulan = [
                                1 => 'Januari',
                                2 => 'Februari',
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember',
                            ];

                            $idPegawai = null;
                            $getDataEvaluasiKinerja = json_decode($data->semua_id, true);
                            $idPegawai = $getDataEvaluasiKinerja[0]['id_pegawai'];
                            $totalLaporan = null;
                            $laporanPerBulan = [];
                            $totalWaktuPerBulan = [];

                            $getLaporanKinerja = \App\Models\LaporanKinerja::where('pegawai_id', $idPegawai)->get();
                            $totalLaporan = $getLaporanKinerja->count();
                            $firstLaporan = $getLaporanKinerja->first();

                            // Menghitung total menit kerja
                            $totalMinutes = 0;

                            foreach ($getLaporanKinerja as $laporan) {
                                $start = strtotime($laporan->jam_mulai);
                                $end = strtotime($laporan->jam_berakhir);
                                $totalMinutes += ($end - $start) / 60;
                            }

                            foreach ($getLaporanKinerja as $laporan) {
                                $bulan = Carbon::parse($laporan->tanggal_kegiatan)->format('n'); // Dapatkan nomor bulan (1-12)

                                if (!isset($laporanPerBulan[$bulan])) {
                                    $laporanPerBulan[$bulan] = 0;
                                    $totalWaktuPerBulan[$bulan] = 0;
                                }

                                $laporanPerBulan[$bulan]++;
                                $jamMulai = strtotime($laporan->jam_mulai);
                                $jamBerakhir = strtotime($laporan->jam_berakhir);
                                $totalWaktuPerBulan[$bulan] += ($jamBerakhir - $jamMulai) / 60; // Total waktu dalam menit
                            }

                            // Konversi total menit ke jam dan menit
                            $totalHours = floor($totalMinutes / 60);
                            $remainingMinutes = $totalMinutes % 60;
                        @endphp
                        <div class="modal fade" id="modaldemo8{{ $data->id }}">
                            <div class="modal-dialog modal-fullscreen role="document">
                                <div class="modal-content modal-content-demo">
                                    <div class="modal-header">
                                        <h6 class="modal-title">Info</h6><button aria-label="Close" class="btn-close"
                                            data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <label class="form-label"><strong>Data</strong></label>
                                        <hr>
                                        <div class="table-responsive rounded-2 mb-4">
                                            <table class="table">
                                                <tr>
                                                    <td style="border: none !important;">Nama Pegawai</td>
                                                    <td style="border: none !important;">:</td>
                                                    <td style="border: none !important;">
                                                        {{ $firstLaporan->pegawai->nama_lengkap ?? '' }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: none !important;">Jabatan</td>
                                                    <td style="border: none !important;">:</td>
                                                    <td style="border: none !important;" class="text-capitalize">
                                                        {{ $firstLaporan->pegawai->jabatan ?? '' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border: none !important;">Tahun</td>
                                                    <td style="border: none !important;">:</td>
                                                    <td class="text-capitalize" style="border: none !important;">
                                                        {{ $year ?? '' }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: none !important;">Total Laporan</td>
                                                    <td style="border: none !important;">:</td>
                                                    <td class="text-capitalize" style="border: none !important;">
                                                        {{ $totalLaporan ?? '' }}</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: none !important;">Total Waktu</td>
                                                    <td style="border: none !important;">:</td>
                                                    <td class="text-capitalize" style="border: none !important;">
                                                        {{ $totalHours }} jam {{ $remainingMinutes }} menit</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <label class="form-label"><strong>Lainnya</strong></label>
                                        <hr>
                                        <div class="table-responsive rounded-2 mb-4">
                                            <table
                                                class="table table-bordered border text-nowrap text-md-nowrap mg-b-0 text-center align-center">
                                                <thead>
                                                    <tr>
                                                        <th>Keterangan</th>
                                                        @for ($i = 1; $i <= 12; $i++)
                                                            <th class="text-capitalize">
                                                                {{ $namaBulan[$i] ?? 'Januari' }}</th>
                                                        @endfor
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Total Laporan Bulanan</td>
                                                        @for ($i = 1; $i <= 12; $i++)
                                                            <td>{{ $laporanPerBulan[$i] ?? 0 }}</td>
                                                        @endfor
                                                    </tr>
                                                    <tr>
                                                        <td>Total Waktu Bulanan</td>
                                                        @for ($i = 1; $i <= 12; $i++)
                                                            @php
                                                                $totalMinutes = $totalWaktuPerBulan[$i] ?? 0;
                                                                $totalHours = floor($totalMinutes / 60);
                                                                $remainingMinutes = $totalMinutes % 60;
                                                            @endphp
                                                            <td>{{ $totalHours }} jam {{ $remainingMinutes }} menit</td>
                                                        @endfor
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <a class="btn btn-sm btn-outline-primary remove-item-btn"
                                            href="{{ route('evaluation.detail', $data->id) }}">Detail</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="noresult" id="no-data" style="display: none;">
                        <div class="text-center">
                            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                            <h5 class="mt-2">Data Tidak Ada 🙏 </h5>

                        </div>
                    </div>
                </div>
                <div class="mt-2 text-end">
                    <nav aria-label="...">
                        <ul class="pagination justify-content-end">
                            <li class="page-item disabled">
                                <span class="page-link">Previous</span>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item active" aria-current="page">
                                <span class="page-link">2</span>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    @else
        <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full"
            data-sidebar-position="fixed" data-header-position="fixed">
            <div class="position-relative overflow-hidden min-vh-100 d-flex align-items-center justify-content-center">
                <div class="d-flex align-items-center justify-content-center w-100">
                    <div class="row justify-content-center w-100">
                        <div class="col-lg-4">
                            <div class="text-center">
                                <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/backgrounds/errorimg.svg"
                                    alt="" class="img-fluid">
                                <h3 class="fw-semibold">Tidak Ada Data</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}'
            });
        @endif
    </script>
    <script>
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Opss',
                text: '{{ session('error') }}'
            });
        @endif
    </script>
@endsection
