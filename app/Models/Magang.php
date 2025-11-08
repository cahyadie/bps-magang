<?php
// app/Models/Magang.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Magang extends Model
{
    protected $fillable = [
        'nama',
        'foto',
        'asal_kampus',
        'tanggal_mulai',
        'tanggal_selesai',
        'link_pekerjaan',
        'whatsapp',
        'instagram',
        'tiktok',
        'periode_bulan',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    // Auto-calculate periode dan status sebelum save
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($magang) {
            // Hitung periode bulan
            if ($magang->tanggal_mulai && $magang->tanggal_selesai) {
                $mulai = Carbon::parse($magang->tanggal_mulai);
                $selesai = Carbon::parse($magang->tanggal_selesai);
                $magang->periode_bulan = $mulai->diffInMonths($selesai);
            }

            // Tentukan status
            $now = Carbon::now();
            $mulai = Carbon::parse($magang->tanggal_mulai);
            $selesai = Carbon::parse($magang->tanggal_selesai);

            if ($now->lt($mulai)) {
                $magang->status = 'belum';
            } elseif ($now->between($mulai, $selesai)) {
                $magang->status = 'aktif';
            } else {
                $magang->status = 'selesai';
            }
        });
    }

    // Accessor untuk initial avatar
    public function getInitialsAttribute()
    {
        $words = explode(' ', $this->nama);
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($this->nama, 0, 2));
    }

    // Accessor untuk foto URL atau initial
    public function getFotoUrlAttribute()
    {
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }
        return null;
    }
}
