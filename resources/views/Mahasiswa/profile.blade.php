@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto pt-16">
        {{-- Kartu Profil & Progress Level --}}
        <div class="bg-white shadow-md p-6 relative">
            <div
                class="absolute left-0 top-0 w-3/5 h-36 bg-[#1F4C6D] rounded-tr-full rounded-br-full transform z-0 drop-shadow-[2px_6px_20px_rgba(0,0,0,0.5)] flex items-center px-6">
                <div class="w-20 h-20 bg-[#1F4C6D] rounded-full ml-5">
                    <img src="{{ asset(session('foto_profil')) }}" alt="User Profile"
                        class="w-full h-full object-cover rounded-full border-4 border-[rgba(251,195,77,1)]">
                </div>

                <div class="ml-4 flex flex-col justify-center h-20">
                    <h2 id="user" class="text-xl font-bold font-rubik text-white"></h2>
                    <p id="level-text" class="text-white font-rubik">LEVEL 0</p>
                    <p class="text-2xl font-bold font-russo text-[#FFBC45] flex items-center">
                        <img src="{{ asset('assets/images/Poin.png') }}" alt="Poin Icon" class="w-5 h-5 mr-1"> <span
                            id="poin"></span>
                    </p>
                </div>
            </div>
            <div class="flex">
                <div class="flex flex-col items-end ml-auto pr-20">
                    <div class="inline-flex items-end space-x-1">
                        <p class="text-gray-700 text-3xl font-bold font-rubik">PERINGKAT</p>
                        <p id="peringkat" class="text-6xl font-bold font-russo text-[#1F4C6D]"></p>
                    </div>
                    <a href="/leaderboard-mhs">
                        <button
                            class="bg-[#1F4C6D] text-white font-rubik px-4 py-2 rounded-md mt-2 mr-14">Leaderboard</button>
                    </a>
                </div>
            </div>

            {{-- Progress Level --}}
            <div class="mt-10">
                <div class="flex justify-between text-gray-700 font-rubik font-bold">
                    <p>LEVEL 0</p>
                    <p>LEVEL 1</p>
                    <p>LEVEL 2</p>
                    <p>LEVEL 3</p>
                </div>
                <div class="flex justify-between mb-3 text-2xl font-russo font-bold">
                    <p class="text-[#FFBC45] flex items-center">
                        <img src="{{ asset('assets/images/Poin.png') }}" alt="Poin Icon" class="w-5 h-5 mr-1">
                        <span class="level-threshold" data-level="0">0</span>
                    </p>
                    <p class="text-[#FFBC45] flex items-center ml-4">
                        <img src="{{ asset('assets/images/Poin.png') }}" alt="Poin Icon" class="w-5 h-5 mr-1">
                        <span class="level-threshold" data-level="1">{{ $data['level1']['nilai'] }}</span>
                    </p>
                    <p class="text-[#FFBC45] flex items-center ml-4">
                        <img src="{{ asset('assets/images/Poin.png') }}" alt="Poin Icon" class="w-5 h-5 mr-1">
                        <span class="level-threshold" data-level="2">{{ $data['level2']['nilai'] }}</span>
                    </p>
                    <p class="text-[#FFBC45] flex items-center">
                        <img src="{{ asset('assets/images/Poin.png') }}" alt="Poin Icon" class="w-5 h-5 mr-1">
                        <span class="level-threshold" data-level="3">{{ $data['level3']['nilai'] }}</span>
                    </p>
                </div>
                <div class="relative bg-gray-300 h-7 rounded-full mt-2 overflow-hidden">
                    <div class="absolute inset-0 bg-gray-300 rounded-full"></div>
                    <div id="progress-fill"
                        class="bg-[#1F4C6D] h-7 rounded-full absolute left-0 top-0 w-0 transition-[width] duration-1000 ease-out">
                    </div>
                    <div id="progress-indicator"
                        class="absolute left-0 w-6 h-6 bg-yellow-500 rounded-full border-4 border-white top-1/2 -translate-y-1/2 transform -translate-x-1/2 transition-all duration-1000 ease-out">
                    </div>
                </div>

                {{-- Tombol Klaim (dengan div pembungkus untuk centering dan spacing) --}}
                <div class="flex justify-center mt-4 space-x-4"> {{-- Ditambahkan space-x-4 untuk jarak --}}
                    {{-- Tombol Level 1 --}}
                    <button id="claim-button1"
                        class="block bg-[#1F4C6D] font-rubik mr-60 text-white px-4 py-2 rounded-md opacity-0 transition-opacity duration-300 hidden"
                        onclick="openModal(1)">Klaim </button> {{-- Kirim level ke openModal --}}

                    {{-- Tombol Level 2 --}}
                    <button id="claim-button2"
                        class="block bg-[#1F4C6D] font-rubik  text-white px-4 py-2 rounded-md opacity-0 transition-opacity duration-300 hidden"
                        onclick="openModal(2)">Klaim </button> {{-- Kirim level ke openModal --}}

                    {{-- Tombol Level 3 --}}
                    <button id="claim-button3"
                        class="block bg-[#1F4C6D] font-rubik text-white px-4 py-2 rounded-md opacity-0 transition-opacity duration-300 hidden"
                        onclick="openModal(3)">Klaim </button> {{-- Kirim level ke openModal --}}
                </div>
            </div>
        </div>

        {{-- Pencapaian --}}
        <div class="bg-white shadow-md p-6 mt-6">
            <h3 class="text-lg font-rubik font-bold mb-4 text-center">PENCAPAIAN</h3>
            <div class="grid grid-cols-4 text-center text-lg font-semibold">
                <div>
                    <p class="text-gray-500 font-rubik">Kunjungan</p>
                    <p id="kunjungan-count" class="text-3xl font-bold font-russo text-[#1F4C6D]">0</p>
                </div>
                <div>
                    <p class="text-gray-500 font-rubik">Peminjaman</p>
                    <p id="pinjaman-count" class="text-3xl font-bold font-russo text-[#1F4C6D]">0</p>
                </div>
                <div>
                    <p class="text-gray-500 font-rubik">Kegiatan</p>
                    <p id="kegiatan-count" class="text-3xl font-bold font-russo text-[#1F4C6D]">0</p>
                </div>
                <div id="challenge-container">
                    <p class="text-gray-500 font-rubik">Challenge</p>
                    <p id="challenge-count" class="text-3xl font-bold font-russo text-[#1F4C6D]">0</p>
                </div>
            </div>
        </div>
    </div>

    <div id="modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-xl font-bold mb-4">Klaim Hadiah Level <span id="modal-level">X</span>!</h2>
            <p class="text-gray-600">Selamat! Anda telah mencapai level baru dan berhak mengklaim hadiah.</p>
            {{-- Anda bisa menambahkan info hadiah spesifik di sini --}}
            <div class="mt-4 flex justify-end">
                <button class="bg-gray-400 text-white px-4 py-2 rounded-md mr-2" onclick="closeModal()">Tutup</button>
                <button class="bg-blue-500 text-white px-4 py-2 rounded-md" onclick="alert('Klaim diproses!')">Klaim
                    Sekarang</button>
            </div>
        </div>
    </div>

    <script>
        function openModal(level) { // Terima parameter level
            document.getElementById('modal-level').textContent = level; // Tampilkan level di modal
            document.getElementById('modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }
    </script>

    <script>
        const idCivitas = "{{ session('civitas')['id_civitas'] }}";
    </script>
    <script src="{{ asset('js/pencapaian.js') }}"></script>
    <script src="{{ asset('js/profileuser.js') }}"></script>
@endsection
