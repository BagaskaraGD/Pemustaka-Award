@extends('layouts.app')

@section('content')
    <main class="flex-1 p-1">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-3xl font-bold font-rubik">Leaderboard</h2>

            <div class="relative">
                <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown"
                    class="text-slate-800 bg-white border border-slate-300 hover:bg-slate-50 focus:ring-4 focus:outline-none focus:ring-slate-200 font-semibold rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center shadow-sm transition-all duration-200"
                    type="button">
                    {{ $selectedPeriodeName ?? 'Pilih Periode' }}
                    <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 4 4 4-4" />
                    </svg>
                </button>

                <div id="dropdown"
                    class="z-10 hidden bg-amber-50 divide-y divide-amber-50 rounded-lg shadow-lg w-48 absolute right-0 mt-2 border border-amber-50 p-2
                    transition-all duration-300 ease-out"
                    {{-- HAPUS transform dan opacity-0 --}} style="--tw-shadow-color: white; --tw-shadow: var(--tw-shadow-colored);">
                    <ul id="periodeList" class="space-y-1" aria-labelledby="dropdownDefaultButton">
                        {{-- Konten akan diisi oleh periodedropdown.js --}}
                    </ul>
                </div>
            </div>
        </div>

        {{-- Tampilkan hanya peringkat 1 --}}
        @php
            $topUser = $top5[0] ?? null; // Pengguna peringkat 1
            $topUserPhoto = asset('assets/images/profile.png'); // Default photo jika tidak ada data jkel atau user

            if ($topUser && isset($topUser['jkel'])) {
                // Pastikan $topUser ada dan memiliki field 'jkel'
                $gender = strtolower(trim($topUser['jkel'])); // Ambil, ubah ke huruf kecil, dan trim spasi
                if ($gender == 'pria' || $gender == 'l') {
                    $topUserPhoto = asset('assets/images/Cylo.png');
                } elseif ($gender == 'wanita' || $gender == 'p') {
                    $topUserPhoto = asset('assets/images/Cyla.png');
                }
            }
        @endphp
        <div class="relative bg-[#880e4f] text-white pt-32 px-10 pb-28 rounded-t-[25px] min-h-[350px]">
            <div class="flex justify-center items-end">
                @if ($topUser)
                    <div class="text-center">
                        <div class="relative w-36 h-36 mx-auto">
                            <img src="{{ $topUserPhoto }}" alt="User Profile Peringkat 1"
                                class="w-full h-full object-cover rounded-full border-4 border-[rgba(251,195,77,1)]">

                            <div
                                class="absolute top-0 right-0 bg-[rgba(251,195,77,1)] text-[#880e4f] font-bold text-sm px-3 py-1 rounded-full shadow-md">
                                1st
                            </div>
                        </div>
                        <p class="mt-2 font-bold font-rubik text-lg text-[rgba(251,195,77,1)]">{{ $topUser['nama'] }}</p>
                        <p class="mt-2 font-bold font-rubik text-lg text-[rgba(251,195,77,1)]">{{ $topUser['nim'] }}</p>
                        <div class="flex justify-center items-center space-x-2 mt-2">
                            <img src="{{ asset('assets/images/Poin.png') }}" alt="Poin Icon" class="w-8 h-8">
                            <p class="text-lg leading-none font-russo">{{ $topUser['total_rekap_poin'] }}</p>
                        </div>
                    </div>
                @else
                    <div class="text-center">
                        <p class="text-lg font-bold font-rubik text-[rgba(251,195,77,1)]">Data Peringkat 1 Tidak Tersedia
                        </p>
                    </div>
                @endif
            </div>


            <div class="absolute bottom-[-2px] left-0 w-full z-10">
                <div class="bg-white p-4 rounded-t-[25px] w-full flex items-center justify-center">
                    <h3 class="text-2xl font-bold font-rubik text-[#880e4f] text-center">Points Leaderboard</h3>
                </div>
            </div>
        </div>


        <div class="overflow-hidden rounded-b-[25px] w-full bg-white">
            <table class="w-full text-gray-700 border-collapse">
                <thead class="p-0">
                    <tr class="bg-white border-t border-gray-300">
                        <th class="p-3 font-rubik text-left border-t border-gray-300">Place</th>
                        <th class="p-3 font-rubik text-left border-t border-gray-300">Profile</th>
                        <th class="p-3 font-rubik text-left border-t border-gray-300">Nama</th>
                        <th class="p-3 font-rubik text-left border-t border-gray-300">NIM</th>
                        <th class="p-3 font-rubik text-left border-t border-gray-300">Status</th>
                        <th class="p-3 font-rubik text-center border-t border-gray-300">Points</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($top5 as $index => $user)
                        @php
                            $userPhoto = asset('assets/images/profile.png'); // Default photo
                            if (isset($user['jkel'])) {
                                // Pastikan field 'jkel' ada
                                $gender = strtolower(trim($user['jkel']));
                                if ($gender == 'pria' || $gender == 'l') {
                                    $userPhoto = asset('assets/images/Cylo.png');
                                } elseif ($gender == 'wanita' || $gender == 'p') {
                                    $userPhoto = asset('assets/images/Cyla.png');
                                }
                            }
                        @endphp
                        <tr class="{{ $index % 2 == 0 ? 'bg-gray-100' : 'bg-white' }}">
                            <td class="p-3">
                                <span
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[rgba(251,195,77,1)] text-[#880e4f] font-medium border border-gray-300">
                                    {{ $index + 1 }}
                                </span>
                            </td>
                            <td class="p-3">
                                <img src="{{ $userPhoto }}" alt="Profile {{ $user['nama'] ?? '' }}"
                                    class="w-6 h-6 object-cover rounded-full">
                            </td>
                            <td class="p-3 font-rubik font-bold text-[#880e4f]">{{ $user['nama'] ?? '-' }}</td>
                            <td class="p-3 font-rubik font-bold text-[#880e4f]">{{ $user['nim'] ?? '-' }}</td>
                            <td class="p-3 font-rubik font-bold text-[#880e4f]">{{ $user['status'] ?? '-' }}</td>
                            <td class="p-3 text-center font-russo text-[#880e4f]">
                                <div class="flex items-center justify-center space-x-2">
                                    <img src="{{ asset('assets/images/Poin.png') }}" alt="Poin Icon" class="w-5 h-5">
                                    <span>{{ $user['total_rekap_poin'] ?? 0 }}</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <br>

        <div class="bg-white p-1 rounded-t-[25px] shadow-lg w-full flex items-center justify-center h-[45px]">
            <h3 class="text-2xl font-bold font-rubik text-[#880e4f] text-center">
                My Ranking
            </h3>
        </div>
        <div class="overflow-hidden rounded-b-[25px] w-full bg-white">
            <table class="w-full text-gray-700 border-collapse">
                <thead class="p-0">
                    {{-- Header tabel My Ranking disamakan dengan Top 5 --}}
                    <tr class="bg-white border-t border-gray-300">
                        <th class="p-3 font-rubik text-left border-t border-gray-300">Place</th>
                        <th class="p-3 font-rubik text-left border-t border-gray-300">Profile</th>
                        <th class="p-3 font-rubik text-left border-t border-gray-300">Nama</th>
                        <th class="p-3 font-rubik text-left border-t border-gray-300">NIM</th>
                        <th class="p-3 font-rubik text-left border-t border-gray-300">Status</th> {{-- Diubah dari Email menjadi Status --}}
                        <th class="p-3 font-rubik text-center border-t border-gray-300">Points</th>
                    </tr>
                </thead>
                <tbody onclick="window.location.href='{{ route('profile2') }}'" id="myRankingTableBody">
                </tbody>
            </table>
        </div>
    </main>
    <script src="{{ asset('js/periodedropdown.js') }}"></script>
    <script>
        const idCivitas = "{{ session('civitas')['id_civitas'] }}";
        const fotoProfilSession =
            "{{ asset(session('foto_profil', 'assets/images/profile.png')) }}"; // Ambil dari session, dengan default
    </script>
    <script src="{{ asset('js/myrankingDosen.js') }}"></script>
@endsection
