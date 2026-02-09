<!doctype html>
<html lang="id" class="h-full">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Absensi - SIAKAD</title>
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
    // ==================== KELOLA ABSENSI ====================

    function renderKelolaAbsensi() {
        return `
            ${renderHeader('Kelola Absensi')}
            <div class="max-w-6xl mx-auto p-6">
                <div class="mb-4">
                    <button onclick="window.location.href='dashboard-akademik.php?user=${encodeURIComponent(JSON.stringify(currentUser))}&role=${currentRole}'"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                        ← Kembali
                    </button>
                </div>

                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 bg-blue-600 text-white">
                        <h3 class="text-xl font-bold">Data Absensi Mahasiswa</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">NIM</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Nama</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Mata Kuliah</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Tanggal</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                ${absensiData.map(a => {
                                    const jadwal = jadwalData.find(j => j.id === a.jadwalId);
                                    return `
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 text-gray-800">${a.nim}</td>
                                            <td class="px-6 py-4 text-gray-600">${a.nama}</td>
                                            <td class="px-6 py-4 text-gray-600">${jadwal ? jadwal.mataKuliah : '-'}</td>
                                            <td class="px-6 py-4 text-gray-600">${a.tanggal}</td>
                                            <td class="px-6 py-4">
                                                <span class="px-3 py-1 rounded-full text-sm font-semibold ${
                                                    a.status === 'Hadir' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'
                                                }">
                                                    ${a.status}
                                                </span>
                                            </td>
                                        </tr>
                                    `;
                                }).join('')}
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
        document.getElementById('content').innerHTML = renderKelolaAbsensi();
    }

    init();
  </script>
 </body>
</html>
