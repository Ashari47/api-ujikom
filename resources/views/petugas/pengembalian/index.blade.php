@extends('layouts.app')

@section('title', 'Pemantauan Pengembalian - Dashboard Petugas')
@section('header-title', 'Pemantauan & Proses Pengembalian Alat')

@section('content')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <div class="font-['Inter']">

        @if(session('success'))
            <div class="mb-6 flex items-center gap-3 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 flex items-center gap-3 rounded-xl bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.007M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Header + search --}}
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="font-['Manrope'] text-2xl font-bold text-slate-800">Daftar Peminjaman Aktif</h3>
                <p class="mt-1 text-sm text-slate-500">{{ $peminjamans->count() }} peminjaman belum dikembalikan</p>
            </div>
            <form action="{{ route('petugas.pengembalian.index') }}" method="GET" class="flex items-center gap-2">
                <div class="relative">
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama peminjam"
                        class="w-56 rounded-full border border-slate-200 bg-white py-2.5 pl-9 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100 sm:w-64">
                </div>
                <button type="submit" class="rounded-full bg-slate-800 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700">
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('petugas.pengembalian.index') }}" class="text-sm text-slate-400 hover:text-slate-600">Reset</a>
                @endif
            </form>
        </div>

        {{-- Cards --}}
        <div class="space-y-4">
            @forelse($peminjamans as $item)
                @php
                    $avatarPalette = ['#4F46E5', '#0D9488', '#DB2777', '#D97706', '#2563EB', '#7C3AED'];
                    $namaPeminjam = $item->user->name ?? 'User Dihapus';
                    $avatarColor = $avatarPalette[crc32($namaPeminjam) % count($avatarPalette)];
                    $initials = collect(explode(' ', trim($namaPeminjam)))
                        ->filter()
                        ->map(fn ($w) => mb_substr($w, 0, 1))
                        ->take(2)
                        ->implode('');
                    $initials = $initials !== '' ? strtoupper($initials) : 'U';

                    // Error bag per peminjaman, supaya pesan error hanya muncul di kartu yang bersangkutan
                    $bag = $errors->getBag('pengembalian' . $item->id);
                @endphp

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full text-sm font-bold text-white"
                                style="background-color: {{ $avatarColor }}">
                                {{ $initials }}
                            </div>
                            <div>
                                <p class="font-['Manrope'] text-base font-semibold text-slate-800">{{ $namaPeminjam }}</p>
                                <p class="text-xs text-slate-500">Dipinjam {{ $item->tgl_pinjam }}, kembali {{ $item->tgl_kembali_plan }}</p>
                            </div>
                        </div>

                        <div class="shrink-0 text-right">
                            @if($item->status == 'telat')
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-600">
                                    <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                    Terlambat {{ $item->hari_telat }} hari
                                </span>
                                <p class="mt-1 text-xs text-slate-400">Rp{{ number_format($item->denda_otomatis, 0, ',', '.') }}</p>
                            @else
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    Tepat waktu
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-1 gap-4 border-t border-slate-100 pt-4 md:grid-cols-[1.4fr_1fr] md:items-end">
                        <div class="flex flex-wrap gap-2">
                            @foreach($item->detailPinjam as $detail)
                                <span class="inline-flex items-center gap-1.5 rounded-lg bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-600 ring-1 ring-slate-200">
                                    {{ $detail->alat->nama_alat ?? 'Alat Dihapus' }}
                                    <span class="text-slate-400">&times;{{ $detail->jumlah }}</span>
                                </span>
                            @endforeach
                        </div>

                        <form action="{{ route('petugas.pengembalian.proses', $item->id) }}" method="POST"
                            onsubmit="return confirm('Proses pengembalian alat ini?')"
                            class="flex flex-col gap-2 sm:flex-row sm:items-end">
                            @csrf
                            <div class="flex-1">
                                <label class="mb-1 block text-xs font-medium text-slate-500">Kondisi</label>
                                <select name="kondisi_kembali" required
                                    oninvalid="this.setCustomValidity('Kondisi harus dipilih')"
                                    onchange="this.setCustomValidity('')"
                                    class="w-full rounded-lg border {{ $bag->has('kondisi_kembali') ? 'border-red-400' : 'border-slate-200' }} bg-white px-3 py-2 text-sm text-slate-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                                    <option value="" disabled selected>Pilih kondisi</option>
                                    <option value="Baik">Baik</option>
                                    <option value="Rusak Ringan">Rusak Ringan</option>
                                    <option value="Rusak Berat">Rusak Berat</option>
                                </select>
                                @if($bag->has('kondisi_kembali'))
                                    <p class="mt-1 text-xs text-red-500">{{ $bag->first('kondisi_kembali') }}</p>
                                @endif
                            </div>
                            <div class="flex-1">
                                <label class="mb-1 block text-xs font-medium text-slate-500">Denda (Rp)</label>
                                <input type="number" name="denda" value="{{ $item->denda_otomatis }}"
                                    class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                            </div>
                            <button type="submit"
                                class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700">
                                Terima
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-200 bg-white px-6 py-16 text-center">
                    <p class="font-['Manrope'] text-lg font-semibold text-slate-800">Tidak ada peminjaman aktif</p>
                    <p class="mt-1 text-sm text-slate-500">Semua alat sudah kembali ke gudang.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection