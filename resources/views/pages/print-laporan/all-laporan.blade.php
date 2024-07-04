<!DOCTYPE html>
<html>

<head>
    <style>
        h2 {
            display: block;
            margin: auto;
            text-align: center;
            margin-bottom: 8px;
            font-family: sans-serif;
            font-size: 15px;
        }

        h3 {
            font-size: 13px;
        }

        .tabel-kop,
        .kop td,
        .kop th {
            border: none !important;
            padding: 2px;
            font-size: 11px;
        }

        .kop img {
            width: 50px;
        }

        .pegawai-container {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>
    @foreach ($laporanGroupedByPegawai as $pegawaiId => $laporan)
        @php
            $pegawai = $laporan->first()->pegawai;
        @endphp
        <div class="pegawai-container">

        </div>
        <table class="tabel-kop" style="width: 100%; text-align: center; margin-bottom: 20px;">
            <tr>
                @php
                    $imageUrl = public_path('assets/images/logo-pramukaa.png');
                    $imageData = base64_encode(file_get_contents($imageUrl));
                    $imageSrc = 'data:image/jpeg;base64,' . $imageData;
                @endphp
                <td class="kop" style="width: 8%;"><img src="{{ $imageSrc }}" alt="Logo" style="width: 100%;">
                </td>
                <td class="kop">
                    <h2>GERAKAN PRAMUKA</h2>
                    <h3 style="text-align: center;">KWARTIR DAERAH SUMATERA SELATAN</h3>
                    <p style="text-align: center;">Jl. Aerobik POM IX Kampus No. 1294 Telp./Fax. 0711-350074</p>
                    <p style="text-align: center;">Palembang 30137, Email: kwardasumsel05@yahoo.com</p>
                </td>
            </tr>
        </table>
        <hr style="margin-top: -20px; margin-bottom: 20px; height: 2px; border: none; background-color: black;" />
        @if ($jenis === 'seluruh')
            <h2>Laporan Kinerja Harian Tahun {{ $tahun ?? null }}</h2>
        @else
            @php
                $months = [
                    1 => 'Januari',
                    2 => 'Februari',
                    3 => 'Maret',
                    4 => 'April',
                    5 => 'Mei',
                    6 => 'Juni',
                    7 => 'Juli',
                    8 => 'Agustus',
                    9 => 'September',
                    10 => 'Oktober',
                    11 => 'November',
                    12 => 'Desember',
                ];
                $namaBulan = $months[$bulan];
            @endphp
            <h2>Laporan Kinerja Harian Bulan {{ $namaBulan }} Tahun {{ $tahun ?? null }}</h2>
        @endif
        <table style="margin-bottom: 8px;">
            <tr>
                <td><strong style="font-size: 12px;">Nama Pegawai</strong></td>
                <td>:</td>
                <td><span style="font-size: 12px;">{{ $pegawai->nama_lengkap }}</span></td>
            </tr>
            <tr>
                <td><strong style="font-size: 12px;">Jabatan</strong></td>
                <td>:</td>
                <td><span style="font-size: 12px; text-transform: capitalize;">{{ $pegawai->jabatan }}</span></td>
            </tr>
        </table>
        <table style="border-collapse: collapse; width: 100%; text-transform: capitalize;">
            <thead>
                <tr>
                    <th style="border: 1px solid #080808; text-align: center; padding: 4px; font-size: 10px;">No</th>
                    <th style="border: 1px solid #080808; text-align: center; padding: 4px; font-size: 10px;">Tanggal
                    </th>
                    <th style="border: 1px solid #080808; text-align: center; padding: 4px; font-size: 10px;">Jam Mulai
                    </th>
                    <th style="border: 1px solid #080808; text-align: center; padding: 4px; font-size: 10px;">Jam
                        Selesai</th>
                    <th style="border: 1px solid #080808; text-align: center; padding: 4px; font-size: 10px;">Kegiatan
                        Yang Dilakukan</th>
                    <th style="border: 1px solid #080808; text-align: center; padding: 4px; font-size: 10px;">Kegiatan
                        Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($laporan as $index => $data)
                    <tr>
                        <td style="border: 1px solid #080808; text-align: center; padding: 4px; font-size: 10px;">
                            {{ $index + 1 }}</td>
                        <td style="border: 1px solid #080808; text-align: center; padding: 4px; font-size: 10px;">
                            {{ optional(\Carbon\Carbon::parse($data->tanggal_kegiatan)->locale('id'))->isoFormat('DD MMMM YYYY') ?? '' }}
                        </td>
                        <td style="border: 1px solid #080808; text-align: center; padding: 4px; font-size: 10px;">
                            {{ $data->jam_mulai }}</td>
                        <td style="border: 1px solid #080808; text-align: center; padding: 4px; font-size: 10px;">
                            {{ $data->jam_berakhir }}</td>
                        <td style="border: 1px solid #080808; text-align: center; padding: 4px; font-size: 10px;">
                            @if (isset($data->keterangan_kegiatan) && strlen($data->keterangan_kegiatan) > 30)
                                @foreach (str_split($data->keterangan_kegiatan, 30) as $chunk)
                                    {{ $chunk }}<br>
                                @endforeach
                            @else
                                {{ $data->keterangan_kegiatan ?? '' }}
                            @endif
                        </td>
                        <td style="border: 1px solid #080808; text-align: center; padding: 4px; font-size: 10px;">
                            @if ($data->is_verify === 0)
                                <div class="badge bg-warning  me-1 mb-1 mt-1">Menunggu</div>
                            @elseif ($data->is_verify === 1)
                                <div class="badge bg-danger  me-1 mb-1 mt-1">Tolak</div>
                            @elseif ($data->is_verify === 2)
                                <div class="badge bg-success  me-1 mb-1 mt-1">Acc</div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div style="page-break-after: always;"></div>
    @endforeach
</body>

</html>
