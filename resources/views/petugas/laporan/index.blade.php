@extends('layouts.app')

@section('title', 'Laporan Peminjaman - Dashboard Petugas')
@section('header-title', 'Laporan Peminjaman & Pengembalian Alat')

@section('content')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <div class="font-['Inter']">

        {{-- Filter --}}
        <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-4 font-['Manrope'] text-lg font-bold text-slate-800">Filter Laporan</h3>
            <form action="{{ route('petugas.laporan.index') }}" method="GET"
                class="grid grid-cols-1 gap-4 md:grid-cols-4 md:items-end">
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-slate-500">Status peminjaman</label>
                    <select name="status"
                        class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                        <option value="">Semua status</option>
                        <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                        <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                        <option value="telat" {{ request('status') == 'telat' ? 'selected' : '' }}>Telat</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-slate-500">Dari tanggal (pinjam)</label>
                    <input type="date" name="dari_tanggal" value="{{ request('dari_tanggal') }}"
                        class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-slate-500">Sampai tanggal (pinjam)</label>
                    <input type="date" name="sampai_tanggal" value="{{ request('sampai_tanggal') }}"
                        class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm text-slate-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="flex-1 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">
                        Filter
                    </button>
                    <a href="{{ route('petugas.laporan.index') }}"
                        class="flex items-center justify-center rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Hasil --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-slate-100 p-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="font-['Manrope'] text-lg font-bold text-slate-800">Hasil Rekap Laporan</h3>
                    <p class="mt-0.5 text-sm text-slate-500">{{ $laporans->count() }} data ditemukan</p>
                </div>
                <a href="{{ route('petugas.laporan.cetak', request()->all()) }}" target="_blank"
                    class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.72 13.83a42 42 0 0110.56 0M6.34 18h11.32M6.34 18l-.23 2.52a1.13 1.13 0 001.12 1.23h9.54a1.13 1.13 0 001.12-1.23L17.66 18M6.34 18H5.25A2.25 2.25 0 013 15.75V9.46a2.25 2.25 0 011.84-2.18 48 48 0 0114.32 0A2.25 2.25 0 0121 9.46v6.29A2.25 2.25 0 0118.75 18h-1.09M7.5 7.1V3.5a1.13 1.13 0 011.13-1.13h6.75A1.13 1.13 0 0116.5 3.5v3.6" />
                    </svg>
                    Cetak / Print
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 text-xs font-medium text-slate-400">
                            <th class="px-6 py-3">No</th>
                            <th class="px-6 py-3">Peminjam</th>
                            <th class="px-6 py-3">Tgl pinjam</th>
                            <th class="px-6 py-3">Rencana kembali</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Detail alat</th>
                            <th class="px-6 py-3 text-right">Denda</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700">
                        @forelse($laporans as $index => $item)
                            @php
                                $namaPeminjam = $item->user->name ?? '-';
                                $avatarPalette = ['#4F46E5', '#0D9488', '#DB2777', '#D97706', '#2563EB', '#7C3AED'];
                                $avatarColor = $avatarPalette[crc32($namaPeminjam) % count($avatarPalette)];
                                $initials = collect(explode(' ', trim($namaPeminjam)))
                                    ->filter()
                                    ->map(fn ($w) => mb_substr($w, 0, 1))
                                    ->take(2)
                                    ->implode('');
                                $initials = $initials !== '' ? strtoupper($initials) : '-';
                                $denda = $item->pengembalian->denda ?? 0;

                                $statusStyle = match ($item->status) {
                                    'dikembalikan', 'selesai' => 'bg-emerald-50 text-emerald-600',
                                    'dipinjam' => 'bg-blue-50 text-blue-600',
                                    'telat' => 'bg-red-50 text-red-600',
                                    'diajukan' => 'bg-amber-50 text-amber-600',
                                    default => 'bg-slate-100 text-slate-500',
                                };
                                $dotStyle = match ($item->status) {
                                    'dikembalikan', 'selesai' => 'bg-emerald-500',
                                    'dipinjam' => 'bg-blue-500',
                                    'telat' => 'bg-red-500',
                                    'diajukan' => 'bg-amber-500',
                                    default => 'bg-slate-400',
                                };
                            @endphp
                            <tr class="border-b border-slate-50 align-top transition hover:bg-slate-50/60">
                                <td class="px-6 py-4 tabular-nums text-slate-400">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-xs font-bold text-white"
                                            style="background-color: {{ $avatarColor }}">
                                            {{ $initials }}
                                        </div>
                                        <span class="font-medium text-slate-800">{{ $namaPeminjam }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 tabular-nums text-slate-500">{{ $item->tgl_pinjam->format('d-m-Y') }}</td>
                                <td class="px-6 py-4 tabular-nums text-slate-500">{{ $item->tgl_kembali_plan->format('d-m-Y') }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusStyle }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $dotStyle }}"></span>
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($item->detailPinjam as $detail)
                                            <span class="inline-flex items-center rounded-lg bg-slate-50 px-2.5 py-1 text-xs font-medium text-slate-600 ring-1 ring-slate-200">
                                                {{ $detail->alat->nama_alat ?? '-' }}
                                                <span class="ml-1 text-slate-400">&times;{{ $detail->jumlah }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right font-semibold tabular-nums {{ $denda > 0 ? 'text-red-600' : 'text-slate-400' }}">
                                    Rp {{ number_format($denda, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <p class="font-['Manrope'] text-base font-semibold text-slate-800">Tidak ada data laporan</p>
                                    <p class="mt-1 text-sm text-slate-500">Coba ubah filter di atas.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection