@extends('layouts.app') {{-- Pastikan Anda memiliki layout bernama 'app' --}}

@section('content')
    {{-- Memuat file JavaScript eksternal --}}
    <script src="{{ asset('js/aksaramodal.js') }}"></script>

    {{-- Main Content --}}
    <div class="flex items-center gap-x-4">
        {{-- "Tambah Review" Button --}}
        <a href="/formaksaradinamika-mhs">
            <div class="w-12 h-12 rounded-full bg-[#1f4c6d] flex items-center justify-center
                        transition-all duration-300 ease-in-out transform hover:scale-110 hover:shadow-lg cursor-pointer">
                <i class="fa-solid fa-plus text-white text-xl"></i>
            </div>
        </a>

        {{-- "Info" Button --}}
        <button onclick="openModal()"
            class="transition-all duration-300 ease-in-out transform hover:scale-110 hover:drop-shadow-md cursor-pointer">
            <i class="fa-sharp fa-solid fa-circle-info fa-3x" style="color: #1f4c6d;"></i>
        </button>
    </div>

    {{-- Modal Informasi Umum --}}
    <div id="modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-10">
        <div class="bg-white p-12 rounded-3xl shadow-2xl w-[900px] text-center mb-10">
            <h2 class="text-4xl font-extrabold text-gray-800 mb-8">Aksara Dinamika Challenge</h2>
            <div class="grid grid-cols-2 gap-10 text-left">
                {{-- Step 1 --}}
                <div class="flex items-start space-x-6 p-4 rounded-lg
                            transition-all duration-300 ease-in-out transform hover:scale-[1.02] hover:shadow-md cursor-default">
                    <img src="/assets/images/rakbuku.png" alt="Cari Buku" class="w-20 h-20">
                    <div>
                        <p class="font-bold text-2xl">1. Cari Buku</p>
                        <p class="text-xl text-gray-600">di perpustakaan Universitas Dinamika</p>
                    </div>
                </div>
                {{-- Step 2 --}}
                <div class="flex items-start space-x-6 p-4 rounded-lg
                            transition-all duration-300 ease-in-out transform hover:scale-[1.02] hover:shadow-md cursor-default">
                    <img src="/assets/images/book.png" alt="Review Buku" class="w-20 h-20">
                    <div>
                        <p class="font-bold text-xl">2. Review Buku</p>
                        <p class="text-xl text-gray-600">dan bagikan pendapatmu</p>
                    </div>
                </div>
                {{-- Step 3 --}}
                <div class="flex items-start space-x-6 p-4 rounded-lg
                            transition-all duration-300 ease-in-out transform hover:scale-[1.02] hover:shadow-md cursor-default">
                    <img src="/assets/images/sosmed.png" alt="Upload Postingan" class="w-20 h-20">
                    <div>
                        <p class="font-bold text-2xl">3. Upload Postingan</p>
                        <p class="text-xl text-gray-600">ke social media tentang buku yang kamu review</p>
                    </div>
                </div>
                {{-- Step 4 --}}
                <div class="flex items-start space-x-6 p-4 rounded-lg
                            transition-all duration-300 ease-in-out transform hover:scale-[1.02] hover:shadow-md cursor-default">
                    <img src="/assets/images/poins.png" alt="Have Fun" class="w-30 h-20">
                    <div>
                        <p class="font-bold text-2xl">4. Have Fun</p>
                        <p class="text-xl text-gray-600">dan dapatkan poin sebanyak-banyaknya</p>
                    </div>
                </div>
            </div>
            <div class="mt-10 text-3xl font-extrabold text-gray-700 cursor-pointer
                        transition-all duration-300 ease-in-out hover:text-blue-600 hover:scale-110"
                onclick="closeModal()">
                ARE YOU READY TO PLAY?
            </div>
        </div>
    </div>

    {{-- Modal Status Ditolak/Diterima (Histori Review) --}}
    <div id="ditolakModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-20">
        <div
            class="bg-white p-10 rounded-xl shadow-2xl w-[700px] text-left transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
            {{-- Judul Modal (akan diubah oleh JS) --}}
            <h2 class="text-3xl font-extrabold text-red-600 mb-4 border-b-2 border-red-200 pb-2 text-center"></h2>
            {{-- Pesan Status (akan diubah oleh JS) --}}
            <p class="text-lg text-gray-700 mb-4 text-center" id="ditolakMessage"></p>

            {{-- Keterangan Admin (akan ditampilkan/sembunyikan oleh JS) --}}
            <div id="adminKeteranganContainer" class="bg-red-50 p-4 rounded-lg border border-red-200 mb-6 hidden">
                <p class="font-semibold text-red-800 text-left mb-2">Keterangan Admin Terakhir:</p>
                <p class="text-gray-800 text-left" id="adminKeterangan"></p>
            </div>

            {{-- Bagian Histori Review --}}
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6 max-h-60 overflow-y-auto">
                <p class="font-semibold text-gray-700 mb-4">Histori Review:</p>
                {{-- Container utama untuk garis dan item timeline --}}
                <div class="relative pl-6">
                    {{-- Garis timeline vertikal (tetap di sini) --}}
                    <div class="absolute left-2 top-0 bottom-0 w-0.5 bg-gray-300"></div>
                    {{-- Div tempat item histori akan ditambahkan oleh JavaScript --}}
                    <div id="reviewHistoryItems" class="relative">
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-center space-x-4">
                <button onclick="closeDitolakModal()"
                    class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-6 rounded-full
                        transition duration-300 ease-in-out transform hover:scale-105">
                    Tutup
                </button>
                {{-- Tombol Perbaiki (akan ditampilkan/sembunyikan oleh JS) --}}
                <button id="perbaikiButton" onclick="handlePerbaikiClick()"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-full
                        transition duration-300 ease-in-out transform hover:scale-105 hidden">
                    Perbaiki
                </button>
            </div>
        </div>
    </div>

    {{-- Tabel Histori Aksara Dinamika --}}
    <div class="ml-9 mt-6 w-full max-w-6xl bg-white shadow-2xl rounded-3xl p-8 space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-center">
            <h2 class="text-3xl font-bold text-gray-800">Histori Aksara Dinamika</h2>
            <input type="text" id="searchInput" placeholder="🔍 Cari nama..."
                class="mt-4 sm:mt-0 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500 rounded-full px-6 py-2 w-full sm:w-80"
                oninput="filterTable()" />
        </div>

        <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm">
            <table class="min-w-full text-sm text-gray-700">
                <thead class="bg-[#1f4c6d] text-white text-sm uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 text-left">Judul Buku</th>
                        <th class="px-6 py-4 text-left">Tanggal Confirm</th>
                        <th class="px-6 py-4 text-left">Periode</th>
                        <th class="px-6 py-4 text-left">Status</th>
                    </tr>
                </thead>
                <tbody id="dataTable" class="bg-white divide-y divide-gray-100">
                    @foreach ($data as $item)
                        <tr class="hover:bg-gray-50 cursor-pointer
                                   transition-all duration-300 ease-in-out hover:shadow-md hover:translate-y-[-2px]" {{-- Added hover classes here --}}
                            @if (strtolower($item['status']) == 'ditolak' || strtolower($item['status']) == 'diterima')
                                onclick="openHistoryModal(
                                    '{{ $item['judul'] }}',
                                    '{{ strtolower($item['status']) }}',
                                    '{{ $item['keterangan'] ?? 'Tidak ada keterangan dari admin.' }}',
                                    '{{ $item['id_aksara_dinamika'] }}',
                                    '{{ $civitasId }}',
                                    '{{ $item['induk_buku'] }}',
                                )"
                            @endif
                        >
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $item['judul'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item['tgl_review'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item['nama_periode'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item['status'] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Variabel JavaScript untuk ID Civitas (jika diperlukan secara global) --}}
    <script>
        const idCivitas = "{{ $civitasId ?? '' }}"; // Pastikan variabel $civitasId tersedia dari controller Anda
    </script>
@endsection