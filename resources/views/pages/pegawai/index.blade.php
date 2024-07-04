@extends('layouts.master')

@section('title', 'Data Pegawai')

@section('content')

    @if (Auth::user()->role === 'admin')
        @if ($data_get->count() > 0)
            <div class="card w-100 position-relative overflow-hidden">
                <div class="mt-2 mb-3 text-center">
                    <h5 class="fw-semibold mb-0 mt-3">Data Pegawai</h5>
                </div>
                <div class="container">
                    <div class="row g-4 mb-3">
                        <div class="col-sm-auto">
                            <div>
                                <a href="{{ route('pegawai.create') }}" class="btn  btn-outline-primary">
                                    Tambah</a>
                            </div>
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
                <div class="card-body p-4 mt-3">
                    <div class="table-responsive rounded-2 mb-4">
                        <table class="table table-bordered border text-nowrap text-md-nowrap mg-b-0 text-center">
                            <thead class="text-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Bergabung</th>
                                    <th>No KTA</th>
                                    <th>Email</th>
                                    <th>Nama Lengkap</th>
                                    <th>Tempat/Tgl Lahir</th>
                                    <th>Jabatan</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Alamat</th>
                                    <th>No Hp</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
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
                                            {{ optional(\Carbon\Carbon::parse($data->tanggal_masuk)->locale('id'))->isoFormat('DD MMMM YYYY') ?? '' }}
                                        </td>
                                        <td>
                                            {{ $data->no_kta ?? '' }}
                                        </td>
                                        <td>
                                            {{ $data->email ?? '' }}
                                        </td>
                                        <td class="text-capitalize">
                                            {{ $data->nama_lengkap ?? '' }}
                                        </td>
                                        <td class="text-capitalize">
                                            {{ $data->tempat_lahir ?? '' }},
                                            {{ optional(\Carbon\Carbon::parse($data->tgl_lahir)->locale('id'))->isoFormat('DD MMMM YYYY') ?? '' }}
                                        </td>
                                        <td class="text-capitalize">
                                            {{ $data->jabatan ?? '' }}
                                        </td>
                                        <td class="text-capitalize">
                                            {{ $data->jenis_kelamin ?? '' }}
                                        </td>
                                        <td class="text-capitalize">
                                            @if (isset($data->alamat) && strlen($data->alamat) > 14)
                                                @foreach (str_split($data->alamat, 14) as $chunk)
                                                    {{ $chunk }}<br>
                                                @endforeach
                                            @else
                                                {{ $data->alamat ?? '' }}
                                            @endif
                                        </td>

                                        <td>
                                            {{ $data->no_hp ?? null }}
                                        </td>
                                        <td>
                                            @if ($data->status === 'aktif')
                                                <span class="badge bg-success">Aktif</span>
                                            @elseif ($data->status === 'tidak aktif')
                                                <span class="badge bg-danger">Tidak Aktif</span>
                                            @elseif ($data->status === 'Phk')
                                                <span class="badge bg-warning">PHK</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <div class="edit">
                                                    <a class="btn btn-sm btn-success edit-item-btn"
                                                        href="{{ route('pegawai.edit', $data->id) }}">Edit</a>
                                                </div>
                                                <div class="remove">
                                                    <a class="btn btn-sm btn-danger remove-item-btn"
                                                        data-bs-effect="effect-fall" data-bs-toggle="modal"
                                                        href="#modaldemo8{{ $data->id }}">Hapus</a>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                    <div class="modal fade" id="modaldemo8{{ $data->id }}">
                                        <div class="modal-dialog modal-dialog-centered text-center" role="document">
                                            <div class="modal-content modal-content-demo">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Peringatan</h6><button aria-label="Close"
                                                        class="btn-close" data-bs-dismiss="modal"><span
                                                            aria-hidden="true">&times;</span></button>
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
                                                    <form action="{{ route('pegawai.hapus', $data->id) }}" method="POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button class="btn btn-danger">Hapus</button>
                                                    </form>
                                                    <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="fotoPegawai{{ $data->id }}">
                                        <div class="modal-dialog modal-dialog-centered text-center" role="document">
                                            <div class="modal-content modal-content-demo">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Foto</h6>
                                                </div>
                                                <div class="modal-body">
                                                    <img src="{{ Storage::url('public/foto-pegawai/' . $data->foto) }}"
                                                        alt="{{ $data->nama_lengkap ?? '-' }}" width="200px">
                                                    <div class="modal-footer">
                                                        <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
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
                                    <a href="{{ route('pegawai.create') }}" class="btn btn-primary">
                                        Tambah</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @else
        @if ($data_get->count() > 0)
            <div class="card w-100 position-relative overflow-hidden">
                <div class="mt-2 mb-3 text-center">
                    <h5 class="fw-semibold mb-0 mt-3">Data Saya</h5>
                </div>
                <div class="container">
                    <div class="row g-4">
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
                        <table class="table table-bordered border text-nowrap text-md-nowrap mg-b-0 text-center">
                            <thead class="text-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Bergabung</th>
                                    <th>No KTA</th>
                                    <th>Email</th>
                                    <th>Nama Lengkap</th>
                                    <th>Tempat/Tgl Lahir</th>
                                    <th>Jabatan</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Alamat</th>
                                    <th>No Hp</th>
                                    <th>Status</th>
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
                                            {{ optional(\Carbon\Carbon::parse($data->tanggal_masuk)->locale('id'))->isoFormat('DD MMMM YYYY') ?? '' }}
                                        </td>
                                        <td>
                                            {{ $data->no_kta ?? '' }}
                                        </td>
                                        <td>
                                            {{ $data->email ?? '' }}
                                        </td>
                                        <td class="text-capitalize">
                                            {{ $data->nama_lengkap ?? '' }}
                                        </td>
                                        <td class="text-capitalize">
                                            {{ $data->tempat_lahir ?? '' }},
                                            {{ optional(\Carbon\Carbon::parse($data->tgl_lahir)->locale('id'))->isoFormat('DD MMMM YYYY') ?? '' }}
                                        </td>
                                        <td class="text-capitalize">
                                            {{ $data->jabatan ?? '' }}
                                        </td>
                                        <td class="text-capitalize">
                                            {{ $data->jenis_kelamin ?? '' }}
                                        </td>
                                        <td class="text-capitalize">
                                            @if (isset($data->alamat) && strlen($data->alamat) > 14)
                                                @foreach (str_split($data->alamat, 14) as $chunk)
                                                    {{ $chunk }}<br>
                                                @endforeach
                                            @else
                                                {{ $data->alamat ?? '' }}
                                            @endif
                                        </td>

                                        <td>
                                            {{ $data->no_hp ?? null }}
                                        </td>
                                        <td>
                                            @if ($data->status === 'aktif')
                                                <span class="badge bg-success">Aktif</span>
                                            @elseif ($data->status === 'tidak aktif')
                                                <span class="badge bg-danger">Tidak Aktif</span>
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
@endsection
