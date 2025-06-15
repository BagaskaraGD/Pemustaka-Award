// public/js/sidebar.js

document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const mainContent = document.getElementById("main-content");
    const toggleButton = document.getElementById("sidebar-toggle");

    if (!sidebar || !mainContent || !toggleButton) {
        console.error(
            "Elemen sidebar, main-content, atau tombol toggle tidak ditemukan!"
        );
        return;
    }

    // Fungsi untuk menerapkan status sidebar
    const applySidebarState = (state) => {
        if (state === "closed") {
            sidebar.classList.add("w-20", "closed", "p-2"); // Perkecil sidebar
            sidebar.classList.remove("w-64", "p-4");
            mainContent.classList.add("ml-20"); // Sesuaikan margin konten utama
            mainContent.classList.remove("ml-64");
        } else {
            // "open"
            sidebar.classList.add("w-64", "p-4");
            sidebar.classList.remove("w-20", "closed", "p-2"); // Perbesar sidebar
            mainContent.classList.add("ml-64");
            mainContent.classList.remove("ml-20"); // Sesuaikan margin konten utama
        }
    };

    // Cek status tersimpan di localStorage saat halaman dimuat
    const savedState = localStorage.getItem("sidebarState");
    // Terapkan state yang tersimpan, defaultnya 'open' jika tidak ada
    applySidebarState(savedState || "open");

    // Event listener untuk tombol toggle
    toggleButton.addEventListener("click", function () {
        const isClosed = sidebar.classList.contains("closed");
        const newState = isClosed ? "open" : "closed";

        // Terapkan state baru
        applySidebarState(newState);

        // Simpan state baru ke localStorage
        localStorage.setItem("sidebarState", newState);
    });
});
