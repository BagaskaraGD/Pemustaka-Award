@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto px-4 pt-4 md:pt-16">
        {{-- Kartu Profil & Progress Level --}}
        <div class="bg-white shadow-lg rounded-xl p-4 md:p-6">

            {{-- Bagian Atas: Info User & Peringkat --}}
            {{-- Container ini relatif di desktop untuk menampung banner absolut --}}
            <div class="relative md:h-36 flex flex-col md:flex-row">

                {{-- Banner Profil Pengguna --}}
                {{-- DESKTOP: Absolut, lebar 3/5, bentuk melengkung --}}
                {{-- MOBILE: Statis, lebar penuh, bentuk persegi panjang --}}
                <div
                    class="md:absolute md:left-0 md:top-0 w-full md:w-3/5 md:h-full bg-[#880e4f] rounded-xl md:rounded-none md:rounded-tr-full md:rounded-br-full flex items-center px-6 py-4 shadow-lg md:drop-shadow-[2px_6px_20px_rgba(0,0,0,0.5)] z-10">
                    <img src="{{ asset(session('foto_profil')) }}" alt="User Profile"
                        class="w-16 h-16 md:w-20 md:h-20 object-cover rounded-full border-4 border-[rgba(251,195,77,1)] shrink-0">
                    <div class="ml-4">
                        <h2 id="user" class="text-lg md:text-xl font-bold font-rubik text-white"></h2>
                        <p id="level-text" class="text-sm md:text-base text-white font-rubik">LEVEL 0</p>
                        <p class="text-xl md:text-2xl font-bold font-russo text-[#FFBC45] flex items-center">
                            <img src="{{ asset('assets/images/Poin.png') }}" alt="Poin Icon" class="w-5 h-5 mr-1">
                            <span id="poin">0</span>
                        </p>
                    </div>
                </div>

                {{-- Peringkat & Tombol Leaderboard --}}
                {{-- DESKTOP: Mengisi sisa ruang di kanan --}}
                {{-- MOBILE: Di bawah banner, di tengah --}}
                <div class="w-full flex flex-col items-center md:items-end mt-4 md:mt-0 md:pl-[60%]">
                    <div class="flex items-center space-x-2 md:space-x-1">
                        <p class="text-gray-700 text-2xl md:text-3xl font-bold font-rubik">PERINGKAT</p>
                        <p id="peringkat" class="text-5xl md:text-6xl font-bold font-russo text-[#880e4f]">-</p>
                    </div>
                    <a href="/leaderboard-mhs" class="w-full md:w-auto mt-2">
                        <button class="bg-[#880e4f] text-white font-rubik px-4 py-2 rounded-md w-full md:w-auto">
                            Leaderboard
                        </button>
                    </a>
                </div>
            </div>

            {{-- Progress Level --}}
            <div class="mt-10">
                <div class="flex justify-between text-gray-700 font-rubik font-bold text-xs sm:text-base">
                    <p>LEVEL 0</p>
                    <p>LEVEL 1</p>
                    <p>LEVEL 2</p>
                    <p>LEVEL 3</p>
                </div>
                <div class="flex justify-between mb-3 text-lg sm:text-2xl font-russo font-bold">
                    <p class="text-[#FFBC45] flex items-center">
                        <img src="{{ asset('assets/images/Poin.png') }}" alt="Poin Icon" class="w-5 h-5 mr-1">
                        <span class="level-threshold" data-level="0">0</span>
                    </p>
                    <p class="text-[#FFBC45] flex items-center">
                        <img src="{{ asset('assets/images/Poin.png') }}" alt="Poin Icon" class="w-5 h-5 mr-1">
                        <span class="level-threshold" data-level="1">{{ $data['level1']['nilai'] ?? 'N/A' }}</span>
                    </p>
                    <p class="text-[#FFBC45] flex items-center">
                        <img src="{{ asset('assets/images/Poin.png') }}" alt="Poin Icon" class="w-5 h-5 mr-1">
                        <span class="level-threshold" data-level="2">{{ $data['level2']['nilai'] ?? 'N/A' }}</span>
                    </p>
                    <p class="text-[#FFBC45] flex items-center">
                        <img src="{{ asset('assets/images/Poin.png') }}" alt="Poin Icon" class="w-5 h-5 mr-1">
                        <span class="level-threshold" data-level="3">{{ $data['level3']['nilai'] ?? 'N/A' }}</span>
                    </p>
                </div>

                <div class="relative bg-gray-300 h-7 rounded-full mt-2">
                    <div id="progress-fill"
                        class="bg-[#880e4f] h-full rounded-full absolute left-0 top-0 w-0 transition-[width] duration-1000 ease-out">
                    </div>
                    <div id="progress-indicator"
                        class="absolute w-6 h-6 bg-yellow-500 rounded-full border-4 border-white top-1/2 transform -translate-y-1/2 -translate-x-1/2 transition-all duration-1000 ease-out z-20"
                        style="left: 0%;"></div>
                    <div id="marker-lvl1" class="progress-marker" style="left: 33.33%;"><span id="icon-marker-lvl1"></span>
                    </div>
                    <div id="marker-lvl2" class="progress-marker" style="left: 66.67%;"><span id="icon-marker-lvl2"></span>
                    </div>
                    <div id="marker-lvl3" class="progress-marker" style="left: 100%;"><span id="icon-marker-lvl3"></span>
                    </div>
                </div>

                <div class="mt-6 relative h-10 flex justify-around">
                    <div class="claim-button-wrapper absolute" style="left: 33.33%; transform: translateX(-50%);"><button
                            id="claim-button1" data-level="1"
                            class="claim-reward-button bg-[#880e4f] font-rubik text-white px-3 py-1 md:px-4 md:py-2 rounded-md text-xs md:text-base"
                            style="display: none;">Klaim Lvl 1</button></div>
                    <div class="claim-button-wrapper absolute" style="left: 66.67%; transform: translateX(-50%);"><button
                            id="claim-button2" data-level="2"
                            class="claim-reward-button bg-[#880e4f] font-rubik text-white px-3 py-1 md:px-4 md:py-2 rounded-md text-xs md:text-base"
                            style="display: none;">Klaim Lvl 2</button></div>
                    <div class="claim-button-wrapper absolute" style="left: 100%; transform: translateX(-50%);"><button
                            id="claim-button3" data-level="3"
                            class="claim-reward-button bg-[#880e4f] font-rubik text-white px-3 py-1 md:px-4 md:py-2 rounded-md text-xs md:text-base"
                            style="display: none;">Klaim Lvl 3</button></div>
                </div>
            </div>
        </div>

        {{-- Pencapaian --}}
        <div class="bg-white shadow-lg rounded-xl p-6 mt-6">
            <h3 class="text-lg font-rubik font-bold mb-4 text-center">PENCAPAIAN</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div>
                    <p class="text-gray-500 font-rubik text-sm md:text-base">Kunjungan</p>
                    <p id="kunjungan-count" class="text-2xl md:text-3xl font-bold font-russo text-[#880e4f]">0</p>
                </div>
                <div>
                    <p class="text-gray-500 font-rubik text-sm md:text-base">Peminjaman</p>
                    <p id="pinjaman-count" class="text-2xl md:text-3xl font-bold font-russo text-[#880e4f]">0</p>
                </div>
                <div>
                    <p class="text-gray-500 font-rubik text-sm md:text-base">Kegiatan</p>
                    <p id="kegiatan-count" class="text-2xl md:text-3xl font-bold font-russo text-[#880e4f]">0</p>
                </div>
                <div>
                    <p class="text-gray-500 font-rubik text-sm md:text-base">Challenge</p>
                    <p id="challenge-count" class="text-2xl md:text-3xl font-bold font-russo text-[#880e4f]">0</p>
                </div>
            </div>
        </div>
    </div>

    @parent

    <style>
        .progress-marker {
            position: absolute;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 24px;
            height: 24px;
            background-color: #FFBC45;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
            z-index: 10;
            font-size: 12px;
        }

        .progress-marker .fa-lock,
        .progress-marker .fa-unlock-alt,
        .progress-marker .fa-check,
        .progress-marker .fa-times {
            color: white;
        }
    </style>

    <script>
        const idCivitas = "{{ session('civitas.id_civitas') }}";
    </script>
    <script src="{{ asset('js/pencapaian.js') }}"></script>
    <script src="{{ asset('js/profileuserD.js') }}"></script>
@endsection
