@extends('layouts.master')

@section('title', 'Daftar Pelatihan Yang Harus Diperhatikan')

@section('content')

    {{-- {{ dd($tanggalSaatIni === $tanggalTerkahirBulan) }} --}}
    @if ($tanggalSaatIni == $tanggalTerkahirBulan)
        @if ($data_get->count() > 0)
            <div class="card w-100 position-relative overflow-hidden">
                <div class="mt-2 mb-3 text-center">
                    <h5 class="fw-semibold mb-0 mt-3">Daftar Pelatihan Yang Harus Diperhatikan Kehadiran Kurang Dari 80 %
                    </h5>
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
                                    <th class="big">Nama Pegawai</th>
                                    <th class="big">Jabatan</th>
                                    <th class="big">Persentase Kehadiran</th>
                                    <th class="big">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($data_get as $data)
                                    @php
                                        $getDataInPelatihan = \App\Models\PelatihanPegawai::where(
                                            'absen_id',
                                            $data->id,
                                        )->first();
                                    @endphp
                                    <tr>
                                        <td class="text-capitalize">{{ $no++ }}</td>
                                        <td>
                                            {{ $data->nama_pengguna ?? null }}
                                        </td>
                                        <td class="text-capitalize">
                                            {{ $data->jabatan ?? null }}
                                        </td>
                                        <td class="text-capitalize bg-danger text-white">
                                            {{ number_format($data->persentase_kehadiran, 2) ?? null }}%
                                        </td>
                                        <td>
                                            @if ($getDataInPelatihan)
                                                @if ($getDataInPelatihan->is_catat === 0)
                                                    <div class="remove">
                                                        <a class="btn btn-sm btn-outline-primary remove-item-btn"
                                                            data-bs-effect="effect-fall" data-bs-toggle="modal"
                                                            href="#modaldemo8{{ $data->id }}">Kirimkan Pelatihan</a>
                                                    </div>
                                                @else
                                                    <div class="badge bg-success  me-1 mb-1 mt-1">Selesai</div>
                                                @endif
                                            @else
                                                <div class="remove">
                                                    <a class="btn btn-sm btn-outline-primary remove-item-btn"
                                                        data-bs-effect="effect-fall" data-bs-toggle="modal"
                                                        href="#modaldemo8{{ $data->id }}">Kirimkan Pelatihan</a>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="modaldemo8{{ $data->id }}">
                                        <div class="modal-dialog modal-xl " role="document">
                                            <div class="modal-content modal-content-demo">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Kirimkan Pelatihan</h6><button
                                                        aria-label="Close" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('pelatihan.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="absen_id" value="{{ $data->id }}">
                                                    <input type="hidden" name="user_id" value="{{ $data->user_id }}">
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <div class="col-xl-12 cok-sm-12">
                                                                <label class="form-label">Deskripsikan Pelatihan</label>
                                                                <div id="snow-editor" style="height: 300px;">
                                                                </div>
                                                                <input type="hidden" name="pelatihan_deksripsi"
                                                                    id="kontenEditorInput">
                                                                @error('pelatihan_deksripsi')
                                                                    <small class="text-danger">{{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Kirimkan</button>
                                                    </div>
                                                </form>
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
                                <h4 class="fw-semibold">Pelatihan / Catatan Untuk Pegawai Akan Di Buka Setiap Akkhir Bulan
                                </h4>
                                <p>Untuk Saat Ini Pegawai Yang Absenya Kurang Dari 80% Totalnya Yaitu:
                                    <strong>{{ $data_get->count() ?? 0 }} Pegawai</strong>
                                </p>
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
