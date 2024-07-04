@extends('layouts.master')

@section('title', 'Absensi')

@section('content')
    <style>
        .dropzone {
            border: 2px dashed #ccc;
            padding: 20px;
            text-align: center;
            cursor: pointer;
        }



        .dropzone:hover {
            border-color: #aaa;
        }

        .dropzone__text {
            display: block;
            margin-bottom: 10px;
        }

        .dropzone__input {
            display: none;
        }

        .loader {
            display: block !important;
            border: 16px solid #f3f3f3;
            margin-top: 20px;
            margin-left: auto;
            margin-right: auto;
            border-top: 16px solid #061f72;
            border-radius: 50%;
            width: 120px;
            height: 120px;
            text-align: center;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>



    @if ($jamAbsen >= $jamMulaiMasuk && $jamAbsen <= $jamBatasAbsenMasuk)
        @if (!$absenHariIni)
            <div class="container">
                <h5 class="fw-semibold mb-0 mt-3 mb-3">Hi👋, Selamat Datang Nama Pengguna, Silahkan Absen Masuk</h5>
            </div>
            <div class="card w-100 position-relative overflow-hidden">
                <div class="card-body p-4 mt-3">
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-xl-3 col-sm-3">
                                <label class="form-label"><strong>Nip</strong></label>
                            </div>
                            <div class="col-xl-9 col-sm-9">
                                <input type="text" readonly value="19092828" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-xl-3 col-sm-3">
                                <label class="form-label"><strong>Nama Lengkap</strong></label>
                            </div>
                            <div class="col-xl-9 col-sm-9">
                                <input type="text" readonly value="{{ Auth::user()->pegawai->nama_lengkap ?? null }}"
                                    class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="row">
                            <div class="col-xl-3 col-sm-3">
                                <label class="form-label"><strong>Jabatan</strong></label>
                            </div>
                            <div class="col-xl-9 col-sm-9">
                                <input type="text" readonly value="{{ Auth::user()->pegawai->jabatan ?? null }}"
                                    class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <form action="{{ route('absensi.absen') }}" method="POST">
                            @csrf
                            <button class="btn btn-success" type="submit">Absen</button>
                        </form>
                        <a class="btn btn-outline-primary" data-bs-effect="effect-fall" data-bs-toggle="modal"
                            href="#modaldemo8">Izin Atau Lainnya</a>
                    </div>

                    <div class="mt-3">
                        <label class="form-label"><strong>*Note</strong></label>
                        <table>
                            <tr>
                                <td><strong>Izin</strong></td>
                                <td>=</td>
                                <td>Batas Mengajukan Izin Maksimal 1 Kali Dalam 1 Bulan</td>
                            </tr>
                            <tr>
                                <td><strong>Sakit</strong></td>
                                <td>=</td>
                                <td>Batas Mengajukan Sakit Maksimal 2 Kali Dalam 1 Bulan</td>
                            </tr>
                        </table>
                    </div>

                    <div class="modal fade" id="modaldemo8">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content modal-content-demo">
                                <div class="modal-header">
                                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('absensi.absensilainnya') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row g-3">
                                            <div class="mb-3">
                                                <div class="row">
                                                    <div class="mb-3">
                                                        <label class="form-check-label" for="izin">
                                                            Pilih Jenis Absensi
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-6 col-sm-12">
                                                        <div class="form-check form-radio-warning mb-3">
                                                            <input class="form-check-input" type="radio"
                                                                name="jenis_absen" id="izin" value="izin">
                                                            <label class="form-check-label" for="izin">
                                                                Izin
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6 col-sm-12">
                                                        <div class="form-check form-radio-primary mb-3">
                                                            <input class="form-check-input" type="radio"
                                                                name="jenis_absen" id="sakit" value="sakit">
                                                            <label class="form-check-label" for="sakit">
                                                                Sakit
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-3" id="form-izin" style="display: none">
                                                <div class="d-grid">
                                                    <button class="btn btn-primary" type="submit">Klik</button>
                                                </div>
                                            </div>

                                            <div class="mb-3" id="form" style="display: none;">
                                                <label class="form-label" id="suratSakit">Surat Sakit</label>
                                                <div class="col-lg-12 mb-4">
                                                    <div class="dropzone" id="dropzone">
                                                        <img id="DefaultGambar"
                                                            src="{{ asset('assets/images/attack.svg') }}" alt="DATA"
                                                            width="200">
                                                        <div id="SrcLoading" style="display: none;">
                                                            <img src="{{ asset('assets/images/pdf.png') }}" alt="DATA"
                                                                width="100px">
                                                        </div>

                                                        <div id="Couuus" class="s"></div>
                                                        <span class="dropzone__text" id="dropzone-preview">Drag & Drop
                                                            files
                                                            here
                                                            or
                                                            click to
                                                            upload</span>
                                                        <input name="file_berkas" type="file" class="dropzone__input"
                                                            id="fileInput">
                                                    </div>
                                                    <small class="text-danger" id="legend">Format Harus .PDF</small>
                                                </div>
                                                <div class="d-grid">
                                                    <button class="btn btn-primary" type="submit">Klik</button>
                                                </div>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
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
                                    <h3 class="fw-semibold">Anda Sudah Absen</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @elseif($jamAbsen >= $jamMulaiPulang && $jamAbsen <= $jamBatasAbsenPulang)
        @if (!$absenHariIni)
            <div class="container">
                <h5 class="fw-semibold mb-0 mt-3 mb-3">Hi👋, Selamat Datang Nama Pengguna, Silahkan Absen Pulang</h5>
            </div>
            <div class="card w-100 position-relative overflow-hidden">
                <div class="card-body p-4 mt-3">
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-xl-3 col-sm-3">
                                <label class="form-label"><strong>Nip</strong></label>
                            </div>
                            <div class="col-xl-9 col-sm-9">
                                <input type="text" readonly value="19092828" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-xl-3 col-sm-3">
                                <label class="form-label"><strong>Nama Lengkap</strong></label>
                            </div>
                            <div class="col-xl-9 col-sm-9">
                                <input type="text" readonly value="Frans Bachtiar, S.Kom" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="row">
                            <div class="col-xl-3 col-sm-3">
                                <label class="form-label"><strong>Jabatan</strong></label>
                            </div>
                            <div class="col-xl-9 col-sm-9">
                                <input type="text" readonly value="Ketua Pengurus" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <form action="{{ route('absensi.absen') }}" method="POST">
                            @csrf
                            <button class="btn btn-success" type="submit">Absen</button>
                        </form>
                        <a class="btn btn-outline-primary" data-bs-effect="effect-fall" data-bs-toggle="modal"
                            href="#modaldemo8">Izin Atau Lainnya</a>
                    </div>

                    <div class="mt-3">
                        <label class="form-label"><strong>*Note</strong></label>
                        <table>
                            <tr>
                                <td><strong>Izin</strong></td>
                                <td>=</td>
                                <td>Batas Mengajukan Izin Maksimal 1 Kali Dalam 1 Bulan</td>
                            </tr>
                            <tr>
                                <td><strong>Sakit</strong></td>
                                <td>=</td>
                                <td>Batas Mengajukan Sakit Maksimal 2 Kali Dalam 1 Bulan</td>
                            </tr>
                        </table>
                    </div>

                    <div class="modal fade" id="modaldemo8">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content modal-content-demo">
                                <div class="modal-header">
                                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('absensi.absensilainnya') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row g-3">
                                            <div class="mb-3">
                                                <div class="row">
                                                    <div class="mb-3">
                                                        <label class="form-check-label" for="izin">
                                                            Pilih Jenis Absensi
                                                        </label>
                                                    </div>
                                                    <div class="col-xl-6 col-sm-12">
                                                        <div class="form-check form-radio-warning mb-3">
                                                            <input class="form-check-input" type="radio"
                                                                name="jenis_absen" id="izin" value="izin">
                                                            <label class="form-check-label" for="izin">
                                                                Izin
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6 col-sm-12">
                                                        <div class="form-check form-radio-primary mb-3">
                                                            <input class="form-check-input" type="radio"
                                                                name="jenis_absen" id="sakit" value="sakit">
                                                            <label class="form-check-label" for="sakit">
                                                                Sakit
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-3" id="form-izin" style="display: none">
                                                <div class="d-grid">
                                                    <button class="btn btn-primary" type="submit">Klik</button>
                                                </div>
                                            </div>

                                            <div class="mb-3" id="form" style="display: none;">
                                                <label class="form-label" id="suratSakit">Surat Sakit</label>
                                                <div class="col-lg-12 mb-4">
                                                    <div class="dropzone" id="dropzone">
                                                        <img id="DefaultGambar"
                                                            src="{{ asset('assets/images/attack.svg') }}" alt="DATA"
                                                            width="200">
                                                        <div id="SrcLoading" style="display: none;">
                                                            <img src="{{ asset('assets/images/pdf.png') }}" alt="DATA"
                                                                width="100px">
                                                        </div>

                                                        <div id="Couuus" class="s"></div>
                                                        <span class="dropzone__text" id="dropzone-preview">Drag & Drop
                                                            files
                                                            here
                                                            or
                                                            click to
                                                            upload</span>
                                                        <input name="file_berkas" type="file" class="dropzone__input"
                                                            id="fileInput">
                                                    </div>
                                                    <small class="text-danger" id="legend">Format Harus .PDF</small>
                                                </div>
                                                <div class="d-grid">
                                                    <button class="btn btn-primary" type="submit">Klik</button>
                                                </div>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
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
                                    <h3 class="fw-semibold">Anda Sudah Absen</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @elseif ($jamAbsen > $jamBatasAbsenMasuk && $jamAbsen <= $jamMulaiPulang)
        <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full"
            data-sidebar-position="fixed" data-header-position="fixed">
            <div class="position-relative overflow-hidden min-vh-100 d-flex align-items-center justify-content-center">
                <div class="d-flex align-items-center justify-content-center w-100">
                    <div class="row justify-content-center w-100">
                        <div class="col-lg-4">
                            <div class="text-center">
                                <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/backgrounds/errorimg.svg"
                                    alt="" class="img-fluid">
                                <h3 class="fw-semibold">Maaf, Waktu Absen Masuk Telah Habis!</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($jamAbsen > $jamBatasAbsenPulang && $jamAbsen <= strtotime('17:30'))
        <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full"
            data-sidebar-position="fixed" data-header-position="fixed">
            <div class="position-relative overflow-hidden min-vh-100 d-flex align-items-center justify-content-center">
                <div class="d-flex align-items-center justify-content-center w-100">
                    <div class="row justify-content-center w-100">
                        <div class="col-lg-4">
                            <div class="text-center">
                                <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/backgrounds/errorimg.svg"
                                    alt="" class="img-fluid">
                                <h3 class="fw-semibold">Maaf, Waktu Absen Pulang Telah Habis!</h3>
                            </div>
                        </div>
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
                                <h5 class="fw-semibold">Belum Bisa Absen, Absen Masuk Dimulai Dari Jam 06:15 Dan Jam Pulang
                                    Dimulai Dari Jam 13:00. Terimakasih!</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


    {{-- @if ($data_get->count() > 0)
        <div class="card w-100 position-relative overflow-hidden">
            <div class="mt-2 mb-3 text-center">
                <h4 class=" fw-semibold mb-0 mt-3">Lowongan</h4>
            </div>
            <div class="d-flex flex-wrap align-items-center">
                <div class="container">
                    <a href="{{ route('lowongan.create') }}" class="btn  btn-outline-primary"><i class="fe fe-plus me-2"></i>
                        Tambah</a>
                    <input type="text" class="mt-2 search" id="search-table" placeholder="Cari...."
                        style="position: absolute; right: 25px !important; height: 30px !important; border: 1px solid #dfe5ef; border-radius: 4px; padding: 10px;">
                </div>
            </div>
            <div class="card-body p-4 mt-3">
                <div class="table-responsive table-card mt-1 mb-4">
                    <table class="table table-bordered align-middle table-nowrap text-center">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Lowongan</th>

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
                                    <td>{{ $data->nama_lowongan ?? '' }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <div class="edit">
                                                <a class="btn btn-sm btn-success edit-item-btn"
                                                    href="{{ route('lowongan.edit', $data->id) }}">Edit</a>
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
                                                <form action="{{ route('lowongan.hapus', $data->id) }}" method="POST">
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
                    <div class="noresult" id="no-data" style="display: none;">
                        <div class="text-center">
                            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                            <h5 class="mt-2">Maaf! Tidak Ada Data </h5>

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
                                <a href="{{ route('lowongan.create') }}" class="btn btn-primary"><i
                                        class="fe fe-plus me-2"></i>

                                    Tambah</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif --}}

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

    <script>
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('fileInput');
        const dropzonePreview = document.getElementById('dropzone-preview');
        const Legenda = document.getElementById('legend');
        const DefaultGambar = document.getElementById('DefaultGambar');
        const PdfFoto = document.getElementById('SrcLoading');
        const loadingIndicator = document.getElementById('Couuus');

        dropzone.addEventListener('dragover', (event) => {
            event.preventDefault();
            dropzone.classList.add('dragover');
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('dragover');
        });

        dropzone.addEventListener('drop', (event) => {
            event.preventDefault();
            dropzone.classList.remove('dragover');
            const files = event.dataTransfer.files;
            handleFiles(files);
            fileInput.files = files;
        });

        fileInput.addEventListener('change', (event) => {
            const files = event.target.files;
            handleFiles(files);
        });

        // Add click event to trigger file input
        dropzone.addEventListener('click', () => {
            fileInput.click();

        });

        function handleFiles(files) {
            // Menghapus semua elemen yang ada di dalam dropzone-preview
            dropzonePreview.innerHTML = '';

            for (const file of files) {
                console.log('Uploaded file:', file.name);

                // Menampilkan nama file setelah diupload
                const fileItem = document.createElement('li');
                fileItem.textContent = file.name;

                // Menambahkan kelas CSS sesuai dengan ekstensi file
                const fileExt = file.name.split('.').pop().toLowerCase();
                if (fileExt === 'pdf') {
                    DefaultGambar.style.display = "none";
                    loadingIndicator.classList.remove('s');
                    loadingIndicator.classList.add('loader');
                    setTimeout(() => {
                        loadingIndicator.classList.remove('loader');
                        PdfFoto.style.display = "block";
                        fileItem.classList.add('text-success');
                        Legenda.innerHTML = "";
                    }, 2000);
                } else {
                    DefaultGambar.style.display = "none";
                    loadingIndicator.classList.remove('s');
                    loadingIndicator.classList.add('loader');
                    setTimeout(() => {
                        loadingIndicator.classList.remove('loader');
                        PdfFoto.style.display = "block";
                        fileItem.classList.add('text-danger');
                        fileItem.textContent += ' - FORMAT TIDAK SESUAI!!';
                    }, 2000);

                }
                dropzonePreview.appendChild(fileItem);
                Legenda.appendChild(Legenda);
            }
        }
    </script>

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
