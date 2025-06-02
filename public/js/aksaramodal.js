// public/js/aksaramodal.js

// Fungsi untuk membuka modal informasi umum (yang sudah ada)
function openModal() {
    document.getElementById("modal").classList.remove("hidden");
}

// Fungsi untuk menutup modal informasi umum (yang sudah ada)
function closeModal() {
    document.getElementById("modal").classList.add("hidden");
}

// Variabel global untuk menyimpan ID Aksara Dinamika dan Civitas yang sedang aktif
let currentAksaraDinamikaId = null;
let currentCivitasId = null;
let currentIndukBuku = null;

async function openHistoryModal(
    judulBuku,
    status,
    adminKeterangan,
    aksaraDinamikaId,
    civitasId,
    indukBuku
) {
    const modal = document.getElementById("ditolakModal");
    const modalTitle = modal.querySelector("h2");
    const ditolakMessage = document.getElementById("ditolakMessage");
    const adminKeteranganContainer = document.getElementById(
        "adminKeteranganContainer"
    );
    const adminKeteranganText = document.getElementById("adminKeterangan");
    const perbaikiButton = document.getElementById("perbaikiButton");
    const reviewHistoryItems = document.getElementById("reviewHistoryItems");

    currentIndukBuku = indukBuku;
    currentAksaraDinamikaId = aksaraDinamikaId;
    currentCivitasId = civitasId;

    reviewHistoryItems.innerHTML = "";

    let latestAdminKeterangan =
        adminKeterangan || "Tidak ada keterangan dari admin.";

    try {
        const response = await fetch(
            `http://127.0.0.1:8000/api/histori-status/${civitasId}/${indukBuku}`
        );
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const apiResponse = await response.json();
        const historyData = apiResponse.data;

        if (historyData && historyData.length > 0) {
            const sortedHistory = [...historyData].sort(
                (a, b) => new Date(b.tgl_status) - new Date(a.tgl_status)
            );

            const latestDitolakEntry = sortedHistory.find(
                (entry) =>
                    entry.status.toLowerCase() === "ditolak" &&
                    entry.keterangan &&
                    entry.keterangan.trim() !== "" &&
                    entry.keterangan !== "Tidak ada keterangan dari admin."
            );

            if (latestDitolakEntry) {
                latestAdminKeterangan = latestDitolakEntry.keterangan;
            }

            historyData.forEach((entry) => {
                const itemDiv = document.createElement("div");
                itemDiv.classList.add("relative", "mb-4", "pb-4");

                const dot = document.createElement("div");
                dot.classList.add(
                    "absolute",
                    "-left-4",
                    "top-0",
                    "w-3",
                    "h-3",
                    "rounded-full",
                    "border-2",
                    "bg-white",
                    "z-10"
                );
                if (entry.status === "ditolak") {
                    dot.classList.add("border-red-500", "bg-red-200");
                } else if (entry.status === "diterima") {
                    dot.classList.add("border-green-500", "bg-green-200");
                } else {
                    dot.classList.add("border-gray-500", "bg-gray-200");
                }

                const contentDiv = document.createElement("div");
                contentDiv.classList.add(
                    "p-3",
                    "bg-gray-100",
                    "rounded-lg",
                    "shadow-sm",
                    "border",
                    "border-gray-200"
                );

                const statusP = document.createElement("p");
                statusP.classList.add("font-semibold", "text-lg");
                if (entry.status === "ditolak") {
                    statusP.classList.add("text-red-700");
                } else if (entry.status === "diterima") {
                    statusP.classList.add("text-green-700");
                } else {
                    statusP.classList.add("text-gray-700");
                }
                statusP.textContent = `Status: ${
                    entry.status.charAt(0).toUpperCase() + entry.status.slice(1)
                }`;

                const dateP = document.createElement("p");
                dateP.classList.add("text-sm", "text-gray-600", "mt-1");
                dateP.textContent = `Tanggal: ${new Date(
                    entry.tgl_status
                ).toLocaleString("id-ID")}`;

                if (
                    entry.keterangan &&
                    entry.keterangan.trim() !== "" &&
                    entry.keterangan !== "Tidak ada keterangan dari admin."
                ) {
                    const keteranganP = document.createElement("p");
                    keteranganP.classList.add(
                        "text-base",
                        "text-gray-800",
                        "mt-2"
                    );
                    keteranganP.textContent = `Keterangan: ${entry.keterangan}`;
                    contentDiv.appendChild(keteranganP);
                }

                contentDiv.appendChild(statusP);
                contentDiv.appendChild(dateP);
                itemDiv.appendChild(dot);
                itemDiv.appendChild(contentDiv);
                reviewHistoryItems.appendChild(itemDiv);
            });
        } else {
            reviewHistoryItems.innerHTML +=
                '<p class="ml-0 text-gray-500">Tidak ada histori review untuk pengajuan ini.</p>';
        }
    } catch (error) {
        console.error("Gagal mengambil histori review:", error);
        reviewHistoryItems.innerHTML +=
            '<p class="ml-0 text-red-500">Gagal memuat histori review. Silakan coba lagi.</p>';
        latestAdminKeterangan = adminKeterangan || "Gagal memuat keterangan.";
    }

    if (status === "ditolak") {
        modalTitle.textContent = "Status Ditolak";
        modalTitle.className =
            "text-3xl font-extrabold text-red-600 mb-4 border-b-2 border-red-200 pb-2 text-center";
        ditolakMessage.textContent = `Pengajuan Anda untuk buku "${judulBuku}" ditolak. Mohon periksa kembali persyaratan atau hubungi admin.`;
        adminKeteranganContainer.classList.remove("hidden");
        adminKeteranganText.textContent = latestAdminKeterangan;
        perbaikiButton.classList.remove("hidden");
    } else if (status === "diterima") {
        modalTitle.textContent = "Status Diterima";
        modalTitle.className =
            "text-3xl font-extrabold text-green-600 mb-4 border-b-2 border-green-200 pb-2 text-center";
        ditolakMessage.textContent = `Pengajuan Anda untuk buku "${judulBuku}" telah diterima.`;
        adminKeteranganContainer.classList.add("hidden");
        perbaikiButton.classList.add("hidden");
    } else {
        console.warn("Status tidak diharapkan:", status);
        return;
    }

    modal.classList.remove("hidden");
}

function closeDitolakModal() {
    document.getElementById("ditolakModal").classList.add("hidden");
}

function handlePerbaikiClick() {
    if (currentIndukBuku && currentCivitasId && currentAksaraDinamikaId) {
        window.location.href = `/formaksaradinamika-mhs/edit/${currentAksaraDinamikaId}/${currentIndukBuku}/${currentCivitasId}`;
    } else {
        alert("Data ID tidak tersedia untuk perbaikan. Mohon refresh halaman.");
    }
}

// --- PAGINASI UNTUK TABEL AKSARA DINAMIKA ---
let aksaraCurrentPage = 1;
const aksaraItemsPerPage = 7;
let allAksaraDataRows = [];

function renderAksaraTable(rowsToRender) {
    const tableBody = document.getElementById("dataTable");
    if (!tableBody) return; // Pastikan tableBody ada

    tableBody.innerHTML = "";

    const startIndex = (aksaraCurrentPage - 1) * aksaraItemsPerPage;
    const endIndex = startIndex + aksaraItemsPerPage;
    const paginatedRows = rowsToRender.slice(startIndex, endIndex);

    if (paginatedRows.length === 0) {
        const tr = document.createElement("tr");
        const td = document.createElement("td");
        td.colSpan = 4;
        td.className = "px-6 py-4 text-center text-gray-500";
        td.textContent =
            rowsToRender.length > 0
                ? "Tidak ada data di halaman ini."
                : "Tidak ada data histori Aksara Dinamika.";
        tr.appendChild(td);
        tableBody.appendChild(tr);
    } else {
        paginatedRows.forEach((rowElement) => {
            tableBody.appendChild(rowElement.cloneNode(true));
        });
    }
    renderAksaraPaginationControls(rowsToRender.length);
}

function renderAksaraPaginationControls(totalItems) {
    const paginationControls = document.getElementById("aksaraTablePagination");
    if (!paginationControls) return;
    paginationControls.innerHTML = "";

    const totalPages = Math.ceil(totalItems / aksaraItemsPerPage);

    if (totalPages <= 1) return;

    const createButton = (text, page, isDisabled = false) => {
        const button = document.createElement("button");
        button.innerHTML = text;
        button.className = `px-3 py-1 mx-1 text-sm font-medium rounded-md border border-gray-300 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed`;
        if (
            page === aksaraCurrentPage &&
            text !== "&laquo; Previous" &&
            text !== "Next &raquo;"
        ) {
            button.classList.add(
                "bg-blue-500",
                "text-white",
                "border-blue-500"
            );
            button.classList.remove("hover:bg-gray-50");
        }
        button.disabled = isDisabled;
        button.addEventListener("click", () => {
            aksaraCurrentPage = page; // Set halaman baru

            // Ambil term pencarian saat ini
            const currentSearchTerm = document
                .getElementById("searchInput")
                .value.toLowerCase();
            // Filter ulang data berdasarkan term pencarian
            const currentlyFilteredRows = allAksaraDataRows.filter((row) => {
                const textContent = row.textContent.toLowerCase();
                return textContent.includes(currentSearchTerm);
            });
            // Render tabel dengan data yang sudah difilter dan halaman yang baru
            renderAksaraTable(currentlyFilteredRows);
        });
        return button;
    };

    paginationControls.appendChild(
        createButton(
            "&laquo; Previous",
            aksaraCurrentPage - 1,
            aksaraCurrentPage === 1
        )
    );

    for (let i = 1; i <= totalPages; i++) {
        if (
            totalPages > 5 &&
            i > 2 &&
            i < totalPages - 1 &&
            Math.abs(i - aksaraCurrentPage) > 1
        ) {
            if (!paginationControls.querySelector(".ellipsis")) {
                const ellipsis = document.createElement("span");
                ellipsis.textContent = "...";
                ellipsis.className = "px-3 py-1 mx-1 text-sm";
                ellipsis.classList.add("ellipsis");
                paginationControls.appendChild(ellipsis);
            }
            continue;
        }
        paginationControls.appendChild(createButton(i.toString(), i));
    }

    paginationControls.appendChild(
        createButton(
            "Next &raquo;",
            aksaraCurrentPage + 1,
            aksaraCurrentPage === totalPages
        )
    );
}

// Fungsi filterTable hanya untuk menangani perubahan pada input search
function filterTable() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const filteredRows = allAksaraDataRows.filter((row) => {
        const text = row.textContent.toLowerCase();
        return text.includes(input);
    });
    aksaraCurrentPage = 1; // Reset ke halaman pertama SETIAP KALI filter/pencarian baru
    renderAksaraTable(filteredRows);
}

document.addEventListener("DOMContentLoaded", () => {
    const tableBody = document.getElementById("dataTable");
    if (tableBody) {
        allAksaraDataRows = Array.from(tableBody.querySelectorAll("tr"));

        let paginationDiv = document.getElementById("aksaraTablePagination");
        if (!paginationDiv) {
            paginationDiv = document.createElement("div");
            paginationDiv.id = "aksaraTablePagination";
            paginationDiv.className =
                "flex justify-center items-center space-x-1 mt-4";
            const tableContainer = tableBody.closest(".overflow-x-auto");
            if (tableContainer && tableContainer.parentNode) {
                tableContainer.parentNode.insertBefore(
                    paginationDiv,
                    tableContainer.nextSibling
                );
            } else {
                tableBody.insertAdjacentElement("afterend", paginationDiv);
            }
        }
        // Untuk render awal, kita panggil filterTable. Ini akan secara otomatis
        // menampilkan semua data (karena searchInput kosong) dan mengatur halaman ke 1.
        filterTable();
    }

    const searchInput = document.getElementById("searchInput");
    if (searchInput) {
        searchInput.addEventListener("input", filterTable);
    }
});
