@extends('layouts.app')

@section('content')
    <script src="{{ asset('js/aksaramodal.js') }}"></script>
    {{-- Main Content --}}
    <a href="/formaksaradinamika-mhs"> <button
            class="bg-white h-14 w-14 text-4xl shadow-lg justify-center mb-4">+</button></a>
    <button class="bg-white h-14 w-14 text-4xl shadow-lg justify-center mb-4  ml-2" onclick="openModal()">i</button>
    <!-- Modal -->
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
                        <p class="font-bold text-2xl">2. Review Buku</p>
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

    <!-- Daftar Item -->
    <div class="space-y-4">
        <!-- Item 1: Sedang Menunggu -->
        <div class="bg-white shadow-md p-4 rounded-md flex justify-between items-center relative">
            <div>
                <p class="font-bold">Panduan Java Dasar Bagian 1</p>
                <p class="text-gray-600 text-sm">10 Feb 2025</p>
            </div>
            <div class="text-gray-500 text-sm flex items-center">
                Sedang Menunggu
                <span class="w-2 h-2 bg-gray-400 rounded-full ml-2"></span>
            </div>
        </div>

        <!-- Item 2: Diterima -->
        <div
            class="bg-white shadow-md p-4 rounded-md flex justify-between items-center relative border-b-4 border-green-500">
            <div>
                <p class="font-bold">Manusia Ulang-Alik: Biografi Umar Kayam</p>
                <p class="text-gray-600 text-sm">15 Jan 2025</p>
            </div>
            <div class="text-green-600 text-sm flex items-center">
                Diterima
                <span class="w-2 h-2 bg-green-500 rounded-full ml-2"></span>
            </div>
        </div>

        <!-- Item 3: Ditolak -->
        <div class="bg-white shadow-md p-4 rounded-md flex justify-between items-center relative border-b-4 border-red-500">
            <div>
                <p class="font-bold">Panduan Belajar Desain Grafis Dengan Adobe Photoshop CS</p>
                <p class="text-gray-600 text-sm">26 Des 2024</p>
            </div>
            <div class="text-red-600 text-sm flex items-center">
                Ditolak
                <span class="w-2 h-2 bg-red-500 rounded-full ml-2"></span>
            </div>
        </div>
    </div>
@endsection
