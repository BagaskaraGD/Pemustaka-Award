<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KegiatanController extends Controller
{
    public function viewKegiatan1()
    {
        return view('Mahasiswa/kegiatan');
    }
    public function viewriwayatKegiatan1()
    {
        return view('Mahasiswa/riwayatkegiatan');
    }

    public function viewKegiatan2()
    {
        return view('Dosen/kegiatan');
    }
    public function viewriwayatKegiatan2()
    {
        return view('Dosen/riwayatkegiatan');
    }

}