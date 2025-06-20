@extends('layouts.app') {{-- Pastikan Anda memiliki layout bernama 'app' --}}

@section('content')
    <p>ID Civitas: {{ session('civitas')['id_civitas'] }}</p>
    <p>Status: {{ session('status') }}</p>
    <div class="max-w-4xl mx-auto pt-16">
        <div class="bg-white shadow-2xl p-8 rounded-2xl border border-gray-200">
            <h2 class="text-3xl font-extrabold text-gray-800 mb-6 text-center">Form Review Buku</h2>

            @if (!empty($data) && is_array($data))
                <?php
                $bookData = $data[0];
                ?>
                {{-- UBAH BAGIAN INI: Pastikan TIDAK ADA parameter di route() --}}
                <form action="{{ route('review.store') }}" method="POST">
                    @csrf
  
                    <div class="grid grid-cols-1 gap-6">                      
                        <input type="hidden" name="perbaikan" value="true"> {{-- Gunakan 'true' atau 1 agar lebih mudah dibaca sebagai boolean --}}
                        <div>
                            <label class="block text-lg font-medium text-gray-700">Kode Buku</label>
                            <input type="text" id="kodebuku" name="kodebuku"
                                class="w-full border border-gray-300 p-3 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed"
                                value="{{ $bookData['induk_buku'] ?? '' }}" readonly>
                        </div>
                        <div>
                            <label class="block text-lg font-medium text-gray-700">Judul</label>
                            <input type="text" id="judul" name="judul"
                                class="w-full border border-gray-300 p-3 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed"
                                value="{{ $bookData['judul'] ?? '' }}" readonly>
                        </div>

                        <div>
                            <label class="block text-lg font-medium text-gray-700">Pengarang</label>
                            <input type="text" id="pengarang" name="pengarang"
                                class="w-full border border-gray-300 p-3 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed"
                                value="{{ $bookData['pengarang_all'] ?? '' }}" readonly>
                        </div>

                        <div>
                            <label class="block text-lg font-medium text-gray-700">Review</label>
                            <textarea class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-500" id="review"
                                name="review" rows="4">{{ $bookData['review'] ?? '' }}</textarea>
                        </div>

                        <div>
                            <label class="block text-lg font-medium text-gray-700">Link Bukti Upload Review di
                                Sosmed</label>
                            <input type="text"
                                class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-500"
                                id="sosmed" name="sosmed" value="{{ $bookData['link_upload'] ?? '' }}">
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit"
                                class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-bold py-3 px-6 rounded-md text-lg shadow-lg hover:opacity-90 transition-all">
                                Kirim Review
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <p class="text-center text-red-500">Data buku tidak ditemukan atau ada masalah saat memuat data.</p>
            @endif
        </div>
    </div>
    <script>
        const idCivitas = "{{ session('civitas')['id_civitas'] }}";
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <div id="successModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3 text-center">
            <img src="/assets/images/approve.png" alt="Approve" class="w-30 h-20 mx-auto">
            <p class="mt-2 text-gray-700">Form berhasil dikirim.</p>
            <button onclick="closeSuccessModal()"
                class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                OK
            </button>
        </div>
    </div>

    <div id="failedModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3 text-center">
            <img src="/assets/images/failed.jpg" alt="failed" class="w-30 h-20 mx-auto">
            <p class="mt-2 text-gray-700">Form Gagal Dikirim.</p>
            <button onclick="closeFailedModal()" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                OK
            </button>
        </div>
    </div>

    {{-- <-- Script untuk menampilkan modal --> --}}
    <script>
        function closeSuccessModal() {
            document.getElementById('successModal').classList.add('hidden');
        }

        function closeFailedModal() {
            document.getElementById('failedModal').classList.add('hidden');
        }

        @if (session('success'))
            document.getElementById('successModal').classList.remove('hidden');
        @endif

        @if (session('failed'))
            document.getElementById('failedModal').classList.remove('hidden');
        @endif
    </script>
@endsection
