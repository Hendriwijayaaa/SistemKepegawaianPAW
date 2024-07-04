@extends('layouts.master')

@section('title', 'Edit Data Laporan Kinerja Harian')

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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <!-- Or for RTL support -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />

    @if ($absenMasuk && !$absenPulang)
        <div class="card w-100 position-relative overflow-hidden">
            <div class="mt-2 mb-3 text-center">
                <h4 class="fw-semibold mb-0">Edit Laporan Kinerja Harian</h4>
            </div>
            <div class="d-flex flex-wrap align-items-center">
                <div class="container">
                    <a id="kembaliBtn" class="btn  btn-outline-primary">Kembali</a>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('laporankinerja.update', $getData->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-xl-4 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Kegiatan</label>
                                <input type="date" name="tanggal_kegiatan" class="form-control" autocomplete="off"
                                    value="{{ $getData->tanggal_kegiatan ?? null }}" readonly>
                                @error('tanggal_kegiatan')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xl-4 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Jam Mulai</label>
                                <input type="time" name="jam_mulai" class="form-control" autocomplete="off"
                                    value="{{ $getData->jam_absen ?? '01:00' }}" readonly>
                                @error('jam_mulai')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xl-4 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Jam Selesai</label>
                                <input type="time" name="jam_berakhir" class="form-control"
                                    placeholder="Masukkan Tempat Lahir" value="{{ $getData->jam_berakhir ?? null }}"
                                    readonly>
                                @error('jam_berakhir')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-xl-12 col-sm-12">
                            <div class="mb-3">
                                <label class="form-label">Deskripsi Kegiatan</label>
                                <textarea autocomplete="off" name="keterangan_kegiatan" class="form-control text-capitalize" rows="8"
                                    placeholder="Masukkan Deskripsi Kegiatan...">{{ $getData->keterangan_kegiatan ?? null }}</textarea>
                                @error('keterangan_kegiatan')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3" id="form">
                            <label class="form-label" id="suratSakit">Bukti Kegiatan</label>
                            <div class="col-xl-12 col-sm-12 mb-4">
                                <div class="dropzone" id="dropzone">
                                    <img id="DefaultGambar" src="{{ asset('assets/images/attack.svg') }}" alt="DATA"
                                        width="200">
                                    <div id="SrcLoading" style="display: none;">
                                        <img src="{{ asset('assets/images/pdf.png') }}" alt="DATA" width="100px">
                                    </div>

                                    <div id="Couuus" class="s"></div>
                                    <span class="dropzone__text" id="dropzone-preview">Drag & Drop files
                                        here
                                        or
                                        click to
                                        upload</span>
                                    <input name="bukti_kegiatan" type="file" class="dropzone__input" id="fileInput"
                                        value="{{ $getData->bukti_kegiatan ?? null }}">
                                </div>
                                <small class="text-danger" id="legend">Format Harus .jpg, jpeg, .png, .heic</small>
                            </div>

                        </div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn mb-1 btn-rounded btn-primary">
                            Edit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @elseif ($absenPulang)
        <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full"
            data-sidebar-position="fixed" data-header-position="fixed">
            <div class="position-relative overflow-hidden min-vh-100 d-flex align-items-center justify-content-center">
                <div class="d-flex align-items-center justify-content-center w-100">
                    <div class="row justify-content-center w-100">
                        <div class="col-lg-4">
                            <div class="text-center">
                                <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/backgrounds/errorimg.svg"
                                    alt="" class="img-fluid">
                                <h3 class="fw-semibold">Laporan Kinerja Harian Berakhir</h3>
                                <p>Besok akan dibuka lagi setelah Anda absen masuk.</p>
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
                                <h3 class="fw-semibold">Harap Absen Masuk Terlebih Dahulu</h3>
                                <p>Untuk memulai laporan kinerja harian, Anda harus absen masuk terlebih dahulu.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

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
                if (['jpg', 'jpeg', 'png', 'heic'].includes(fileExt)) {
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
