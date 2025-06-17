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
        /* Base transition */
        #sidebar,
        #main-content {
            transition: all 0.3s ease-in-out;
        }

        /* Desktop Styles (768px and up) */
        @media (min-width: 768px) {
            #sidebar {
                width: 16rem;
                /* 256px */
                height: 100vh;
                position: fixed;
                top: 0;
                left: 0;
                display: flex;
                flex-direction: column;
                background-color: white;
                box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            }

            #sidebar.closed {
                width: 5rem;
                /* 80px */
            }

            .nav-text {
                transition: opacity 0.2s ease, max-width 0.3s ease;
                white-space: nowrap;
                overflow: hidden;
                max-width: 150px;
            }

            #sidebar.closed .nav-text,
            #sidebar.closed .brand-text {
                opacity: 0;
                max-width: 0;
            }

            #sidebar.closed .sidebar-header,
            #sidebar.closed .nav-link,
            #sidebar.closed .logout-button-desktop {
                justify-content: center;
            }

            #sidebar.closed .nav-link img,
            #sidebar.closed .nav-link i,
            #sidebar.closed .logout-button-desktop i {
                margin-right: 0;
            }

            #main-content {
                margin-left: 16rem;
                /* 256px */
            }

            #main-content.sidebar-closed {
                margin-left: 5rem;
                /* 80px */
            }

            .logout-button-mobile {
                display: none;
                /* Sembunyikan tombol logout mobile di desktop */
            }
        }

        /* Mobile Styles (less than 768px) */
        @media (max-width: 767px) {
            body {
                padding-bottom: 60px;
                /* Space for bottom nav */
            }

            #sidebar {
                width: 100%;
                height: 60px;
                position: fixed;
                bottom: 0;
                left: 0;
                top: auto;
                flex-direction: row;
                justify-content: space-around;
                z-index: 50;
                background-color: white;
                border-top: 1px solid #e5e7eb;
                box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            }

            #sidebar .sidebar-header,
            #sidebar .logout-button-desktop {
                display: none;
                /* Sembunyikan header dan logout desktop di mobile */
            }

            #sidebar nav {
                width: 100%;
            }

            #sidebar nav ul {
                display: flex;
                justify-content: space-around;
                width: 100%;
                height: 100%;
            }

            #sidebar nav ul li {
                flex: 1;
                margin-bottom: 0;
            }

            #sidebar .nav-link {
                flex-direction: column;
                justify-content: center;
                align-items: center;
                height: 100%;
                padding: 0.25rem 0;
                border-radius: 0;
            }

            #sidebar .nav-link img,
            #sidebar .nav-link i {
                margin-right: 0;
                margin-bottom: 2px;
                width: 1.25rem;
                /* 20px */
                height: 1.25rem;
                /* 20px */
            }

            #sidebar .nav-text {
                font-size: 0.65rem;
                line-height: 1;
            }

            .logout-button-mobile {
                color: #ef4444;
                /* Warna merah untuk logout */
            }

            .logout-button-mobile .fa-sign-out-alt {
                color: #ef4444 !important;
            }

            #main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body class="bg-gray-100">
    @php
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
        <aside id="sidebar">
            <div class="sidebar-header flex items-center justify-between mb-5 p-4">
                <h1 class="text-lg font-bold font-rubik brand-text">
                    <span style="color: {{ $config['themeColor'] }}">Pemustaka</span><span
                        class="text-black">Award</span>
                </h1>
                <button id="sidebar-toggle" class="p-3 rounded-md hover:bg-gray-200 focus:outline-none">
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
                                'label' => 'Aksara',
                            ],
                        ];
                    @endphp

                    @foreach ($menuItems as $item)
                        @php
                            $isActive =
                                Request::is(ltrim($item['route'], '/')) ||
                                (isset($item['related_route']) &&
                                    (Request::is(ltrim($item['related_route'], '/')) ||
                                        Request::is(ltrim($item['related_route'], '/') . '/*')));
                        @endphp
                        <li class="mb-2">
                            <a href="{{ url($item['route']) }}"
                                class="nav-link flex items-center p-3 rounded-lg {{ $isActive ? $config['themeBgClass'] . ' ' . $config['textColor'] . ' shadow-md' : 'text-gray-700 hover:bg-gray-100' }}">
                                <img src="{{ asset('assets/images/' . ($isActive ? $item['icon_active'] : $item['icon_inactive'])) }}"
                                    alt="{{ $item['label'] }} Icon" class="w-6 h-6 mr-4 shrink-0">
                                <span class="font-semibold nav-text">{{ $item['label'] }}</span>
                            </a>
                        </li>
                    @endforeach

                    {{-- PERBAIKAN: Tombol Logout untuk Mobile --}}
                    <li class="logout-button-mobile">
                        <form action="{{ route('logout') }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit"
                                class="nav-link flex items-center p-3 rounded-lg text-red-500 hover:bg-red-100 w-full">
                                <i class="fas fa-sign-out-alt w-6 h-6 mr-4 shrink-0"></i>
                                <span class="font-semibold nav-text">Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>

            {{-- PERBAIKAN: Tombol Logout hanya untuk Desktop --}}
            <div class="mt-auto logout-button-desktop">
                <form action="{{ route('logout') }}" method="POST" class="p-4">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center p-3 rounded-lg text-red-500 hover:bg-red-100">
                        <i class="fas fa-sign-out-alt w-6 h-6 mr-4 shrink-0"></i>
                        <span class="font-semibold nav-text">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <main id="main-content" class="flex-1 p-5 overflow-y-auto">
            @yield('content')
        </main>
    </div>

    <script src="https://kit.fontawesome.com/a2411311d5.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sidebar = document.getElementById("sidebar");
            const mainContent = document.getElementById("main-content");
            const toggleButton = document.getElementById("sidebar-toggle");

            if (!sidebar || !mainContent || !toggleButton) return;

            const applySidebarState = (state) => {
                if (window.innerWidth < 768) return;
                if (state === "closed") {
                    sidebar.classList.add("closed");
                    mainContent.classList.add("sidebar-closed");
                } else {
                    sidebar.classList.remove("closed");
                    mainContent.classList.remove("sidebar-closed");
                }
            };

            const savedState = localStorage.getItem("sidebarState");
            applySidebarState(savedState || "open");

            toggleButton.addEventListener("click", function() {
                const isClosed = sidebar.classList.contains("closed");
                const newState = isClosed ? "open" : "closed";
                applySidebarState(newState);
                localStorage.setItem("sidebarState", newState);
            });

            const handleResize = () => {
                if (window.innerWidth >= 768) {
                    mainContent.style.paddingBottom = '0';
                    const currentState = localStorage.getItem("sidebarState") || "open";
                    applySidebarState(currentState);
                } else {
                    mainContent.style.paddingBottom = '80px';
                    sidebar.classList.remove("closed");
                    mainContent.classList.remove("sidebar-closed");
                }
            };

            window.addEventListener('resize', handleResize);
            handleResize(); // Initial check
        });
    </script>
</body>

</html>
