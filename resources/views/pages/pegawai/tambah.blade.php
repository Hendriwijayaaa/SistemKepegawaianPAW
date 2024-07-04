@extends('layouts.master')

@section('title', 'Tambah Data Pegawai')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <!-- Or for RTL support -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />

    <div class="card w-100 position-relative overflow-hidden">
        <div class="mt-2 mb-3 text-center">
            <h6 class="fs-4 fw-semibold mb-0">Tambah Data Pegawai</h6>
        </div>
        <div class="d-flex flex-wrap align-items-center">
            <div class="container">
                <a id="kembaliBtn" class="btn  btn-outline-primary">
                    Kembali</a>
            </div>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('pegawai.store') }}" method="POST">
                @csrf
                <div class="row">
                    <label class="form-label">Identitas</label>
                    <hr>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control"
                                placeholder="Masukkan Nama Lengkap" autocomplete="off" value="{{ old('nama_lengkap') }}">
                            @error('nama_lengkap')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nomor KTA</label>
                            <input type="number" name="no_kta" class="form-control" placeholder="Masukkan Nomor KTA.."
                                autocomplete="off" value="{{ old('no_kta') }}">
                            @error('no_kta')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" class="form-control" value="{{ old('tgl_lahir') }}">
                            @error('tgl_lahir')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jabatan</label>
                            <input type="text" name="jabatan" class="form-control text-capitalize"
                                placeholder="Masukkan Data Jabatan" value="{{ old('jabatan') }}" autocomplete="off">
                            @error('jabatan')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select single-select-field"
                                data-placeholder="Pilih Jenis Kelamin">
                                <option></option>
                                <option value="Laki-Laki" {{ old('jenis_kelamin') == 'Laki-Laki' ? 'selected' : '' }}>
                                    Laki-Laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>
                                    Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control"
                                placeholder="Masukkan Tempat Lahir" value="{{ old('tempat_lahir') }}">
                            @error('tempat_lahir')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea autocomplete="off" name="alamat" class="form-control" rows="13" placeholder="Masukkan Alamat Anda...">{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label class="form-label">Lainnya</label>
                    <hr>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label class="form-label">Nomor Handphone</label>
                            <input type="number" name="no_hp" class="form-control" placeholder="Masukkan Nomor Telepon"
                                value="{{ old('no_hp') }}" autocomplete="off">
                            @error('no_hp')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control select2-show-search form-select"
                                data-placeholder="Pilih Status">
                                <option label="Pilih Status"></option>
                                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="tidak aktif" {{ old('status') == 'tidak aktif' ? 'selected' : '' }}>Tidak
                                    Aktif</option>
                            </select>
                            @error('status')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label class="form-label">Tanggal Bergabung Pegawai</label>
                            <input type="date" name="tanggal_bergabung" class="form-control"
                                value="{{ old('tanggal_bergabung') }}">
                            @error('tanggal_bergabung')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" autocomplete="off" class="form-control" name="email"
                                placeholder="Masukkan Email" value="{{ old('email') }}">
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="text-end mt-3 mb-3">
                        <button type="submit" class="btn mb-1 btn-rounded btn-primary">
                            Tambah
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Opss',
                text: '{{ session('error') }}'
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
