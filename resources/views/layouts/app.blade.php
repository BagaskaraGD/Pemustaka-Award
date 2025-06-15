{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="api-base-url" content="{{ config('services.backend.base_url') }}">
    <title>@yield('title', 'Pemustaka Award')</title>

    {{-- Aset CSS dan JS --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Rubik:wght@300..900&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Russo+One&display=swap">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />

    <style>
        #sidebar,
        #main-content {
            transition: all 0.3s ease-in-out;
        }

        .nav-text {
            transition: opacity 0.2s ease-in-out, width 0.3s ease-in-out;
            white-space: nowrap;
            overflow: hidden;
        }

        #sidebar.closed .nav-text {
            opacity: 0;
            width: 0;
        }

        #sidebar.closed .sidebar-header {
            justify-content: center;
        }

        #sidebar.closed .brand-text {
            display: none;
        }

        #sidebar.closed .nav-link,
        #sidebar.closed .logout-button {
            justify-content: center;
        }

        #sidebar.closed .nav-link img,
        #sidebar.closed .nav-link i,
        #sidebar.closed .logout-button i {
            margin-right: 0;
        }
        
    </style>
</head>

<body class="bg-gray-200">
    @php
        // Logika PHP tetap sama
        $userStatus = session('status');
        $isDosen = in_array($userStatus, ['DOSEN', 'TENDIK']);
        $config = [
            'themeColor' => $isDosen ? '#880e4f' : 'rgba(31,76,109,1)',
            'themeBgClass' => $isDosen ? 'bg-[#880e4f]' : 'bg-[rgba(31,76,109,1)]',
            'textColor' => 'text-white',
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
        <aside id="sidebar" class="w-64 bg-white p-4 shadow-md h-screen fixed top-0 left-0 flex flex-col">
            <div class="flex items-center justify-between mb-5 sidebar-header">
                <h1 class="text-lg font-bold font-rubik nav-text brand-text">
                    <span style="color: {{ $config['themeColor'] }}">Pemustaka</span> <span
                        class="text-black">Award</span>
                </h1>
                <button id="sidebar-toggle" class="p-2 rounded-md hover:bg-gray-200 focus:outline-none">
                    <i class="fa-solid fa-bars" style="color: {{ $config['themeColor'] }};"></i>
                </button>
            </div>

            <nav class="flex-grow">
                <ul>
                    @php
                        $menuItems = [
                            [
                                'route' => $config['routes']['leaderboard'],
                                'icon_active' => 'Leaderboard.png',
                                'icon_inactive' => 'BlackLeaderboard.png',
                                'label' => 'Leaderboard',
                            ],
                            [
                                'route' => $config['routes']['profile'],
                                'icon_active' => 'Profile.png',
                                'icon_inactive' => 'BlackProfile.png',
                                'label' => 'Profile',
                            ],
                            [
                                'route' => $config['routes']['kegiatan'],
                                'related_route' => $config['routes']['riwayatkegiatan'],
                                'icon_active' => 'Kegiatan.png',
                                'icon_inactive' => 'BlackKegiatan.png',
                                'label' => 'Kegiatan',
                            ],
                            [
                                'route' => $config['routes']['aksara'],
                                'related_route' => $config['routes']['formaksara'],
                                'icon_active' => 'Aksara.png',
                                'icon_inactive' => 'BlackAksara.png',
                                'label' => 'Aksara Dinamika',
                            ],
                        ];
                    @endphp

                    @foreach ($menuItems as $item)
                        @php
                            $isActive =
                                Request::is($item['route']) ||
                                (isset($item['related_route']) &&
                                    (Request::is($item['related_route']) ||
                                        Request::is($item['related_route'] . '/*')));
                        @endphp
                        <li class="mb-3">
                            <a href="{{ url($item['route']) }}"
                                class="flex items-center p-3 rounded-lg nav-link {{ $isActive ? $config['themeBgClass'] . ' ' . $config['textColor'] . ' shadow-md' : 'text-gray-700 hover:bg-gray-100' }}">
                                <img src="{{ asset('assets/images/' . ($isActive ? $item['icon_active'] : $item['icon_inactive'])) }}"
                                    alt="{{ $item['label'] }} Icon" class="w-6 h-6 mr-4 shrink-0">
                                <span class="font-semibold nav-text">{{ $item['label'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            <div class="mt-auto">
                <form action="{{ route('logout') }}" method="POST">
                    {{-- PERBAIKAN: Kode @csrf yang sebelumnya terhapus, kini sudah dikembalikan. --}}
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center p-3 rounded-lg text-red-500 hover:bg-red-100 logout-button">
                        <i class="fas fa-sign-out-alt w-6 h-6 mr-4 shrink-0"></i>
                        <span class="font-semibold nav-text">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <main id="main-content" class="flex-1 p-5 ml-64 overflow-y-auto h-screen">
            @yield('content')
        </main>
    </div>

    <script src="https://kit.fontawesome.com/a2411311d5.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script src="{{ asset('js/sidebar.js') }}" defer></script>
</body>

</html>
