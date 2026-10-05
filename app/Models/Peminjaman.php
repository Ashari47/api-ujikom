<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'user_id', 'tgl_pinjam', 'tgl_kembali_plan', 'status'
    ];

    protected function casts(): array
    {
        return [
            'tgl_pinjam' => 'date:Y-m-d',
            'tgl_kembali_plan' => 'date:Y-m-d',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function detailPinjam(): HasMany
    {
        return $this->hasMany(DetailPinjam::class);
    }

    public function pengembalian(): HasOne
    {
        return $this->hasOne(Pengembalian::class);
    }

    // ===== AUTO STATUS & DENDA =====

    public function getHariTelatAttribute()
    {
        if (!in_array($this->status, ['dipinjam', 'telat'])) {
            return 0;
        }

        $now = Carbon::now()->startOfDay();

        if ($now->lessThanOrEqualTo($this->tgl_kembali_plan)) {
            return 0;
        }

        return (int) $this->tgl_kembali_plan->diffInDays($now);
    }

    public function getDendaOtomatisAttribute()
    {
        return $this->hari_telat * 5000;
    }
}