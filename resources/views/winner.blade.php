<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemenang Pemustaka Award</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(-45deg, #232526, #414345);
            color: white;
        }

        .card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .crown {
            font-size: 5rem;
            text-shadow: 0 0 15px gold;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col items-center justify-center p-4">

    <div class="text-center w-full max-w-2xl mx-auto">
        <h1 class="text-4xl md:text-5xl font-bold text-yellow-300">Selamat kepada Pemenang!</h1>
        @if ($periode && isset($periode['nama_periode']))
            <h2 class="text-2xl md:text-3xl mt-2">Pemustaka Award Periode {{ $periode['nama_periode'] }}</h2>
        @else
            <h2 class="text-2xl md:text-3xl mt-2">Pemustaka Award</h2>
        @endif
        <p class="mt-4 text-lg text-gray-300">Periode kompetisi telah berakhir. Nantikan periode selanjutnya!</p>
    </div>

    {{-- Logika untuk menentukan foto sudah dihapus dari sini --}}

    @if ($winner)
        <div class="card rounded-2xl shadow-lg p-8 mt-10 text-center w-full max-w-md">
            <div class="crown mb-4">👑</div>
            
            {{-- Baris ini sudah benar, ia akan menggunakan URL foto yang diberikan oleh Controller --}}
            <img src="{{ $winner['foto'] ?? asset('assets/images/profile.png') }}" alt="Foto Pemenang"
                class="w-32 h-32 rounded-full mx-auto border-4 border-yellow-400 object-cover">

            <h3 class="text-3xl font-bold mt-4">{{ $winner['nama'] ?? 'Nama Pemenang' }}</h3>
            <p class="text-gray-300 text-xl">{{ $winner['nomor_induk'] ?? 'NIM/NIK' }}</p>
            <div class="mt-6 bg-yellow-500 text-gray-900 rounded-lg px-6 py-3 inline-block">
                <span class="text-2xl font-bold">{{ $winner['total_poin'] ?? '0' }}</span>
                <span class="text-lg">Poin</span>
            </div>
        </div>
    @else
        <div class="card rounded-2xl shadow-lg p-8 mt-10 text-center w-full max-w-md">
            <p class="text-xl">Tidak dapat memuat data pemenang untuk periode ini.</p>
        </div>
    @endif

    <div class="mt-10">
        <a href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg transition duration-300">
            Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

</body>
</html>