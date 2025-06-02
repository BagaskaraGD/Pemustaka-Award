document.addEventListener("DOMContentLoaded", async function () {
    console.log("DOM Loaded. Starting profile user script.");
    try {
        if (typeof idCivitas === "undefined") {
            console.error("idCivitas is not defined!");
            return;
        }
        console.log("Fetching data for idCivitas:", idCivitas);

        const endpoint = `http://127.0.0.1:8000/api/myrank/mhs/${idCivitas}`;
        const response = await fetch(endpoint);

        if (!response.ok) {
            console.error(`HTTP error! Status: ${response.status}`);
            return;
        }

        const result = await response.json();
        console.log("API Response:", result);

        if (!result.data) {
            console.error("API response does not contain 'data'.", result);
            return;
        }

        const rankData = result.data;
        const userPoints = parseInt(rankData.total_rekap_poin, 10); // Pakai parseInt
        const nim = rankData.nim;
        const peringkat = rankData.peringkat;

        console.log("User Points (Parsed):", userPoints);

        document.getElementById("user").textContent = nim;
        document.getElementById("peringkat").textContent = peringkat;
        document.getElementById("poin").textContent = userPoints;

        updateProgress(userPoints);
    } catch (error) {
        console.error("Gagal mengambil atau memproses data:", error);
    }
});

// Fungsi bantuan untuk menampilkan tombol
function showButton(buttonElement) {
    if (buttonElement) {
        buttonElement.classList.remove("hidden");
        // Beri sedikit waktu agar 'hidden' hilang sebelum transisi opacity
        requestAnimationFrame(() => {
            setTimeout(() => {
                buttonElement.classList.remove("opacity-0");
            }, 10);
        });
    } else {
        console.warn("Attempted to show a button that was not found.");
    }
}

// Fungsi bantuan untuk menyembunyikan tombol (jika diperlukan nanti)
function hideButton(buttonElement) {
    if (buttonElement) {
        buttonElement.classList.add("opacity-0");
        setTimeout(() => {
            buttonElement.classList.add("hidden");
        }, 300); // Sesuaikan dengan durasi transisi
    }
}

function updateProgress(userPoints) {
    console.log("Updating progress with points:", userPoints);
    const levelElements = document.querySelectorAll(".level-threshold");
    const levelThresholds = {
        0: parseInt(levelElements[0].textContent),
        1: parseInt(levelElements[1].textContent),
        2: parseInt(levelElements[2].textContent),
        3: parseInt(levelElements[3].textContent),
    };

    let currentLevel = 0;
    let positionPercentage = 0;

    if (userPoints >= levelThresholds[3]) {
        currentLevel = 3;
        positionPercentage = 100;
    } else if (userPoints >= levelThresholds[2]) {
        currentLevel = 2;
        const range = levelThresholds[3] - levelThresholds[2];
        const progress =
            range > 0 ? (userPoints - levelThresholds[2]) / range : 0;
        positionPercentage = 66.66 + progress * 33.33;
    } else if (userPoints >= levelThresholds[1]) {
        currentLevel = 1;
        const range = levelThresholds[2] - levelThresholds[1];
        const progress =
            range > 0 ? (userPoints - levelThresholds[1]) / range : 0;
        positionPercentage = 33.33 + progress * 33.33;
    } else {
        currentLevel = 0;
        const range = levelThresholds[1] || 1;
        const progress = userPoints / range;
        positionPercentage = progress * 33.33;
    }

    positionPercentage = Math.max(0, Math.min(100, positionPercentage));

    console.log("Calculated Current Level:", currentLevel);

    document.getElementById("level-text").textContent = `LEVEL ${currentLevel}`;

    const progressFill = document.getElementById("progress-fill");
    const progressIndicator = document.getElementById("progress-indicator");

    // Ambil semua tombol klaim
    const claimContainer1 = document.getElementById("claim-button1-container");
    const claimContainer2 = document.getElementById("claim-button2-container");
    const claimContainer3 = document.getElementById("claim-button3-container");

    console.log("Button 1:", claimContainer1);
    console.log("Button 2:", claimContainer2);
    console.log("Button 3:", claimContainer3);

    // Update progress bar
    progressFill.style.width = `${positionPercentage}%`;
    progressIndicator.style.left = `${positionPercentage}%`;

    // --- LOGIKA MENAMPILKAN TOMBOL ---
    // Logika ini akan menampilkan *semua* tombol untuk level yang telah dicapai.
    // Jika Anda hanya ingin menampilkan *satu* tombol (misalnya, yang tertinggi),
    // Anda perlu mengubah logika ini dan mungkin menambahkan sistem untuk melacak klaim.

    if (currentLevel >= 1) {
        console.log("Showing button container for Level 1");
        showButton(claimContainer1);
    }
    if (currentLevel >= 2) {
        console.log("Showing button container for Level 2");
        showButton(claimContainer2);
    }
    if (currentLevel >= 3) {
        console.log("Showing button container for Level 3");
        showButton(claimContainer3);
    }
    // else { hideButton(claimButton3); }
    // --- AKHIR LOGIKA TOMBOL ---
}
