@extends('layouts.app') {{-- Pastikan Anda memiliki layout bernama 'app' --}}

@section('content')
    {{-- Memuat file JavaScript eksternal --}}
    <script src="{{ asset('js/aksaramodal.js') }}"></script>

    {{-- MODIFIKASI: Tambahkan div pembungkus ini untuk membuat semua konten di dalamnya menjadi rata tengah. --}}
    <div class="max-w-6xl mx-auto p-4">

        {{-- Main Content --}}
        <div class="flex items-center gap-x-4">
            {{-- "Tambah Review" Button --}}
            <a href="/formaksaradinamika-mhs">
                <div
                    class="w-12 h-12 rounded-full bg-[#880e4f] flex items-center justify-center
                           transition-all duration-300 ease-in-out transform hover:scale-110 hover:shadow-lg cursor-pointer">
                    <i class="fa-solid fa-plus text-white text-xl"></i>
                </div>
            </a>

            {{-- "Info" Button --}}
            <button onclick="openModal()"
                class="transition-all duration-300 ease-in-out transform hover:scale-110 hover:drop-shadow-md cursor-pointer">
                <i class="fa-sharp fa-solid fa-circle-info fa-3x" style="color: #880e4f;"></i>
            </button>
        </div>

        {{-- Tabel Histori Aksara Dinamika --}}
        <div class="mt-6 w-full bg-white shadow-2xl rounded-3xl p-4 md:p-8 space-y-6">
            <div class="flex flex-col sm:flex-row justify-between items-center">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Histori Aksara Dinamika</h2>
                <input type="text" id="searchInput" placeholder="🔍 Cari nama..."
                    class="mt-4 sm:mt-0 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500 rounded-full px-6 py-2 w-full sm:w-80"
                    oninput="filterTable()" />
            </div>

            <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm">
                <table class="min-w-full text-sm text-gray-700">
                    <thead class="bg-[#880e4f] text-white text-sm uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4 text-left">Judul Buku</th>
                            <th class="px-6 py-4 text-left">Tanggal Konfirmasi</th>
                            <th class="px-6 py-4 text-left">Periode</th>
                            <th class="px-6 py-4 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody id="dataTable" class="bg-white divide-y divide-gray-100">
                        @foreach ($data as $item)
                            <tr class="hover:bg-gray-50 cursor-pointer
                                  transition-all duration-300 ease-in-out hover:shadow-md hover:translate-y-[-2px]"
                                onclick="openHistoryModal(
                                    '{{ addslashes($item['judul']) }}',
                                    '{{ strtolower($item['status']) }}',
                                    '{{ addslashes($item['keterangan'] ?? 'Tidak ada keterangan dari admin.') }}',
                                    '{{ $item['id_aksara_dinamika'] }}',
                                    '{{ $civitasId }}',
                                    '{{ $item['induk_buku'] }}'
                                )">
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

    </div> {{-- Ini adalah tag penutup untuk div pembungkus yang kita tambahkan. --}}


    {{-- Modal Informasi Umum --}}
    <div id="modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-10 p-4">
        {{-- PERBAIKAN: Lebar modal dibuat responsif --}}
        <div class="bg-white p-6 md:p-12 rounded-3xl shadow-2xl w-full max-w-sm md:max-w-4xl text-center">
            <h2 class="text-2xl md:text-4xl font-extrabold text-gray-800 mb-6 md:mb-8">Aksara Dinamika Challenge</h2>
            {{-- Grid dibuat 1 kolom di mobile dan 2 kolom di desktop --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-10 text-left">
                {{-- Step 1 --}}
                <div class="flex items-center space-x-4 p-2 rounded-lg">
                    <img src="/assets/images/rakbuku.png" alt="Cari Buku" class="w-16 h-16 md:w-20 md:h-20 shrink-0">
                    <div>
                        <p class="font-bold text-lg md:text-2xl">1. Cari Buku</p>
                        <p class="text-base md:text-xl text-gray-600">di perpustakaan Universitas Dinamika</p>
                    </div>
                </div>
                {{-- Step 2 --}}
                <div class="flex items-center space-x-4 p-2 rounded-lg">
                    <img src="/assets/images/book.png" alt="Review Buku" class="w-16 h-16 md:w-20 md:h-20 shrink-0">
                    <div>
                        <p class="font-bold text-lg md:text-xl">2. Review Buku</p>
                        <p class="text-base md:text-xl text-gray-600">dan bagikan pendapatmu</p>
                    </div>
                </div>
                {{-- Step 3 --}}
                <div class="flex items-center space-x-4 p-2 rounded-lg">
                    <img src="/assets/images/sosmed.png" alt="Upload Postingan" class="w-16 h-16 md:w-20 md:h-20 shrink-0">
                    <div>
                        <p class="font-bold text-lg md:text-2xl">3. Upload Postingan</p>
                        <p class="text-base md:text-xl text-gray-600">ke social media</p>
                    </div>
                </div>
                {{-- Step 4 --}}
                <div class="flex items-center space-x-4 p-2 rounded-lg">
                    <img src="/assets/images/poins.png" alt="Have Fun" class="w-16 h-16 md:w-20 md:h-20 shrink-0">
                    <div>
                        <p class="font-bold text-lg md:text-2xl">4. Have Fun</p>
                        <p class="text-base md:text-xl text-gray-600">dan dapatkan poin</p>
                    </div>
                </div>
            </div>
            <div class="mt-8 text-xl md:text-3xl font-extrabold text-gray-700 cursor-pointer
                      transition-all duration-300 ease-in-out hover:text-blue-600 hover:scale-110"
                onclick="closeModal()">
                ARE YOU READY TO PLAY?
            </div>
        </div>
    </div>

    {{-- Modal Status Ditolak/Diterima (Histori Review) --}}
    <div id="ditolakModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-20 p-4">
        <div class="bg-white p-6 md:p-10 rounded-xl shadow-2xl w-full max-w-md text-left transform transition-all">
            {{-- Judul Modal (akan diubah oleh JS) --}}
            <h2 class="text-2xl md:text-3xl font-extrabold text-red-600 mb-4 border-b-2 border-red-200 pb-2 text-center">
            </h2>
            {{-- Pesan Status (akan diubah oleh JS) --}}
            <p class="text-base md:text-lg text-gray-700 mb-4 text-center" id="ditolakMessage"></p>

            {{-- Keterangan Admin (akan ditampilkan/sembunyikan oleh JS) --}}
            <div id="adminKeteranganContainer" class="bg-red-50 p-4 rounded-lg border border-red-200 mb-6 hidden">
                <p class="font-semibold text-red-800 text-left mb-2">Keterangan Admin Terakhir:</p>
                <p class="text-gray-800 text-left" id="adminKeterangan"></p>
            </div>

            {{-- Bagian Histori Review --}}
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6 max-h-60 overflow-y-auto">
                <p class="font-semibold text-gray-700 mb-4">Histori Review:</p>
                <div class="relative pl-6">
                    <div class="absolute left-2 top-0 bottom-0 w-0.5 bg-gray-300"></div>
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
                <button id="perbaikiButton" onclick="handlePerbaikiClick()"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-full
                           transition duration-300 ease-in-out transform hover:scale-105 hidden">
                    Perbaiki
                </button>
            </div>
        </div>
    </div>

    <script>
        // Pastikan variabel ini didefinisikan dengan aman
        const idCivitas = "{{ $civitasId ?? '' }}";
    </script>
@endsection
