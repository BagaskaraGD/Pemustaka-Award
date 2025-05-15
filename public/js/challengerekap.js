document.addEventListener("DOMContentLoaded", function () {
    const apiUrlGet = `http://127.0.0.1:8000/api/challenge-count/${idCivitas}`;
    const apiUrlPoin = "http://127.0.0.1:8000/api/pembobotan/aksara-dinamika";


    fetch(apiUrlGet)
        .then((response) => response.json())
        .then((data) => {
            const count = Number(data.count);
            if (isNaN(count)) throw new Error("Jumlah aksara tidak valid");

            console.log("Jumlah Aksara:", count); // DEBUG

            // Ambil poin pembobotan
            return fetch(apiUrlPoin)
                .then((res) => res.json())
                .then((bobotData) => {
                    const poin = Number(bobotData.data?.[0]);
                    if (isNaN(poin))
                        throw new Error("Poin pembobotan tidak valid");

                    const totalPoin = count * poin;
                    console.log(
                        `Total Poin = ${count} x ${poin} = ${totalPoin}`
                    ); // DEBUG

                    const apiUrlPut = `http://127.0.0.1:8000/api/rekap-poin/aksara/${idCivitas}/${count}/${totalPoin}`;

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
