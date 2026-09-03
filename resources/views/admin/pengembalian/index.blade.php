@extends('layouts.app')

@section('title', 'Kelola Pengembalian')
@section('header-title', 'Riwayat Pengembalian Alat')

@section('content')

@if(session('success'))
    <div class="mb-4 p-4 bg-emerald-100 border border-emerald-300 text-emerald-700 rounded-lg text-sm">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg text-sm">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-lg shadow p-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <h2 class="text-xl font-bold text-gray-800">Riwayat Pengembalian</h2>

        <div class="flex items-center gap-3">
            <form action="{{ route('admin.pengembalian.index') }}" method="GET" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama peminjam..." class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-64">
                <button type="submit" class="bg-gray-900 hover:bg-gray-800 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
                    Cari
                </button>
            </form>

            <a href="{{ route('admin.pengembalian.pilih') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition flex items-center gap-1">
                + Proses Pengembalian
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                    <th class="py-3 px-4 font-semibold">PEMINJAM</th>
                    <th class="py-3 px-4 font-semibold">ALAT DIKEMBALIKAN</th>
                    <th class="py-3 px-4 font-semibold">TGL KEMBALI</th>
                    <th class="py-3 px-4 font-semibold">KONDISI</th>
                    <th class="py-3 px-4 font-semibold">DENDA</th>
                    <th class="py-3 px-4 font-semibold">PETUGAS</th>
                    <th class="py-3 px-4 font-semibold text-center">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($pengembalians as $p)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-4 font-medium text-gray-800">
                        {{ $p->peminjaman->user->name ?? 'N/A' }}
                    </td>

                    <td class="py-4 px-4 text-gray-600">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($p->peminjaman->detailPinjam as $detail)
                                <li>
                                    <span class="font-medium text-gray-800">{{ $detail->alat->nama_alat ?? 'Alat' }}</span>
                                    <span class="text-xs text-gray-500">({{ $detail->jumlah }} pcs)</span>
                                </li>
                            @endforeach
                        </ul>
                    </td>

                    <td class="py-4 px-4 text-gray-600">
                        {{ \Carbon\Carbon::parse($p->tgl_kembali)->format('Y-m-d') }}
                    </td>

                    <td class="py-4 px-4">
                        @php
                            $kondisiColor = match(true) {
                                str_contains(strtolower($p->kondisi_kembali), 'baik') => 'bg-green-100 text-green-800',
                                str_contains(strtolower($p->kondisi_kembali), 'hilang') => 'bg-red-100 text-red-800',
                                default => 'bg-orange-100 text-orange-800',
                            };
                        @endphp
                        <span class="{{ $kondisiColor }} text-xs px-3 py-1 rounded-full font-medium">{{ $p->kondisi_kembali }}</span>
                    </td>

                    <td class="py-4 px-4 text-gray-600">
                        Rp {{ number_format($p->denda, 0, ',', '.') }}
                    </td>

                    <td class="py-4 px-4 text-gray-600">
                        {{ $p->petugas->name ?? '-' }}
                    </td>

                    <td class="py-4 px-4 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <a href="{{ route('admin.pengembalian.edit', $p->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-semibold px-4 py-1 rounded transition w-full">
                                Edit
                            </a>
                            <form action="{{ route('admin.pengembalian.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Yakin batalkan pengembalian ini? Stok akan dikurangi lagi dan status peminjaman kembali ke Dipinjam.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-xs font-semibold px-4 py-1 rounded transition w-full">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-6 text-center text-gray-400">Belum ada data pengembalian.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $pengembalians->links() }}
    </div>
</div>
@endsection