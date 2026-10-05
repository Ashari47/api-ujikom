<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PetugasController extends Controller
{
    // ===== PERSETUJUAN PEMINJAMAN =====

    public function indexPeminjaman(Request $request)
    {
        $search = $request->input('search');

        $peminjamans = Peminjaman::with([
            'user',
            'detailPinjam.alat'
        ])
            ->where('status', 'diajukan')
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        return view(
            'petugas.peminjaman.index',
            compact('peminjamans', 'search')
        );
    }


    public function setujuiPeminjaman($id)
    {
        DB::beginTransaction();

        try {
            $peminjaman = Peminjaman::with('detailPinjam')
                ->findOrFail($id);

            $peminjaman->update([
                'status' => 'dipinjam'
            ]);

            foreach ($peminjaman->detailPinjam as $detail) {

                $alat = Alat::findOrFail($detail->alat_id);

                $alat->stok -= $detail->jumlah;

                $alat->save();
            }

            DB::commit();

            return redirect()
                ->back()
                ->with(
                    'success',
                    'Peminjaman disetujui dan stok alat dikurangi.'
                );

        } catch (\Exception $e) {

            DB::rollback();

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Terjadi kesalahan: ' . $e->getMessage()
                );
        }
    }


    public function tolakPeminjaman($id)
    {
        try {

            $peminjaman = Peminjaman::findOrFail($id);

            if ($peminjaman->status == 'diajukan') {

                $peminjaman->delete();

                return redirect()
                    ->back()
                    ->with(
                        'success',
                        'Pengajuan peminjaman berhasil ditolak.'
                    );
            }

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Status peminjaman sudah berubah.'
                );

        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Terjadi kesalahan: ' . $e->getMessage()
                );
        }
    }


    // ===== PEMANTAUAN PENGEMBALIAN =====

    public function indexPengembalian(Request $request)
    {
        $search = $request->input('search');

        // Auto-update status jadi "telat" kalau tanggal rencana kembali
        // sudah lewat dari HARI INI (perbandingan per-tanggal, bukan per-jam)
        Peminjaman::where('status', 'dipinjam')
            ->whereDate('tgl_kembali_plan', '<', now()->toDateString())
            ->update(['status' => 'telat']);

        $peminjamans = Peminjaman::with([
            'user',
            'detailPinjam.alat',
            'pengembalian'
        ])
            ->whereIn('status', [
                'dipinjam',
                'telat'
            ])
            ->when($search, function ($query, $search) {
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where(
                        'name',
                        'like',
                        "%{$search}%"
                    );
                });
            })
            ->latest()
            ->get();

        return view(
            'petugas.pengembalian.index',
            compact('peminjamans', 'search')
        );
    }


    public function prosesPengembalian(
        Request $request,
        $peminjamanId
    ) {
        // Error bag per peminjaman, supaya pesan error hanya muncul di kartu yang bersangkutan
        $request->validateWithBag('pengembalian' . $peminjamanId, [
            'kondisi_kembali' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'denda'           => 'nullable|integer',
        ], [
            'kondisi_kembali.required' => 'Kondisi harus dipilih.',
            'kondisi_kembali.in'       => 'Kondisi tidak valid.',
            'denda.integer'            => 'Denda harus berupa angka bulat.',
        ]);

        DB::beginTransaction();

        try {

            $peminjaman = Peminjaman::with('detailPinjam')
                ->findOrFail($peminjamanId);


            // Membuat data pengembalian
            Pengembalian::create([
                'peminjaman_id' => $peminjaman->id,
                'tgl_kembali' => now(),
                'kondisi_kembali' => $request->kondisi_kembali,
                'denda' => $request->denda ?? 0,
                'petugas_id' => auth()->id(),
            ]);


            // Mengubah status peminjaman
            $peminjaman->update([
                'status' => 'dikembalikan'
            ]);


            // Mengembalikan stok alat
            foreach ($peminjaman->detailPinjam as $detail) {

                $alat = Alat::findOrFail(
                    $detail->alat_id
                );

                $alat->stok += $detail->jumlah;

                $alat->save();
            }


            DB::commit();

            return redirect()
                ->back()
                ->with(
                    'success',
                    'Pengembalian berhasil dicatat dan stok dipulihkan.'
                );

        } catch (\Exception $e) {

            DB::rollback();

            return redirect()
                ->back()
                ->with(
                    'error',
                    'Terjadi kesalahan: ' . $e->getMessage()
                );
        }
    }


    // ===== LAPORAN =====

    public function laporan(Request $request)
    {
        $status = $request->input('status');

        $dari_tanggal = $request->input(
            'dari_tanggal'
        );

        $sampai_tanggal = $request->input(
            'sampai_tanggal'
        );


        $laporans = Peminjaman::with([
            'user',
            'detailPinjam.alat',
            'pengembalian'
        ])

            // Filter berdasarkan status
            ->when($status, function ($query, $status) {

                return $query->where(
                    'status',
                    $status
                );

            })

            // Filter berdasarkan tanggal
            ->when(
                $dari_tanggal && $sampai_tanggal,
                function ($query) use (
                    $dari_tanggal,
                    $sampai_tanggal
                ) {

                    return $query->whereBetween(
                        'tgl_pinjam',
                        [
                            $dari_tanggal,
                            $sampai_tanggal
                        ]
                    );

                }
            )

            ->latest()
            ->get();


        return view(
            'petugas.laporan.index',
            compact(
                'laporans',
                'status',
                'dari_tanggal',
                'sampai_tanggal'
            )
        );
    }


    // ===== CETAK LAPORAN =====

    public function cetakLaporan(Request $request)
    {
        $status = $request->input('status');

        $dari_tanggal = $request->input(
            'dari_tanggal'
        );

        $sampai_tanggal = $request->input(
            'sampai_tanggal'
        );


        $laporans = Peminjaman::with([
            'user',
            'detailPinjam.alat',
            'pengembalian'
        ])

            // Filter berdasarkan status
            ->when($status, function ($query, $status) {

                return $query->where(
                    'status',
                    $status
                );

            })

            // Filter berdasarkan tanggal
            ->when(
                $dari_tanggal && $sampai_tanggal,
                function ($query) use (
                    $dari_tanggal,
                    $sampai_tanggal
                ) {

                    return $query->whereBetween(
                        'tgl_pinjam',
                        [
                            $dari_tanggal,
                            $sampai_tanggal
                        ]
                    );

                }
            )

            ->latest()
            ->get();


        return view(
            'petugas.laporan.cetak',
            compact(
                'laporans',
                'status',
                'dari_tanggal',
                'sampai_tanggal'
            )
        );
    }
}