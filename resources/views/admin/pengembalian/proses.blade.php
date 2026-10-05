@extends('layouts.app')
@section('title', 'Proses Pengembalian')
@section('header-title', 'Proses Pengembalian Alat')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Detail Peminjaman</h2>

        <div class="grid grid-cols-2 gap-4 text-sm mb-4">
            <div>
                <span class="text-xs text-gray-400 block">Peminjam</span>
                <span class="font-medium text-gray-800">{{ $peminjaman->user->name ?? 'N/A' }}</span>
            </div>
            <div>
                <span class="text-xs text-gray-400 block">Status Saat Ini</span>
                @if($telat)
                    <span class="bg-red-100 text-red-800 text-xs px-3 py-1 rounded-full font-medium">Telat Kembali</span>
                @else
                    <span class="bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full font-medium">Tepat Waktu</span>
                @endif
            </div>
            <div>
                <span class="text-xs text-gray-400 block">Tgl Pinjam</span>
                <span class="text-gray-700">{{ \Carbon\Carbon::parse($peminjaman->tgl_pinjam)->format('Y-m-d') }}</span>
            </div>
            <div>
                <span class="text-xs text-gray-400 block">Rencana Kembali</span>
                <span class="text-gray-700">{{ \Carbon\Carbon::parse($peminjaman->tgl_kembali_plan)->format('Y-m-d') }}</span>
            </div>
        </div>

        <div>
            <span class="text-xs text-gray-400 block mb-1">Alat Dipinjam</span>
            <ul class="list-disc list-inside space-y-1 text-sm">
                @foreach($peminjaman->detailPinjam as $detail)
                    <li>
                        <span class="font-medium text-gray-800">{{ $detail->alat->nama_alat ?? 'Alat' }}</span>
                        <span class="text-xs text-gray-500">({{ $detail->jumlah }} pcs)</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Form Pengembalian</h2>

        @if ($errors->any())
            <div class="bg-red-50 text-red-700 text-sm rounded-lg p-3 mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.pengembalian.store', $peminjaman->id) }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="kondisi_kembali" class="block text-sm font-medium text-gray-700 mb-1">Kondisi Alat</label>
                <select name="kondisi_kembali" id="kondisi_kembali" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                    <option value="" selected disabled>-- Pilih Kondisi --</option>
                    <option value="Baik">Baik</option>
                    <option value="Rusak Ringan">Rusak Ringan</option>
                    <option value="Rusak Berat">Rusak Berat</option>
                    <option value="Hilang">Hilang</option>
                </select>
            </div>

            <div class="mb-6">
                <label for="denda" class="block text-sm font-medium text-gray-700 mb-1">Denda (Rp)</label>
                <input type="number" name="denda" id="denda" min="0" value="0" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.pengembalian.pilih') }}" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800">
                    Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2 rounded-lg transition">
                    Simpan Pengembalian
                </button>
            </div>
        </form>
    </div>

</div>
@endsection