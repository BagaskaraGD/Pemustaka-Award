// aksaramodal.js

// Fungsi untuk membuka modal informasi umum (modal besar berisi 4 poin)
function openModal() {
    document.getElementById("modal").classList.remove("hidden");
}

// Fungsi untuk menutup modal informasi umum
function closeModal() {
    document.getElementById("modal").classList.add("hidden");
}

// Variabel global untuk menyimpan ID item Aksara Dinamika yang sedang dibuka modal 'ditolak'
let currentItemIdToFix = null;
// Variabel global untuk menyimpan ID Civitas (ID pengguna) yang sedang login
let currentCivitasId = null;

/**
 * Fungsi untuk membuka modal Status Ditolak/Diterima dan memuat histori review.
 * @param {string} message - Pesan utama yang akan ditampilkan di modal.
 * @param {string} keteranganAdmin - Keterangan terakhir dari admin.
 * @param {string} itemId - ID unik dari entri Aksara Dinamika yang sedang dilihat.
 * @param {string} civitasId - ID unik dari civitas (pengguna) yang sedang login.
 */
async function openDitolakModal(message, keteranganAdmin, itemId, civitasId) {
    // Setel teks pesan utama dan keterangan admin di modal
    document.getElementById("ditolakMessage").innerText = message;
    document.getElementById("adminKeterangan").innerText = keteranganAdmin;

    // Simpan ID ke variabel global untuk digunakan oleh fungsi lain (misal: tombol 'Perbaiki')
    currentItemIdToFix = itemId;
    currentCivitasId = civitasId;

    // Untuk debugging, bisa dihapus setelah yakin ID sudah terkirim dengan benar
    console.log("ID Aksara Dinamika (itemId):", itemId);
    console.log("ID Civitas (currentCivitasId):", civitasId);

    const timelineContainer = document.getElementById("reviewHistoryTimeline");

    // Bersihkan konten histori review sebelumnya dan tampilkan pesan loading
    timelineContainer.innerHTML = `
        <div class="absolute left-2 top-0 bottom-0 w-0.5 bg-gray-300"></div>
        <p class="text-sm text-gray-500 ml-6">Memuat histori review...</p>
    `;

    // Tampilkan modal terlebih dahulu untuk user experience yang lebih baik
    document.getElementById("ditolakModal").classList.remove("hidden");

    try {
        // Lakukan fetch data histori status dari API
        // Pastikan URL menggunakan itemId dan civitasId yang valid
        const response = await fetch(
            `http://127.0.0.1:8000/api/histori-status/${civitasId}/${itemId}`
        );

        if (!response.ok) {
            // Jika respons API tidak sukses (misal: 404, 500), lemparkan error
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const apiData = await response.json();
        // Ambil array data histori, jika tidak ada, gunakan array kosong
        const reviewHistory = apiData.data || [];

        // Bersihkan konten loading setelah data berhasil diambil
        timelineContainer.innerHTML =
            '<div class="absolute left-2 top-0 bottom-0 w-0.5 bg-gray-300"></div>';

        // Cek apakah ada data histori review yang diambil dari API
        if (reviewHistory.length > 0) {
            // Urutkan histori berdasarkan tanggal status dari yang terbaru ke terlama
            // (Opsional, tapi seringkali lebih baik untuk histori)
            reviewHistory.sort((a, b) => new Date(b.tgl_status) - new Date(a.tgl_status));

            reviewHistory.forEach((entry) => {
                let statusClass = "";
                // Pastikan entry.status ada dan ubah ke lowercase untuk perbandingan
                const status = (entry.status || "").toLowerCase();

                // Tentukan warna berdasarkan status
                switch (status) {
                    case "diterima":
                        statusClass = "bg-green-500";
                        break;
                    case "ditolak":
                        statusClass = "bg-red-500";
                        break;
                    case "menunggu":
                        statusClass = "bg-yellow-500";
                        break;
                    default:
                        statusClass = "bg-gray-400"; // Warna default jika status tidak dikenali
                }

                // Format tanggal agar lebih rapi (opsional)
                const formattedDate = entry.tgl_status ? new Date(entry.tgl_status).toLocaleDateString('id-ID', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                }) : "Tanggal tidak tersedia";


                // Buat elemen timeline item menggunakan template literals
                const timelineItem = `
                    <div class="relative mb-6 flex items-start">
                        <div class="absolute left-0 top-1.5 w-3 h-3 ${statusClass} rounded-full z-10 -ml-1"></div>
                        <div class="ml-6 flex-grow">
                            <p class="font-semibold text-sm text-gray-800">${formattedDate}</p>
                            <p class="text-sm text-gray-600">
                                <span class="font-bold uppercase">${status}</span>: ${
                    entry.keterangan || "Tidak ada keterangan."
                }
                            </p>
                        </div>
                    </div>
                `;
                // Tambahkan item ke dalam container timeline
                timelineContainer.insertAdjacentHTML("beforeend", timelineItem);
            });
        } else {
            // Jika tidak ada histori review
            timelineContainer.insertAdjacentHTML(
                "beforeend",
                '<p class="text-sm text-gray-500 ml-6">Tidak ada histori review.</p>'
            );
        }
    } catch (error) {
        // Tangani error jika gagal mengambil histori review
        console.error("Gagal mengambil histori review:", error);
        timelineContainer.innerHTML = `
            <div class="absolute left-2 top-0 bottom-0 w-0.5 bg-gray-300"></div>
            <p class="text-sm text-red-500 ml-6">Gagal memuat histori. Coba lagi nanti.</p>
        `;
    }
}

// Fungsi untuk menutup modal Status Ditolak
function closeDitolakModal() {
    document.getElementById("ditolakModal").classList.add("hidden");
    // Reset ID item agar tidak ada data lama yang tersimpan
    currentItemIdToFix = null;
    currentCivitasId = null;
}

// Fungsi yang dipanggil saat tombol "Perbaiki" diklik
function handlePerbaikiClick() {
    if (currentItemIdToFix) {
        // Redirect ke halaman edit dengan ID Aksara Dinamika yang relevan
        window.location.href = `/formaksaradinamika-mhs/edit/${currentItemIdToFix}`;
    } else {
        alert("Tidak ada item yang dipilih untuk diperbaiki.");
    }
}

// Fungsi filterTable (opsional, bisa tetap di sini atau dipindahkan ke file lain jika mau)
// Fungsi ini berfungsi untuk mencari/filter data di dalam tabel
function filterTable() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const rows = document.querySelectorAll("#dataTable tr"); // Dapatkan semua baris data di tabel

    rows.forEach(row => {
        const text = row.textContent.toLowerCase(); // Ambil semua teks dari baris
        // Jika teks baris mengandung input pencarian, tampilkan barisnya, jika tidak, sembunyikan
        row.style.display = text.includes(input) ? "" : "none";
    });
}