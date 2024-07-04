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
            <h6 class="fs-6 fw-semibold mb-0">Edit Master Akun</h6>
        </div>
        <div class="d-flex flex-wrap align-items-center">
            <div class="container">
                <a id="kembaliBtn" class="btn  btn-outline-primary">
                    Kembali</a>
            </div>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('masterakun.updayes', $data_get->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-3">
                        <label class="form-label mb-3">Nomor Induk Kepegawaian</label>
                    </div>
                    <div class="col-lg-9">
                        <div class="mb-3">
                            <select name="pegawai_id" class="form-select single-select-field"
                                data-placeholder="Nomor Induk Kepegawaian">
                                <option value="{{ $data_get->pegawai_id ?? null }}">
                                    {{ $data_get->pegawai->no_kta ?? null }} ~
                                    {{ $data_get->nama ?? null }}</option>
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
                            @php
                                $Role = ['karyawan', 'admin', 'hrd'];
                                $user = \App\Models\User::find($data_get->id);
                                $existingHakAkses = $user ? [$user->role] : [];
                                $jenisHakAkses = array_diff($Role, $existingHakAkses);
                            @endphp
                            <select name="role" class="form-select single-select-field text-capitalize"
                                data-placeholder="Pilih Hak Akses">
                                <option value="{{ $data_get->role ?? null }}">
                                    {{ $data_get->role ?? null }}</option>
                                @foreach ($jenisHakAkses as $hakAkses)
                                    <option value="{{ $hakAkses }}">{{ $hakAkses }}</option>
                                @endforeach
                            </select>
                            @error('role')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3">
                        <label class="form-label mb-3">Status Akun</label>
                    </div>
                    <div class="col-lg-9">
                        <div class="mb-3">
                            @php
                                $Status = ['aktif', 'tidak aktif', 'banned'];
                                $user = \App\Models\User::find($data_get->id);
                                $existingStatusAkun = $user ? [$user->status] : [];
                                $jenisStatusAkun = array_diff($Status, $existingStatusAkun);
                            @endphp
                            <select name="status" class="form-select single-select-field text-capitalize"
                                data-placeholder="Pilih Hak Akses">
                                <option value="{{ $data_get->status ?? null }}">
                                    {{ $data_get->status ?? null }}</option>
                                @foreach ($jenisStatusAkun as $statusAkun)
                                    <option value="{{ $statusAkun }}">{{ $statusAkun }}</option>
                                @endforeach
                            </select>
                            @error('status')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <button type="submit"
                            class="justify-content-center w-100 btn mb-1 btn-rounded btn-primary d-flex align-items-center">
                            <i class="ti ti-send fs-4 me-2"></i>
                            Update
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
