<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Magang extends Model
{
    use HasFactory; 

    protected $fillable = [
        'user_id',
        'nama',
        'email',
        'foto',
        'asal_kampus',
        'prodi',
        'tanggal_mulai',
        'tanggal_selesai',
        'whatsapp',
        'instagram',
        'tiktok',
        'kesan',
        'pesan',
        'link_pekerjaan',
        'periode_bulan',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($magang) {
            if ($magang->tanggal_mulai && $magang->tanggal_selesai) {
                $mulai = Carbon::parse($magang->tanggal_mulai);
                $selesai = Carbon::parse($magang->tanggal_selesai);
                
                $periode = $mulai->diffInMonths($selesai);
                if ($selesai->day > $mulai->day) {
                    $periode += 1;
                }
                if ($periode == 0 && $mulai->diffInDays($selesai) > 0) {
                    $periode = 1; 
                }
                $magang->periode_bulan = $periode;

                $now = Carbon::now();
                if ($now->lt($mulai)) {
                    $magang->status = 'belum';
                } elseif ($now->between($mulai, $selesai)) {
                    $magang->status = 'aktif';
                } else {
                    $magang->status = 'selesai';
                }
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getInitialsAttribute()
    {
        $words = explode(' ', $this->nama);
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($this->nama, 0, 2));
    }

    public function getFotoUrlAttribute()
    {
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }
        return null; // Bisa diganti URL default avatar jika mau
    }

    /**
     * ✅ INI YANG HILANG SEBELUMNYA
     * Accessor untuk $magang->status_context
     */
    public function getStatusContextAttribute()
    {
        // Default values jika status kosong/null
        $default = [
            'class' => 'bg-gray-500/20 text-gray-400 border border-gray-500/30', 
            'text' => 'Tanpa Status'
        ];

        if (!$this->status) {
            return $default;
        }

        return match ($this->status) {
            'aktif'   => ['class' => 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30', 'text' => 'Aktif'],
            'selesai' => ['class' => 'bg-blue-500/20 text-blue-400 border border-blue-500/30', 'text' => 'Selesai'],
            'belum'   => ['class' => 'bg-amber-500/20 text-amber-400 border border-amber-500/30', 'text' => 'Belum Mulai'],
            default   => $default,
        };
    }
}