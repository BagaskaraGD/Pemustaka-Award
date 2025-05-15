  document.addEventListener("DOMContentLoaded", async function () {
        // Ganti dengan variabel dinamis jika perlu
        // Endpoint API
        const apiChallenge = `http://127.0.0.1:8000/api/rekap-poin/jumlah/aksara/${idCivitas}`;
        const apiKegiatan = `http://127.0.0.1:8000/api/rekap-poin/jumlah/kegiatan/${idCivitas}`;

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

        } catch (error) {
            console.error("Gagal mengambil data:", error);
        }
    });