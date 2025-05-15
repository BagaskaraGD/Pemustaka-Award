@extends('layouts.app')

@section('content')
    <h2 class="text-3xl font-bold font-rubik mb-5">Kegiatan</h2>


    <form action="{{ route('kehadiran.store') }}" method="POST" class="flex items-center mb-4">
        @csrf
        <input type="text" name="kode" placeholder="Kode" class="border p-2 rounded-l-md w-64 font-rubik font-semibold"
            id="koderandom">
        <button type="submit"
            class="bg-blue-500 text-white h-10 flex rounded-r-md font-semibold font-rubik items-center justify-center px-4 -ml-1">
            <span class="mr-2">+</span> Add Kegiatan
        </button>
    </form>

    <!-- Table Riwayat Kegiatan -->
    <div class="overflow-hidden rounded-b-[25px] w-full bg-white">
        <table class="w-full text-gray-700 border-collapse">
            <thead class="p-0">
                <tr class="bg-white border-t border-gray-300">
                    <th class="p-3 font-rubik text-center border-t border-gray-300">Judul Kegiatan</th>
                    <th class="p-3 font-rubik text-center border-t border-gray-300">Tanggal Kegiatan</th>
                    <th class="p-3 font-rubik text-center border-t border-gray-300">Jam Kegiatan</th>
                    <th class="p-3 font-rubik text-center border-t border-gray-300">Pemateri</th>
                    <th class="p-3 font-rubik text-center border-t border-gray-300">Media/Lokasi Kegiatan</th>
                    <th class="p-3 font-rubik text-center border-t border-gray-300">Poin</th>
                    <th class="p-3 font-rubik text-center border-t border-gray-300">Cetak</th>
                </tr>
            </thead>
            <tbody id="mykehadirantable">
                <!-- Akan diisi dari JavaScript -->
            </tbody>
        </table>
    </div>

    <!-- Total Poin -->
    <div class="flex justify-end mt-4 font-rubik">
        <div class="bg-gray-100 px-6 py-3 rounded-lg shadow-md font-semibold">Total Poin: <span id="totalpoin"></span></div>
    </div>
    <script>
        const idCivitas = "{{ session('civitas')['id_civitas'] }}";
    </script>
    <script src="{{ asset('js/kehadiran.js') }}"></script>
    
@endsection
