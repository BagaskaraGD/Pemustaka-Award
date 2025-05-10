document.addEventListener('DOMContentLoaded', function() {
    const apiUrl = `http://127.0.0.1:8000/api/challenge-count/${idCivitas}`;
    console.log("Endpoint yang dipanggil:", apiUrl);
    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            if (data.count !== undefined) {
                document.getElementById('challenge-count').textContent = data.count;
            }
        })
        .catch(error => {
            console.error('Error fetching challenge count:', error);
            // Biarkan tetap 0 jika error
        });
});