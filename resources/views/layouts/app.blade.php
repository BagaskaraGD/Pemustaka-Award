{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="api-base-url" content="{{ config('services.backend.base_url') }}">
    <title>@yield('title', 'Pemustaka Award')</title>

    {{-- Aset CSS dan JS lainnya tetap sama --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Rubik:wght@300..900&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Russo+One&display=swap">
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- ... sisa tag head ... --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
</head>

<body class="bg-gray-200">
    {{-- Logika Dinamis untuk Sidebar --}}
    @php
        $userStatus = session('status');
        $isDosen = in_array($userStatus, ['DOSEN', 'TENDIK']);

        // Konfigurasi dinamis berdasarkan status
        $config = [
            'themeColor' => $isDosen ? '#880e4f' : 'rgba(31,76,109,1)',
            'themeBgClass' => $isDosen ? 'bg-[#880e4f]' : 'bg-[rgba(31,76,109,1)]',
            'textColor' => 'text-white',
            'brandTextColor' => $isDosen ? 'text-[#880e4f]' : 'text-[rgba(31,76,109,1)]',
            'routes' => [
                'leaderboard' => $isDosen ? 'leaderboard-dosen' : 'leaderboard-mhs',
                'profile' => $isDosen ? 'profile-dosen' : 'profile-mhs',
                'kegiatan' => $isDosen ? 'kegiatan-dosen' : 'kegiatan-mhs',
                'riwayatkegiatan' => $isDosen ? 'riwayatkegiatan-dosen' : 'riwayatkegiatan-mhs',
                'aksara' => $isDosen ? 'aksara-dosen' : 'aksara-mhs',
                'formaksara' => $isDosen ? 'formaksaradinamika-dosen' : 'formaksaradinamika-mhs',
            ],
        ];
    @endphp

    <div class="flex">
        <aside class="w-64 bg-white p-5 shadow-md h-screen fixed top-0 left-0">
            <h1 class="text-lg font-bold font-rubik">
                <span style="color: {{ $config['themeColor'] }}">Pemustaka</span> <span class="text-black">Award</span>
            </h1>
            <nav class="mt-5">
                <ul>
                    {{-- Item Leaderboard --}}
                    <li class="mb-3">
                        <div
                            class="flex items-center p-3 rounded-lg {{ Request::is($config['routes']['leaderboard']) ? $config['themeBgClass'] . ' ' . $config['textColor'] : 'text-gray-700 hover:bg-gray-100' }}">
                            <img src="{{ Request::is($config['routes']['leaderboard']) ? asset('assets/images/Leaderboard.png') : asset('assets/images/BlackLeaderboard.png') }}"
                                alt="Leaderboard Icon" class="w-6 h-6 mr-4">
                            <a href="{{ url($config['routes']['leaderboard']) }}" class="font-semibold">Leaderboard</a>
                        </div>
                    </li>
                    {{-- Item Profile --}}
                    <li class="mb-3">
                        <div
                            class="flex items-center p-3 rounded-lg {{ Request::is($config['routes']['profile']) ? $config['themeBgClass'] . ' ' . $config['textColor'] : 'text-gray-700 hover:bg-gray-100' }}">
                            <img src="{{ Request::is($config['routes']['profile']) ? asset('assets/images/Profile.png') : asset('assets/images/BlackProfile.png') }}"
                                alt="Profile Icon" class="w-6 h-6 mr-4">
                            <a href="{{ url($config['routes']['profile']) }}" class="font-semibold">Profile</a>
                        </div>
                    </li>
                    {{-- Item Kegiatan --}}
                    <li class="mb-3">
                        <div
                            class="flex items-center p-3 rounded-lg {{ Request::is($config['routes']['kegiatan']) || Request::is($config['routes']['riwayatkegiatan']) ? $config['themeBgClass'] . ' ' . $config['textColor'] : 'text-gray-700 hover:bg-gray-100' }}">
                            <img src="{{ Request::is($config['routes']['kegiatan']) || Request::is($config['routes']['riwayatkegiatan']) ? asset('assets/images/Kegiatan.png') : asset('assets/images/BlackKegiatan.png') }}"
                                alt="Kegiatan Icon" class="w-6 h-6 mr-4">
                            <a href="{{ url($config['routes']['kegiatan']) }}" class="font-semibold">Kegiatan</a>
                        </div>
                    </li>
                    {{-- Item Aksara Dinamika --}}
                    <li class="mb-3">
                        <div
                            class="flex items-center p-3 rounded-lg {{ Request::is($config['routes']['aksara']) || Request::is($config['routes']['formaksara']) || Request::is($config['routes']['formaksara'] . '/edit/*/*/*') ? $config['themeBgClass'] . ' ' . $config['textColor'] : 'text-gray-700 hover:bg-gray-100' }}">
                            <img src="{{ Request::is($config['routes']['aksara']) || Request::is($config['routes']['formaksara']) || Request::is($config['routes']['formaksara'] . '/edit/*/*/*') ? asset('assets/images/Aksara.png') : asset('assets/images/BlackAksara.png') }}"
                                alt="Aksara Icon" class="w-6 h-6 mr-4">
                            <a href="{{ url($config['routes']['aksara']) }}" class="font-semibold">Aksara Dinamika</a>
                        </div>
                    </li>
                </ul>
                <div class="absolute bottom-5 w-full pr-10">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center p-3 rounded-lg text-red-500 hover:bg-red-100">
                            <i class="fas fa-sign-out-alt w-6 h-6 mr-4"></i>
                            <span class="font-semibold">Logout</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <main class="flex-1 p-5 ml-64 overflow-y-auto h-screen">
            @yield('content')
        </main>
    </div>

    <script src="https://kit.fontawesome.com/a2411311d5.js" crossorigin="anonymous"></script>
</body>

</html>
