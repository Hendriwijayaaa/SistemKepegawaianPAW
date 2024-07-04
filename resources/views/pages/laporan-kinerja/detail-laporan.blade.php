@extends('layouts.master')

@section('title', 'Semua Data Laporan Kinerja')

@section('content')
    <style>
        .big {
            font-size: 10px !important;
        }
    </style>

    @if ($data_get->count() > 0)
        <div class="card w-100 position-relative overflow-hidden">
            <div class="mt-2 mb-3 text-center">
                <h5 class="fw-semibold mb-0 mt-3">Semua Data Laporan Kinerja</h5>
            </div>
            <div class="container">
                <div class="row g-4 mb-3">
                    <div class="col-sm-auto">
                        <a data-bs-effect="effect-fall" data-bs-toggle="modal" href="#modaldemo8"
                            class="btn btn-outline-info">Print Laporan</a>
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
                                <th class="big">Tanggal</th>
                                <th class="big">Nama Pegawai</th>
                                <th class="big">Jabatan</th>
                                <th class="big">Jam Mulai</th>
                                <th class="big">Jam Selesai</th>
                                <th class="big">Kegiatan Yang Dilakukan</th>
                                <th class="big">Status</th>
                                <th class="big">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($data_get as $data)
                                <tr>
                                    <td class="text-capitalize">{{ $no++ }}</td>
                                    <td class="text-capitalize">
                                        {{ optional(\Carbon\Carbon::parse($data->tanggal_kegiatan)->locale('id'))->isoFormat('DD MMMM YYYY') ?? '' }}
                                    </td>
                                    <td>
                                        {{ $data->pegawai->nama_lengkap ?? '' }}
                                    </td>
                                    <td>
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
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="ri-more-fill align-middle"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end" style="">
                                                <li><button class="dropdown-item" data-bs-effect="effect-fall"
                                                        data-bs-toggle="modal" href="#fotoBukti{{ $data->id }}"><i
                                                            class="ri-eye-fill align-bottom me-2 text-muted"></i>
                                                        Lihat Bukti</button></li>
                                                @if ($data->is_verify === 0)
                                                    <form action="{{ route('laporankinerja.acc', $data->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <li><button type="submit" class="dropdown-item edit-item-btn"><i
                                                                    class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                                                Acc Laporan</button></li>
                                                    </form>
                                                    <form action="{{ route('laporankinerja.tolak', $data->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <li>
                                                            <button type="submit" class="dropdown-item remove-item-btn">
                                                                <i class="ri-dislike-fill align-bottom me-2 text-muted"></i>
                                                                Tolak Laporan
                                                            </button>
                                                        </li>
                                                    </form>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <div class="modal fade" id="modaldemo8{{ $data->id }}">
                                    <div class="modal-dialog modal-dialog-centered text-center" role="document">
                                        <div class="modal-content modal-content-demo">
                                            <div class="modal-header">
                                                <h6 class="modal-title">Peringatan</h6><button aria-label="Close"
                                                    class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="col-lg-12">
                                                    <div class="text-center">
                                                        <img src="{{ asset('assets/images/user-illustarator-1.png') }}"
                                                            alt="aaaaa" width="150px" class="mb-3 text-center">
                                                    </div>
                                                </div>

                                                <p>Apakah Anda Yakin Ingin Menghapus ?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <form action="{{ route('laporankinerja.hapus', $data->id) }}"
                                                    method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button class="btn btn-danger">Hapus</button>
                                                </form>
                                                <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="fotoBukti{{ $data->id }}">
                                    <div class="modal-dialog modal-dialog-centered text-center" role="document">
                                        <div class="modal-content modal-content-demo">
                                            <div class="modal-header">
                                                <h6 class="modal-title">Peringatan</h6><button aria-label="Close"
                                                    class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <img src="{{ Storage::url('public/bukti-kegiatan/' . $data->bukti_kegiatan) }}"
                                                    alt="{{ $data->bukti_kegiatan ?? '-' }}" width="200px">
                                            </div>
                                        </div>
                                    </div>
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

                <div class="modal fade" id="modaldemo8">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content modal-content-demo">
                            <div class="modal-header">
                                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('laporankinerja.cetakbyid', $id) }}" method="POST">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="mb-3">
                                            <div class="row">
                                                <div class="mb-3">
                                                    <label class="form-check-label" for="izin">
                                                        Pilih Jenis Print Laporan
                                                    </label>
                                                </div>
                                                <div class="col-xl-6 col-sm-12">
                                                    <div class="form-check form-radio-warning mb-3">
                                                        <input class="form-check-input" type="radio" name="jenis_print"
                                                            id="izin" value="seluruh">
                                                        <label class="form-check-label" for="seluruh">
                                                            Seluruh Laporan Pegawai
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-sm-12">
                                                    <div class="form-check form-radio-primary mb-3">
                                                        <input class="form-check-input" type="radio" name="jenis_print"
                                                            id="sakit" value="perbulan">
                                                        <label class="form-check-label" for="Perbulan">
                                                            Perbulan
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3" id="form-izin" style="display: none">
                                            <div class="d-grid">
                                                <button class="btn btn-primary" type="submit">Print Laporan</button>
                                            </div>
                                        </div>

                                        <div class="mb-3" id="form" style="display: none;">
                                            <div class="row">
                                                <div class="col-xl-12 col-sm-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Bulan</label>
                                                        <select name="bulan" class="form-select single-select-field"
                                                            data-placeholder="Pilih Bulan">
                                                            <option value="">Pilih Bulan</option>
                                                            @foreach (range(1, 12) as $month)
                                                                <option value="{{ $month }}">
                                                                    {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-xl-12 col-sm-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Tahun</label>
                                                        <input type="number" class="form-control"
                                                            placeholder="Masukkan Tahun...." name="tahun"
                                                            autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-xl-12 col-sm-12">
                                                    <button type="submit" class="btn btn-primary">Print
                                                        Laporan</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
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
