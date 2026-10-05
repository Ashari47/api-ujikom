@extends('layouts.app')

@section('title', 'Persetujuan Peminjaman - Dashboard Petugas')
@section('header-title', 'Daftar Pengajuan Peminjaman Alat')

@section('content')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <div class="font-['Inter']">

        {{-- Alert Success --}}
        @if(session('success'))
            <div class="mb-5 flex items-center gap-2.5 rounded-2xl bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Alert Error --}}
        @if(session('error'))
            <div class="mb-5 flex items-center gap-2.5 rounded-2xl bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v3.75m0 3.75h.008M10.29 3.86l-7.1 12.28A1.5 1.5 0 004.49 18.4h15.02a1.5 1.5 0 001.3-2.26L13.71 3.86a1.5 1.5 0 00-2.6 0z" />
                </svg>
                {{ session('error') }}
            </div>
        @endif


        {{-- Main Card --}}
        <div class="overflow-hidden rounded-3xl bg-white shadow-[0_2px_14px_rgba(15,23,42,0.07)]">

            {{-- Header --}}
            <div class="flex flex-col gap-4 p-6 md:flex-row md:items-center md:justify-between">

                <div>
                    <h3 class="font-['Manrope'] text-lg font-extrabold text-slate-900">
                        Menunggu Verifikasi Persetujuan
                    </h3>
                    <p class="mt-1 text-sm text-slate-400">
                        Kelola pengajuan peminjaman alat dari pengguna.
                    </p>
                </div>

                {{-- Search --}}
                <form action="{{ route('petugas.peminjaman.index') }}" method="GET"
                    class="flex w-full items-center md:w-80">

                    <div class="relative flex-1">
                        <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nama peminjam..."
                            class="w-full rounded-full border-0 bg-slate-50 py-2.5 pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <button type="submit"
                        class="ml-2 shrink-0 rounded-full bg-slate-900 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-slate-800">
                        Cari
                    </button>

                    @if(request('search'))
                        <a href="{{ route('petugas.peminjaman.index') }}"
                            class="ml-2 flex shrink-0 items-center rounded-full bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-500 transition hover:bg-slate-200">
                            Reset
                        </a>
                    @endif

                </form>
            </div>


            {{-- Table --}}
            <div class="overflow-x-auto px-2 pb-2">
                <table class="w-full border-collapse text-left">

                    <thead>
                        <tr class="text-xs font-bold uppercase tracking-wider text-slate-400">

                            <th class="px-4 py-3">
                                Peminjam
                            </th>

                            <th class="px-4 py-3">
                                Tanggal Pinjam
                            </th>

                            <th class="px-4 py-3">
                                Rencana Kembali
                            </th>

                            <th class="px-4 py-3">
                                Detail Alat
                            </th>

                            <th class="px-4 py-3 text-center">
                                Aksi
                            </th>

                        </tr>
                    </thead>


                    <tbody class="text-sm text-slate-700">

                        @forelse($peminjamans as $item)

                            <tr class="transition hover:bg-slate-50">

                                {{-- Peminjam --}}
                                <td class="rounded-l-2xl px-4 py-4">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-900 text-xs font-bold text-white">
                                            @php
                                                $name = $item->user->name ?? 'User Dihapus';

                                                $initials = collect(explode(' ', trim($name)))
                                                    ->filter()
                                                    ->map(fn ($w) => mb_substr($w, 0, 1))
                                                    ->take(2)
                                                    ->implode('');

                                                $initials = $initials !== ''
                                                    ? strtoupper($initials)
                                                    : 'U';
                                            @endphp

                                            {{ $initials }}
                                        </div>

                                        <div>
                                            <p class="font-semibold text-slate-900">
                                                {{ $item->user->name ?? 'User Dihapus' }}
                                            </p>

                                            @if($item->user)
                                                <p class="mt-0.5 text-xs text-slate-400">
                                                    Pengguna
                                                </p>
                                            @endif
                                        </div>

                                    </div>

                                </td>


                                {{-- Tanggal Pinjam --}}
                                <td class="px-4 py-4">

                                    <span class="font-medium text-slate-700">
                                        {{ $item->tgl_pinjam }}
                                    </span>

                                </td>


                                {{-- Rencana Kembali --}}
                                <td class="px-4 py-4">

                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600">
                                        {{ $item->tgl_kembali_plan }}
                                    </span>

                                </td>


                                {{-- Detail Alat --}}
                                <td class="px-4 py-4">

                                    <div class="space-y-1.5">

                                        @foreach($item->detailPinjam as $detail)

                                            <div class="flex items-center gap-2 text-xs">

                                                <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-slate-400"></span>

                                                <span class="font-semibold text-slate-700">
                                                    {{ $detail->alat->nama_alat ?? 'Alat Dihapus' }}
                                                </span>

                                                <span class="text-slate-400">
                                                    × {{ $detail->jumlah }}
                                                </span>

                                            </div>

                                        @endforeach

                                    </div>

                                </td>


                                {{-- Aksi --}}
                                <td class="rounded-r-2xl px-4 py-4">

                                    @if($item->status == 'diajukan')

                                        <div class="flex items-center justify-center gap-2">

                                            {{-- Setujui --}}
                                            <form action="{{ route('petugas.peminjaman.setujui', $item->id) }}"
                                                method="POST">

                                                @csrf

                                                <button
                                                    type="submit"
                                                    onclick="return confirm('Setujui peminjaman alat ini?')"
                                                    class="rounded-full bg-emerald-500 px-4 py-1.5 text-xs font-bold text-white shadow-[0_3px_10px_rgba(16,185,129,0.25)] transition hover:bg-emerald-600">

                                                    Setujui

                                                </button>

                                            </form>


                                            {{-- Tolak --}}
                                            <form action="{{ route('petugas.peminjaman.tolak', $item->id) }}"
                                                method="POST">

                                                @csrf

                                                <button
                                                    type="submit"
                                                    onclick="return confirm('Yakin ingin menolak pengajuan peminjaman ini?')"
                                                    class="rounded-full bg-red-500 px-4 py-1.5 text-xs font-bold text-white shadow-[0_3px_10px_rgba(239,68,68,0.20)] transition hover:bg-red-600">

                                                    Tolak

                                                </button>

                                            </form>

                                        </div>

                                    @else

                                        <div class="flex justify-center">

                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-600">

                                                <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>

                                                {{ ucfirst($item->status) }}

                                            </span>

                                        </div>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5" class="px-4 py-16 text-center">

                                    <div class="flex flex-col items-center justify-center">

                                        <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-slate-100">

                                            <svg class="h-6 w-6 text-slate-400"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                                stroke-width="1.8">

                                                <path stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M9 12h6m-6 4h4m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z" />

                                            </svg>

                                        </div>

                                        <p class="font-['Manrope'] text-base font-bold text-slate-800">
                                            Tidak ada pengajuan peminjaman baru
                                        </p>

                                        <p class="mt-1 text-sm text-slate-400">
                                            Belum ada pengajuan yang perlu diverifikasi.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>

        </div>

    </div>
@endsection