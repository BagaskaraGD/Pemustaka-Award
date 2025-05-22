document.addEventListener("DOMContentLoaded", function () {
     // Pastikan idCivitas tersedia di sini
    const apiUrlGet = `http://127.0.0.1:8000/api/kunjungan-count/${idCivitas}`;
    const apiUrlPoin = "http://127.0.0.1:8000/api/range-kunjungan/kunjungan";

    fetch(apiUrlGet)
        .then((response) => response.json())
        .then((data) => {
            const count = Number(data.count);
            if (isNaN(count)) throw new Error("Jumlah kunjungan tidak valid");

            console.log("Jumlah kunjungan:", count); // DEBUG

            // Ambil data range poin pembobotan
            return fetch(apiUrlPoin)
                .then((res) => res.json())
                .then((bobotData) => {
                    const ranges = bobotData.data; // Mengambil array data range
                    if (!Array.isArray(ranges) || ranges.length === 0) {
                        throw new Error(
                            "Data range poin tidak ditemukan atau tidak valid"
                        );
                    }

                    let totalPoin = 0;
                    // Iterasi melalui setiap range untuk menemukan yang sesuai
                    for (const range of ranges) {
                        const rangeAwal = Number(range.range_awal);
                        const rangeAkhir = Number(range.range_akhir);
                        const bobot = Number(range.bobot);

                        if (
                            isNaN(rangeAwal) ||
                            isNaN(rangeAkhir) ||
                            isNaN(bobot)
                        ) {
                            console.warn(
                                "Salah satu nilai range poin tidak valid, melewati range ini:",
                                range
                            );
                            continue; // Lewati jika ada data yang tidak valid
                        }

                        // Periksa apakah 'count' berada dalam range saat ini
                        if (
                            count >= rangeAwal &&
                            (count <= rangeAkhir || rangeAkhir === 0)
                        ) {
                            // rangeAkhir 0 bisa berarti 'tidak terbatas'
                            totalPoin = bobot;
                            break; // Hentikan iterasi setelah range yang cocok ditemukan
                        }
                    }

                    console.log(
                        `Total Poin untuk ${count} kunjungan: ${totalPoin}`
                    ); // DEBUG

                    const apiUrlPut = `http://127.0.0.1:8000/api/rekap-poin/kunjungan/${idCivitas}/${count}/${totalPoin}`;

                    return fetch(apiUrlPut, {
                        method: "PUT",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({
                            count: count,
                            total_poin: totalPoin,
                        }),
                    });
                });
        })
        .then((res) => res.json())
        .then((updatedData) => {
            console.log("Data berhasil diperbarui:", updatedData);
        })
        .catch((error) => {
            console.error("Terjadi kesalahan:", error.message);
        });
});
