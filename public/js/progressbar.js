document.addEventListener("DOMContentLoaded", function () {
    // 1. Ambil poin user dari elemen profil
    const userPoints = parseInt(
        document.getElementById("poin").textContent.trim()
    );

    // 2. Ambil threshold level dari HTML
    const levelElements = document.querySelectorAll(".level-threshold");
    const levelThresholds = {
        0: parseInt(levelElements[0].textContent),
        1: parseInt(levelElements[1].textContent),
        2: parseInt(levelElements[2].textContent),
        3: parseInt(levelElements[3].textContent),
    };

    // 3. Hitung level dan progress saat ini
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

    // 4. Animasikan progress bar
    const progressFill = document.getElementById("progress-fill");
    const progressIndicator = document.getElementById("progress-indicator");
    const claimButton = document.getElementById("claim-button");

    setTimeout(() => {
        progressFill.style.width = `${positionPercentage}%`;
        progressIndicator.style.left = `${positionPercentage}%`;

        // 5. Tampilkan tombol klaim jika mencapai level baru
        if (currentLevel > 0 && userPoints >= levelThresholds[currentLevel]) {
            claimButton.classList.remove("hidden");
            setTimeout(() => {
                claimButton.classList.remove("opacity-0");
            }, 50);
        }
    }, 100);

    // 6. Fungsi untuk update progress jika poin bertambah
    window.updateProgress = function (newPoints) {
        // Implementasi update dinamis bisa ditambahkan di sini
        console.log("Update progress to:", newPoints);
    };
});
