let activeRewardsData = [];
let currentPeriodeId = null;
let userProfileData = null;
const apiBaseUrl = document
    .querySelector('meta[name="api-base-url"]')
    .getAttribute("content");
const ICONS_HTML = {
    locked: '<i class="fas fa-lock"></i>',
    unlocked: '<i class="fas fa-unlock-alt"></i>',
    claimed: '<i class="fas fa-check"></i>', // BERUBAH: dari fa-check-circle menjadi fa-check
    slotsFull: '<i class="fas fa-times"></i>', // BERUBAH: dari fa-times-circle menjadi fa-times
};

document.addEventListener("DOMContentLoaded", async function () {
    console.log("DOM Loaded. Starting profile user script.");
    await fetchInitialData();
    attachClaimButtonListeners();
});

async function fetchInitialData() {
    try {
        if (typeof apiBaseUrl === "undefined") {
            console.error("apiBaseUrl is not defined!");
            activeRewardsData = []; // Inisialisasi jika error
            return;
        }
        const rewardsResponse = await fetch(
            `${apiBaseUrl}/penerima-reward/rewards/active?id_civitas=${idCivitas}`
        );
        console.log("Fetching active rewards from:", rewardsResponse.url);
        if (!rewardsResponse.ok) {
            console.error(
                `Error fetching active rewards: ${
                    rewardsResponse.status
                }, ${await rewardsResponse.text()}`
            );
            activeRewardsData = [];
        } else {
            const rewardsResult = await rewardsResponse.json();
            if (rewardsResult.success && rewardsResult.data) {
                activeRewardsData = rewardsResult.data;
                currentPeriodeId = rewardsResult.current_periode_id;
                console.log("Active Rewards Data:", activeRewardsData);
                console.log("Current Periode ID:", currentPeriodeId);
            } else {
                console.error(
                    "Failed to load active rewards data:",
                    rewardsResult.message || "No data returned"
                );
                activeRewardsData = [];
            }
        }
    } catch (error) {
        console.error("Error fetching active rewards:", error);
        activeRewardsData = [];
    }

    try {
        if (typeof idCivitas === "undefined" || !idCivitas) {
            console.error("idCivitas is not defined or empty!");
            updateUIFallback();
            return;
        }
        const endpoint = `${apiBaseUrl}/myrank/mhs/${idCivitas}`;
        const response = await fetch(endpoint);

        if (!response.ok) {
            console.error(
                `HTTP error! Status: ${
                    response.status
                }, ${await response.text()}`
            );
            updateUIFallback();
            return;
        }
        userProfileData = await response.json();
        console.log("API Response (User Rank):", userProfileData);

        if (!userProfileData.data) {
            console.error(
                "API response (User Rank) does not contain 'data'.",
                userProfileData
            );
            updateUIFallback();
            return;
        }
        displayUserProfile(userProfileData.data);
        updateProgressAndClaimStates(
            userProfileData.data.total_rekap_poin || 0
        );
    } catch (error) {
        console.error("Gagal mengambil atau memproses data user:", error);
        updateUIFallback();
    }
}

function updateUIFallback() {
    if (document.getElementById("user"))
        document.getElementById("user").textContent = "Error";
    if (document.getElementById("peringkat"))
        document.getElementById("peringkat").textContent = "-";
    if (document.getElementById("poin"))
        document.getElementById("poin").textContent = "0";
    updateProgressAndClaimStates(0);
}

function displayUserProfile(rankData) {
    const userPoints = parseInt(rankData.total_rekap_poin, 10) || 0;
    const nim = rankData.nim || "N/A";
    const peringkat = rankData.peringkat || "-";
    if (document.getElementById("user"))
        document.getElementById("user").textContent = nim;
    if (document.getElementById("peringkat"))
        document.getElementById("peringkat").textContent = peringkat;
    if (document.getElementById("poin"))
        document.getElementById("poin").textContent = userPoints;
}

function attachClaimButtonListeners() {
    document.querySelectorAll(".claim-reward-button").forEach((button) => {
        button.addEventListener("click", function () {
            const level = parseInt(this.getAttribute("data-level"));
            handleClaimReward(level);
        });
    });
}

function updateProgressAndClaimStates(userPointsStr) {
    const userPoints = parseInt(userPointsStr, 10) || 0;
    console.log("Updating progress and claim states with points:", userPoints);

    const levelElements = document.querySelectorAll(".level-threshold");
    if (levelElements.length < 4) {
        console.error("Not all level threshold elements found.");
        return;
    }
    const levelThresholds = {
        0: parseInt(levelElements[0]?.textContent) || 0,
        1: parseInt(levelElements[1]?.textContent) || 0,
        2: parseInt(levelElements[2]?.textContent) || 0,
        3: parseInt(levelElements[3]?.textContent) || 0,
    };

    let currentLevel = 0;
    let positionPercentage = 0;

    if (
        levelThresholds[1] === 0 &&
        levelThresholds[2] === 0 &&
        levelThresholds[3] === 0
    ) {
        if (userPoints > 0) currentLevel = 3;
        positionPercentage = userPoints > 0 ? 100 : 0;
    } else if (userPoints >= levelThresholds[3] && levelThresholds[3] > 0) {
        currentLevel = 3;
        positionPercentage = 100;
    } else if (userPoints >= levelThresholds[2] && levelThresholds[2] > 0) {
        currentLevel = 2;
        const range = levelThresholds[3] - levelThresholds[2];
        const progress =
            range > 0 ? (userPoints - levelThresholds[2]) / range : 0;
        positionPercentage = 66.66 + progress * 33.33;
    } else if (userPoints >= levelThresholds[1] && levelThresholds[1] > 0) {
        currentLevel = 1;
        const range = levelThresholds[2] - levelThresholds[1];
        const progress =
            range > 0 ? (userPoints - levelThresholds[1]) / range : 0;
        positionPercentage = 33.33 + progress * 33.33;
    } else {
        currentLevel = 0;
        const range = levelThresholds[1] > 0 ? levelThresholds[1] : 1;
        const progress = levelThresholds[1] > 0 ? userPoints / range : 0;
        positionPercentage = progress * 33.33;
    }
    positionPercentage = Math.max(0, Math.min(100, positionPercentage));

    if (document.getElementById("level-text")) {
        document.getElementById(
            "level-text"
        ).textContent = `LEVEL ${currentLevel}`;
    }
    const progressFill = document.getElementById("progress-fill");
    const progressIndicator = document.getElementById("progress-indicator");
    if (progressFill) progressFill.style.width = `${positionPercentage}%`;
    if (progressIndicator)
        progressIndicator.style.left = `${positionPercentage}%`;

    [1, 2, 3].forEach((level) => {
        const markerIconEl = document.getElementById(`icon-marker-lvl${level}`);
        const claimButtonEl = document.getElementById(`claim-button${level}`);
        // const claimButtonWrapperEl = document.getElementById(`claim-button-wrapper-lvl${level}`); // Tidak perlu wrapper lagi untuk show/hide button

        if (!markerIconEl || !claimButtonEl) {
            console.warn(
                `UI elements for level ${level} marker/button not found.`
            );
            return;
        }

        claimButtonEl.style.display = "none"; // Sembunyikan tombol klaim secara default
        markerIconEl.innerHTML = ""; // Kosongkan ikon marker

        const numCurrentPeriodeId = parseInt(currentPeriodeId);
        const rewardDetails = activeRewardsData.find(
            (r) =>
                parseInt(r.level_reward) === level &&
                parseInt(r.id_periode) === numCurrentPeriodeId
        );

        if (userPoints < levelThresholds[level]) {
            markerIconEl.innerHTML = ICONS_HTML.locked;
        } else {
            if (rewardDetails) {
                if (rewardDetails.sudah_diklaim_user) {
                    markerIconEl.innerHTML = ICONS_HTML.claimed;
                } else {
                    if (
                        parseInt(rewardDetails.claimed_slots) >=
                        parseInt(rewardDetails.slot_reward)
                    ) {
                        markerIconEl.innerHTML = ICONS_HTML.slotsFull;
                    } else {
                        markerIconEl.innerHTML = ICONS_HTML.unlocked; // Gembok terbuka di marker
                        claimButtonEl.style.display = "inline-block"; // Tampilkan tombol klaim di bawah
                    }
                }
            } else {
                markerIconEl.innerHTML = ICONS_HTML.locked;
                console.warn(
                    `No reward configured for Lvl ${level} in Period ${numCurrentPeriodeId}`
                );
            }
        }
    });
}

async function handleClaimReward(level) {
    console.log(`Attempting to claim reward for level ${level}`);
    if (!idCivitas) {
        showClaimNotificationModal(
            false,
            "Gagal Klaim",
            "ID Civitas tidak ditemukan. Silakan login ulang."
        );
        return;
    }
    const numCurrentPeriodeId = parseInt(currentPeriodeId);
    if (isNaN(numCurrentPeriodeId)) {
        showClaimNotificationModal(
            false,
            "Gagal Klaim",
            "Periode aktif tidak dapat ditentukan."
        );
        return;
    }

    const rewardToClaim = activeRewardsData.find(
        (reward) =>
            parseInt(reward.level_reward) === level &&
            parseInt(reward.id_periode) === numCurrentPeriodeId
    );

    if (!rewardToClaim) {
        showClaimNotificationModal(
            false,
            "Gagal Klaim",
            `Reward untuk Level ${level} (Periode ${numCurrentPeriodeId}) tidak tersedia.`
        );
        return;
    }

    const payload = {
        id_reward: rewardToClaim.id_reward,
        id_civitas: idCivitas,
    };

    console.log("Sending payload to /penerima-reward:", payload);
    try {
        const response = await fetch(`${apiBaseUrl}/penerima-reward`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    ?.getAttribute("content"),
                Accept: "application/json",
            },
            body: JSON.stringify(payload),
        });

        const responseText = await response.text();
        let result;
        try {
            result = JSON.parse(responseText);
        } catch (e) {
            console.error("Failed to parse JSON response:", responseText, e);
            showClaimNotificationModal(
                false,
                "Klaim Gagal",
                "Respons server tidak valid."
            );
            return;
        }
        console.log("Parsed API result:", result);

        // ... di dalam try block ...
        if (response.ok && result.success) {
            // Buat pesan baru dengan nama reward dari variabel rewardToClaim
            const successMessage = `Anda berhasil mendapatkan: ${rewardToClaim.bentuk_reward}. ${result.message}`;

            showClaimNotificationModal(true, "Klaim Berhasil!", successMessage); // <--- Menampilkan pesan yang sudah dimodifikasi

            await fetchInitialData();
        } else {
            // ...
            showClaimNotificationModal(
                false,
                "Klaim Gagal",
                result.message || `Terjadi kesalahan: ${response.status}`
            );
        }
    } catch (error) {
        console.error("Error claiming reward:", error);
        showClaimNotificationModal(
            false,
            "Klaim Gagal",
            "Terjadi kesalahan jaringan atau respons tidak valid."
        );
    }
}

function showClaimNotificationModal(isSuccess, title, message) {
    const modal = document.getElementById("claimNotifModal");
    const modalContent = modal.querySelector(".bg-white");
    const img = document.getElementById("claimNotifImage");
    const titleEl = document.getElementById("claimNotifTitle");
    const messageEl = document.getElementById("claimNotifMessage");

    if (!modal || !modalContent || !img || !titleEl || !messageEl) {
        console.error("Modal elements for claim notification not found!");
        return;
    }

    titleEl.textContent = title;
    messageEl.textContent = message;
    const baseUrl = document.body.getAttribute("data-base-url") || "";

    if (isSuccess) {
        img.src = `${baseUrl}/assets/images/bag.png`;
        titleEl.className = "text-2xl font-bold mb-3 text-green-600";
    } else {
        img.src = `${baseUrl}/assets/images/failed.jpg`;
        titleEl.className = "text-2xl font-bold mb-3 text-red-600";
    }

    modal.classList.remove("opacity-0", "pointer-events-none", "hidden");
    requestAnimationFrame(() => {
        modalContent.classList.remove("scale-95", "opacity-0");
        modalContent.classList.add("scale-100", "opacity-100");
    });
}

function closeClaimNotifModal() {
    const modal = document.getElementById("claimNotifModal");
    const modalContent = modal.querySelector(".bg-white");
    if (!modal || !modalContent) return;

    modalContent.classList.add("scale-95", "opacity-0");
    modalContent.classList.remove("scale-100", "opacity-100");
    setTimeout(() => {
        modal.classList.add("opacity-0", "pointer-events-none", "hidden");
    }, 300);
}
