document.addEventListener("DOMContentLoaded", async function () {
    try {
        const endpoint = `http://127.0.0.1:8000/api/myrank/${idCivitas}`;
        const response = await fetch(endpoint);

        if (!response.ok) {
            console.error(`HTTP error! Status: ${response.status}`);
            return;
        }

        const result = await response.json();
        const rankData = result.data;
        const userPoints = rankData.total_rekap_poin; // Ambil rekap_jumlah poin dari API response
        console.log("DATA API:", userPoints); //DEBUG
        const nim = rankData.nim;
        const peringkat = rankData.peringkat;

        // Tampilkan data ke elemen HTML
        document.getElementById("user").textContent = nim;
        document.getElementById("peringkat").textContent = peringkat;
        document.getElementById("poin").textContent = userPoints;

        // Lanjutkan perhitungan untuk progress bar
        updateProgress(userPoints);
    } catch (error) {
        console.error("Gagal mengambil data:", error);
    }
});

function updateProgress(userPoints) {
    // Ambil threshold level dari HTML
    const levelElements = document.querySelectorAll(".level-threshold");
    const levelThresholds = {
        0: parseInt(levelElements[0].textContent),
        1: parseInt(levelElements[1].textContent),
        2: parseInt(levelElements[2].textContent),
        3: parseInt(levelElements[3].textContent),
    };

    let currentLevel = 0;
    let progressPercentage = 0;
    let positionPercentage = 0;

    if (userPoints >= levelThresholds[3]) {
        currentLevel = 3;
        progressPercentage = 100;
        positionPercentage = 100;
    } else if (userPoints >= levelThresholds[2]) {
        currentLevel = 2;
        progressPercentage =
            ((userPoints - levelThresholds[2]) /
                (levelThresholds[3] - levelThresholds[2])) *
            100;
        positionPercentage = 66 + progressPercentage * 0.33;
    } else if (userPoints >= levelThresholds[1]) {
        currentLevel = 1;
        progressPercentage =
            ((userPoints - levelThresholds[1]) /
                (levelThresholds[2] - levelThresholds[1])) *
            100;
        positionPercentage = 33 + progressPercentage * 0.33;
    } else {
        currentLevel = 0;
        progressPercentage = (userPoints / levelThresholds[1]) * 100;
        positionPercentage = progressPercentage * 0.33;
    }

    // Animasikan progress bar
    const progressFill = document.getElementById("progress-fill");
    const progressIndicator = document.getElementById("progress-indicator");
    const claimButton = document.getElementById("claim-button");

    setTimeout(() => {
        progressFill.style.width = `${positionPercentage}%`;
        progressIndicator.style.left = `${positionPercentage}%`;

        // Tampilkan tombol klaim jika mencapai level baru
        if (currentLevel > 0 && userPoints >= levelThresholds[currentLevel]) {
            claimButton.classList.remove("hidden");
            setTimeout(() => {
                claimButton.classList.remove("opacity-0");
            }, 50);
        }
    }, 100);
}
