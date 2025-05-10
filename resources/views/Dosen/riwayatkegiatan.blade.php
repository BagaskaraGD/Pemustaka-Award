@extends('layouts.appd')

@section('content')
    <!-- Header -->
    <div class="bg-gray-200 p-4 rounded-md mb-4 font-rubik">
        <h1 class="text-2xl font-bold">Riwayat Kegiatan</h1>
    </div>

    <!-- Table Riwayat Kegiatan -->
    <div class="bg-white p-6 rounded-lg shadow-md font-rubik">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100 border-b border-gray-300">
                    <th class="p-3 text-left font-semibold">Judul Kegiatan</th>
                    <th class="p-3 text-left font-semibold">Tanggal Kegiatan</th>
                    <th class="p-3 text-left font-semibold">Jam Kegiatan</th>
                    <th class="p-3 text-left font-semibold">Pemateri</th>
                    <th class="p-3 text-left font-semibold">Media/Lokasi Kegiatan</th>
                    <th class="p-3 text-left font-semibold">Poin</th>
                    <th class="p-3 text-left font-semibold">Cetak</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-gray-300">
                    <td class="p-4 font-semibold">Seminar</td>
                    <td class="p-4">12/02/2025</td>
                    <td class="p-4">09.00 - 12.00</td>
                    <td class="p-4">Dosen</td>
                    <td class="p-4">Lokasi</td>
                    <td class="p-4">10</td>
                    <td class="p-4"><button class="bg-green-100 text-green-700 px-4 py-2 rounded-lg flex items-center"><span class="mr-2">🖨</span> Cetak Sertifikat</button></td>
                </tr>
                <tr class="border-b border-gray-300">
                    <td class="p-4 font-semibold">Literasi</td>
                    <td class="p-4">13/02/2025</td>
                    <td class="p-4">10.00 - 13.00</td>
                    <td class="p-4">Dosen</td>
                    <td class="p-4">Lokasi</td>
                    <td class="p-4">5</td>
                    <td class="p-4"><button class="bg-green-100 text-green-700 px-4 py-2 rounded-lg flex items-center"><span class="mr-2">🖨</span> Cetak Sertifikat</button></td>
                </tr>
                <tr class="border-b border-gray-300">
                    <td class="p-4 font-semibold">Literasi</td>
                    <td class="p-4">11/02/2025</td>
                    <td class="p-4">12.00 - 14.00</td>
                    <td class="p-4">Dosen</td>
                    <td class="p-4">Lokasi</td>
                    <td class="p-4">10</td>
                    <td class="p-4"><button class="bg-green-100 text-green-700 px-4 py-2 rounded-lg flex items-center"><span class="mr-2">🖨</span> Cetak Sertifikat</button></td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Total Poin -->
    <div class="flex justify-end mt-4 font-rubik">
        <div class="bg-gray-100 px-6 py-3 rounded-lg shadow-md font-semibold">Total Poin: 30</div>
    </div>
@endsection
