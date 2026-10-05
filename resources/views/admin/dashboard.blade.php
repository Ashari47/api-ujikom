@extends('layouts.app')

@section('title', 'Dashboard Admin - Sistem Peminjaman')
@section('header-title', 'Ringkasan Aktivitas Sistem')

@section('content')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <div class="font-['Inter']">

        {{-- Welcome banner --}}
        <div class="mb-6 flex items-center gap-4 rounded-3xl bg-gradient-to-br from-blue-600 to-blue-700 p-6 text-white shadow-[0_4px_20px_rgba(37,99,235,0.25)]">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-white/15 text-lg font-extrabold">
                {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <p class="text-sm text-blue-100">Selamat datang kembali,</p>
                <p class="font-['Manrope'] text-lg font-extrabold">{{ auth()->user()->name }}</p>
            </div>
            <span class="ml-auto rounded-full bg-white/15 px-3.5 py-1.5 text-xs font-bold uppercase tracking-wide">
                {{ auth()->user()->role }}
            </span>
        </div>

        {{-- Stat cards --}}
        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="flex items-center gap-4 rounded-3xl bg-white p-5 shadow-[0_2px_14px_rgba(15,23,42,0.07)] transition hover:shadow-[0_8px_24px_rgba(15,23,42,0.12)]">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-xl">
                    📦
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Total Alat</p>
                    <p class="font-['Manrope'] text-2xl font-extrabold text-slate-900">{{ $totalAlat }}</p>
                </div>
            </div>

            <div class="flex items-center gap-4 rounded-3xl bg-white p-5 shadow-[0_2px_14px_rgba(15,23,42,0.07)] transition hover:shadow-[0_8px_24px_rgba(15,23,42,0.12)]">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-amber-50 text-xl">
                    🔄
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Peminjaman Aktif</p>
                    <p class="font-['Manrope'] text-2xl font-extrabold text-slate-900">{{ $peminjamanAktif }}</p>
                </div>
            </div>

            <div class="flex items-center gap-4 rounded-3xl bg-white p-5 shadow-[0_2px_14px_rgba(15,23,42,0.07)] transition hover:shadow-[0_8px_24px_rgba(15,23,42,0.12)]">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-xl">
                    ✅
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Pengembalian Bulan Ini</p>
                    <p class="font-['Manrope'] text-2xl font-extrabold text-slate-900">{{ $pengembalianBulanIni }}</p>
                </div>
            </div>

            <div class="flex items-center gap-4 rounded-3xl bg-white p-5 shadow-[0_2px_14px_rgba(15,23,42,0.07)] transition hover:shadow-[0_8px_24px_rgba(15,23,42,0.12)]">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-purple-50 text-xl">
                    👥
                </div>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">Total User</p>
                    <p class="font-['Manrope'] text-2xl font-extrabold text-slate-900">{{ $totalUser }}</p>
                </div>
            </div>
        </div>

        {{-- Log aktivitas --}}
        <div class="overflow-hidden rounded-3xl bg-white shadow-[0_2px_14px_rgba(15,23,42,0.07)]">
            <div class="flex flex-col gap-3 p-6 sm:flex-row sm:items-center sm:justify-between">
                <h3 class="font-['Manrope'] text-lg font-extrabold text-slate-900">Log Aktivitas Terbaru</h3>

                <div class="flex flex-wrap items-center gap-3.5 text-xs font-medium text-slate-500">
                    <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-blue-500"></span>Import</span>
                    <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-emerald-500"></span>Approve</span>
                    <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-amber-500"></span>Request</span>
                    <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-red-500"></span>Return/Denda</span>
                    <span class="flex items-center gap-1.5"><span class="h-2 w-2 rounded-full bg-purple-500"></span>Konfigurasi</span>
                </div>
            </div>

            <div class="overflow-x-auto px-2 pb-2">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="text-xs font-bold uppercase tracking-wider text-slate-400">
                            <th class="px-4 py-3">Waktu</th>
                            <th class="px-4 py-3">User</th>
                            <th class="px-4 py-3">Aktivitas</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700">
                        @forelse($logs as $log)
                            @php
                                $text = strtolower($log->aktivitas);
                                $dotColor = 'bg-slate-400';
                                if (str_contains($text, 'import')) $dotColor = 'bg-blue-500';
                                elseif (str_contains($text, 'setuju') || str_contains($text, 'approve')) $dotColor = 'bg-emerald-500';
                                elseif (str_contains($text, 'ajukan') || str_contains($text, 'mengajukan')) $dotColor = 'bg-amber-500';
                                elseif (str_contains($text, 'kembali') || str_contains($text, 'telat') || str_contains($text, 'denda')) $dotColor = 'bg-red-500';
                                elseif (str_contains($text, 'ubah') || str_contains($text, 'konfigurasi')) $dotColor = 'bg-purple-500';
                            @endphp
                            <tr class="transition hover:bg-slate-50">
                                <td class="rounded-l-2xl px-4 py-3.5 font-mono text-xs text-slate-400 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($log->created_at)->format('Y-m-d H:i:s') }}
                                </td>
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">
                                        {{ $log->user->name ?? 'Sistem' }}
                                    </span>
                                </td>
                                <td class="rounded-r-2xl px-4 py-3.5">
                                    <div class="flex items-start gap-2">
                                        <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full {{ $dotColor }}"></span>
                                        <span>{{ $log->aktivitas }}</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-16 text-center">
                                    <p class="font-['Manrope'] text-base font-bold text-slate-800">Belum ada log aktivitas</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
