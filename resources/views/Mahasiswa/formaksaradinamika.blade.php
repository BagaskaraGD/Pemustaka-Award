@extends('layouts.app')

@section('content')
    <p>ID Civitas: {{ session('civitas')['id_civitas'] }}</p>
    <p>Status: {{ session('status') }}</p>
    <div class="max-w-4xl mx-auto pt-16">
        <div class="bg-white shadow-2xl p-8 rounded-2xl border border-gray-200">
            <h2 class="text-3xl font-extrabold text-gray-800 mb-6 text-center">Form Review Buku</h2>
            <form action="{{ route('review.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-lg font-medium text-gray-700">Kode Buku</label>
                        <select id="kodebuku" name="kodebuku"
                            class="js-data-example-ajax w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-500"></select>
                    </div>

                    <div>
                        <label class="block text-lg font-medium text-gray-700">Judul</label>
                        <input type="text" id="judul" name="judul"
                            class="w-full border border-gray-300 p-3 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed"
                            readonly>
                    </div>

                    <div>
                        <label class="block text-lg font-medium text-gray-700">Pengarang</label>
                        <input type="text" id="pengarang" name="pengarang"
                            class="w-full border border-gray-300 p-3 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed"
                            readonly>
                    </div>

                    <div>
                        <label class="block text-lg font-medium text-gray-700">Review</label>
                        <textarea class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-500" id="review"
                            name="review" rows="4" required></textarea>
                    </div>

                    <div>
                        <label class="block text-lg font-medium text-gray-700">Yang Merekomendasikan</label>
                        <select id="rekomendasi" name="rekomendasi"
                            class="js-data-example-ajax w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-500"></select>
                    </div>

                    <div>
                        <label class="block text-lg font-medium text-gray-700">Link Bukti Upload Review di Sosmed</label>
                        <input type="text"
                            class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-500"
                            id="sosmed" name="sosmed">
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                            class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-bold py-3 px-6 rounded-md text-lg shadow-lg hover:opacity-90 transition-all">
                            Kirim Review
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        const idCivitas = "{{ session('civitas')['id_civitas'] }}";
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    
    <script>
        const routeBukuSearch = "{{ route('buku.search') }}";
    </script>
    <script src="{{ asset('js/caribuku.js') }}"></script>

    <script>
        const routeRekomendasiSearch = "{{ route('karyawan.search') }}";
    </script>
    <script src="{{ asset('js/carikaryawan.js') }}"></script>

    <div id="successModal"
        class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50
                                 transition-opacity duration-300 ease-out opacity-0 pointer-events-none">
        {{-- Konten modal dengan animasi transform & opacity --}}
        <div
            class="bg-white p-6 rounded-lg shadow-lg w-1/3 text-center
                    transform transition-all duration-300 ease-out scale-95 opacity-0">
            <img src="/assets/images/approve.png" alt="Approve" class="w-30 h-20 mx-auto">
            <p class="mt-2 text-gray-700">Form berhasil dikirim.</p>
            <button onclick="closeSuccessModal()"
                class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                OK
            </button>
        </div>
    </div>

    <div id="failedModal"
        class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 z-50
                                transition-opacity duration-300 ease-out opacity-0 pointer-events-none">
        {{-- Konten modal dengan animasi transform & opacity --}}
        <div
            class="bg-white p-6 rounded-lg shadow-lg w-1/3 text-center
                    transform transition-all duration-300 ease-out scale-95 opacity-0">
            <img src="/assets/images/failed.jpg" alt="failed" class="w-30 h-20 mx-auto">
            <p class="mt-2 text-gray-700">Form Gagal Dikirim.</p>
            <button onclick="closeFailedModal()" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                OK
            </button>
        </div>
    </div>

    {{-- Script untuk menampilkan modal dengan animasi Tailwind --}}
    <script>
        function openSuccessModal() {
            const modal = document.getElementById('successModal');
            const modalContent = modal.querySelector('.bg-white'); // Targetkan konten di dalam modal

            // 1. Tampilkan overlay modal (transisi opacity pada div utama modal)
            modal.classList.remove('opacity-0', 'pointer-events-none');

            // 2. Animasikan konten modal (scale dan opacity pada div konten)
            // Menggunakan requestAnimationFrame untuk memastikan browser siap untuk transisi
            requestAnimationFrame(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            });
        }

        function closeSuccessModal() {
            const modal = document.getElementById('successModal');
            const modalContent = modal.querySelector('.bg-white');

            // 1. Animasikan konten modal untuk menghilang
            modalContent.classList.add('scale-95', 'opacity-0');
            modalContent.classList.remove('scale-100', 'opacity-100');

            // 2. Setelah animasi konten selesai, sembunyikan overlay modal
            setTimeout(() => {
                modal.classList.add('opacity-0', 'pointer-events-none');
            }, 300); // Durasi harus cocok dengan `duration-300` pada kelas transisi
        }

        function openFailedModal() {
            const modal = document.getElementById('failedModal');
            const modalContent = modal.querySelector('.bg-white');

            modal.classList.remove('opacity-0', 'pointer-events-none');
            requestAnimationFrame(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            });
        }

        function closeFailedModal() {
            const modal = document.getElementById('failedModal');
            const modalContent = modal.querySelector('.bg-white');

            modalContent.classList.add('scale-95', 'opacity-0');
            modalContent.classList.remove('scale-100', 'opacity-100');
            setTimeout(() => {
                modal.classList.add('opacity-0', 'pointer-events-none');
            }, 300);
        }

        // Cek jika ada session 'success' atau 'failed', maka tampilkan modal yang sesuai
        @if (session('success'))
            openSuccessModal();
        @endif

        @if (session('failed'))
            openFailedModal();
        @endif
    </script>
@endsection
