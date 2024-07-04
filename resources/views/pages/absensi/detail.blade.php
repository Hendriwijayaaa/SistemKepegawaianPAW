@extends('layouts.master')

@section('title', 'Detail Absensi')

@section('content')
    <style>
        .big {
            font-size: 9px !important;
        }
    </style>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />

    @if (Auth::user()->role === 'admin')
        @if ($getData->count() > 0)
            <div class="card w-100 position-relative overflow-hidden">
                <div class="mt-2 mb-3 text-center">
                    <h5 class="fw-semibold mb-0 mt-3">Kehadiran</h5>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12 col-sm-12">
                            <label class="form-label">Bulan</label>
                            <form action="{{ route('absensi.search') }}" method="GET">
                                <select name="bulan" class="form-select single-select-field"
                                    data-placeholder="Pilih Bulan" onchange="this.form.submit()">
                                    <option value="">Pilih Bulan</option>
                                    @foreach (range(1, 12) as $month)
                                        <option value="{{ $month }}">
                                            {{ \Carbon\Carbon::create()->month($month)->format('F') }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive rounded-2 mb-4">
                        <table class="table table-bordered border text-nowrap text-md-nowrap mg-b-0 text-center">
                            <thead class="text-dark">
                                <tr>
                                    <th rowspan="2" class="align-middle big">Nama Lengkap</th>
                                    <th rowspan="2" class="align-middle big">Jabatan</th>
                                    <th colspan="{{ count($dates) }}" class="big">Kehadiran</th>
                                    <th rowspan="2" class="align-middle big">Persentase</th>
                                </tr>
                                <tr>
                                    @foreach ($dates as $date)
                                        <th class="big">{{ $date['day'] }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($getData as $data)
                                    <tr>
                                        <td rowspan="2" class="align-middle">{{ $data->nama_pengguna }}</td>
                                        <td rowspan="2" class="align-middle text-capitalize">
                                            {{ $data->jabatan }}
                                        </td>
                                        @foreach ($dates as $date)
                                            @php
                                                $keterangan = $dataAbsenMasuk[$data->pegawai_id][$date['date']] ?? '-';
                                                $bgClass = '';
                                                if ($keterangan === 'H') {
                                                    $bgClass = 'bg-success';
                                                } elseif ($keterangan === 'I') {
                                                    $bgClass = 'bg-warning';
                                                } elseif ($keterangan === 'S') {
                                                    $bgClass = 'bg-warning';
                                                } elseif ($keterangan === 'A') {
                                                    $bgClass = 'bg-danger';
                                                } elseif ($keterangan === 'L') {
                                                    $bgClass = 'bg-success';
                                                }
                                            @endphp
                                            <td class="{{ $bgClass }}"
                                                @if ($keterangan === 'S') style="cursor: pointer;"data-bs-effect="effect-fall" data-bs-toggle="modal"
                                                        href="#modaldemo8{{ $data->id }}" @endif>
                                                {{ $keterangan }}</td>
                                        @endforeach
                                        <td rowspan="2" class="align-middle">
                                            {{ number_format($data->persentase_kehadiran, 2) }}%
                                        </td>
                                    </tr>
                                    <tr>
                                        @foreach ($dates as $date)
                                            @php
                                                $keterangan = $dataAbsenPulang[$data->pegawai_id][$date['date']] ?? '-';
                                                $bgClass = '';
                                                if ($keterangan === 'H') {
                                                    $bgClass = 'bg-success';
                                                } elseif ($keterangan === 'I') {
                                                    $bgClass = 'bg-warning';
                                                } elseif ($keterangan === 'S') {
                                                    $bgClass = 'bg-warning';
                                                } elseif ($keterangan === 'A') {
                                                    $bgClass = 'bg-danger';
                                                } elseif ($keterangan === 'L') {
                                                    $bgClass = 'bg-success';
                                                }
                                            @endphp
                                            <td class="{{ $bgClass }}">{{ $keterangan }}</td>
                                        @endforeach
                                    </tr>
                                    <div class="modal fade" id="modaldemo8{{ $data->id }}">

                                        <div class="modal-dialog modal-dialog-centered text-center" role="document">
                                            <div class="modal-content modal-content-demo">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Surat Sakit</h6><button aria-label="Close"
                                                        class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="embed-responsive embed-responsive-16by9">
                                                        <iframe class="embed-responsive-item"
                                                            src="{{ Storage::url('public/berkas/' . $data->file_berkas) }}"
                                                            allowfullscreen></iframe>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-2">
                        <div class="container">
                            <strong>Note:</strong>
                            <table>
                                <tr>
                                    <td>L</td>
                                    <td>=</td>
                                    <td>Libur Untuk Hari Sabtu Dan Hari Minggu</td>
                                </tr>
                                <tr>
                                    <td>H</td>
                                    <td>=</td>
                                    <td>Hadir</td>
                                </tr>
                                <tr>
                                    <td>S</td>
                                    <td>=</td>
                                    <td>Sakit</td>
                                </tr>
                                <tr>
                                    <td>I</td>
                                    <td>=</td>
                                    <td>Izin</td>
                                </tr>
                                <tr>
                                    <td>A</td>
                                    <td>=</td>
                                    <td>Tanpa Keterangan</td>
                                </tr>
                            </table>
                        </div>
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
    @elseif (Auth::user()->role === 'tu' || Auth::user()->role === 'pegawai')
        @if ($getData->count() > 0)
            <div class="card w-100 position-relative overflow-hidden">
                <div class="mt-2 mb-3 text-center">
                    <h5 class="fw-semibold mb-0 mt-3">Kehadiran</h5>
                </div>
                <div class="container">
                    <div class="row ">
                        <div class="col-xl-12 col-sm-12">
                            <label class="form-label">Bulan</label>
                            <form action="{{ route('absensi.search') }}" method="GET">
                                <select name="bulan" class="form-select single-select-field"
                                    data-placeholder="Pilih Bulan" onchange="this.form.submit()">
                                    <option value="">Pilih Bulan</option>
                                    @foreach (range(1, 12) as $month)
                                        <option value="{{ $month }}">
                                            {{ \Carbon\Carbon::create()->month($month)->format('F') }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">

                    <div class="table-responsive rounded-2 mb-4">
                        <table class="table table-bordered border text-nowrap text-md-nowrap mg-b-0 text-center">
                            <thead class="text-dark">
                                <tr>
                                    <th rowspan="2" class="align-middle big">Nama Lengkap</th>
                                    <th rowspan="2" class="align-middle big">Jabatan</th>
                                    <th colspan="{{ count($dates) }}" class="big">Kehadiran</th>
                                    <th rowspan="2" class="align-middle big">Persentase</th>
                                </tr>
                                <tr>
                                    @foreach ($dates as $date)
                                        <th class="big">{{ $date['day'] }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td rowspan="2" class="align-middle">{{ $pengguna->pegawai->nama_lengkap }}</td>
                                    <td rowspan="2" class="align-middle text-capitalize">
                                        {{ $pengguna->pegawai->jabatan }}
                                    </td>
                                    @foreach ($dates as $date)
                                        @php
                                            $keterangan = $dataAbsenMasuk[$date['date']];
                                            $bgClass = '';
                                            if ($keterangan === 'H') {
                                                $bgClass = 'bg-success';
                                            } elseif ($keterangan === 'I') {
                                                $bgClass = 'bg-warning';
                                            } elseif ($keterangan === 'S') {
                                                $bgClass = 'bg-warning';
                                            } elseif ($keterangan === 'A') {
                                                $bgClass = 'bg-danger';
                                            } elseif ($keterangan === 'L' || $keterangan === 'L') {
                                                $bgClass = 'bg-success';
                                            }
                                        @endphp
                                        <td class="{{ $bgClass }}"
                                            @foreach ($getData as $data)
                                                @if ($keterangan === 'S') style="cursor: pointer;"data-bs-effect="effect-fall" data-bs-toggle="modal"
                                                        href="#modaldemo8{{ $data->id }}" @endif @endforeach>
                                            {{ $keterangan ?? '-' }}</td>
                                    @endforeach
                                    <td rowspan="2" class="align-middle">
                                        {{ number_format($attendancePercentage, 2) }}%
                                    </td>
                                </tr>
                                {{-- data absensi pulang --}}
                                <tr>
                                    @foreach ($dates as $date)
                                        @php
                                            $keterangan = $dataAbsenPulang[$date['date']];
                                            $bgClass = '';
                                            if ($keterangan === 'H') {
                                                $bgClass = 'bg-success';
                                            } elseif ($keterangan === 'I') {
                                                $bgClass = 'bg-warning';
                                            } elseif ($keterangan === 'S') {
                                                $bgClass = 'bg-warning';
                                            } elseif ($keterangan === 'A') {
                                                $bgClass = 'bg-danger';
                                            } elseif ($keterangan === 'L' || $keterangan === 'L') {
                                                $bgClass = 'bg-success';
                                            }
                                        @endphp
                                        <td class="{{ $bgClass }}">{{ $keterangan ?? '-' }}</td>
                                    @endforeach
                                </tr>

                                @foreach ($getData as $data)
                                    <div class="modal fade" id="modaldemo8{{ $data->id }}">

                                        <div class="modal-dialog modal-dialog-centered text-center" role="document">
                                            <div class="modal-content modal-content-demo">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Surat Sakit</h6><button aria-label="Close"
                                                        class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="embed-responsive embed-responsive-16by9">
                                                        <iframe class="embed-responsive-item"
                                                            src="{{ Storage::url('public/berkas/' . $data->file_berkas) }}"
                                                            allowfullscreen></iframe>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>


                    <div class="mt-2">
                        <div class="container">
                            <strong>Note:</strong>
                            <table>
                                <tr>
                                    <td>L</td>
                                    <td>=</td>
                                    <td>Libur Untuk Hari Sabtu Dan Hari Minggu</td>
                                </tr>
                                <tr>
                                    <td>H</td>
                                    <td>=</td>
                                    <td>Hadir</td>
                                </tr>
                                <tr>
                                    <td>S</td>
                                    <td>=</td>
                                    <td>Sakit</td>
                                </tr>
                                <tr>
                                    <td>I</td>
                                    <td>=</td>
                                    <td>Izin</td>
                                </tr>
                                <tr>
                                    <td>A</td>
                                    <td>=</td>
                                    <td>Tanpa Keterangan</td>
                                </tr>
                            </table>
                        </div>
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
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Opss',
                text: '{{ session('error') }}'
            });
        @endif
    </script>
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Opss',
                text: '{{ session('success') }}'
            });
        @endif
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $('.single-select-field').select2({
            placeholder: 'This is my placeholder',
            allowClear: true
        });
    </script>

@endsection
