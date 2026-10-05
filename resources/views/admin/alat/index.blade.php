@extends('layouts.app')

@section('title', 'Kelola Alat - Panel Admin')
@section('header-title', 'Manajemen Data Alat')

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

        <div class="overflow-hidden rounded-3xl bg-white shadow-[0_2px_14px_rgba(15,23,42,0.07)]">

            {{-- Header --}}
            <div class="flex flex-col gap-4 p-6 md:flex-row md:items-center md:justify-between">
                <h3 class="font-['Manrope'] text-lg font-extrabold text-slate-900">Daftar Alat Laboratorium</h3>

                <div class="flex w-full items-center gap-2.5 md:w-auto">
                    <form action="{{ route('admin.alat.index') }}" method="GET" class="flex w-full md:w-80">
                        <div class="relative flex-1">
                            <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama alat, kategori..."
                                class="w-full rounded-full border-0 bg-slate-50 py-2.5 pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <button type="submit"
                            class="ml-2 shrink-0 rounded-full bg-slate-900 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-slate-800">
                            Cari
                        </button>
                        @if(request('search'))
                            <a href="{{ route('admin.alat.index') }}"
                                class="ml-2 flex shrink-0 items-center rounded-full bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-500 transition hover:bg-slate-200">
                                Reset
                            </a>
                        @endif
                    </form>

                    <a href="{{ route('admin.alat.create') }}"
                        class="flex shrink-0 items-center gap-1.5 whitespace-nowrap rounded-full bg-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-[0_4px_14px_rgba(37,99,235,0.35)] transition hover:bg-blue-700">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah Alat
                    </a>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto px-2 pb-2">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="text-xs font-bold uppercase tracking-wider text-slate-400">
                            <th class="px-4 py-3">Gambar</th>
                            <th class="px-4 py-3">Nama Alat</th>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3">Stok</th>
                            <th class="px-4 py-3">Kondisi</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700">
                        @forelse($alats as $alat)
                            <tr class="transition hover:bg-slate-50">
                                <td class="rounded-l-2xl px-4 py-3.5">
                                    @if($alat->gambar)
                                        <img src="{{ asset($alat->gambar) }}" alt="{{ $alat->nama_alat }}" class="h-12 w-12 rounded-xl border border-slate-100 object-cover">
                                    @else
                                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-50 text-[10px] italic text-slate-400">
                                            N/A
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 font-semibold text-slate-900">{{ $alat->nama_alat }}</td>
                                <td class="px-4 py-3.5">
                                    <span class="inline-block rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-500">
                                        {{ $alat->kategori->nama_kategori ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 font-bold text-slate-900">{{ $alat->stok }}</td>
                                <td class="px-4 py-3.5">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold
                                        @if(strtolower($alat->status_kondisi) == 'baik') bg-emerald-50 text-emerald-600
                                        @else bg-amber-50 text-amber-600 @endif">
                                        <span class="h-1.5 w-1.5 rounded-full @if(strtolower($alat->status_kondisi) == 'baik') bg-emerald-500 @else bg-amber-500 @endif"></span>
                                        {{ $alat->status_kondisi }}
                                    </span>
                                </td>
                                <td class="rounded-r-2xl px-4 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.alat.edit', $alat->id) }}"
                                            class="rounded-full bg-amber-400 px-3.5 py-1.5 text-xs font-bold text-amber-900 transition hover:bg-amber-500">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.alat.destroy', $alat->id) }}"
                                            method="POST" onsubmit="return confirm('Yakin ingin menghapus alat ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-full bg-red-500 px-3.5 py-1.5 text-xs font-bold text-white transition hover:bg-red-600">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-16 text-center">
                                    <p class="font-['Manrope'] text-base font-bold text-slate-800">Belum ada data alat</p>
                                    <p class="mt-1 text-sm text-slate-400">Klik "Tambah Alat" untuk mulai menambahkan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 pb-6 pt-2">
                {{ $alats->links() }}
            </div>
        </div>
    </div>
@endsection