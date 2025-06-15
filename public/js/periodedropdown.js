// public/js/periodedropdown.js

document.addEventListener("DOMContentLoaded", function () {
    const dropdownButton = document.getElementById("dropdownDefaultButton");
    const periodeList = document.getElementById("periodeList");
    const currentPath = window.location.pathname;

    if (!dropdownButton || !periodeList) {
        console.error("Elemen dropdown tidak ditemukan.");
        return;
    }

    // 1. Dapatkan nama periode yang sedang aktif dari teks tombol itu sendiri.
    const activePeriodeName = dropdownButton.textContent.trim();

    fetch("/periode/dropdown")
        .then((response) => response.json())
        .then((data) => {
            periodeList.innerHTML = ""; // Kosongkan daftar

            // 2. Tambahkan link untuk kembali ke "Periode Saat Ini" jika yang aktif BUKAN periode saat ini.
            // Ini akan muncul jika kita sedang melihat periode lampau.
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has("periode")) {
                const homeLink = document.createElement("li");
                homeLink.innerHTML = `
                    <a href="${currentPath}" class="flex items-center gap-x-3 rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-amber-100 transition-colors duration-200">
                        <i class="fa-solid fa-star w-5 text-center text-amber-500"></i>
                        <span>Periode Saat Ini</span>
                    </a>
                `;
                periodeList.appendChild(homeLink);
            }

            // 3. Loop melalui data dan hanya tampilkan periode yang BEDA dari yang sedang aktif.
            data.forEach((item) => {
                if (item.nama_periode !== activePeriodeName) {
                    const li = document.createElement("li");
                    const filterUrl = `${currentPath}?periode=${item.id_periode}`;

                    li.innerHTML = `
                        <a href="${filterUrl}" class="flex items-center gap-x-3 rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-amber-100 transition-colors duration-200">
                            <i class="fa-solid fa-trophy w-5 text-center text-gray-400"></i>
                            <span>${item.nama_periode}</span>
                        </a>
                    `;
                    periodeList.appendChild(li);
                }
            });
        })
        .catch((error) => {
            console.error("Error fetching periode:", error);
            periodeList.innerHTML =
                '<li><span class="block px-4 py-2 text-red-500">Gagal memuat</span></li>';
        });
});
