@extends('layouts.app')

@section('content')
@php
    // Pilihan kondisi. Kalau nilai di database belum ada di daftar ini, tetap ditampilkan.
    $kondisiList = ['Baik', 'Rusak Ringan', 'Rusak Berat'];
    $kondisiSekarang = old('kondisi_kembali', $pengembalian->kondisi_kembali);
    if ($kondisiSekarang && !in_array($kondisiSekarang, $kondisiList)) {
        $kondisiList[] = $kondisiSekarang;
    }
@endphp

<div class="max-w-2xl mx-auto">

    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('admin.pengembalian.index') }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700">
            <span>&larr;</span> Kembali ke daftar pengembalian
        </a>
        <h1 class="mt-3 text-2xl font-extrabold text-slate-900">Edit Pengembalian</h1>
        <p class="text-sm text-slate-500">Ubah kondisi alat dan denda. Tanggal kembali dan status peminjaman tidak dapat diubah.</p>
    </div>

    {{-- Pesan error umum --}}
    @if (session('error'))
        <div class="mb-4 rounded-2xl bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="rounded-3xl bg-white p-8 shadow-xl shadow-slate-200/60">

        {{-- Ringkasan data (hanya baca) --}}
        <div class="mb-8 grid grid-cols-1 gap-4 rounded-2xl bg-slate-50 p-5 sm:grid-cols-2">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Peminjam</p>
                <p class="mt-1 font-bold text-slate-900">{{ $pengembalian->peminjaman->user->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Tanggal Kembali</p>
                <p class="mt-1 font-bold text-slate-900">
                    {{ \Carbon\Carbon::parse($pengembalian->tgl_kembali)->translatedFormat('d F Y') }}
                </p>
            </div>
            <div class="sm:col-span-2">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Alat</p>
                <div class="mt-2 flex flex-wrap gap-2">
                    @forelse ($pengembalian->peminjaman->detailPinjam ?? [] as $detail)
                        <span class="rounded-full bg-blue-600 px-3 py-1 text-xs font-semibold text-white">
                            {{ $detail->alat->nama_alat ?? '-' }} x{{ $detail->jumlah }}
                        </span>
                    @empty
                        <span class="text-sm text-slate-500">-</span>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Form edit --}}
        <form method="POST" action="{{ route('admin.pengembalian.update', $pengembalian->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Kondisi kembali --}}
            <div>
                <label for="kondisi_kembali" class="mb-2 block text-sm font-semibold text-slate-700">
                    Kondisi Kembali
                </label>
                <select id="kondisi_kembali" name="kondisi_kembali"
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100">
                    @foreach ($kondisiList as $kondisi)
                        <option value="{{ $kondisi }}" @selected($kondisiSekarang == $kondisi)>{{ $kondisi }}</option>
                    @endforeach
                </select>
                @error('kondisi_kembali')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Denda --}}
            <div>
                <label for="denda" class="mb-2 block text-sm font-semibold text-slate-700">
                    Denda (Rp)
                </label>
                <input type="number" id="denda" name="denda" min="0" step="1"
                       value="{{ old('denda', $pengembalian->denda) }}"
                       placeholder="0"
                       class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100">
                @error('denda')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="rounded-full bg-blue-600 px-8 py-3 text-sm font-bold text-white shadow-lg shadow-blue-500/40 transition hover:bg-blue-700">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.pengembalian.index') }}"
                   class="rounded-full bg-slate-100 px-8 py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-200">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection