@extends('layouts.master')

@section('title', 'Edit Data Pegawai')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <!-- Or for RTL support -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />

    <div class="card w-100 position-relative overflow-hidden">
        <div class="mt-2 mb-3 text-center">
            <h6 class="fs-4 fw-semibold mb-0">Edit Data Pegawai</h6>
        </div>
        <div class="d-flex flex-wrap align-items-center">
            <div class="container">
                <a id="kembaliBtn" class="btn  btn-outline-primary">
                    Kembali</a>
            </div>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('pegawai.update', $get_data->id) }}" method="POST">
                @csrf
                <div class="row">
                    <label class="form-label">Identitas</label>
                    <hr>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control"
                                placeholder="Masukkan Nama Lengkap" autocomplete="off"
                                value="{{ $get_data->nama_lengkap ?? null }}">
                            @error('nama_lengkap')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nomor KTA</label>
                            <input type="number" name="no_kta" class="form-control" placeholder="Masukkan Nomor KTA.."
                                autocomplete="off" value="{{ $get_data->no_kta ?? null }}">
                            @error('no_kta')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" class="form-control"
                                value="{{ $get_data->tgl_lahir ?? null }}">
                            @error('tgl_lahir')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jabatan</label>
                            <input type="text" name="jabatan" class="form-control text-capitalize"
                                placeholder="Masukkan Data Jabatan" value="{{ $get_data->jabatan ?? null }}">
                            @error('jabatan')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select single-select-field"
                                data-placeholder="Pilih Jenis Kelamin">
                                @if (isset($get_data->jenis_kelamin) && $get_data->jenis_kelamin == 'Laki-Laki')
                                    <option value="Laki-Laki" selected>Laki-Laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                @elseif(isset($get_data->jenis_kelamin) && $get_data->jenis_kelamin == 'Perempuan')
                                    <option value="Laki-Laki">Laki-Laki</option>
                                    <option value="Perempuan" selected>Perempuan</option>
                                @else
                                    <option value="Laki-Laki" {{ old('jenis_kelamin') == 'Laki-Laki' ? 'selected' : '' }}>
                                        Laki-Laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>
                                        Perempuan</option>
                                @endif
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
                                placeholder="Masukkan Tempat Lahir" value="{{ $get_data->tempat_lahir }}">
                            @error('tempat_lahir')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea autocomplete="off" name="alamat" class="form-control" rows="13" placeholder="Masukkan Alamat Anda...">{{ $get_data->alamat ?? null }}</textarea>
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
                                value="{{ $get_data->no_hp }}">
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
                                @if (isset($get_data->status) && $get_data->status == 'aktif')
                                    <option value="aktif" selected>Aktif</option>
                                    <option value="tidak aktif">Tidak Aktif</option>
                                @elseif(isset($get_data->status) && $get_data->status == 'tidak aktif')
                                    <option value="aktif">Aktif</option>
                                    <option value="tidak aktif" selected>Tidak Aktif</option>
                                @else
                                    <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="tidak aktif" {{ old('status') == 'tidak aktif' ? 'selected' : '' }}>
                                        Tidak Aktif</option>
                                @endif
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
                                value="{{ $get_data->tanggal_bergabung ?? null }}">
                            @error('tanggal_bergabung')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" autocomplete="off" class="form-control" name="email"
                                placeholder="Masukkan Email" value="{{ $get_data->email ?? null }}">
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="text-end mt-3 mb-3">
                        <button type="submit" class="btn mb-1 btn-rounded btn-primary">
                            Edit
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>


    {{-- <script>
        // Membuat referensi ke elemen input file
        const inputFoto = document.querySelector('input[name="foto"]');

        // Membuat referensi ke elemen div yang akan menampilkan gambar
        const tampilkanFoto = document.getElementById('tampilkanFoto');

        // Menambahkan event listener untuk input file
        inputFoto.addEventListener('change', function() {
            // Memastikan bahwa pengguna telah memilih file
            if (inputFoto.files.length > 0) {
                // Mendapatkan file yang dipilih oleh pengguna
                const file = inputFoto.files[0];

                // Mengecek apakah file yang dipilih adalah gambar
                if (file.type.startsWith('image/')) {
                    // Membuat objek URL untuk file yang dipilih
                    const imageUrl = URL.createObjectURL(file);

                    // Menampilkan gambar di dalam elemen img
                    tampilkanFoto.innerHTML = `<img src="${imageUrl}" alt="Foto" width='100px'>`;
                } else {
                    // Jika file yang dipilih bukan gambar, beri pesan kesalahan
                    tampilkanFoto.innerHTML = '<p class="text-danger">Pilih file gambar</p>';
                }
            } else {
                // Jika tidak ada file yang dipilih, kosongkan div
                tampilkanFoto.innerHTML = '';
            }
        });
    </script> --}}


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
