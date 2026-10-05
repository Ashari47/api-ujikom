<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name', 'email', 'password', 'role', 'no_hp', 'alamat', 'foto_profile'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // Laravel otomatis meng-hash teks apapun yang masuk ke properti password!
        ];
    }

    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function logAktivitas(): HasMany
    {
        return $this->hasMany(LogAktivitas::class);
    }

    /**
     * Cek apakah user masih punya peminjaman yang belum selesai.
     * Selesai = dikembalikan, telat (sudah dikembalikan terlambat), atau ditolak.
     */
    public function punyaPeminjamanAktif(): bool
    {
        return $this->peminjaman()
            ->whereNotIn('status', ['dikembalikan', 'telat', 'ditolak'])
            ->exists();
    }
}