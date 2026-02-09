<!doctype html>
<html lang="id" class="h-full">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jadwal Mengajar - SIAKAD</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="/_sdk/element_sdk.js"></script>
  <style>
        body {
            box-sizing: border-box;
        }
    </style>
  <style>@view-transition { navigation: auto; }</style>
  <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
  <script src="common.js"></script>
 </head>
 <body class="h-full w-full bg-gradient-to-br from-blue-50 to-blue-100">
  <div id="app" class="h-full w-full overflow-auto">
    <div id="content"></div>
  </div>

  <script>
    // ==================== JADWAL DOSEN ====================

    function renderJadwalDosen() {
        const dsn = currentUser;
        const jadwalDsn = jadwalData.filter(j => j.dosen === dsn.nama);

        return `
            ${renderHeader('Jadwal Mengajar')}
            <div class="max-w-6xl mx-auto p-6">
                <div class="mb-4">
                    <button onclick="window.location.href='dashboard-dosen.php?user=${encodeURIComponent(JSON.stringify(currentUser))}&role=${currentRole}'"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                        ← Kembali
                    </button>
                </div>

                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-blue-600 text-white">
                                <tr>
                                    <th class="px-6 py-4 text-left font-semibold">Mata Kuliah</th>
                                    <th class="px-6 py-4 text-left font-semibold">Hari</th>
                                    <th class="px-6 py-4 text-left font-semibold">Jam</th>
                                    <th class="px-6 py-4 text-left font-semibold">Program Studi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                ${jadwalDsn.map(j => `
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-gray-800">${j.mataKuliah}</td>
                                        <td class="px-6 py-4 text-gray-600">${j.hari}</td>
                                        <td class="px-6 py-4 text-gray-600">${j.jam}</td>
                                        <td class="px-6 py-4 text-gray-600">${j.prodi}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        `;
    }

    // ==================== INISIALISASI ====================

    async function init() {
        // Check if user is logged in
        const urlParams = new URLSearchParams(window.location.search);
        const userData = urlParams.get('user');
        const roleData = urlParams.get('role');

        if (!userData || !roleData) {
            window.location.href = 'login.php';
            return;
        }

        currentUser = JSON.parse(decodeURIComponent(userData));
        currentRole = roleData;

        await loadInitialData();
        document.getElementById('content').innerHTML = renderJadwalDosen();
    }

    init();
  </script>
 </body>
</html>
