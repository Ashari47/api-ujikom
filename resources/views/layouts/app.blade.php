<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-100 font-['Inter'] antialiased">
    <div class="flex h-screen overflow-hidden">
        <aside class="hidden w-64 flex-col bg-slate-950 text-white md:flex">
            <div class="flex items-center gap-2.5 border-b border-white/5 p-5">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-600 font-['Manrope'] text-sm font-extrabold">
                    @if(auth()->user()->role === 'admin') A
                    @elseif(auth()->user()->role === 'petugas') P
                    @else U
                    @endif
                </div>
                <div class="font-['Manrope'] text-sm font-extrabold uppercase tracking-wider text-white">
                    @if(auth()->user()->role === 'admin')
                        Panel Admin
                    @elseif(auth()->user()->role === 'petugas')
                        Panel Petugas
                    @else
                        Panel Peminjam
                    @endif
                </div>
            </div>

            <nav class="flex-1 space-y-1 p-4">

                @php
                    $activeClass = 'bg-blue-600 text-white font-semibold shadow-[0_4px_14px_rgba(37,99,235,0.35)]';
                    $inactiveClass = 'text-slate-400 hover:bg-white/5 hover:text-white';
                @endphp

                {{-- MENU KHUSUS ADMIN --}}
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm transition {{ request()->routeIs('admin.dashboard') ? $activeClass : $inactiveClass }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6a2.25 2.25 0 012.25-2.25h12A2.25 2.25 0 0120.25 6v2.25a2.25 2.25 0 01-2.25 2.25h-12a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25v-2.25zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" /></svg>
                        Dashboard
                    </a>

                    <a href="{{ route('admin.alat.index') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm transition {{ request()->routeIs('admin.alat*') ? $activeClass : $inactiveClass }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" /></svg>
                        Kelola Alat
                    </a>

                    <a href="{{ route('admin.kategori.index') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm transition {{ request()->routeIs('admin.kategori*') ? $activeClass : $inactiveClass }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 6.878V6a2.25 2.25 0 012.25-2.25h7.5A2.25 2.25 0 0118 6v.878m-12 0c.235-.083.487-.128.75-.128h10.5c.263 0 .515.045.75.128m-12 0A2.25 2.25 0 004.5 9v.878m13.5-3A2.25 2.25 0 0119.5 9v.878m0 0a2.246 2.246 0 00-.75-.128H5.25c-.263 0-.515.045-.75.128m15 0A2.25 2.25 0 0121 12v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6c0-.98.626-1.813 1.5-2.122" /></svg>
                        Kelola Kategori
                    </a>

                    <a href="{{ route('admin.peminjaman.index') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm transition {{ request()->routeIs('admin.peminjaman*') ? $activeClass : $inactiveClass }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" /></svg>
                        Kelola Peminjaman
                    </a>

                    <a href="{{ route('admin.pengembalian.index') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm transition {{ request()->routeIs('admin.pengembalian*') ? $activeClass : $inactiveClass }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
                        Kelola Pengembalian
                    </a>

                    <a href="{{ route('admin.log.index') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm transition {{ request()->routeIs('admin.log*') ? $activeClass : $inactiveClass }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Log Aktivitas
                    </a>

                    <a href="{{ route('admin.user.index') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm transition {{ request()->routeIs('admin.user*') ? $activeClass : $inactiveClass }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" /></svg>
                        Kelola User
                    </a>

                {{-- MENU KHUSUS PETUGAS --}}
                @elseif(auth()->user()->role === 'petugas')
                    <a href="{{ route('petugas.peminjaman.index') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm transition {{ request()->routeIs('petugas.peminjaman*') ? $activeClass : $inactiveClass }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Persetujuan Peminjaman
                    </a>

                    <a href="{{ route('petugas.pengembalian.index') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm transition {{ request()->routeIs('petugas.pengembalian*') ? $activeClass : $inactiveClass }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
                        Pemantauan Pengembalian
                    </a>

                    <a href="{{ route('petugas.laporan.index') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm transition {{ request()->routeIs('petugas.laporan*') ? $activeClass : $inactiveClass }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.83a42 42 0 0110.56 0M6.34 18h11.32M6.34 18l-.23 2.52a1.13 1.13 0 001.12 1.23h9.54a1.13 1.13 0 001.12-1.23L17.66 18M6.34 18H5.25A2.25 2.25 0 013 15.75V9.46a2.25 2.25 0 011.84-2.18 48 48 0 0114.32 0A2.25 2.25 0 0121 9.46v6.29A2.25 2.25 0 0118.75 18h-1.09M7.5 7.1V3.5a1.13 1.13 0 011.13-1.13h6.75A1.13 1.13 0 0116.5 3.5v3.6" /></svg>
                        Cetak Laporan
                    </a>

                {{-- MENU KHUSUS PEMINJAM --}}
                @elseif(auth()->user()->role === 'peminjam')
                    <a href="{{ route('peminjam.katalog') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm transition {{ request()->routeIs('peminjam.katalog') ? $activeClass : $inactiveClass }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75" /></svg>
                        Katalog Alat
                    </a>

                    <a href="{{ route('peminjam.riwayat') }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm transition {{ request()->routeIs('peminjam.riwayat') ? $activeClass : $inactiveClass }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Riwayat & Pengembalian
                    </a>
                @endif

            </nav>

            <div class="space-y-3 border-t border-white/5 p-4">
                <div class="flex items-center gap-2.5 rounded-xl bg-white/5 px-3 py-2.5">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-600 text-xs font-bold">
                        {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="truncate text-xs text-slate-400">Logged in as</p>
                        <p class="truncate text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl border border-red-500/20 bg-red-500/10 px-4 py-2.5 text-sm font-semibold text-red-400 transition hover:border-red-500 hover:bg-red-500 hover:text-white">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex flex-1 flex-col overflow-y-auto">
            <header class="z-10 flex h-16 items-center bg-white px-6 shadow-sm">
                <div class="font-['Manrope'] text-lg font-bold text-slate-800">
                    @yield('header-title', 'Dashboard')
                </div>
            </header>
            <main class="flex-1 p-6">
                @if(session('success'))
                <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
                @endif
                @if(session('error'))
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ session('error') }}
                </div>
                @endif
                @if($errors->any())
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-inside list-disc">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>