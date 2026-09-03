@extends('layouts.app')

@section('title', 'Proses Pengembalian')
@section('header-title', 'Proses Pengembalian Alat')

@section('content')

<div class="bg-white rounded-lg shadow p-6 max-w-xl">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Proses Pengembalian</h2>

    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
        <p class="text-sm text-gray-500 mb-1">Peminjam</p>
        <p class="font-semibold text-gray-800 mb-3">{{ $peminjaman->user->name ?? 'N/A' }}</p>

        <p class="text-sm text-gray-500 mb-1">Alat yang dipinjam</p>
        <ul class="list-disc list-inside text-sm text-gray-700">
            @foreach($peminjaman->detailPinjam as $detail)
                <li>{{ $detail->alat->nama_alat ?? 'Alat' }} — {{ $detail->jumlah }} pcs</li>
            @endforeach
        </ul>
    </div>

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.pengembalian.store', $peminjaman->id) }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kembali</label>
            <input type="date" name="tgl_kembali" value="{{ old('tgl_kembali', now()->format('Y-m-d')) }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kondisi Alat</label>
            <select name="kondisi_kembali" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="Baik">Baik</option>
                <option value="Rusak Ringan">Rusak Ringan</option>
                <option value="Rusak Berat">Rusak Berat</option>
                <option value="Hilang">Hilang</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Denda (Rp)</label>
            <input type="number" name="denda" value="{{ old('denda', 0) }}" min="0"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <p class="text-xs text-gray-400 mt-1">Isi 0 jika tidak ada denda.</p>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2 rounded-lg transition">
                Simpan Pengembalian
            </button>
            <a href="{{ route('admin.pengembalian.pilih') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-semibold px-6 py-2 rounded-lg transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection