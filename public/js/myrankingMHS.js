document.addEventListener("DOMContentLoaded", function () {
    const apiUrl = `http://127.0.0.1:8000/api/myrank/mhs/${idCivitas}`;

    fetch(apiUrl)
        .then((response) => response.json())
        .then((data) => {
            console.log("DATA API:", data); //DEBUG

            const tableBody = document.getElementById("myRankingTableBody");
            tableBody.innerHTML = "";

            const user = data.data;
            console.log("DATA data:", user)  // Akses user pertama dari array "count"

            if (user) {
                const row = document.createElement("tr");
                row.className = "bg-[rgba(31,76,109,1)]";

                row.innerHTML = `
                    <td class="p-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white text-[rgba(31,76,109,1)] font-medium border border-gray-300">
                            ${user.peringkat ?? "-"}
                        </span>
                    </td>
                    <td class="p-3">
                        <div class="w-6 h-6 bg-blue-500 rounded-full"></div>
                    </td>
                    <td class="p-3 text-white font-rubik font-bold">${
                        user.nama
                    }</td>
                    <td class="p-3 text-white font-rubik font-bold">${
                        user.nim
                    }</td>
                    <td class="p-3 text-white font-rubik font-bold">${
                        user.nim
                    }@dinamika.ac.id</td>
                    <td class="p-3 text-center font-russo text-white">
                        <div class="flex items-center justify-center space-x-2">
                            <img src="/assets/images/Poin.png" alt="Poin Icon" class="w-5 h-5">
                            <span>${user.total_rekap_poin}</span>
                        </div>
                    </td>
                `;
                tableBody.appendChild(row);
            } else {
                tableBody.innerHTML =
                    '<tr><td colspan="6" class="text-center text-gray-500 py-4">Data tidak ditemukan</td></tr>';
            }
        })
        .catch((error) => {
            console.error("Gagal ambil data myrank:", error);
        });

});
