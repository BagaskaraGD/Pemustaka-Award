@extends('layouts.app')

@section('content')

        <h2 class="text-3xl font-bold font-rubik mb-5">Kegiatan</h2>


    <!-- Input Kode dan Tombol -->
    <div class="flex items-center mb-4">
        <input type="text" placeholder="Kode" class="border p-2 rounded-l-md w-64 font-rubik font-semibold">
        <button class="bg-blue-500 text-white h-10 flex rounded-r-md font-semibold font-rubik items-center justify-center px-4 -ml-1">
            <span class="mr-2">+</span> Add Kegiatan
        </button>
        <a href="/riwayatkegiatan-mhs" class="bg-white border px-4 py-2 rounded-md font-semibold font-rubik flex items-center ml-2">
            <span class="mr-2">&#x1F504;</span> Riwayat
        </a>
    </div>

    <!-- Daftar Kegiatan -->
    <div class="space-y-4">
        <!-- Item 1: Sedang Berlangsung -->
        <div class="bg-white shadow-md p-4 rounded-md flex justify-between items-center relative">
            <div>
                <p class="font-medium font-rubik">Seminar</p>
                <p class="text-gray-600 text-sm font-medium font-rubik">10 Feb 2025</p>
            </div>
            <div class="text-gray-500 text-sm flex items-center font-medium font-rubik">
                Sedang Berlangsung
                <span class="w-2 h-2 bg-gray-400 rounded-full ml-2"></span>
            </div>
        </div>

        <!-- Item 2: Selesai -->
        <div class="bg-white shadow-md p-4 rounded-md flex justify-between items-center relative border-b-4 border-green-500">
            <div>
                <p class="font-medium font-rubik">Seminar</p>
                <p class="text-gray-600 text-sm font-medium font-rubik">15 Jan 2025</p>
            </div>
            <div class="text-green-600 text-sm flex items-center font-medium font-rubik">
                Selesai
                <span class="w-2 h-2 bg-green-500 rounded-full ml-2"></span>
            </div>
        </div>
    </div>
@endsection
