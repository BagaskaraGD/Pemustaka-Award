@extends('layouts.appd')

@section('content')
    <P>Nama : {{ session('nama') }}</P>
    <p>ID Civitas: {{ session('civitas')['id_civitas'] }}</p>
    <p>Status: {{ session('status') }}</p>
    <!-- Main Content -->
    <main class="flex-1 p-1">
        <!-- Container Judul dan Dropdown -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-3xl font-bold font-rubik">Leaderboard</h2>

            <!-- Dropdown Container -->
            <div class="relative">
                <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    type="button">
                    Pilih Periode
                    <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 4 4 4-4" />
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div id="dropdown"
                    class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700 absolute right-0 mt-2">
                    <ul id="periodeList" class="py-2 text-sm text-gray-700 dark:text-gray-200"
                        aria-labelledby="dropdownDefaultButton">
                        <!-- Item Periode akan diisi secara dinamis -->
                    </ul>
                </div>
            </div>
        </div>

        <!-- Background Biru -->
        <div class="relative bg-[#880e4f] text-white pt-32 px-10 pb-28 rounded-t-[25px] min-h-[350px]">
            <div class="flex justify-center items-end">
                <div class="text-center">
                    <div class="relative w-36 h-36 mx-auto">
                        <!-- Foto Profil -->
                        <img src="assets/images/profile.png" alt="User Profile"
                            class="w-full h-full object-cover rounded-full border-4 border-[rgba(251,195,77,1)] bg-gray-300">

                        <!-- Badge Peringkat -->
                        <div
                            class="absolute top-0 right-0 bg-[rgba(251,195,77,1)] text-[#880e4f] font-bold text-sm px-3 py-1 rounded-full shadow-md">
                            1st
                        </div>
                    </div>
                    <p class="mt-2 font-bold font-rubik text-lg text-[rgba(251,195,77,1)]">User 1</p>
                    <div class="flex items-center space-x-2 ml-8">
                        <img src="{{ asset('assets/images/Poin.png') }}" alt="Poin Icon" class="w-5 h-5">
                        <p class="text-md font-russo">1200</p>
                    </div>
                </div>
            </div>


            <!-- Header Points Leaderboard -->
            <div class="absolute bottom-[-2px] left-0 w-full z-10">
                <div class="bg-white p-4 rounded-t-[25px] w-full flex items-center justify-center">
                    <h3 class="text-2xl font-bold font-rubik text-[#880e4f] text-center">Points Leaderboard</h3>
                </div>
            </div>
        </div>


        <!-- Tabel Leaderboard -->
        <div class="overflow-hidden rounded-b-[25px] w-full bg-white">
            <table class="w-full text-gray-700 border-collapse">
                <thead class="p-0">
                    <tr class="bg-white border-t border-gray-300">
                        <th class="p-3 font-rubik text-left border-t border-gray-300">Place</th>
                        <th class="p-3 font-rubik text-left border-t border-gray-300">Profile</th>
                        <th class="p-3 font-rubik text-left border-t border-gray-300">Nama</th>
                        <th class="p-3 font-rubik text-left border-t border-gray-300">NIM</th>
                        <th class="p-3 font-rubik text-left border-t border-gray-300">Email</th>
                        <th class="p-3 font-rubik text-center border-t border-gray-300">Points</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ([['place' => 1, 'name' => 'User 1', 'nim' => '22410100016', 'email' => '22410100016@dinamika.ac.id', 'points' => 1200], ['place' => 2, 'name' => 'User 2', 'nim' => '22410100016', 'email' => '22410100016@dinamika.ac.id', 'points' => 1000], ['place' => 3, 'name' => 'User 3', 'nim' => '22410100016', 'email' => '22410100016@dinamika.ac.id', 'points' => 800], ['place' => 4, 'name' => 'User 4', 'nim' => '22410100016', 'email' => '22410100016@dinamika.ac.id', 'points' => 650], ['place' => 5, 'name' => 'User 5', 'nim' => '22410100016', 'email' => '22410100016@dinamika.ac.id', 'points' => 500]] as $user)
                        <tr class="{{ $loop->index % 2 == 0 ? 'bg-gray-100' : 'bg-white' }}">
                            <td class="p-3">
                                <span
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[rgba(251,195,77,1)] text-[#880e4f] font-medium border border-gray-300">
                                    {{ $user['place'] }}
                                </span>
                            </td>
                            <td class="p-3">
                                <div class="w-6 h-6 bg-blue-500 rounded-full"></div>
                            </td>
                            <td class="p-3 font-rubik font-bold text-[#880e4f]">{{ $user['name'] }}</td>
                            <td class="p-3 font-rubik font-bold text-[#880e4f]">{{ $user['nim'] }}</td>
                            <td class="p-3 font-rubik font-bold text-[#880e4f]">{{ $user['email'] }}</td>
                            <td class="p-3 text-center font-russo text-[#880e4f]">
                                <div class="flex items-center justify-center space-x-2">
                                    <img src="{{ asset('assets/images/Poin.png') }}" alt="Poin Icon" class="w-5 h-5">
                                    <span>{{ $user['points'] }}</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <br>

        <!-- My Ranking -->
        <div class="bg-white p-1 rounded-t-[25px] shadow-lg w-full flex items-center justify-center h-[45px]">
            <h3 class="text-2xl font-bold font-rubik text-[#880e4f] text-center">
                My Ranking
            </h3>
        </div>
        <div class="overflow-hidden rounded-b-[25px] w-full bg-white">
            <table class="w-full text-gray-700 border-collapse">
                <thead class="p-0">
                    <tr class="bg-white border-t border-gray-300">
                        <th class="p-3 font-rubik text-left border-t border-gray-300">Place</th>
                        <th class="p-3 font-rubik text-left border-t border-gray-300">Profile</th>
                        <th class="p-3 font-rubik text-left border-t border-gray-300">Nama</th>
                        <th class="p-3 font-rubik text-left border-t border-gray-300">NIM</th>
                        <th class="p-3 font-rubik text-left border-t border-gray-300">Email</th>
                        <th class="p-3 font-rubik text-center border-t border-gray-300">Points</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ([['place' => 1, 'name' => 'User 1', 'nim' => '22410100016', 'email' => '22410100016@dinamika.ac.id', 'points' => 1200]] as $user)
                        <tr class="bg-[#880e4f]">
                            <td class="p-3">
                                <span
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white text-[#880e4f] font-medium border border-gray-300">
                                    {{ $user['place'] }}
                                </span>
                            </td>
                            <td class="p-3">
                                <div class="w-6 h-6 bg-blue-500 rounded-full"></div>
                            </td>
                            <td class="p-3 text-white font-rubik font-bold">{{ $user['name'] }}</td>
                            <td class="p-3 text-white font-rubik font-bold">{{ $user['nim'] }}</td>
                            <td class="p-3 text-white font-rubik font-bold">{{ $user['email'] }}</td>
                            <td class="p-3 text-center font-russo text-white">
                                <div class="flex items-center justify-center space-x-2">
                                    <img src="{{ asset('assets/images/Poin.png') }}" alt="Poin Icon" class="w-5 h-5">
                                    <span>{{ $user['points'] }}</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <script src="{{ asset('js/periodedropdown.js') }}"></script>
    </main>
@endsection
