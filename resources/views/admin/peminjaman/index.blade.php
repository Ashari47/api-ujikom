@extends('layouts.app')

@section('title', 'Kelola Peminjaman')
@section('header-title', 'Manajemen Transaksi Peminjaman')

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
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-3xl bg-white shadow-[0_2px_14px_rgba(15,23,42,0.07)]">

            {{-- Header --}}
            <div class="flex flex-col gap-4 p-6 md:flex-row md:items-center md:justify-between">
                <h3 class="font-['Manrope'] text-lg font-extrabold text-slate-900">Daftar Transaksi Peminjaman</h3>

                <div class="flex w-full items-center gap-2.5 md:w-auto">
                    <form action="{{ route('admin.peminjaman.index') }}" method="GET" class="flex w-full md:w-80">
                        <div class="relative flex-1">
                            <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama peminjam / status..."
                                class="w-full rounded-full border-0 bg-slate-50 py-2.5 pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <button type="submit"
                            class="ml-2 shrink-0 rounded-full bg-slate-900 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-slate-800">
                            Cari
                        </button>
                    </form>

                    <a href="{{ route('admin.peminjaman.create') }}"
                        class="flex shrink-0 items-center gap-1.5 whitespace-nowrap rounded-full bg-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-[0_4px_14px_rgba(37,99,235,0.35)] transition hover:bg-blue-700">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah Peminjaman
                    </a>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto px-2 pb-2">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="text-xs font-bold uppercase tracking-wider text-slate-400">
                            <th class="px-4 py-3">Peminjam</th>
                            <th class="px-4 py-3">Alat yang Dipinjam</th>
                            <th class="px-4 py-3">Tgl Pinjam / Rencana Kembali</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700">
                        @forelse($peminjamans as $peminjaman)
                            @php
                                // Nilai status di database memakai huruf kecil
                                $statusStyle = match($peminjaman->status) {
                                    'diajukan' => ['bg-amber-50 text-amber-600', 'bg-amber-500'],
                                    'dipinjam' => ['bg-blue-50 text-blue-600', 'bg-blue-500'],
                                    'dikembalikan' => ['bg-emerald-50 text-emerald-600', 'bg-emerald-500'],
                                    'telat', 'ditolak' => ['bg-red-50 text-red-600', 'bg-red-500'],
                                    default => ['bg-slate-100 text-slate-500', 'bg-slate-400'],
                                };
                            @endphp
                            <tr class="transition hover:bg-slate-50">
                                <td class="rounded-l-2xl px-4 py-3.5 font-semibold text-slate-900">
                                    {{ $peminjaman->user->name ?? 'N/A' }}
                                </td>

                                <td class="px-4 py-3.5">
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($peminjaman->detailPinjam as $detail)
                                            <span class="inline-flex items-center rounded-lg bg-slate-50 px-2.5 py-1 text-xs font-medium text-slate-600 ring-1 ring-slate-200">
                                                {{ $detail->alat->nama_alat ?? 'Alat' }}
                                                <span class="ml-1 text-slate-400">&times;{{ $detail->jumlah }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                </td>

                                <td class="px-4 py-3.5 text-xs text-slate-500">
                                    <div><span class="text-slate-400">Pinjam:</span> <span class="font-medium text-slate-700">{{ $peminjaman->tgl_pinjam ? \Carbon\Carbon::parse($peminjaman->tgl_pinjam)->format('d-m-Y') : '-' }}</span></div>
                                    <div class="mt-0.5"><span class="text-slate-400">Rencana:</span> <span class="font-medium text-slate-700">{{ $peminjaman->tgl_kembali_plan ? \Carbon\Carbon::parse($peminjaman->tgl_kembali_plan)->format('d-m-Y') : '-' }}</span></div>
                                </td>

                                <td class="px-4 py-3.5">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold {{ $statusStyle[0] }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $statusStyle[1] }}"></span>
                                        {{ $peminjaman->status }}
                                    </span>
                                </td>

                                <td class="rounded-r-2xl px-4 py-3.5">
                                    <div class="flex flex-col items-center gap-2">
                                        @if($peminjaman->status === 'dikembalikan')
                                            {{-- Sudah selesai: status tidak bisa diubah lagi dari sini --}}
                                            <span class="w-full rounded-full bg-slate-50 px-3 py-1.5 text-center text-xs font-semibold text-slate-400">
                                                Sudah dikembalikan
                                            </span>
                                        @else
                                            <form action="{{ route('admin.peminjaman.updateStatus', $peminjaman->id) }}" method="POST" class="w-full">
                                                @csrf
                                                @method('PUT')
                                                <select name="status"
                                                    onchange="if (this.value === 'dikembalikan') { window.location.href = '{{ route('admin.pengembalian.proses', $peminjaman->id) }}'; } else { this.form.submit(); }"
                                                    class="w-full cursor-pointer rounded-full border-0 bg-slate-50 px-3 py-1.5 text-xs font-semibold text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                    <option value="diajukan" {{ $peminjaman->status == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                                                    <option value="dipinjam" {{ $peminjaman->status == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                                    <option value="telat" {{ $peminjaman->status == 'telat' ? 'selected' : '' }}>Telat</option>
                                                    @if($peminjaman->status === 'dipinjam')
                                                        {{-- Memilih ini membuka form pengembalian (isi kondisi & denda) --}}
                                                        <option value="dikembalikan">Dikembalikan</option>
                                                    @endif
                                                </select>
                                            </form>
                                        @endif

                                        <form action="{{ route('admin.peminjaman.destroy', $peminjaman->id) }}" method="POST" class="w-full" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
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
                                <td colspan="5" class="px-4 py-16 text-center">
                                    <p class="font-['Manrope'] text-base font-bold text-slate-800">Belum ada data peminjaman</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 pb-6 pt-2">
                {{ $peminjamans->links() }}
            </div>
        </div>
    </div>
@endsection