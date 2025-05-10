document.addEventListener('DOMContentLoaded', function() {
    fetch('/periode/dropdown')
        .then(response => response.json())
        .then(data => {
            const periodeList = document.getElementById('periodeList');
            periodeList.innerHTML = ''; // kosongkan isi sebelumnya

            data.forEach(item => {
                const li = document.createElement('li');
                li.innerHTML = `
                    <a href="#"
                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                        ${item.nama_periode}
                    </a>
                `;
                periodeList.appendChild(li);
            });
        })
        .catch(error => {
            console.error('Error fetching periode:', error);
        });
});