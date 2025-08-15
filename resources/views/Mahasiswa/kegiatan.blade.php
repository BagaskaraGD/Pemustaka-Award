@extends('layouts.app')

@section('content')
    <h2
        class="text-3xl md:text-4xl font-extrabold font-rubik mb-6 text-gray-800 border-b-2 border-blue-600 pb-2 inline-block">
        Kegiatan
    </h2>

    {{-- Container for "Klaim Poin" and "Search" Inputs --}}
    <div class="flex flex-col sm:flex-row items-stretch mb-8 gap-4">
        {{-- Form Tambah Kegiatan --}}
        <form action="{{ route('kehadiran.store') }}" method="POST"
            class="flex-grow-0 w-full sm:w-1/2 lg:w-1/3 flex items-center gap-2 px-3 py-2 bg-white rounded-xl shadow-lg border border-gray-100 transform transition-all duration-300 hover:shadow-xl hover:scale-[1.01]">
            @csrf
            <input type="text" name="kode" placeholder="Kode Kegiatan" {{-- Ganti placeholder agar lebih ringkas --}}
                class="flex-grow border border-gray-300 p-2 rounded-lg shadow-inner focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm font-rubik placeholder-gray-400 transition-all duration-200 ease-in-out focus:placeholder-opacity-0"
                {{-- Kurangi p-2.5 jadi p-2 dan text-base jadi text-sm --}} id="koderandom">
            <button type="submit"
                class="w-full sm:w-auto bg-[rgba(31,76,109,1)] text-white text-sm font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-blue-700 transition-all duration-200 ease-in-out flex items-center justify-center space-x-2 transform hover:scale-105 hover:shadow-lg">
                {{-- Kurangi py-2.5 jadi py-2, px-6 jadi px-4, text-base jadi text-sm --}}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" {{-- Kurangi w-5 h-5 jadi w-4 h-4 --}}
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                    </path>
                </svg>
                <span>Klaim</span> {{-- Persingkat teks tombol --}}
            </button>
        </form>

        {{-- Search Input --}}
        <div
            class="flex-grow-0 w-full sm:w-1/2 lg:w-1/3 flex items-center gap-3 px-3 py-2 bg-white rounded-xl shadow-lg border border-gray-100 transform transition-all duration-300 hover:shadow-xl hover:scale-[1.01]">
            {{-- Kurangi p-5 jadi px-3 py-2 --}}
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                {{-- Kurangi w-5 h-5 jadi w-4 h-4 --}} xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <input type="text" id="activity-search" placeholder="Cari kegiatan..." {{-- Persingkat placeholder --}}
                class="flex-grow border-none focus:ring-0 focus:outline-none text-sm font-rubik placeholder-gray-400 transition-all duration-200 ease-in-out focus:placeholder-opacity-0">
            {{-- Kurangi text-base jadi text-sm --}}
        </div>
    </div>

    {{-- Container untuk Daftar Kegiatan yang Dapat Di-toggle --}}
    <div id="activity-list-container" class="space-y-4">
        {{-- Data kegiatan akan diisi oleh JavaScript di sini --}}
        <div
            class="bg-white shadow-xl rounded-xl overflow-hidden p-6 text-center text-gray-500 italic border border-gray-200">
            Memuat data kegiatan...
        </div>
    </div>

    {{-- Total Poin --}}
    <div class="flex justify-end mt-6 font-rubik">
        <div class="bg-white border border-gray-200 px-6 py-3 rounded-xl shadow-md flex items-center space-x-3">
            <div class="text-lg font-bold text-gray-700">Total Poin:</div>
            <div class="text-3xl font-extrabold text-[rgba(31,76,109,1)]" id="totalpoin">0</div>
        </div>
    </div>

    {{-- Script untuk melewatkan ID Civitas ke JavaScript --}}
    <script>
        const idCivitas = "{{ session('civitas')['id_civitas'] }}";
    </script>
    {{-- Memanggil file JavaScript --}}
    <script src="{{ asset('js/kehadiran.js') }}"></script>

    {{-- Modal Notifikasi Error --}}
    <div id="errorModal"
        class="fixed inset-0 z-[999] flex items-center justify-center bg-black bg-opacity-60 p-4 transition-opacity duration-300 ease-out opacity-0 pointer-events-none hidden">
        <div
            class="bg-white p-6 rounded-lg shadow-xl w-full max-w-sm text-center transform transition-all duration-300 ease-out scale-95 opacity-0">
            <img src="{{ asset('assets/images/failed.jpg') }}" alt="Notifikasi Gagal" class="w-24 h-24 mx-auto mb-4">
            <h3 class="text-2xl font-bold mb-3 text-red-600">Gagal!</h3>
            <p id="errorMessage" class="text-gray-600 mb-5"></p>
            <button onclick="closeErrorModal()"
                class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-300">
                Tutup
            </button>
        </div>
    </div>

    <script>
        function openErrorModal(message) {
            document.getElementById('errorMessage').textContent = message;
            const modal = document.getElementById('errorModal');
            const modalContent = modal.querySelector('.bg-white');

            modal.classList.remove('opacity-0', 'pointer-events-none', 'hidden');
            requestAnimationFrame(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            });
        }

        function closeErrorModal() {
            const modal = document.getElementById('errorModal');
            const modalContent = modal.querySelector('.bg-white');

            modalContent.classList.add('scale-95', 'opacity-0');
            modalContent.classList.remove('scale-100', 'opacity-100');
            setTimeout(() => {
                modal.classList.add('opacity-0', 'pointer-events-none', 'hidden');
            }, 300);
        }

        @if (session('error_modal'))
            document.addEventListener('DOMContentLoaded', function() {
                openErrorModal("{{ session('error_modal') }}");
            });
        @endif

        @if (session('success'))
            // Anda bisa menambahkan modal sukses di sini jika mau
            // alert("{{ session('success') }}");
        @endif
    </script>
@endsection
