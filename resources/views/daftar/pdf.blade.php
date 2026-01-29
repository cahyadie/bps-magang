<!DOCTYPE html>
<html>
<head>
    <title>Detail Pendaftaran</title>
    <style>
        body { font-family: sans-serif; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #ddd; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 5px 0; color: #666; font-size: 14px; }
        
        .section-title { font-size: 16px; font-weight: bold; background-color: #f4f4f4; padding: 8px; margin-top: 20px; border-left: 4px solid #d97757; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th, table td { text-align: left; padding: 8px; border-bottom: 1px solid #eee; font-size: 14px; vertical-align: top; }
        table th { width: 35%; color: #555; }
        
        .status-badge { display: inline-block; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; color: white; }
        .bg-green { background-color: #2ecc71; }
        .bg-red { background-color: #e74c3c; }
        .bg-orange { background-color: #e67e22; }
        .bg-gray { background-color: #95a5a6; }

        .photo-container { text-align: center; margin-top: 20px; }
        .photo-img { width: 150px; height: 180px; object-fit: cover; border: 1px solid #ddd; padding: 3px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Bukti Pendaftaran Magang</h1>
        <p>Dicetak pada: {{ date('d F Y') }}</p>
    </div>

    @if($pendaftaran->pas_foto)
        <div class="photo-container">
            {{-- Gunakan public_path agar dompdf bisa membaca file lokal --}}
            <img src="{{ public_path('storage/' . $pendaftaran->pas_foto) }}" class="photo-img">
        </div>
    @endif

    <div class="section-title">Informasi Peserta</div>
    <table>
        <tr>
            <th>Nama Lengkap</th>
            <td>{{ $pendaftaran->nama_pendaftar }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $pendaftaran->email }}</td>
        </tr>
        
        {{-- [BARU] Menambahkan Agama, Provinsi, Kabupaten, Alamat --}}
        <tr>
            <th>Agama</th>
            <td>{{ $pendaftaran->agama ?? '-' }}</td>
        </tr>
        <tr>
            <th>Provinsi Domisili</th>
            <td>{{ $pendaftaran->provinsi ?? '-' }}</td>
        </tr>
        <tr>
            <th>Kabupaten / Kota</th>
            <td>{{ $pendaftaran->kabupaten ?? '-' }}</td>
        </tr>
        <tr>
            <th>Alamat Lengkap</th>
            <td>{{ $pendaftaran->alamat ?? '-' }}</td>
        </tr>
        {{-- [END BARU] --}}

        <tr>
            <th>Asal Kampus</th>
            <td>{{ $pendaftaran->asal_kampus }}</td>
        </tr>
        <tr>
            <th>Prodi / Jurusan</th>
            <td>{{ $pendaftaran->prodi }}</td>
        </tr>
        <tr>
            <th>Periode Magang</th>
            <td>
                {{ \Carbon\Carbon::parse($pendaftaran->tanggal_mulai)->format('d M Y') }} - 
                {{ \Carbon\Carbon::parse($pendaftaran->tanggal_selesai)->format('d M Y') }}
            </td>
        </tr>
        <tr>
            <th>Status Pendaftaran</th>
            <td>
                @php
                    $badgeClass = match($pendaftaran->status) {
                        'approved' => 'bg-green',
                        'rejected' => 'bg-red',
                        'conditional' => 'bg-orange',
                        default => 'bg-gray',
                    };
                    $statusLabel = match($pendaftaran->status) {
                        'approved' => 'DISETUJUI',
                        'rejected' => 'DITOLAK',
                        'conditional' => 'BERSYARAT',
                        default => 'MENUNGGU',
                    };
                @endphp
                <span class="status-badge {{ $badgeClass }}">{{ $statusLabel }}</span>
            </td>
        </tr>
    </table>

    @if($pendaftaran->remarks)
    <div class="section-title">Catatan</div>
    <div style="padding: 10px; font-size: 14px; border: 1px dashed #ccc; margin-top:10px;">
        {{ $pendaftaran->remarks }}
    </div>
    @endif

</body>
</html>