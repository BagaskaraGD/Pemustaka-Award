  document.addEventListener("DOMContentLoaded", async function () {
        const apiBaseUrl = document.querySelector('meta[name="api-base-url"]').getAttribute('content');
        // Ganti dengan variabel dinamis jika perlu
        // Endpoint API
        const apiChallenge = `${apiBaseUrl}/rekap-poin/jumlah/aksara/${idCivitas}`;
        const apiKegiatan = `${apiBaseUrl}/rekap-poin/jumlah/kegiatan/${idCivitas}`;
        const apiKunjungan = `${apiBaseUrl}/rekap-poin/jumlah/kunjungan/${idCivitas}`;
        const apiPinjaman = `${apiBaseUrl}/rekap-poin/jumlah/pinjaman/${idCivitas}`;

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