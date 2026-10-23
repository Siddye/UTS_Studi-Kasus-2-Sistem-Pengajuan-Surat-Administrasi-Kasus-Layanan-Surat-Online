<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nim',
        'prodi',
        'no_hp',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isMahasiswa(): bool
    {
        return $this->role === 'mahasiswa';
    }

    public function isPetugas(): bool
    {
        return $this->role === 'petugas';
    }

    public function isKepalaBagian(): bool
    {
        return $this->role === 'kepala_bagian';
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'petugas' => 'Petugas Administrasi',
            'kepala_bagian' => 'Kepala Bagian',
            default => 'Mahasiswa',
        };
    }

    public function surats(): HasMany
    {
        return $this->hasMany(Surat::class, 'user_id');
    }

    public function suratDiproses(): HasMany
    {
        return $this->hasMany(Surat::class, 'petugas_id');
    }

    public function suratDisetujui(): HasMany
    {
        return $this->hasMany(Surat::class, 'kepala_bagian_id');
    }
}
