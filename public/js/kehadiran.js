document.addEventListener("DOMContentLoaded", function () {
    const apiUrl = `http://127.0.0.1:8000/api/hadir-kegiatan/kehadiran/${idCivitas}`;
    console.log("ID CIVITAS:", idCivitas); //DEBUG
    console.log("API URL:", apiUrl); //DEBUG

    fetch(apiUrl)
        .then((response) => response.json())
        .then((data) => {
            console.log("DATA API:", data); //DEBUG

            const tableBody = document.getElementById("mykehadirantable");
            const totalPoinEl = document.getElementById("totalpoin");
            tableBody.innerHTML = "";

            let totalPoin = 0;

            data.data.forEach((user) => {
                totalPoin += parseInt(user.bobot); // Hitung poin

                const row = document.createElement("tr");
                row.className = "bg-[rgba(31,76,109,1)]";
                row.innerHTML = `
                    <td class="p-3 text-white font-rubik text-center">${user.judul_kegiatan}</td>
                    <td class="p-3 text-white font-rubik text-center">${user.tgl_kegiatan}</td>
                    <td class="p-3 text-white font-rubik text-center">${user.jam_kegiatan}</td>
                    <td class="p-3 text-white font-rubik text-center">${user.nama_pemateri}</td>
                    <td class="p-3 text-white font-rubik text-center">${user.lokasi}</td>
                    <td class="p-3 text-white font-rubik text-center">${user.bobot}</td>
                    <td class="p-3 text-white font-rubik text-center">
                        <button class="bg-green-500 hover:bg-green-600 text-white font-bold py-1 px-2 rounded">Cetak</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });

            // Tampilkan dan simpan total poin
            totalPoinEl.textContent = totalPoin;
            localStorage.setItem("totalPoin", totalPoin); // 🔥 Simpan di localStorage
        })
        .catch((error) => {
            console.error("Gagal ambil data kehadiran:", error);
        });
});