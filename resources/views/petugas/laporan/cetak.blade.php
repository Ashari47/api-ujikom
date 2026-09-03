<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cetak Laporan Peminjaman</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            font-size: 14px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .info {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
        }

        th {
            text-align: center;
            background-color: #eee;
        }

        td {
            vertical-align: top;
        }

        .center {
            text-align: center;
        }

        .no-print {
            margin-bottom: 20px;
        }

        button {
            padding: 8px 15px;
            cursor: pointer;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                margin: 10px;
            }
        }
    </style>
</head>

<body>

    {{-- Tombol cetak --}}
    <div class="no-print">
        <button onclick="window.print()">
            Cetak Laporan
        </button>
    </div>


    <h2>LAPORAN PEMINJAMAN ALAT</h2>


    {{-- Informasi filter --}}
    <div class="info">

        <p>
            <strong>Status:</strong>
            {{ $status ? ucfirst($status) : 'Semua Status' }}
        </p>

        <p>
            <strong>Periode:</strong>

            {{ $dari_tanggal ?: '-' }}

            s/d

            {{ $sampai_tanggal ?: '-' }}
        </p>

    </div>


    <table>

        <thead>

            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Tanggal Pinjam</th>
                <th>Alat</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th>Tanggal Kembali</th>
                <th>Denda</th>
            </tr>

        </thead>


        <tbody>

            @forelse ($laporans as $laporan)

                <tr>

                    {{-- Nomor --}}
                    <td class="center">
                        {{ $loop->iteration }}
                    </td>


                    {{-- Nama peminjam --}}
                    <td>
                        {{ $laporan->user->name ?? '-' }}
                    </td>


                    {{-- Tanggal pinjam --}}
                    <td>
                        {{ $laporan->tgl_pinjam ?? '-' }}
                    </td>


                    {{-- Alat --}}
                    <td>

                        @if ($laporan->detailPinjam)

                            @foreach ($laporan->detailPinjam as $detail)

                                {{ $detail->alat->nama_alat ?? '-' }}

                                @if (!$loop->last)
                                    <br>
                                @endif

                            @endforeach

                        @else

                            -

                        @endif

                    </td>


                    {{-- Jumlah --}}
                    <td class="center">

                        @if ($laporan->detailPinjam)

                            @foreach ($laporan->detailPinjam as $detail)

                                {{ $detail->jumlah }}

                                @if (!$loop->last)
                                    <br>
                                @endif

                            @endforeach

                        @else

                            -

                        @endif

                    </td>


                    {{-- Status --}}
                    <td>
                        {{ ucfirst($laporan->status ?? '-') }}
                    </td>


                    {{-- Tanggal kembali --}}
                    <td>

                        @if ($laporan->pengembalian)

                            {{ $laporan->pengembalian->tgl_kembali ?? '-' }}

                        @else

                            -

                        @endif

                    </td>


                    {{-- Denda --}}
                    <td>

                        @if ($laporan->pengembalian)

                            Rp
                            {{ number_format($laporan->pengembalian->denda ?? 0, 0, ',', '.') }}

                        @else

                            Rp 0

                        @endif

                    </td>

                </tr>


            @empty

                <tr>

                    <td colspan="8" class="center">
                        Tidak ada data laporan.
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>


</body>

</html>