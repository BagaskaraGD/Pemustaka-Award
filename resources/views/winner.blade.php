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
            /* Hapus atau komentari baris ini */
            /* overflow: hidden; */
        }

        .card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Crown emoji mungkin tidak bisa diwarnai dengan CSS, tapi kita coba */
        .crown {
            font-size: 5rem;
            text-shadow: 0 0 15px gold;
            /* Tetap kuning emas untuk mahkota */
        }

        /* Custom styling for status-specific borders and text */
        /* Pastikan warna ini cocok dengan yang Anda definisikan di tailwind.config.js */
        .border-mahasiswa-primary {
            border-color: rgba(31, 76, 109, 1);
        }

        .bg-mahasiswa-primary {
            background-color: rgba(31, 76, 109, 1);
        }

        .border-dosen-primary {
            border-color: #880e4f;
        }

        .bg-dosen-primary {
            background-color: #880e4f;
        }

        /* Untuk memastikan teks di dalam kartu tetap putih pada background gelap card */
        .card h3,
        .card p,
        .card span {
            color: white;
            /* Atur teks di dalam kartu menjadi putih secara default */
        }
    </style>
</head>

<body class="min-h-screen flex flex-col items-center justify-center p-4">

    @php
        $winnerStatus = $winner['status_user'] ?? ''; // Ambil status pemenang dari data yang dilewatkan
        $isMahasiswa = $winnerStatus === 'MHS';

        // Tentukan kelas warna dinamis berdasarkan status pemenang
        $cardBorderColorClass = $isMahasiswa ? 'border-mahasiswa-primary' : 'border-dosen-primary';
        $totalPoinBgColorClass = $isMahasiswa ? 'bg-mahasiswa-primary' : 'bg-dosen-primary';
        // mainAccentColor akan digunakan untuk confetti
        $mainAccentColor = $isMahasiswa ? 'rgba(31,76,109,1)' : '#880e4f';
    @endphp

    <div class="text-center w-full max-w-2xl mx-auto">
        <h1 class="text-4xl md:text-5xl font-bold text-yellow-300">Selamat kepada Pemenang!</h1>
        @if ($periode && isset($periode['nama_periode']))
            <h2 class="text-2xl md:text-3xl mt-2">Pemustaka Award Periode {{ $periode['nama_periode'] }}</h2>
        @else
            <h2 class="text-2xl md:text-3xl mt-2">Pemustaka Award</h2>
        @endif
        <p class="mt-4 text-lg text-gray-300">Periode kompetisi telah berakhir. Nantikan periode selanjutnya!</p>
    </div>

    @if ($winner)
        {{-- Card dengan warna dinamis --}}
        <div class="card rounded-2xl shadow-lg p-8 mt-10 text-center w-full max-w-md {{ $cardBorderColorClass }}">
            <div class="crown mb-4">👑</div>

            {{-- Foto Pemenang dengan border dinamis --}}
            <img src="{{ $winner['foto'] ?? asset('assets/images/profile.png') }}" alt="Foto Pemenang"
                class="w-32 h-32 rounded-full mx-auto border-4 {{ $cardBorderColorClass }} object-cover">

            <h3 class="text-3xl font-bold mt-4">{{ $winner['nama'] ?? 'Nama Pemenang' }}</h3>
            <p class="text-gray-300 text-xl">{{ $winner['nomor_induk'] ?? 'NIM/NIK' }}</p>

            {{-- Bagian Poin dengan background dinamis --}}
            <div class="mt-6 {{ $totalPoinBgColorClass }} text-white rounded-lg px-6 py-3 inline-block">
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

    {{-- Script untuk Confetti --}}
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hanya aktifkan confetti jika ada pemenang yang ditampilkan
            @if ($winner)
                const mainColor = "{{ $mainAccentColor }}"; // Warna utama (biru/marun)
                const colors = [mainColor, '#ffffff', '#ffd700']; // Warna untuk confetti (warna utama, putih, emas)

                // Ledakan confetti awal
                confetti({
                    particleCount: 150,
                    spread: 90,
                    origin: {
                        y: 0.6
                    },
                    colors: colors
                });

                // Ledakan confetti tambahan dari samping (sedikit tertunda)
                setTimeout(() => {
                    confetti({
                        particleCount: 75,
                        angle: 60,
                        spread: 55,
                        origin: {
                            x: 0
                        },
                        colors: colors
                    });
                    confetti({
                        particleCount: 75,
                        angle: 120,
                        spread: 55,
                        origin: {
                            x: 1
                        },
                        colors: colors
                    });
                }, 300);

                // Ledakan terakhir yang lebih besar dan tahan lama (opsional)
                setTimeout(() => {
                    confetti({
                        particleCount: 200,
                        spread: 120,
                        ticks: 250, // Berapa lama confetti terlihat
                        gravity: 0.8,
                        decay: 0.92,
                        scalar: 1,
                        shapes: ['circle', 'square', 'star'], // Bentuk confetti
                        colors: colors
                    });
                }, 600);
            @endif
        });
    </script>
</body>

</html>
