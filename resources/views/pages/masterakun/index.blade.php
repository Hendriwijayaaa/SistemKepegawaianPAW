@extends('layouts.master')

@section('title', 'Master Akun')

@section('content')
    @if ($data_get->count() > 0)
        <div class="card w-100 position-relative overflow-hidden">
            <div class="mt-2 mb-3 text-center">
                <h4 class=" fw-semibold mb-0 mt-3">Master Akun</h4>
            </div>
            <div class="container">
                <div class="row g-4">
                    <div class="col-sm-auto">
                        <div>
                            <a href="{{ route('masterakun.create') }}" class="btn  btn-outline-primary">
                                Tambah</a>
                        </div>
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
                    <table class="table table-bordered border text-nowrap text-md-nowrap table-striped mg-b-0 text-center">
                        <thead class="text-dark">
                            <tr>
                                <th>No</th>
                                <th>Nomor KTA</th>
                                <th>Email</th>
                                <th>Nama Lengkap</th>
                                <th>Status</th>
                                <th>Verifikasi Akun</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($data_get as $data)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $data->pegawai->no_kta ?? '' }}</td>
                                    <td>{{ $data->email ?? '' }}</td>
                                    <td>{{ $data->nama ?? '' }}</td>
                                    <td>

                                        @if ($data->status === 'tidak aktif')
                                            <div class="badge bg-warning  me-1 mb-1 mt-1">Tidak Aktif</div>
                                        @elseif ($data->status === 'banned')
                                            <div class="badge bg-danger  me-1 mb-1 mt-1">Banned</div>
                                        @elseif ($data->status === 'aktif')
                                            <div class="badge bg-success  me-1 mb-1 mt-1">Aktif</div>
                                        @endif
                                    </td>
                                    <td>

                                        @if ($data->is_verify === 0)
                                            <div class="badge bg-danger  me-1 mb-1 mt-1">Belum Di Verifikasi Pengguna</div>
                                        @else
                                            <div class="badge bg-success  me-1 mb-1 mt-1">Terverifikasi</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <div class="edit">
                                                <a class="btn btn-sm btn-success edit-item-btn"
                                                    href="{{ route('masterakun.getView', $data->id) }}">Edit</a>
                                            </div>
                                            <div class="remove">
                                                <a class="btn btn-sm btn-danger remove-item-btn"
                                                    data-bs-effect="effect-fall" data-bs-toggle="modal"
                                                    href="#modaldemo8{{ $data->id }}">Remove</a>
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
                                                <form action="{{ route('masterakun.delete', $data->id) }}" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button class="btn btn-danger">Hapus</button>
                                                </form>
                                                <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                    <p id="no-data" style="display: none;" class="text-center">
                        <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/backgrounds/errorimg.svg"
                            alt="" width="200px"><br>
                        No data found

                    </p>
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
                                <a href="{{ route('masterakun.create') }}" class="btn btn-primary"><i
                                        class="fe fe-plus me-2"></i>

                                    Tambah</a>
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
