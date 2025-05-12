document.addEventListener("DOMContentLoaded", function () {
    // Pertama, ambil data dari endpoint pertama
    const apiUrlGet = `http://127.0.0.1:8000/api/challenge-count/${idCivitas}`;

    fetch(apiUrlGet)
        .then((response) => response.json())
        .then((data) => {
            if (data.count !== undefined) {
                // Tampilkan count di elemen HTML
                document.getElementById("challenge-count").textContent =
                    data.count;
                console.log("Jumlah tantangan:", data.count); // DEBUG

                // Sekarang kirimkan data tersebut ke API kedua
                const apiUrlPut = `http://127.0.0.1:8000/api/rekap-poin/${idCivitas}/${data.count}`;

                fetch(apiUrlPut, {
                    method: "PUT", // Menggunakan PUT request
                    headers: {
                        "Content-Type": "application/json", // Pastikan content type JSON
                    },
                    body: JSON.stringify({ count: data.count }), // Mengirim data yang diterima ke API
                })
                    .then((response) => response.json())
                    .then((updatedData) => {
                        console.log("Data berhasil diperbarui:", updatedData);
                    })
                    .catch((error) => {
                        console.error("Error updating challenge count:", error);
                    });
            }
        })
        .catch((error) => {
            console.error("Error fetching challenge count:", error);
        });
});
