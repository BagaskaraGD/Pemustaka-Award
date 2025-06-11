<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="api-base-url" content="{{ config('services.backend.base_url') }}">
    <title>@yield('title', 'Pemustaka Award')</title>

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Rubik:wght@300..900&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Russo+One&display=swap">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/preline@latest/dist/preline.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            window.HSStaticMethods.autoInit();
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.js"></script>
    </script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        rubik: ["Rubik", "sans-serif"],
                        russo: ["Russo One", "sans-serif"],
                    }
                }
            }
        }
    </script>
    {{-- Select2 --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
</head>

<body class="bg-gray-200">

    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white p-5 shadow-md h-screen fixed top-0 left-0">
            <h1 class="text-lg font-bold font-rubik">
                <span class="text-[#880e4f]">Pemustaka</span> <span class="text-black">Award</span>
            </h1>
            <nav class="mt-5">
                <ul>
                    <li class="mb-3">
                        <div
                            class="flex items-center p-3 rounded-lg {{ Request::is('leaderboard-dosen') ? 'bg-[#880e4f] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            <img src="{{ Request::is('leaderboard-dosen') ? asset('assets/images/Leaderboard.png') : asset('assets/images/BlackLeaderboard.png') }}"
                                alt="Leaderboard Icon" class="w-6 h-6 mr-4">
                            <a href="{{ url('/leaderboard-dosen') }}" class="font-semibold">Leaderboard</a>
                        </div>
                    </li>
                    <li class="mb-3">
                        <div
                            class="flex items-center p-3 rounded-lg {{ Request::is('profile-dosen') ? 'bg-[#880e4f] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            <img src="{{ Request::is('profile-dosen') ? asset('assets/images/Profile.png') : asset('assets/images/BlackProfile.png') }}"
                                alt="Profile Icon" class="w-6 h-6 mr-4">
                            <a href="{{ url('/profile-dosen') }}" class="font-semibold">Profile</a>
                        </div>
                    </li>
                    <li class="mb-3">
                        <div
                            class="flex items-center p-3 rounded-lg {{ Request::is('kegiatan-dosen') || Request::is('riwayatkegiatan-dosen') ? 'bg-[#880e4f] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            <img src="{{ Request::is('kegiatan-dosen') || Request::is('riwayatkegiatan-dosen') ? asset('assets/images/Kegiatan.png') : asset('assets/images/BlackKegiatan.png') }}"
                                alt="Kegiatan Icon" class="w-6 h-6 mr-4">
                            <a href="{{ url('kegiatan-dosen') }}" class="font-semibold">Kegiatan</a>
                        </div>
                    </li>
                    <li class="mb-3">
                        <div
                            class="flex items-center p-3 rounded-lg {{ Request::is('aksara-dosen') || Request::is('formaksaradinamika-dosen') || Request::is('formaksaradinamika-dosen/edit/*/*/*') ? 'bg-[#880e4f] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                            <img src="{{ Request::is('aksara-dosen') || Request::is('formaksaradinamika-dosen') || Request::is('formaksaradinamika-dosen/edit/*/*/*') ? asset('assets/images/Aksara.png') : asset('assets/images/BlackAksara.png') }}"
                                alt="Aksara Icon" class="w-6 h-6 mr-4">
                            <a href="{{ url('aksara-dosen') }}" class="font-semibold">Aksara Dinamika</a>
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

        <!-- Main Content -->
        <main class="flex-1 p-5 ml-64 overflow-y-auto h-screen">
            @yield('content')
        </main>
    </div>
    {{-- <script src="{{ asset('js/kegiatanrekap.js') }}"></script>
    <script src="{{ asset('js/challengerekap.js') }}"></script>
    <script src="{{ asset('js/kunjunganrekap.js') }}"></script>
    <script src="{{ asset('js/pinjamanrekap.js') }}"></script> --}}
    <script src="https://kit.fontawesome.com/a2411311d5.js" crossorigin="anonymous"></script>


</body>


</html>
