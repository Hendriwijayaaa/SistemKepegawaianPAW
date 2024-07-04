@extends('layouts.master')

@section('title', 'Tambah Master Akun')

@section('content')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <!-- Or for RTL support -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />


    <div class="card w-100 position-relative overflow-hidden">
        <div class="mt-2 mb-3 text-center">
            <h6 class="fs-6 fw-semibold mb-0">Tambah Master Akun</h6>
        </div>
        <div class="d-flex flex-wrap align-items-center">
            <div class="container">
                <a id="kembaliBtn" class="btn  btn-outline-primary">
                    Kembali</a>
            </div>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('masterakun.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-3">
                        <label class="form-label mb-3">Nomor Kartu Anggota</label>
                    </div>
                    <div class="col-lg-9">
                        <div class="mb-3">
                            <select name="pegawai_id" class="form-select single-select-field"
                                data-placeholder="Nomor Kartu Anggota">
                                <option></option>
                                @foreach ($getPegawai as $pegawai)
                                    <option value="{{ $pegawai->id ?? null }}">{{ $pegawai->no_kta ?? '-' }} ~
                                        {{ $pegawai->nama_lengkap }}</option>
                                @endforeach
                            </select>
                            @error('pegawai_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3">
                        <label class="form-label mb-3">Password</label>
                    </div>
                    <div class="col-lg-9">
                        <div class="mb-3">
                            <input type="password" name="password" class="form-control" placeholder="Masukkan password">
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3">
                        <label class="form-label mb-3">Hak Akses</label>
                    </div>
                    <div class="col-lg-9">
                        <div class="mb-3">
                            <select name="role" class="form-select single-select-field"
                                data-placeholder="Pilih Hak Akses">
                                <option label="Pilih Hak Akses"></option>
                                <option value="admin">Admin</option>
                                <option value="pegawai">Pegawai</option>
                                <option value="tu">Kepala TU</option>
                            </select>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-3">
                        <label class="form-label mb-3">Status Akun</label>
                    </div>
                    <div class="col-lg-9">
                        <div class="mb-3">
                            <select name="status" class="form-select single-select-field"
                                data-placeholder="Pilih Status Akun">
                                <option label="Pilih Status Akun"></option>
                                <option value="aktif">Aktif</option>
                                <option value="tidak aktif">Tidak Aktif</option>
                                <option value="banned">Banned</option>
                            </select>
                        </div>
                    </div>
                </div>



                <div class="row">

                    <div class="col-lg-12">
                        <button type="submit"
                            class="justify-content-center w-100 btn mb-1 btn-rounded btn-primary d-flex align-items-center">
                            <i class="ti ti-send fs-4 me-2"></i>
                            Tambah
                        </button>
                    </div>
            </form>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $('.single-select-field').select2({
            placeholder: 'This is my placeholder',
            allowClear: true
        });
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
