<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => ['nullable', 'date', 'date_format:Y-m-d'],
            'end_date' => ['nullable', 'date', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            'status' => ['nullable', 'string', 'in:menunggu,dipinjam,dikembalikan,telat'],
            'petugas' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Parameter tidak valid.',
                'errors' => $validator->errors()
            ], 422);
        }

        // PERBAIKAN ADA DI SINI (baris 15):
        // 'detailPinjams.alat' diubah menjadi 'detailpinjam.alat'
        $query = Peminjaman::with([
            'user',
            'detailpinjam.alat'
        ]);

        // Filter tanggal mulai
        $query->when($request->filled('start_date'), function ($q) use ($request) {
            $q->whereDate('tgl_pinjam', '>=', $request->start_date);
        });

        // Filter tanggal akhir
        $query->when($request->filled('end_date'), function ($q) use ($request) {
            $q->whereDate('tgl_pinjam', '<=', $request->end_date);
        });

        // Filter status
        $query->when($request->filled('status'), function ($q) use ($request) {
            $q->where('status', $request->status);
        });

        // Filter petugas
        $query->when($request->filled('petugas'), function ($q) use ($request) {
            $q->where('user_id', $request->petugas);
        });

        // Pagination
        $perPage = $request->input('per_page', 25);

        $laporan = $query->latest()->paginate($perPage);

        return response()->json([
            'message' => 'Laporan peminjaman berhasil ditarik.',
            'data' => $laporan
        ]);
    }
}