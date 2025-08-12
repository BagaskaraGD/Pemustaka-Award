document.addEventListener("DOMContentLoaded", function () {
    const apiBaseUrl = document
        .querySelector('meta[name="api-base-url"]')
        .getAttribute("content");
    const apiUrl = `${apiBaseUrl}/hadir-kegiatan/kehadiran/${idCivitas}`;
    const activityListContainer = document.getElementById(
        "activity-list-container"
    );
    const totalPoinEl = document.getElementById("totalpoin");
    const searchInput = document.getElementById("activity-search");
    const paginationControlsContainer = document.createElement("div");
    paginationControlsContainer.className =
        "flex justify-center items-center space-x-2 mt-6";

    let allActivities = [];
    let currentPage = 1;
    const itemsPerPage = 5;

    function displayMessage(message, type = "info") {
        let icon = "";
        let textColor = "text-gray-500";
        let borderColor = "border-gray-200";
        let animateClass = "";

        if (type === "loading") {
            icon =
                '<svg class="inline-block w-6 h-6 mr-2 text-blue-500 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 005.78 20.264M4.015 11H3a2 2 0 01-2-2V7a2 2 0 012-2h1M4 4l.582-.582m15.356 2l-.707.707M4.968 4.968L4.26 4.26M18.364 5.636L19.07 4.93M5.636 18.364L4.93 19.07"></path></svg>';
            textColor = "text-blue-600";
            borderColor = "border-blue-200";
            animateClass = "animate-pulse";
        } else if (type === "error") {
            icon =
                '<svg class="inline-block w-6 h-6 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            textColor = "text-red-600";
            borderColor = "border-red-200";
        } else if (type === "no-results") {
            icon =
                '<svg class="inline-block w-6 h-6 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            textColor = "text-yellow-700";
            borderColor = "border-yellow-200";
        }

        activityListContainer.innerHTML = `
            <div class="bg-white shadow-lg rounded-xl overflow-hidden p-6 text-center italic border ${borderColor} ${animateClass}">
                ${icon}
                <span class="${textColor} font-semibold">${message}</span>
            </div>
        `;
        paginationControlsContainer.innerHTML = "";
    }

    displayMessage("Memuat data kegiatan...", "loading");

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
            if (data.data && Array.isArray(data.data) && data.data.length > 0) {
                const grouped = {};
                data.data.forEach((kegiatan) => {
                    if (!grouped[kegiatan.id_kegiatan]) {
                        grouped[kegiatan.id_kegiatan] = {
                            id_kegiatan: kegiatan.id_kegiatan,
                            judul_kegiatan: kegiatan.judul_kegiatan,
                            lokasi: kegiatan.lokasi,
                            sesi: [],
                        };
                    }
                    grouped[kegiatan.id_kegiatan].sesi.push({
                        id_jadwal: kegiatan.id_jadwal, // Simpan id_jadwal jika diperlukan untuk cetak
                        tgl_kegiatan: kegiatan.tgl_kegiatan,
                        jam_kegiatan: kegiatan.jam_kegiatan,
                        nama_pemateri: kegiatan.nama_pemateri,
                        bobot: parseInt(kegiatan.bobot) || 0,
                    });
                });
                allActivities = Object.values(grouped);
                currentPage = 1;
                renderActivities(allActivities);
                activityListContainer.parentNode.insertBefore(
                    paginationControlsContainer,
                    activityListContainer.nextSibling
                );
            } else {
                displayMessage(
                    "Belum ada riwayat kegiatan. Mulai kumpulkan poin Anda!",
                    "info"
                );
                totalPoinEl.textContent = "0";
                paginationControlsContainer.innerHTML = "";
            }
        })
        .catch((error) => {
            console.error("Gagal mengambil data kehadiran:", error);
            displayMessage(
                "Gagal memuat data kegiatan. Silakan coba lagi nanti.",
                "error"
            );
            totalPoinEl.textContent = "Error";
            paginationControlsContainer.innerHTML = "";
        });

    function renderActivities(activitiesToRender) {
        activityListContainer.innerHTML = "";
        let currentTotalPoin = 0;

        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const paginatedActivities = activitiesToRender.slice(
            startIndex,
            endIndex
        );

        if (paginatedActivities.length === 0 && activitiesToRender.length > 0) {
            displayMessage("Tidak ada kegiatan di halaman ini.", "no-results");
        } else if (activitiesToRender.length === 0) {
            // Ini akan ditangani oleh blok displayMessage di bagian search atau fetch awal
        } else {
            paginatedActivities.forEach((activity, index) => {
                const activityCard = document.createElement("div");
                activityCard.className = `bg-white rounded-xl shadow-xl border border-gray-200 overflow-hidden group transform transition-all duration-300 hover:shadow-2xl hover:scale-[1.01]`;

                const activityPoin = activity.sesi.reduce(
                    (acc, sesi) => acc + sesi.bobot,
                    0
                );

                const header = document.createElement("div");
                header.className = `p-4 flex items-center justify-between cursor-pointer bg-white hover:bg-gray-50 transition-colors duration-200`;
                header.setAttribute("data-activity-id", activity.id_kegiatan);
                // Perbarui header untuk menyertakan tombol cetak kegiatan
                header.innerHTML = `
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-500 mr-3 transform transition-transform duration-300 chevron-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        <div>
                            <h3 class="font-bold text-lg text-gray-800">${activity.judul_kegiatan}</h3>
                            <p class="text-sm text-gray-600">Lokasi: ${activity.lokasi}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-semibold text-gray-500">${activity.sesi.length} Sesi</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-semibold">
                            ${activityPoin} Poin Total
                        </span>
                        <button class="bg-green-500 hover:bg-green-600 text-white text-xs font-semibold py-1.5 px-3 rounded-md shadow-sm transition-all duration-200 ease-in-out transform hover:scale-105 hover:shadow-lg print-activity-certificate-button" data-kegiatan-id="${activity.id_kegiatan}">
                            <svg class="w-4 h-4 inline-block align-middle mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4"></path></svg>
                            Cetak Sertifikat
                        </button>
                    </div>
                `;
                activityCard.appendChild(header);

                const sessionDetails = document.createElement("div");
                sessionDetails.className = `overflow-hidden max-h-0 transition-all duration-500 ease-in-out`;
                sessionDetails.setAttribute(
                    "data-session-content-id",
                    activity.id_kegiatan
                );

                const sessionTable = document.createElement("table");
                sessionTable.className = `min-w-full text-gray-800`;
                // Hapus kolom "Aksi" (tombol cetak per sesi) dari tabel sesi
                sessionTable.innerHTML = `
                    <thead class="bg-gray-100 border-b border-gray-200">
                        <tr>
                            <th class="p-3 text-left text-xs font-semibold font-rubik text-gray-700 uppercase tracking-wider pl-10">Sesi Detail</th>
                            <th class="p-3 text-left text-xs font-semibold font-rubik text-gray-700 uppercase tracking-wider">Tanggal</th>
                            <th class="p-3 text-left text-xs font-semibold font-rubik text-gray-700 uppercase tracking-wider">Jam</th>
                            <th class="p-3 text-left text-xs font-semibold font-rubik text-gray-700 uppercase tracking-wider">Pemateri</th>
                            <th class="p-3 text-center text-xs font-semibold font-rubik text-gray-700 uppercase tracking-wider">Poin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                    </tbody>
                `;
                const sessionTableBody = sessionTable.querySelector("tbody");

                activity.sesi.forEach((sesi, sesiIndex) => {
                    const sessionRow = document.createElement("tr");
                    sessionRow.className = `${
                        sesiIndex % 2 === 0 ? "bg-white" : "bg-gray-50"
                    }`;
                    // Hapus td untuk tombol cetak per sesi
                    const tglKegiatanFormatted = new Date(
                        sesi.tgl_kegiatan
                    ).toLocaleDateString("id-ID", {
                        day: "2-digit",
                        month: "short", // atau 'long' jika ingin nama bulan lengkap
                        year: "numeric",
                    });
                    const jamKegiatanFormatted = sesi.jam_kegiatan; // Asumsi format ini sudah OK, jika tidak, perlu parsing lebih lanjut

                    sessionRow.innerHTML = `
                        <td class="p-3 text-left text-sm text-gray-700 pl-10 border-l-4 border-blue-200">
                            Sesi ${sesiIndex + 1}
                        </td>
                        <td class="p-3 text-left text-sm text-gray-600">
                            <div class="flex items-center space-x-2">
                                <i class="far fa-calendar-alt text-blue-500"></i>
                                <span>${tglKegiatanFormatted}</span>
                            </div>
                        </td>
                        <td class="p-3 text-left text-sm text-gray-600">
                            <span class="inline-flex items-center px-2 py-1 rounded-md bg-purple-100 text-purple-800 text-xs font-semibold">
                                <i class="far fa-clock text-purple-500 mr-1"></i> ${jamKegiatanFormatted}
                            </span>
                        </td>
                        <td class="p-3 text-left text-sm text-gray-600">${
                            sesi.nama_pemateri
                        }</td>
                        <td class="p-3 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-800 text-xs font-semibold">
                                ${sesi.bobot} Poin
                            </span>
                        </td>
                    `;
                    sessionTableBody.appendChild(sessionRow);
                });

                sessionDetails.appendChild(sessionTable);
                activityCard.appendChild(sessionDetails);
                activityListContainer.appendChild(activityCard);
            });
        }

        currentTotalPoin = activitiesToRender.reduce((total, activity) => {
            return (
                total +
                activity.sesi.reduce(
                    (subtotal, sesi) => subtotal + sesi.bobot,
                    0
                )
            );
        }, 0);

        activityListContainer
            .querySelectorAll("[data-activity-id]")
            .forEach((header) => {
                header.addEventListener("click", (event) => {
                    // Cegah toggle jika yang diklik adalah tombol cetak
                    if (
                        event.target.closest(
                            ".print-activity-certificate-button"
                        )
                    ) {
                        return;
                    }
                    const activityId = header.getAttribute("data-activity-id");
                    const content = document.querySelector(
                        `[data-session-content-id="${activityId}"]`
                    );
                    const chevronIcon = header.querySelector(".chevron-icon");

                    if (content.classList.contains("max-h-0")) {
                        content.classList.remove("max-h-0");
                        content.classList.add("max-h-screen");
                        chevronIcon.classList.add("rotate-90");
                    } else {
                        content.classList.remove("max-h-screen");
                        content.classList.add("max-h-0");
                        chevronIcon.classList.remove("rotate-90");
                    }
                });
            });

        // Tambahkan event listener untuk tombol cetak sertifikat kegiatan
        activityListContainer
            .querySelectorAll(".print-activity-certificate-button")
            .forEach((button) => {
                button.addEventListener("click", function (event) {
                    event.stopPropagation(); // Mencegah klik menyebar ke elemen lain

                    // 1. URL ngrok aplikasi admin Anda
                    const adminAppUrl =
                        "https://6057-118-99-123-12.ngrok-free.app";

                    // 2. Ambil ID Kegiatan dari atribut tombol
                    const kegiatanId = this.getAttribute("data-kegiatan-id");

                    // 3. Buat URL lengkap ke generator sertifikat
                    //    Variabel `idCivitas` sudah ada dari file Blade Anda (yang berisi NIM)
                    const urlSertifikat = `${adminAppUrl}/sertifikat/generate/kegiatan/${kegiatanId}/peserta/${idCivitas}`;

                    // 4. Buka URL di tab baru untuk memulai unduhan PDF
                    console.log("Membuka URL Sertifikat:", urlSertifikat);
                    window.open(urlSertifikat, "_blank");
                });
            });

        totalPoinEl.textContent = currentTotalPoin;
        //localStorage.setItem("totalPoinKegiatan", currentTotalPoin);
        renderPaginationControls(activitiesToRender.length);
    }

    function renderPaginationControls(totalItems) {
        paginationControlsContainer.innerHTML = "";
        const totalPages = Math.ceil(totalItems / itemsPerPage);

        if (totalPages <= 1) return;

        const prevButton = document.createElement("button");
        prevButton.innerHTML = "&laquo; Previous";
        prevButton.className =
            "px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50";
        prevButton.disabled = currentPage === 1;
        prevButton.addEventListener("click", () => {
            if (currentPage > 1) {
                currentPage--;
                renderActivities(
                    allActivities.filter((activity) =>
                        isActivityMatch(
                            activity,
                            searchInput.value.toLowerCase().trim()
                        )
                    )
                );
            }
        });
        paginationControlsContainer.appendChild(prevButton);

        const pageInfo = document.createElement("span");
        pageInfo.className = "px-4 py-2 text-sm text-gray-700";
        pageInfo.textContent = `Halaman ${currentPage} dari ${totalPages}`;
        paginationControlsContainer.appendChild(pageInfo);

        const nextButton = document.createElement("button");
        nextButton.innerHTML = "Next &raquo;";
        nextButton.className =
            "px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50";
        nextButton.disabled = currentPage === totalPages;
        nextButton.addEventListener("click", () => {
            if (currentPage < totalPages) {
                currentPage++;
                renderActivities(
                    allActivities.filter((activity) =>
                        isActivityMatch(
                            activity,
                            searchInput.value.toLowerCase().trim()
                        )
                    )
                );
            }
        });
        paginationControlsContainer.appendChild(nextButton);
    }

    function isActivityMatch(activity, searchTerm) {
        if (!searchTerm) return true;

        const matchesActivity =
            activity.judul_kegiatan.toLowerCase().includes(searchTerm) ||
            activity.lokasi.toLowerCase().includes(searchTerm);

        const matchesSession = activity.sesi.some(
            (sesi) =>
                sesi.tgl_kegiatan.toLowerCase().includes(searchTerm) ||
                sesi.jam_kegiatan.toLowerCase().includes(searchTerm) ||
                sesi.nama_pemateri.toLowerCase().includes(searchTerm)
        );
        return matchesActivity || matchesSession;
    }

    let searchTimeout;
    searchInput.addEventListener("keyup", (event) => {
        clearTimeout(searchTimeout);
        const searchTerm = event.target.value.toLowerCase().trim();

        displayMessage("Mencari kegiatan...", "loading");

        searchTimeout = setTimeout(() => {
            const filteredActivities = allActivities.filter((activity) =>
                isActivityMatch(activity, searchTerm)
            );
            currentPage = 1;

            if (filteredActivities.length === 0 && searchTerm !== "") {
                displayMessage(
                    "Tidak ada kegiatan yang cocok dengan pencarian Anda.",
                    "no-results"
                );
                paginationControlsContainer.innerHTML = "";
            } else if (filteredActivities.length === 0 && searchTerm === "") {
                displayMessage(
                    "Belum ada riwayat kegiatan. Mulai kumpulkan poin Anda!",
                    "info"
                );
                paginationControlsContainer.innerHTML = "";
            } else {
                renderActivities(filteredActivities);
            }
        }, 300);
    });

    if (searchInput.value.trim() === "" && allActivities.length > 0) {
        renderActivities(allActivities);
    }
});
