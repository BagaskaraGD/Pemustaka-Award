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

/**
 * Fungsi untuk membuka modal riwayat (ditolak/diterima) dan menampilkan histori review.
 * @param {string} judulBuku - Judul buku yang terkait dengan pengajuan.
 * @param {string} status - Status pengajuan ('ditolak' atau 'diterima').
 * @param {string} adminKeterangan - Keterangan terakhir dari admin (dari tabel, sebagai fallback).
 * @param {string} aksaraDinamikaId - ID Aksara Dinamika.
 * @param {string} civitasId - ID Civitas (NIM mahasiswa).
 * @param {string} indukBuku - ID Induk Buku (jika ada).
 */
async function openHistoryModal(
    judulBuku,
    status,
    adminKeterangan, // Kita tetap terima ini sebagai fallback awal
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

    reviewHistoryItems.innerHTML = ""; // Bersihkan histori lama

    let latestAdminKeterangan =
        adminKeterangan || "Tidak ada keterangan dari admin."; // Fallback awal

    // --- Mengambil dan Menampilkan Riwayat Review dari API ---
    try {
        const response = await fetch(
            `http://127.0.0.1:8000/api/histori-status/${civitasId}/${indukBuku}`
        );
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const apiResponse = await response.json();
        const historyData = apiResponse.data;

        console.log("Data histori review dari API:", historyData);

        if (historyData && historyData.length > 0) {
            // *** AWAL PERUBAHAN: Cari Keterangan Admin Terakhir dari Histori ***
            // Urutkan histori berdasarkan tanggal (terbaru dulu)
            const sortedHistory = [...historyData].sort(
                (a, b) => new Date(b.tgl_status) - new Date(a.tgl_status)
            );

            // Cari entri 'ditolak' pertama (yang berarti terbaru) yang punya keterangan
            const latestDitolakEntry = sortedHistory.find(
                (entry) =>
                    entry.status.toLowerCase() === "ditolak" &&
                    entry.keterangan &&
                    entry.keterangan.trim() !== "" &&
                    entry.keterangan !== "Tidak ada keterangan dari admin."
            );

            // Jika ditemukan, gunakan keterangannya
            if (latestDitolakEntry) {
                latestAdminKeterangan = latestDitolakEntry.keterangan;
            }
            // *** AKHIR PERUBAHAN ***

            // Tampilkan semua histori (logika Anda yang sudah ada)
            historyData.forEach((entry) => {
                // Gunakan historyData asli agar urutan tetap kronologis (atau sortedHistory jika ingin terbaru di atas)
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
        // Jika API gagal, kita tetap gunakan keterangan awal yang dilewatkan
        latestAdminKeterangan = adminKeterangan || "Gagal memuat keterangan.";
    }

    // --- Atur konten modal berdasarkan status ---
    if (status === "ditolak") {
        modalTitle.textContent = "Status Ditolak";
        modalTitle.className =
            "text-3xl font-extrabold text-red-600 mb-4 border-b-2 border-red-200 pb-2 text-center"; // Set ulang kelas
        ditolakMessage.textContent = `Pengajuan Anda untuk buku "${judulBuku}" ditolak. Mohon periksa kembali persyaratan atau hubungi admin.`;
        adminKeteranganContainer.classList.remove("hidden");
        // *** GUNAKAN KETERANGAN TERBARU YANG DITEMUKAN ***
        adminKeteranganText.textContent = latestAdminKeterangan;
        perbaikiButton.classList.remove("hidden");
    } else if (status === "diterima") {
        modalTitle.textContent = "Status Diterima";
        modalTitle.className =
            "text-3xl font-extrabold text-green-600 mb-4 border-b-2 border-green-200 pb-2 text-center"; // Set ulang kelas
        ditolakMessage.textContent = `Pengajuan Anda untuk buku "${judulBuku}" telah diterima.`;
        adminKeteranganContainer.classList.add("hidden");
        perbaikiButton.classList.add("hidden");
    } else {
        console.warn("Status tidak diharapkan:", status);
        return;
    }

    modal.classList.remove("hidden"); // Tampilkan modal
}

// Fungsi untuk menutup modal riwayat
function closeDitolakModal() {
    document.getElementById("ditolakModal").classList.add("hidden");
}

// Fungsi yang dipanggil saat tombol "Perbaiki" diklik
function handlePerbaikiClick() {
    if (currentIndukBuku && currentCivitasId && currentAksaraDinamikaId) {
        window.location.href = `/formaksaradinamika-mhs/edit/${currentAksaraDinamikaId}/${currentIndukBuku}/${currentCivitasId}`;
    } else {
        alert("Data ID tidak tersedia untuk perbaikan. Mohon refresh halaman.");
    }
}

// Fungsi filterTable
function filterTable() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const rows = document.querySelectorAll("#dataTable tr");
    rows.forEach((row) => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(input) ? "" : "none";
    });
}
