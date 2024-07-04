@extends('layouts.master')

@section('title', 'Semua Data Laporan Kinerja')

@section('content')
    <style>
        .big {
            font-size: 10px !important;
        }
    </style>

    @if (Auth::user()->role === 'tu' || Auth::user()->role === 'pegawai')
        @if ($data_get)
            <div class="card w-100 position-relative overflow-hidden">
                <div class="mt-2 mb-3 text-center">
                    <h5 class="fw-semibold mb-0 mt-3">Laporan Kinerja</h5>
                </div>
                <div class="container">
                    <div class="row g-4 mb-3">
                        <div class="col-sm-auto">
                        </div>
                        <div class="col-sm">
                            <div class="d-flex justify-content-sm-end">
                                <div class="search-box ms-2">
                                    <input type="text" class="form-control search" placeholder="Search..."
                                        id="search-table">
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
                                    <th class="big">Tanggal</th>
                                    <th class="big">Nama Pegawai</th>
                                    <th class="big">Jabatan</th>
                                    <th class="big">Jam Mulai</th>
                                    <th class="big">Jam Selesai</th>
                                    <th class="big">Kegiatan Yang Dilakukan</th>
                                    <th class="big">Status Verifikasi</th>
                                    <th class="big">Status Dinilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                    $idPegawai = null;
                                    $getDataEvaluasi = json_decode($data_get->semua_id, true);
                                    foreach ($getDataEvaluasi as $value) {
                                        $idPegawai[] = $value['id'];
                                    }
                                    $getDataLaporanKinerjaByAllId = \App\Models\LaporanKinerja::whereIn(
                                        'id',
                                        $idPegawai,
                                    )->get();
                                @endphp
                                @foreach ($getDataLaporanKinerjaByAllId as $data)
                                    <tr>
                                        <td class="text-capitalize">{{ $no++ }}</td>
                                        <td class="text-capitalize">
                                            {{ optional(\Carbon\Carbon::parse($data->tanggal_kegiatan)->locale('id'))->isoFormat('DD MMMM YYYY') ?? '' }}
                                        </td>
                                        <td>
                                            {{ $data->pegawai->nama_lengkap ?? '' }}
                                        </td>
                                        <td class="text-capitalize">
                                            {{ $data->pegawai->jabatan ?? '' }}
                                        </td>
                                        <td class="text-capitalize">
                                            {{ $data->jam_mulai ?? '' }}
                                        </td>
                                        <td class="text-capitalize">
                                            {{ $data->jam_berakhir ?? '' }}
                                        </td>
                                        <td class="text-capitalize">
                                            @if (isset($data->keterangan_kegiatan) && strlen($data->keterangan_kegiatan) > 30)
                                                @foreach (str_split($data->keterangan_kegiatan, 30) as $chunk)
                                                    {{ $chunk }}<br>
                                                @endforeach
                                            @else
                                                {{ $data->keterangan_kegiatan ?? '' }}
                                            @endif
                                        </td>

                                        <td class="text-capitalize">
                                            @if ($data->is_verify === 0)
                                                <div class="badge bg-warning  me-1 mb-1 mt-1">Menunggu</div>
                                            @elseif ($data->is_verify === 1)
                                                <div class="badge bg-danger  me-1 mb-1 mt-1">Tolak</div>
                                            @elseif ($data->is_verify === 2)
                                                <div class="badge bg-success  me-1 mb-1 mt-1">Acc</div>
                                            @endif
                                        </td>

                                        <td class="text-capitalize">
                                            @if ($data->is_nilai === 0)
                                                <div class="badge bg-warning  me-1 mb-1 mt-1">Belum Dinilai</div>
                                            @else
                                                <div class="badge bg-success  me-1 mb-1 mt-1">Selesai</div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
                title: 'Berhasil',
                text: '{{ session('error') }}'
            });
        @endif
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const izinRadio = document.getElementById('izin');
            const sakitRadio = document.getElementById('sakit');
            const formIzin = document.getElementById('form-izin');
            const formSakit = document.getElementById('form');

            izinRadio.addEventListener('change', function() {
                if (izinRadio.checked) {
                    formIzin.style.display = 'block';
                    formSakit.style.display = 'none';
                }
            });

            sakitRadio.addEventListener('change', function() {
                if (sakitRadio.checked) {
                    formSakit.style.display = 'block';
                    formIzin.style.display = 'none';
                }
            });
        });
    </script>
@endsection
