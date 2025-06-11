document.addEventListener("DOMContentLoaded", function () {
    const apiBaseUrl = document.querySelector('meta[name="api-base-url"]').getAttribute('content');
    // Pastikan idCivitas dan fotoProfilSession sudah didefinisikan di Blade
    if (typeof idCivitas === "undefined") {
        console.error("idCivitas is not defined!");
        return;
    }
    if (typeof fotoProfilSession === "undefined") {
        console.error("fotoProfilSession is not defined!");
        // Anda bisa set default di sini juga jika perlu, tapi lebih baik dari Blade
    }

    const apiUrl = `${apiBaseUrl}/myrank/dosen/${idCivitas}`;

    fetch(apiUrl)
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then((data) => {
            console.log("DATA API My Ranking:", data); // DEBUG

            const tableBody = document.getElementById("myRankingTableBody");
            if (!tableBody) {
                console.error(
                    "Element with ID 'myRankingTableBody' not found."
                );
                return;
            }
            tableBody.innerHTML = ""; // Kosongkan isi sebelumnya

            const user = data.data;

            if (user) {
                // Gunakan fotoProfilSession yang sudah disiapkan di Blade
                const userRankingPhoto =
                    fotoProfilSession || "/assets/images/profile.png"; // Fallback jika fotoProfilSession undefined

                const row = document.createElement("tr");
                // Styling baris My Ranking
                row.className = "bg-[#880e4f]"; // Background biru gelap seperti screenshot

                row.innerHTML = `
                    <td class="p-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white text-[#880e4f] font-medium border border-gray-300">
                            ${user.peringkat ?? "-"}
                        </span>
                    </td>
                    <td class="p-3">
                        <img src="${userRankingPhoto}" alt="My Profile" class="w-6 h-6 object-cover rounded-full">
                    </td>
                    <td class="p-3 text-white font-rubik font-bold">${
                        user.nama ?? "-"
                    }</td>
                    <td class="p-3 text-white font-rubik font-bold">${
                        user.nim ?? "-"
                    }</td>
                    <td class="p-3 text-white font-rubik font-bold">${
                        user.status ?? "-"
                    }</td>
                    <td class="p-3 text-center font-russo text-white">
                        <div class="flex items-center justify-center space-x-2">
                            <img src="/assets/images/Poin.png" alt="Poin Icon" class="w-5 h-5">
                            <span>${user.total_rekap_poin ?? 0}</span>
                        </div>
                    </td>
                `;
                tableBody.appendChild(row);
            } else {
                tableBody.innerHTML =
                    '<tr><td colspan="6" class="text-center text-gray-500 py-4">Data peringkat Anda tidak ditemukan</td></tr>';
            }
        })
        .catch((error) => {
            console.error("Gagal ambil data myrank:", error);
            const tableBody = document.getElementById("myRankingTableBody");
            if (tableBody) {
                tableBody.innerHTML =
                    '<tr><td colspan="6" class="text-center text-red-500 py-4">Gagal memuat data peringkat Anda</td></tr>';
            }
        });
});
