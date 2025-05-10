<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AksaraDinamika extends Model
{
    use HasFactory;

    public $timestamps = false;


    protected $table = 'AKSARADINAMIKA'; // Pastikan nama tabel sesuai

    protected $primaryKey = 'id_aksara_dinamika'; // Primary Key

    protected $fillable = [
        'kode_buku',
        'review',
        'dosen_usulan',
        'link_upload',
        'tgl_upload',
    ];
}
