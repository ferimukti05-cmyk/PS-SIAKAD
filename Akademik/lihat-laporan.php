<!doctype html>
<html lang="id" class="h-full">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lihat Laporan - SIAKAD</title>
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
    // ==================== LIHAT LAPORAN ====================

    function renderLihatLaporan() {
        return `
            ${renderHeader('Lihat Laporan')}
            <div class="max-w-6xl mx-auto p-6">
                <div class="mb-4 flex justify-between items-center">
                    <button onclick="window.location.href='dashboard-akademik.php?user=${encodeURIComponent(JSON.stringify(currentUser))}&role=${currentRole}'"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                        ← Kembali
                    </button>
                    <button onclick="window.location.href='buat-laporan.php?user=${encodeURIComponent(JSON.stringify(currentUser))}&role=${currentRole}'"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        + Buat Laporan Baru
                    </button>
                </div>

                <!-- Tabel Laporan -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 bg-blue-600 text-white">
                        <h3 class="text-xl font-bold">Daftar Laporan</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">ID Laporan</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Mata Kuliah</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Prodi</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Fakultas</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Semester</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Mahasiswa</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Dosen</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Tanggal</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                ${laporanData.length === 0 ? `
                                    <tr>
                                        <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                                            Belum ada laporan. Silakan buat laporan baru.
                                        </td>
                                    </tr>
                                ` : laporanData.map(lap => `
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-gray-800 font-semibold">${lap.id}</td>
                                        <td class="px-6 py-4 text-gray-600">${lap.mataKuliah}</td>
                                        <td class="px-6 py-4 text-gray-600">${lap.prodi}</td>
                                        <td class="px-6 py-4 text-gray-600">${lap.fakultas}</td>
                                        <td class="px-6 py-4 text-gray-600">${lap.semester}</td>
                                        <td class="px-6 py-4 text-gray-600">${lap.mahasiswa}</td>
                                        <td class="px-6 py-4 text-gray-600">${lap.dosen}</td>
                                        <td class="px-6 py-4 text-gray-600">${lap.tanggal}</td>
                                        <td class="px-6 py-4">
                                            <button onclick="detailLaporan('${lap.id}')"
                                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm mr-2 transition-colors">
                                                Detail
                                            </button>
                                            <button onclick="deleteLaporan('${lap.id}')"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition-colors">
                                                Hapus
                                            </button>
                                        </td>
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
        document.getElementById('content').innerHTML = renderLihatLaporan();
    }

    init();
  </script>
 </body>
</html>
