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

/**
 * Fungsi untuk membuka modal riwayat (ditolak/diterima) dan menampilkan histori review.
 * @param {string} judulBuku - Judul buku yang terkait dengan pengajuan.
 * @param {string} status - Status pengajuan ('ditolak' atau 'diterima').
 * @param {string} adminKeterangan - Keterangan terakhir dari admin.
 * @param {string} aksaraDinamikaId - ID Aksara Dinamika.
 * @param {string} civitasId - ID Civitas (NIM mahasiswa).
 */
async function openHistoryModal(
    judulBuku,
    status,
    adminKeterangan,
    aksaraDinamikaId,
    civitasId
) {
    const modal = document.getElementById("ditolakModal");
    const modalTitle = modal.querySelector("h2");
    const ditolakMessage = document.getElementById("ditolakMessage");
    const adminKeteranganContainer = document.getElementById(
        "adminKeteranganContainer"
    ); // Mengambil container keterangan admin
    const adminKeteranganText = document.getElementById("adminKeterangan");
    const perbaikiButton = document.getElementById("perbaikiButton");
    const reviewHistoryItems = document.getElementById("reviewHistoryItems"); // Mengambil div tempat item histori akan ditambahkan

    // Simpan ID yang diterima dari Blade ke variabel global
    currentAksaraDinamikaId = aksaraDinamikaId;
    currentCivitasId = civitasId;
    console.log("ID Aksara Dinamika (Global):", currentAksaraDinamikaId);
    console.log("ID Civitas (Global):", currentCivitasId);

    // Bersihkan konten riwayat sebelumnya (hanya item histori, garis vertikal tetap ada di HTML)
    reviewHistoryItems.innerHTML = "";

    // --- Atur konten modal berdasarkan status yang diterima ---
    if (status === "ditolak") {
        modalTitle.textContent = "Status Ditolak"; // Judul modal
        modalTitle.classList.remove("text-green-600");
        modalTitle.classList.add("text-red-600");
        modalTitle.classList.remove("border-green-200");
        modalTitle.classList.add("border-red-200");

        ditolakMessage.textContent = `Pengajuan Anda untuk buku "${judulBuku}" ditolak. Mohon periksa kembali persyaratan atau hubungi admin.`;
        adminKeteranganContainer.classList.remove("hidden"); // Tampilkan div keterangan admin
        adminKeteranganText.textContent =
            adminKeterangan || "Tidak ada keterangan dari admin."; // Tampilkan keterangan atau pesan default
        perbaikiButton.classList.remove("hidden"); // Tampilkan tombol "Perbaiki"
    } else if (status === "diterima") {
        modalTitle.textContent = "Status Diterima"; // Judul modal
        modalTitle.classList.remove("text-red-600");
        modalTitle.classList.add("text-green-600");
        modalTitle.classList.remove("border-red-200");
        modalTitle.classList.add("border-green-200");

        ditolakMessage.textContent = `Pengajuan Anda untuk buku "${judulBuku}" telah diterima.`;
        adminKeteranganContainer.classList.add("hidden"); // Sembunyikan div keterangan admin
        perbaikiButton.classList.add("hidden"); // Sembunyikan tombol "Perbaiki"
    } else {
        console.warn(
            "openHistoryModal dipanggil dengan status yang tidak diharapkan:",
            status
        );
        return; // Jangan buka modal jika status tidak ditolak atau diterima
    }

    // --- Mengambil dan Menampilkan Riwayat Review dari API ---
    try {
        // Lakukan permintaan (fetch) ke endpoint API di backend Anda
        const response = await fetch(
            `http://127.0.0.1:8000/api/histori-status/${civitasId}/${aksaraDinamikaId}`
        );
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const apiResponse = await response.json(); // Ubah respons menjadi JSON
        const historyData = apiResponse.data; // Ambil array data dari properti 'data'

        console.log("Data histori review dari API:", historyData);

        if (historyData && historyData.length > 0) {
            historyData.forEach((entry) => {
                const itemDiv = document.createElement("div");
                itemDiv.classList.add("relative", "mb-4", "pb-4"); // Memberikan posisi relatif untuk titik timeline di dalamnya

                // Titik di timeline
                const dot = document.createElement("div");
                // Kelas `absolute` dan `-left-4` (atau nilai lain yang sesuai) akan memposisikan titik
                // di luar kontainer item, sejajar dengan garis vertikal utama.
                // Anda mungkin perlu menyesuaikan `-left-4` sedikit tergantung pada CSS Anda.
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

                // Konten item timeline
                const contentDiv = document.createElement("div");
                // Hapus `ml-6` di sini karena `pl-6` sudah ada pada parent `relative pl-6` di HTML.
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
                // Pastikan menggunakan nama properti tanggal yang benar dari respons API Anda (`tgl_status`)
                dateP.textContent = `Tanggal: ${new Date(
                    entry.tgl_status
                ).toLocaleString("id-ID")}`;

                // Tampilkan keterangan hanya jika ada dan bukan string kosong
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
                reviewHistoryItems.appendChild(itemDiv); // Tambahkan item ke div histori item
            });
        } else {
            // Jika tidak ada data histori, tampilkan pesan
            reviewHistoryItems.innerHTML +=
                '<p class="ml-0 text-gray-500">Tidak ada histori review untuk pengajuan ini.</p>';
        }
    } catch (error) {
        console.error("Gagal mengambil histori review:", error);
        reviewHistoryItems.innerHTML +=
            '<p class="ml-0 text-red-500">Gagal memuat histori review. Silakan coba lagi.</p>';
    }

    // Tampilkan modal setelah kontennya siap
    modal.classList.remove("hidden");
}

// Fungsi untuk menutup modal riwayat
function closeDitolakModal() {
    document.getElementById("ditolakModal").classList.add("hidden");
}

// Fungsi yang dipanggil saat tombol "Perbaiki" diklik
function handlePerbaikiClick() {
    // Pastikan ID Aksara Dinamika dan Civitas ada
    if (currentAksaraDinamikaId && currentCivitasId) {
        // Redirect ke halaman perbaikan, sertakan ID sebagai parameter
        window.location.href = `/formaksaradinamika-mhs/edit/${currentAksaraDinamikaId}?civitas_id=${currentCivitasId}`;
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
