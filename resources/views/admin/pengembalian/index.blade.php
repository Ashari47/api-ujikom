@extends('layouts.app')

@section('title', 'Kelola Pengembalian')
@section('header-title', 'Riwayat Pengembalian Alat')

@section('content')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <div class="font-['Inter']">

        @if(session('success'))
            <div class="mb-5 flex items-center gap-2.5 rounded-2xl bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-5 flex items-center gap-2.5 rounded-2xl bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.007M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-3xl bg-white shadow-[0_2px_14px_rgba(15,23,42,0.07)]">

            {{-- Header --}}
            <div class="flex flex-col gap-4 p-6 md:flex-row md:items-center md:justify-between">
                <h3 class="font-['Manrope'] text-lg font-extrabold text-slate-900">Riwayat Pengembalian</h3>

                <div class="flex w-full items-center gap-2.5 md:w-auto">
                    <form action="{{ route('admin.pengembalian.index') }}" method="GET" class="flex w-full md:w-80">
                        <div class="relative flex-1">
                            <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama peminjam..."
                                class="w-full rounded-full border-0 bg-slate-50 py-2.5 pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <button type="submit"
                            class="ml-2 shrink-0 rounded-full bg-slate-900 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-slate-800">
                            Cari
                        </button>
                    </form>

                    <a href="{{ route('admin.pengembalian.pilih') }}"
                        class="flex shrink-0 items-center gap-1.5 whitespace-nowrap rounded-full bg-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-[0_4px_14px_rgba(37,99,235,0.35)] transition hover:bg-blue-700">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Proses Pengembalian
                    </a>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto px-2 pb-2">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="text-xs font-bold uppercase tracking-wider text-slate-400">
                            <th class="px-4 py-3">Peminjam</th>
                            <th class="px-4 py-3">Alat Dikembalikan</th>
                            <th class="px-4 py-3">Tgl Kembali</th>
                            <th class="px-4 py-3">Kondisi</th>
                            <th class="px-4 py-3">Denda</th>
                            <th class="px-4 py-3">Petugas</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700">
                        @forelse($pengembalians as $p)
                            @php
                                $kondisiStyle = match(true) {
                                    str_contains(strtolower($p->kondisi_kembali), 'baik') => ['bg-emerald-50 text-emerald-600', 'bg-emerald-500'],
                                    str_contains(strtolower($p->kondisi_kembali), 'hilang') => ['bg-red-50 text-red-600', 'bg-red-500'],
                                    default => ['bg-amber-50 text-amber-600', 'bg-amber-500'],
                                };
                            @endphp
                            <tr class="transition hover:bg-slate-50">
                                <td class="rounded-l-2xl px-4 py-3.5 font-semibold text-slate-900">
                                    {{ $p->peminjaman->user->name ?? 'N/A' }}
                                </td>

                                <td class="px-4 py-3.5">
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($p->peminjaman->detailPinjam as $detail)
                                            <span class="inline-flex items-center rounded-lg bg-slate-50 px-2.5 py-1 text-xs font-medium text-slate-600 ring-1 ring-slate-200">
                                                {{ $detail->alat->nama_alat ?? 'Alat' }}
                                                <span class="ml-1 text-slate-400">&times;{{ $detail->jumlah }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                </td>

                                <td class="px-4 py-3.5 text-xs font-medium text-slate-600">
                                    {{ \Carbon\Carbon::parse($p->tgl_kembali)->format('d-m-Y') }}
                                </td>

                                <td class="px-4 py-3.5">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold {{ $kondisiStyle[0] }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $kondisiStyle[1] }}"></span>
                                        {{ $p->kondisi_kembali }}
                                    </span>
                                </td>

                                <td class="px-4 py-3.5 font-semibold tabular-nums {{ $p->denda > 0 ? 'text-red-600' : 'text-slate-400' }}">
                                    Rp {{ number_format($p->denda, 0, ',', '.') }}
                                </td>

                                <td class="px-4 py-3.5 text-slate-500">
                                    {{ $p->petugas->name ?? '-' }}
                                </td>

                                <td class="rounded-r-2xl px-4 py-3.5">
                                    <div class="flex flex-col items-center gap-2">
                                        <a href="{{ route('admin.pengembalian.edit', $p->id) }}"
                                            class="w-full rounded-full bg-amber-400 px-4 py-1.5 text-center text-xs font-bold text-amber-900 transition hover:bg-amber-500">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.pengembalian.destroy', $p->id) }}" method="POST" class="w-full" onsubmit="return confirm('Yakin batalkan pengembalian ini? Stok akan dikurangi lagi dan status peminjaman kembali ke Dipinjam.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full rounded-full bg-red-500 px-4 py-1.5 text-xs font-bold text-white transition hover:bg-red-600">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-16 text-center">
                                    <p class="font-['Manrope'] text-base font-bold text-slate-800">Belum ada data pengembalian</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 pb-6 pt-2">
                {{ $pengembalians->links() }}
            </div>
        </div>
    </div>
@endsection