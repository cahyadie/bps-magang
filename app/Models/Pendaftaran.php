<?php
// app/Models/Pendaftaran.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_pendaftar',
        'email', 
        'surat_permohonan',
        'surat_kampus',
        'pas_foto',
        'asal_kampus',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'remarks',
        'konfirmasi_at',
    ];
}