@extends('layouts.app')

@section('title', 'Kelola Kategori – Panel Admin')
@section('header-title', 'Manajemen Kategori Alat')

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
                <h3 class="font-['Manrope'] text-lg font-extrabold text-slate-900">Daftar Kategori Alat</h3>

                <div class="flex w-full items-center gap-2.5 md:w-auto">
                    <form action="{{ route('admin.kategori.index') }}" method="GET" class="flex w-full md:w-80">
                        <div class="relative flex-1">
                            <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kategori..."
                                class="w-full rounded-full border-0 bg-slate-50 py-2.5 pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <button type="submit"
                            class="ml-2 shrink-0 rounded-full bg-slate-900 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-slate-800">
                            Cari
                        </button>
                        @if(request('search'))
                            <a href="{{ route('admin.kategori.index') }}"
                                class="ml-2 flex shrink-0 items-center rounded-full bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-500 transition hover:bg-slate-200">
                                Reset
                            </a>
                        @endif
                    </form>

                    <a href="{{ route('admin.kategori.create') }}"
                        class="flex shrink-0 items-center gap-1.5 whitespace-nowrap rounded-full bg-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-[0_4px_14px_rgba(37,99,235,0.35)] transition hover:bg-blue-700">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah Kategori
                    </a>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto px-2 pb-2">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr class="text-xs font-bold uppercase tracking-wider text-slate-400">
                            <th class="w-16 px-4 py-3 text-center">No</th>
                            <th class="px-4 py-3">Nama Kategori</th>
                            <th class="w-48 px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700">
                        @forelse($kategori as $index => $k)
                            <tr class="transition hover:bg-slate-50">
                                <td class="rounded-l-2xl px-4 py-3.5 text-center font-medium text-slate-400">
                                    {{ $kategori->firstItem() + $index }}
                                </td>
                                <td class="px-4 py-3.5 font-semibold text-slate-900">{{ $k->nama_kategori }}</td>
                                <td class="rounded-r-2xl px-4 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.kategori.edit', $k->id) }}"
                                            class="rounded-full bg-amber-400 px-3.5 py-1.5 text-xs font-bold text-amber-900 transition hover:bg-amber-500">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.kategori.destroy', $k->id) }}"
                                            method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="rounded-full bg-red-500 px-3.5 py-1.5 text-xs font-bold text-white transition hover:bg-red-600">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-16 text-center">
                                    <p class="font-['Manrope'] text-base font-bold text-slate-800">Belum ada data kategori</p>
                                    <p class="mt-1 text-sm text-slate-400">Klik "Tambah Kategori" untuk mulai menambahkan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 pb-6 pt-2">
                {{ $kategori->links() }}
            </div>
        </div>
    </div>
@endsection