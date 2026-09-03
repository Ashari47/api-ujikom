@extends('layouts.app')
@section('title', 'Log Aktivitas')
@section('header-title', 'Log Aktivitas Sistem')
@section('content')
<div class="bg-white rounded-lg shadow p-6">

    <form method="GET" action="{{ route('admin.log.index') }}" class="mb-6">
        <input
            type="text"
            name="search"
            value="{{ $search ?? '' }}"
            placeholder="Cari aktivitas atau nama user..."
            class="w-full md:w-1/3 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg ml-2">
            Cari
        </button>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                    <th class="py-3 px-4 font-semibold">USER</th>
                    <th class="py-3 px-4 font-semibold">AKTIVITAS</th>
                    <th class="py-3 px-4 font-semibold">WAKTU</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50">
                    <td class="py-4 px-4 font-medium text-gray-800">
                        {{ $log->user->name ?? 'Sistem' }}
                    </td>
                    <td class="py-4 px-4 text-gray-600">
                        {{ $log->aktivitas }}
                    </td>
                    <td class="py-4 px-4 text-gray-500 text-xs">
                        {{ \Carbon\Carbon::parse($log->created_at)->format('Y-m-d H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="py-6 text-center text-gray-400">Belum ada log aktivitas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $logs->links() }}
    </div>

</div>
@endsection