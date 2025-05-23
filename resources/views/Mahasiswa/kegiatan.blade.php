@extends('layouts.app')

@section('content')
    <h2
        class="text-3xl md:text-4xl font-extrabold font-rubik mb-6 text-gray-800 border-b-2 border-blue-600 pb-2 inline-block">
        Dashboard Kegiatan & Poin
    </h2>

    {{-- Form Tambah Kegiatan --}}
    <form action="{{ route('kehadiran.store') }}" method="POST"
        class="flex flex-col sm:flex-row items-center mb-8 gap-4 p-5 bg-white rounded-xl shadow-md border border-gray-100">
        @csrf
        <input type="text" name="kode" placeholder="Masukkan Kode Kegiatan Anda"
            class="flex-grow border border-gray-300 p-2.5 rounded-lg shadow-inner focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base font-rubik placeholder-gray-400"
            id="koderandom">
        <button type="submit"
            class="w-full sm:w-auto bg-[rgba(31,76,109,1)] text-white text-base font-semibold py-2.5 px-6 rounded-lg shadow-md hover:bg-blue-700 transition-colors duration-200 flex items-center justify-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <span>Klaim Poin</span>
        </button>
    </form>

    {{-- Table Riwayat Kegiatan --}}
    <div class="bg-white shadow-xl rounded-xl overflow-hidden mb-6 border border-gray-200">
        <table class="min-w-full text-gray-800">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="p-3 text-left text-xs font-semibold font-rubik text-gray-700 uppercase tracking-wider">Judul
                        Kegiatan</th>
                    <th class="p-3 text-left text-xs font-semibold font-rubik text-gray-700 uppercase tracking-wider">
                        Tanggal</th>
                    <th class="p-3 text-left text-xs font-semibold font-rubik text-gray-700 uppercase tracking-wider">Jam
                    </th>
                    <th class="p-3 text-left text-xs font-semibold font-rubik text-gray-700 uppercase tracking-wider">
                        Pemateri</th>
                    <th class="p-3 text-left text-xs font-semibold font-rubik text-gray-700 uppercase tracking-wider">Lokasi
                    </th>
                    <th class="p-3 text-center text-xs font-semibold font-rubik text-gray-700 uppercase tracking-wider">Poin
                    </th>
                    <th class="p-3 text-center text-xs font-semibold font-rubik text-gray-700 uppercase tracking-wider">Aksi
                    </th>
                </tr>
            </thead>
            <tbody id="mykehadirantable" class="divide-y divide-gray-100">
            </tbody>
        </table>
    </div>

    {{-- Total Poin --}}
    <div class="flex justify-end mt-6 font-rubik">
        <div class="bg-white border border-gray-200 px-6 py-3 rounded-xl shadow-md flex items-center space-x-3">
            <div class="text-lg font-bold text-gray-700">Total Poin:</div>
            <div class="text-3xl font-extrabold text-[rgba(31,76,109,1)]" id="totalpoin">0</div>
        </div>
    </div>

    <script>
        const idCivitas = "{{ session('civitas')['id_civitas'] }}";
    </script>
    <script src="{{ asset('js/kehadiran.js') }}"></script>
@endsection
