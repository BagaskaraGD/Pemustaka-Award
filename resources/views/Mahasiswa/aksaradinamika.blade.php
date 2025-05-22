@extends('layouts.app')

@section('content')
    {{-- Memuat file JavaScript eksternal --}}
    <script src="{{ asset('js/aksaramodal.js') }}"></script>

    {{-- Main Content (sama seperti sebelumnya) --}}
    <div class="flex items-center gap-x-4">
        <a href="/formaksaradinamika-mhs">
            <div class="w-12 h-12 rounded-full bg-[#1f4c6d] flex items-center justify-center transition cursor-pointer">
                <i class="fa-solid fa-plus text-white text-xl"></i>
            </div>
        </a>

        <button onclick="openModal()">
            <i class="fa-sharp fa-solid fa-circle-info fa-3x" style="color: #1f4c6d;"></i>
        </button>
    </div>

    {{-- Modal Informasi (sama seperti sebelumnya) --}}
    <div id="modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-10">
        <div class="bg-white p-12 rounded-3xl shadow-2xl w-[900px] text-center mb-10">
            <h2 class="text-4xl font-extrabold text-gray-800 mb-8">Aksara Dinamika Challenge</h2>
            <div class="grid grid-cols-2 gap-10 text-left">
                <div class="flex items-start space-x-6">
                    <img src="/assets/images/rakbuku.png" alt="Cari Buku" class="w-20 h-20">
                    <div>
                        <p class="font-bold text-2xl">1. Cari Buku</p>
                        <p class="text-xl text-gray-600">di perpustakaan Universitas Dinamika</p>
                    </div>
                </div>
                <div class="flex items-start space-x-6">
                    <img src="/assets/images/book.png" alt="Review Buku" class="w-20 h-20">
                    <div>
                        <p class="font-bold text-xl">2. Review Buku</p>
                        <p class="text-xl text-gray-600">dan bagikan pendapatmu</p>
                    </div>
                </div>
                <div class="flex items-start space-x-6">
                    <img src="/assets/images/sosmed.png" alt="Upload Postingan" class="w-20 h-20">
                    <div>
                        <p class="font-bold text-2xl">3. Upload Postingan</p>
                        <p class="text-xl text-gray-600">ke social media tentang buku yang kamu review</p>
                    </div>
                </div>
                <div class="flex items-start space-x-6">
                    <img src="/assets/images/poins.png" alt="Have Fun" class="w-30 h-20">
                    <div>
                        <p class="font-bold text-2xl">4. Have Fun</p>
                        <p class="text-xl text-gray-600">dan dapatkan poin sebanyak-banyaknya</p>
                    </div>
                </div>
            </div>
            <div class="mt-10 text-3xl font-extrabold text-gray-700 cursor-pointer transition-all duration-300 hover:text-blue-600 hover:scale-110"
                onclick="closeModal()">
                ARE YOU READY TO PLAY?
            </div>
        </div>
    </div>

    {{-- Modal Status Ditolak (sudah diperbarui seperti yang dijelaskan sebelumnya) --}}
    <div id="ditolakModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-20">
        <div
            class="bg-white p-10 rounded-xl shadow-2xl w-[700px] text-left transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
            <h2 class="text-3xl font-extrabold text-red-600 mb-4 border-b-2 border-red-200 pb-2 text-center">Status Ditolak
            </h2>
            <p class="text-lg text-gray-700 mb-4 text-center" id="ditolakMessage"></p>

            <div class="bg-red-50 p-4 rounded-lg border border-red-200 mb-6">
                <p class="font-semibold text-red-800 text-left mb-2">Keterangan Admin Terakhir:</p>
                <p class="text-gray-800 text-left" id="adminKeterangan"></p>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6 max-h-60 overflow-y-auto">
                <p class="font-semibold text-gray-700 mb-4">Histori Review:</p>
                <div id="reviewHistoryTimeline" class="relative pl-6">
                    <div class="absolute left-2 top-0 bottom-0 w-0.5 bg-gray-300"></div>
                </div>
            </div>

            <div class="mt-6 flex justify-center space-x-4">
                <button onclick="closeDitolakModal()"
                    class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-6 rounded-full transition duration-300 ease-in-out transform hover:scale-105">
                    Tutup
                </button>
                <button id="perbaikiButton" onclick="handlePerbaikiClick()"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-full transition duration-300 ease-in-out transform hover:scale-105">
                    Perbaiki
                </button>
            </div>
        </div>
    </div>

    {{-- Tabel Histori Aksara Dinamika (perbarui bagian onclick) --}}
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
                        <th class="px-6 py-4 text-left"> Judul Buku</th>
                        <th class="px-6 py-4 text-left"> Tanggal Review</th>
                        <th class="px-6 py-4 text-left"> Periode</th>
                        <th class="px-6 py-4 text-left"> Status</th>
                    </tr>
                </thead>
                <tbody id="dataTable" class="bg-white divide-y divide-gray-100">
                    @foreach ($data as $item)
                        <tr class="hover:bg-gray-50 cursor-pointer" {{-- PERBAIKI PEMANGGILAN ONCLICK DI SINI --}}
                            onclick="openDitolakModal(
                'Pengajuan Anda untuk buku {{ $item['judul'] }} {{ strtolower($item['status']) == 'ditolak' }}. Mohon periksa kembali persyaratan atau hubungi admin.',
                '{{ $item['keterangan'] ?? 'Tidak ada keterangan dari admin.' }}',
                '{{ $item['id_aksara_dinamika'] }}', {{-- Ini adalah ID Aksara Dinamika dari baris yang diklik --}}
                '{{ $civitasId }}' {{-- Ini adalah ID Civitas yang didapatkan dari controller --}}
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
    {{-- Hapus blok <script> yang ada di sini sebelumnya --}}
    <script>
        // Fungsi filterTable bisa tetap di sini atau dipindahkan ke aksaramodal.js juga.
        // Jika Anda ingin memindahkannya, pastikan untuk memanggilnya di tempat yang tepat
        // (misalnya saat DOMContentLoaded). Untuk saat ini, biarkan di sini juga tidak masalah.
        function filterTable() {
            const input = document.getElementById("searchInput").value.toLowerCase();
            const rows = document.querySelectorAll("#dataTable tr");

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(input) ? "" : "none";
            });
        }
    </script>
    <script>
        const idCivitas = "{{ session('civitas')['id_civitas'] }}";
    </script>
@endsection
