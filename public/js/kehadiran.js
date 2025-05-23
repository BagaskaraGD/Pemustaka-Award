document.addEventListener("DOMContentLoaded", function () {
    const apiUrl = `http://127.0.0.1:8000/api/hadir-kegiatan/kehadiran/${idCivitas}`;

    console.log("ID CIVITAS:", idCivitas); // DEBUG
    console.log("API URL:", apiUrl); // DEBUG

    fetch(apiUrl)
        .then((response) => {
            if (!response.ok) {
                return response.json().then((err) => {
                    throw new Error(
                        `HTTP error! status: ${
                            response.status
                        }, message: ${JSON.stringify(err)}`
                    );
                });
            }
            return response.json();
        })
        .then((data) => {
            console.log("DATA API:", data); // DEBUG

            const tableBody = document.getElementById("mykehadirantable");
            const totalPoinEl = document.getElementById("totalpoin");
            tableBody.innerHTML = ""; // Clear table before filling

            let totalPoin = 0;

            if (data.data && Array.isArray(data.data) && data.data.length > 0) {
                data.data.forEach((kegiatan, index) => {
                    const bobotPoin = parseInt(kegiatan.bobot) || 0;
                    totalPoin += bobotPoin;

                    const row = document.createElement("tr");
                    // Subtle hover effect: slight lift and shadow
                    row.className = `transition-all duration-300 ease-in-out hover:shadow-md hover:scale-[1.01] ${
                        index % 2 === 0 ? "bg-white" : "bg-gray-50"
                    }`;

                    row.innerHTML = `
                        <td class="p-3 text-left text-sm text-gray-700">${kegiatan.judul_kegiatan}</td>
                        <td class="p-3 text-left text-sm text-gray-600">${kegiatan.tgl_kegiatan}</td>
                        <td class="p-3 text-left text-sm text-gray-600">${kegiatan.jam_kegiatan}</td>
                        <td class="p-3 text-left text-sm text-gray-600">${kegiatan.nama_pemateri}</td>
                        <td class="p-3 text-left text-sm text-gray-600">${kegiatan.lokasi}</td>
                        <td class="p-3 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-800 text-xs font-semibold 
                                transition-all duration-200 ease-in-out transform hover:scale-105 hover:bg-green-200 cursor-pointer">
                                ${bobotPoin} Poin
                            </span>
                        </td>
                        <td class="p-3 text-center">
                            <button class="bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold py-1.5 px-3 rounded-md shadow-sm 
                                transition-all duration-200 ease-in-out transform hover:scale-105 hover:shadow-lg">
                                <svg class="w-4 h-4 inline-block align-middle mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4"></path></svg>
                                Cetak
                            </button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            } else {
                const noDataRow = document.createElement("tr");
                noDataRow.innerHTML = `
                    <td colspan="7" class="p-4 text-center text-gray-500 italic bg-gray-50 rounded-b-xl">
                        Belum ada riwayat kegiatan. Mulai kumpulkan poin Anda!
                    </td>
                `;
                tableBody.appendChild(noDataRow);
            }

            totalPoinEl.textContent = totalPoin;
        })
        .catch((error) => {
            console.error("Gagal mengambil data kehadiran:", error);
            const tableBody = document.getElementById("mykehadirantable");
            tableBody.innerHTML = `
                <tr>
                    <td colspan="7" class="p-4 text-center text-red-600 font-semibold italic bg-red-50 rounded-b-xl">
                        Gagal memuat data kegiatan. Silakan coba lagi nanti.
                    </td>
                </tr>
            `;
            document.getElementById("totalpoin").textContent = "Error";
        });
});
