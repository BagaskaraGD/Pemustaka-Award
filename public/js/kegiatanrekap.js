document.addEventListener("DOMContentLoaded", function () {
    const apiBaseUrl = document.querySelector('meta[name="api-base-url"]').getAttribute('content');
    const apiUrlGet = `${apiBaseUrl}/kegiatan-count/${idCivitas}`;

    fetch(apiUrlGet)
        .then((response) => response.json())
        .then((data) => {
            if (data.count !== undefined) {
                // const totalPoin = localStorage.getItem("totalPoinKegiatan") || 0; 
                // document.getElementById("kegiatan-count").textContent =
                    data.count;
                console.log("Jumlah kegiatan:", data.count); // DEBUG
                const apiUrlPut = `${apiBaseUrl}/rekap-poin/kegiatan/${idCivitas}/${data.count}/${totalPoin}`;

                fetch(apiUrlPut, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        count: data.count,
                        total_poin: totalPoin, // boleh juga dikirim di body kalau API perlu
                    }),
                })
                    .then((response) => response.json())
                    .then((updatedData) => {
                        console.log("Data berhasil diperbarui:", updatedData);
                    })
                    .catch((error) => {
                        console.error("Error updating kegiatan count:", error);
                    });
            }
        })
        .catch((error) => {
            console.error("Error fetching kegiatan count:", error);
        });
});
