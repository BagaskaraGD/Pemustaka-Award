<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Gamified Table</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-purple-100 to-blue-100 min-h-screen p-6 flex items-center justify-center">

  <div class="w-full max-w-6xl bg-white shadow-2xl rounded-3xl p-8 space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-center">
      <h2 class="text-3xl font-bold text-gray-800">🏆 Leaderboard Anggota Aktif</h2>
      <input
        type="text"
        id="searchInput"
        placeholder="🔍 Cari nama..."
        class="mt-4 sm:mt-0 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500 rounded-full px-6 py-2 w-full sm:w-80"
        oninput="filterTable()"
      />
    </div>

    <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm">
      <table class="min-w-full text-sm text-gray-700">
        <thead class="bg-purple-600 text-white text-sm uppercase tracking-wider">
          <tr>
            <th class="px-6 py-4 text-left">👤 Nama</th>
            <th class="px-6 py-4 text-left">🎖️ Level</th>
            <th class="px-6 py-4 text-left">🔢 XP</th>
            <th class="px-6 py-4 text-left">📈 Progress</th>
            <th class="px-6 py-4 text-left">🎗️ Badge</th>
          </tr>
        </thead>
        <tbody id="dataTable" class="bg-white divide-y divide-gray-100">
          <tr class="hover:bg-purple-50 transition">
            <td class="px-6 py-4 font-medium">Andi Wijaya</td>
            <td class="px-6 py-4 font-bold text-purple-700">Level 5</td>
            <td class="px-6 py-4">480 / 500</td>
            <td class="px-6 py-4">
              <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-purple-500 h-2.5 rounded-full" style="width: 96%"></div>
              </div>
            </td>
            <td class="px-6 py-4">
              <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-yellow-200 text-yellow-700">🔥 MVP</span>
            </td>
          </tr>
          <tr class="hover:bg-purple-50 transition">
            <td class="px-6 py-4 font-medium">Rina Kartika</td>
            <td class="px-6 py-4 font-bold text-purple-700">Level 3</td>
            <td class="px-6 py-4">250 / 300</td>
            <td class="px-6 py-4">
              <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-blue-400 h-2.5 rounded-full" style="width: 83%"></div>
              </div>
            </td>
            <td class="px-6 py-4">
              <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-blue-200 text-blue-800">🎨 Kreator</span>
            </td>
          </tr>
          <tr class="hover:bg-purple-50 transition">
            <td class="px-6 py-4 font-medium">Budi Santoso</td>
            <td class="px-6 py-4 font-bold text-purple-700">Level 2</td>
            <td class="px-6 py-4">130 / 200</td>
            <td class="px-6 py-4">
              <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-green-400 h-2.5 rounded-full" style="width: 65%"></div>
              </div>
            </td>
            <td class="px-6 py-4">
              <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-200 text-green-800">💡 Starter</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    function filterTable() {
      const input = document.getElementById("searchInput").value.toLowerCase();
      const rows = document.querySelectorAll("#dataTable tr");
      console.log("row: ",rows);

      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(input) ? "" : "none";
      });
    }
  </script>

</body>
</html>
