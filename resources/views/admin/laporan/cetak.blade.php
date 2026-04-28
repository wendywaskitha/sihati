<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Harga Komoditas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .header { margin-bottom: 30px; }
        .header-title {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header-subtitle {
            font-size: 13px;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #444;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            float: right;
            text-align: right;
            width: 250px;
        }
    </style>
</head>
<body>
    <!-- Kop Surat (Header) -->
    <table style="width: 100%; border: none; margin-bottom: 10px;">
        <tr style="border: none;">
            @if(\App\Models\Pengaturan::getValue('app_logo'))
                <td style="width: 15%; border: none; text-align: left; vertical-align: middle;">
                    <img src="{{ public_path(\App\Models\Pengaturan::getValue('app_logo')) }}" style="height: 70px; max-width: 80px; object-fit: contain;" alt="Logo">
                </td>
            @endif
            <td style="border: none; text-align: center; vertical-align: middle;">
                <div style="font-size: 14px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;">PEMERINTAH KABUPATEN MUNA BARAT</div>
                <div style="font-size: 16px; font-weight: bold; text-transform: uppercase; margin-top: 2px; color: #111;">{{ \App\Models\Pengaturan::getValue('instansi_nama', 'DINAS PERTANIAN') }}</div>
                <div style="font-size: 11px; margin-top: 5px; color: #444;">{{ \App\Models\Pengaturan::getValue('instansi_alamat', 'Alamat instansi belum diatur di Pengaturan.') }}</div>
                <div style="font-size: 11px; font-style: italic; color: #444;">Email: {{ \App\Models\Pengaturan::getValue('instansi_email', '-') }}</div>
            </td>
        </tr>
    </table>
    <hr style="border: 1px solid #000; border-width: 2px 0 0 0; margin-top: 5px; margin-bottom: 20px;">

    <div class="header text-center">
        <div class="header-title" style="font-size: 14px; text-decoration: underline;">LAPORAN HARGA KOMODITAS PERTANIAN</div>
        <div class="header-subtitle" style="font-size: 12px; margin-top: 5px;">
            @if($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai'))
                Periode: {{ \Carbon\Carbon::parse($request->tanggal_mulai)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($request->tanggal_selesai)->format('d/m/Y') }}
            @elseif($request->filled('tanggal_mulai'))
                Sejak: {{ \Carbon\Carbon::parse($request->tanggal_mulai)->format('d/m/Y') }}
            @elseif($request->filled('tanggal_selesai'))
                Hingga: {{ \Carbon\Carbon::parse($request->tanggal_selesai)->format('d/m/Y') }}
            @else
                Periode: Seluruh Waktu
            @endif
        </div>
    </div>

    <div>
        <strong>Pasar:</strong> {{ $selected_pasar->nama ?? 'Semua Pasar' }} <br>
        <strong>Komoditas:</strong> {{ $selected_komoditas->nama ?? 'Semua Komoditas' }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th>Tanggal</th>
                <th>Komoditas</th>
                <th>Pasar</th>
                <th>Harga Min</th>
                <th>Harga Max</th>
                <th>Harga Rata-Rata</th>
            </tr>
        </thead>
        <tbody>
            @forelse($hargas as $index => $harga)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($harga->tanggal)->format('d/m/Y') }}</td>
                <td>{{ $harga->komoditas->nama ?? '-' }} ({{ $harga->komoditas->satuan ?? 'Kg' }})</td>
                <td>{{ $harga->pasar->nama ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($harga->harga_min, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($harga->harga_max, 0, ',', '.') }}</td>
                <td class="text-right" style="font-weight: bold;">Rp {{ number_format($harga->harga_avg, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data harga yang sesuai dengan filter.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Muna Barat, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</p>
        <p style="margin-bottom: 60px;">Mengetahui,<br>{{ \App\Models\Pengaturan::getValue('instansi_nama', 'Dinas Pertanian Kabupaten Muna Barat') }}</p>
        <p><strong><u>{{ \App\Models\Pengaturan::getValue('instansi_kepala', 'Administrator Sihati') }}</u></strong><br>
        @if(\App\Models\Pengaturan::getValue('instansi_pangkat'))
            {{ \App\Models\Pengaturan::getValue('instansi_pangkat') }}<br>
        @endif
        @if(\App\Models\Pengaturan::getValue('instansi_nip'))
            NIP. {{ \App\Models\Pengaturan::getValue('instansi_nip') }}
        @endif
        </p>
    </div>
</body>
</html>
