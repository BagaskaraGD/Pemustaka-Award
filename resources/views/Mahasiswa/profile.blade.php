@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto pt-16">
        {{-- Kartu Profil & Progress Level --}}
        <div class="bg-white shadow-md p-6 relative">
            {{-- Bagian User Info & Peringkat (SAMA SEPERTI SEBELUMNYA) --}}
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
                    {{-- Poin Thresholds (SAMA SEPERTI SEBELUMNYA) --}}
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

                {{-- Progress Bar dan Marker Ikon --}}
                <div class="relative bg-gray-300 h-7 rounded-full mt-2">
                    <div class="absolute inset-0 bg-gray-300 rounded-full"></div>
                    <div id="progress-fill"
                        class="bg-[#1F4C6D] h-full rounded-full absolute left-0 top-0 w-0 transition-[width] duration-1000 ease-out">
                    </div>
                    <div id="progress-indicator"
                        class="absolute w-6 h-6 bg-yellow-500 rounded-full border-4 border-white top-1/2 transform -translate-y-1/2 -translate-x-1/2 transition-all duration-1000 ease-out z-20"
                        style="left: 0%;">
                    </div>

                    {{-- Markers Ikon untuk Setiap Level --}}
                    <div id="marker-lvl1" class="progress-marker" style="left: 33.33%;">
                        <span id="icon-marker-lvl1"></span> {{-- Hilangkan kelas text-xs agar FontAwesome default size --}}
                    </div>
                    <div id="marker-lvl2" class="progress-marker" style="left: 66.67%;">
                        <span id="icon-marker-lvl2"></span>
                    </div>
                    <div id="marker-lvl3" class="progress-marker" style="left: 100%;">
                        <span id="icon-marker-lvl3"></span>
                    </div>
                </div>

                {{-- Tombol Klaim (DI BAWAH PROGRESS BAR) --}}
                <div class="mt-6 relative h-10 flex justify-around">
                    <div id="claim-button-wrapper-lvl1" class="claim-button-wrapper"
                        style="position: absolute; left: 33.33%; transform: translateX(-50%);">
                        <button id="claim-button1" data-level="1"
                            class="claim-reward-button bg-[#1F4C6D] font-rubik text-white px-4 py-2 rounded-md"
                            style="display: none;">Klaim Lvl 1</button>
                    </div>
                    <div id="claim-button-wrapper-lvl2" class="claim-button-wrapper"
                        style="position: absolute; left: 66.67%; transform: translateX(-50%);">
                        <button id="claim-button2" data-level="2"
                            class="claim-reward-button bg-[#1F4C6D] font-rubik text-white px-4 py-2 rounded-md"
                            style="display: none;">Klaim Lvl 2</button>
                    </div>
                    <div id="claim-button-wrapper-lvl3" class="claim-button-wrapper"
                        style="position: absolute; left: 100%; transform: translateX(-100%);">
                        <button id="claim-button3" data-level="3"
                            class="claim-reward-button bg-[#1F4C6D] font-rubik text-white px-4 py-2 rounded-md"
                            style="display: none;">Klaim Lvl 3</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pencapaian (SAMA SEPERTI SEBELUMNYA) --}}
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

    {{-- Modal Notifikasi Klaim (SAMA SEPERTI SEBELUMNYA) --}}
    <div id="claimNotifModal"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60 hidden z-[999] transition-opacity duration-300 ease-out opacity-0 pointer-events-none">
        <div
            class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-md text-center transform transition-all duration-300 ease-out scale-95 opacity-0">
            <img id="claimNotifImage" src="{{ asset('/assets/images/approve.png') }}" alt="Notification"
                class="w-28 h-28 mx-auto mb-5">
            <h2 id="claimNotifTitle" class="text-2xl font-bold mb-3"></h2>
            <p id="claimNotifMessage" class="text-gray-700 mb-6"></p>
            <button onclick="closeClaimNotifModal()"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 ease-in-out">
                OK
            </button>
        </div>
    </div>

    {{-- Font Awesome --}}
    <style>
        .progress-marker {
            position: absolute;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 24px;
            /* Sesuaikan ukuran marker jika perlu */
            height: 24px;
            /* Sesuaikan ukuran marker jika perlu */
            background-color: #FFBC45;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
            z-index: 10;
            font-size: 12px;
            /* Sesuaikan ukuran font untuk ikon di dalam marker */
        }

        .progress-marker .fa-lock {
            color: white;
        }

        /* Misal: Gray-700 untuk gembok */
        .progress-marker .fa-unlock-alt {
            color: white;
        }

        /* Misal: Gray-700 untuk gembok terbuka */
        .progress-marker .fa-check {
            color: white;
        }

        /* Hijau untuk centang */
        .progress-marker .fa-times {
            color: white;
        }

        /* Merah untuk silang */
    </style>

    <script>
        const idCivitas = "{{ session('civitas.id_civitas') }}";
    </script>
    <script src="{{ asset('js/pencapaian.js') }}"></script>
    <script src="{{ asset('js/profileuser.js') }}"></script>
@endsection
