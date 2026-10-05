@extends('layouts.app')

@section('title', 'Katalog Alat - Peminjam')
@section('header-title', 'Katalog & Pengajuan Peminjaman')

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
        @if($errors->any())
            <div class="mb-5 rounded-2xl bg-red-50 p-4 text-sm text-red-700">
                <ul class="list-inside list-disc">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('peminjam.peminjaman.ajukan') }}" method="POST" id="form-pinjam">
            @csrf

            {{-- Filter bar --}}
            <div class="mb-6 flex flex-col gap-3 rounded-2xl bg-white p-3 shadow-[0_2px_12px_rgba(15,23,42,0.06)] sm:flex-row sm:items-center">
                <div class="relative flex-1">
                    <svg class="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" id="search-input" value="{{ $search }}" placeholder="Cari nama alat..."
                        class="w-full rounded-full border-0 bg-slate-50 py-2.5 pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <select id="kategori-select"
                    class="rounded-full border-0 bg-slate-50 px-4 py-2.5 text-sm font-medium text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" {{ $kategori_id == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Grid katalog --}}
            <div class="mb-32 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($alats as $alat)
                    <div class="katalog-card overflow-hidden rounded-3xl bg-white p-3 shadow-[0_2px_14px_rgba(15,23,42,0.07)] transition hover:shadow-[0_8px_24px_rgba(15,23,42,0.12)]">
                        <div class="relative h-36 w-full overflow-hidden rounded-2xl bg-slate-100">
                            @if($alat->gambar)
                                <img src="{{ asset($alat->gambar) }}" alt="{{ $alat->nama_alat }}" class="h-full w-full object-cover">
                            @else
                                <div class="flex h-full w-full items-center justify-center">
                                    <svg class="h-9 w-9 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            <span class="absolute left-3 top-3 rounded-full bg-blue-600 px-3 py-1 text-[11px] font-bold text-white shadow-sm">
                                Stok {{ $alat->stok }}
                            </span>
                        </div>

                        <div class="px-1.5 pb-1 pt-3.5">
                            <span class="inline-block rounded-full bg-slate-100 px-2.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-slate-500">
                                {{ $alat->kategori->nama_kategori ?? '-' }}
                            </span>
                            <h4 class="mt-2 font-['Manrope'] text-[15px] font-extrabold leading-snug text-slate-900">{{ $alat->nama_alat }}</h4>

                            <div class="mt-3.5 flex items-center gap-2">
                                <label for="chk-{{ $alat->id }}"
                                    class="chk-label flex flex-1 cursor-pointer items-center justify-center gap-1.5 rounded-full border-2 border-slate-100 py-2 text-xs font-bold text-slate-500 transition">
                                    <input type="checkbox" id="chk-{{ $alat->id }}" class="chk-alat peer sr-only"
                                        data-id="{{ $alat->id }}" data-nama="{{ $alat->nama_alat }}" data-stok="{{ $alat->stok }}">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                    Pilih
                                </label>
                                <input type="number" class="input-jumlah h-[38px] w-16 rounded-full border-2 border-slate-100 text-center text-xs font-bold text-slate-700 focus:border-blue-500 focus:outline-none disabled:bg-slate-50 disabled:text-slate-300"
                                    data-id="{{ $alat->id }}" min="1" max="{{ $alat->stok }}" value="1" disabled placeholder="Jml">
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full rounded-3xl border border-dashed border-slate-300 bg-white py-16 text-center">
                        <p class="font-['Manrope'] font-bold text-slate-800">Tidak ada alat yang tersedia saat ini</p>
                    </div>
                @endforelse
            </div>

            {{-- Panel pengajuan (fixed bottom) --}}
            <div class="fixed bottom-0 left-0 right-0 border-t border-slate-100 bg-white/95 px-4 py-3.5 backdrop-blur md:left-64">
                <div class="mx-auto flex max-w-4xl flex-col items-end gap-3 md:flex-row">
                    <div class="w-full flex-1">
                        <label class="mb-1 block text-xs font-semibold text-slate-400">Tanggal Pinjam</label>
                        <input type="date" name="tgl_pinjam" required min="{{ date('Y-m-d') }}"
                            class="w-full rounded-full border-0 bg-slate-50 px-4 py-2.5 text-sm text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="w-full flex-1">
                        <label class="mb-1 block text-xs font-semibold text-slate-400">Rencana Kembali</label>
                        <input type="date" name="tgl_kembali_plan" required min="{{ date('Y-m-d') }}"
                            class="w-full rounded-full border-0 bg-slate-50 px-4 py-2.5 text-sm text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div id="selected-count" class="whitespace-nowrap pb-2.5 text-xs font-bold text-slate-400 md:pb-0">0 alat dipilih</div>
                    <button type="submit" id="btn-ajukan" disabled
                        class="w-full whitespace-nowrap rounded-full bg-blue-600 px-7 py-3 text-sm font-bold text-white shadow-[0_4px_14px_rgba(37,99,235,0.35)] transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:bg-slate-200 disabled:text-slate-400 disabled:shadow-none md:w-auto">
                        Ajukan Peminjaman
                    </button>
                </div>
            </div>
        </form>

        <style>
            .chk-alat:checked + .chk-label,
            .chk-label:has(.chk-alat:checked) {
                background-color: #2563eb;
                border-color: #2563eb;
                color: #fff;
            }
        </style>

        <script>
            function applyFilter() {
                const search = document.getElementById('search-input').value;
                const kategori_id = document.getElementById('kategori-select').value;
                const params = new URLSearchParams();
                if (search) params.set('search', search);
                if (kategori_id) params.set('kategori_id', kategori_id);
                window.location.href = "{{ route('peminjam.katalog') }}?" + params.toString();
            }
            document.getElementById('kategori-select').addEventListener('change', applyFilter);
            document.getElementById('search-input').addEventListener('keypress', function (e) {
                if (e.key === 'Enter') { e.preventDefault(); applyFilter(); }
            });

            const form = document.getElementById('form-pinjam');
            const checkboxes = document.querySelectorAll('.chk-alat');
            const btnAjukan = document.getElementById('btn-ajukan');
            const selectedCount = document.getElementById('selected-count');

            function updateState() {
                let count = 0;
                form.querySelectorAll('input[type=hidden].dynamic-field').forEach(el => el.remove());

                checkboxes.forEach(chk => {
                    const id = chk.dataset.id;
                    const jumlahInput = document.querySelector(`.input-jumlah[data-id="${id}"]`);
                    const card = chk.closest('.katalog-card');

                    jumlahInput.disabled = !chk.checked;

                    if (card) {
                        card.classList.toggle('ring-2', chk.checked);
                        card.classList.toggle('ring-blue-500', chk.checked);
                    }

                    if (chk.checked) {
                        count++;
                        const hiddenId = document.createElement('input');
                        hiddenId.type = 'hidden';
                        hiddenId.name = 'alat_id[]';
                        hiddenId.value = id;
                        hiddenId.classList.add('dynamic-field');
                        form.appendChild(hiddenId);

                        const hiddenJumlah = document.createElement('input');
                        hiddenJumlah.type = 'hidden';
                        hiddenJumlah.name = 'jumlah[]';
                        hiddenJumlah.value = jumlahInput.value;
                        hiddenJumlah.classList.add('dynamic-field');
                        form.appendChild(hiddenJumlah);
                    }
                });

                selectedCount.textContent = count + ' alat dipilih';
                btnAjukan.disabled = count === 0;
            }

            checkboxes.forEach(chk => chk.addEventListener('change', updateState));
            document.querySelectorAll('.input-jumlah').forEach(inp => inp.addEventListener('input', updateState));
        </script>
    </div>
@endsection