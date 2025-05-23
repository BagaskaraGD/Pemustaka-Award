  document.addEventListener("DOMContentLoaded", async function () {
        // Ganti dengan variabel dinamis jika perlu
        // Endpoint API
        const apiChallenge = `http://127.0.0.1:8000/api/rekap-poin/jumlah/aksara/${idCivitas}`;
        const apiKegiatan = `http://127.0.0.1:8000/api/rekap-poin/jumlah/kegiatan/${idCivitas}`;
        const apiKunjungan = `http://127.0.0.1:8000/api/rekap-poin/jumlah/kunjungan/${idCivitas}`;
        const apiPinjaman = `http://127.0.0.1:8000/api/rekap-poin/jumlah/pinjaman/${idCivitas}`;

        try {
            // Ambil jumlah challenge (aksara)
            const resChallenge = await fetch(apiChallenge);
            const dataChallenge = await resChallenge.json();
            const challengeCount = dataChallenge.jumlah_aksara_dinamika ?? 0;

            // Tampilkan ke HTML
            document.getElementById("challenge-count").textContent = challengeCount;

            // Ambil jumlah kegiatan
            const resKegiatan = await fetch(apiKegiatan);
            const dataKegiatan = await resKegiatan.json();
            const kegiatanCount = dataKegiatan.jumlah_kegiatan ?? 0;

            // Tampilkan ke HTML
            document.getElementById("kegiatan-count").textContent = kegiatanCount;

            // Ambil jumlah Pinjaman
            const resPinjaman = await fetch(apiPinjaman);
            const dataPinjaman = await resPinjaman.json();
            const PinjamanCount = dataPinjaman.jumlah_pinjaman ?? 0;

            // Tampilkan ke HTML
            document.getElementById("pinjaman-count").textContent = PinjamanCount;

            // Ambil jumlah Kunjungan
            const resKunjungan = await fetch(apiKunjungan);
            const dataKunjungan = await resKunjungan.json();
            const KunjunganCount = dataKunjungan.jumlah_kunjungan ?? 0;

            // Tampilkan ke HTML
            document.getElementById("kunjungan-count").textContent = KunjunganCount;

        } catch (error) {
            console.error("Gagal mengambil data:", error);
        }
    });