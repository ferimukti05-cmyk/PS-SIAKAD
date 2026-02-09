<!doctype html>
<html lang="id" class="h-full">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Akademik - SIAKAD</title>
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
    // ==================== DASHBOARD AKADEMIK ====================

    function renderDashboardAkademik() {
        const akd = currentUser;
        return `
            ${renderHeader('Dashboard Akademik')}
            <div class="max-w-6xl mx-auto p-6">
                <!-- Profil Akademik -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Profil Staff Akademik</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center space-x-3">
                            <span class="font-semibold text-gray-700 min-w-32">ID:</span>
                            <span class="text-gray-600">${akd.id}</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="font-semibold text-gray-700 min-w-32">Nama:</span>
                            <span class="text-gray-600">${akd.nama}</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="font-semibold text-gray-700 min-w-32">Fakultas:</span>
                            <span class="text-gray-600">${akd.fakultas}</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <span class="font-semibold text-gray-700 min-w-32">Jabatan:</span>
                            <span class="text-gray-600">${akd.jabatan}</span>
                        </div>
                    </div>
                </div>

                <!-- Menu Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div onclick="window.location.href='kelola-jadwal.php?user=${encodeURIComponent(JSON.stringify(currentUser))}&role=${currentRole}'"
                        class="bg-white rounded-xl shadow-lg p-6 cursor-pointer hover:shadow-2xl transition-all transform hover:-translate-y-1">
                        <div class="flex flex-col items-center text-center space-y-3">
                            <div class="bg-blue-100 p-4 rounded-full">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Kelola Jadwal</h3>
                                <p class="text-gray-600 text-sm">Tambah, edit, hapus jadwal</p>
                            </div>
                        </div>
                    </div>

                    <div onclick="window.location.href='kelola-absensi.php?user=${encodeURIComponent(JSON.stringify(currentUser))}&role=${currentRole}'"
                        class="bg-white rounded-xl shadow-lg p-6 cursor-pointer hover:shadow-2xl transition-all transform hover:-translate-y-1">
                        <div class="flex flex-col items-center text-center space-y-3">
                            <div class="bg-purple-100 p-4 rounded-full">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Kelola Absensi</h3>
                                <p class="text-gray-600 text-sm">Lihat data absensi</p>
                            </div>
                        </div>
                    </div>

                    <div onclick="window.location.href='buat-laporan.php?user=${encodeURIComponent(JSON.stringify(currentUser))}&role=${currentRole}'"
                        class="bg-white rounded-xl shadow-lg p-6 cursor-pointer hover:shadow-2xl transition-all transform hover:-translate-y-1">
                        <div class="flex flex-col items-center text-center space-y-3">
                            <div class="bg-orange-100 p-4 rounded-full">
                                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Buat Laporan</h3>
                                <p class="text-gray-600 text-sm">Generate laporan baru</p>
                            </div>
                        </div>
                    </div>

                    <div onclick="window.location.href='lihat-laporan.php?user=${encodeURIComponent(JSON.stringify(currentUser))}&role=${currentRole}'"
                        class="bg-white rounded-xl shadow-lg p-6 cursor-pointer hover:shadow-2xl transition-all transform hover:-translate-y-1">
                        <div class="flex flex-col items-center text-center space-y-3">
                            <div class="bg-green-100 p-4 rounded-full">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Lihat Laporan</h3>
                                <p class="text-gray-600 text-sm">Daftar semua laporan</p>
                            </div>
                        </div>
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
        document.getElementById('content').innerHTML = renderDashboardAkademik();
    }

    init();
  </script>
 </body>
</html>
