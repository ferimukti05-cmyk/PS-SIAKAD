<!doctype html>
<html lang="id" class="h-full">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Absensi Mahasiswa - SIAKAD</title>
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
    // ==================== ABSEN MAHASISWA ====================

    function renderAbsenMahasiswa() {
        const mhs = currentUser;
        const absenMhs = absensiData.filter(a => a.nim === mhs.nim);

        return `
            ${renderHeader('Absensi Kehadiran')}
            <div class="max-w-6xl mx-auto p-6">
                <div class="mb-4">
                    <button onclick="window.location.href='dashboard-mahasiswa.php?user=${encodeURIComponent(JSON.stringify(currentUser))}&role=${currentRole}'"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                        ← Kembali
                    </button>
                </div>

                <!-- Tombol Absen -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Absen Hari Ini</h3>
                    <button onclick="handleAbsenMahasiswa()" id="absenBtn"
                        class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors shadow-lg">
                        Absen Sekarang
                    </button>
                    <div id="absenMessage" class="mt-4 hidden"></div>
                </div>

                <!-- Riwayat Absensi -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 bg-blue-600 text-white">
                        <h3 class="text-xl font-bold">Riwayat Kehadiran</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Tanggal</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Mata Kuliah</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                ${absenMhs.map(a => {
                                    const jadwal = jadwalData.find(j => j.id === a.jadwalId);
                                    return `
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 text-gray-800">${a.tanggal}</td>
                                            <td class="px-6 py-4 text-gray-600">${jadwal ? jadwal.mataKuliah : '-'}</td>
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

    async function handleAbsenMahasiswa() {
        const messageDiv = document.getElementById('absenMessage');
        const absenBtn = document.getElementById('absenBtn');
        const today = new Date().toISOString().split('T')[0];

        try {
            // Cek apakah sudah absen hari ini
            const existingAbsen = absensiData.find(a =>
                a.nim === currentUser.nim &&
                a.tanggal === today
            );

            if (existingAbsen) {
                messageDiv.className = 'mt-4 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded';
                messageDiv.innerHTML = '<p class="text-yellow-700 text-sm font-semibold">Anda sudah melakukan absensi hari ini!</p>';
                messageDiv.classList.remove('hidden');
                absenBtn.disabled = true;
                absenBtn.textContent = 'Sudah Absen';
                absenBtn.className = 'bg-gray-500 text-white px-8 py-3 rounded-lg font-semibold cursor-not-allowed';
            } else {
                // Tambah data absensi baru via API
                const response = await apiCall('absensi.php', {
                    method: 'POST',
                    body: JSON.stringify({
                        jadwal_id: 'J001', // Default jadwal, bisa diimprove
                        nim: currentUser.nim,
                        nama: currentUser.nama,
                        tanggal: today,
                        status: 'Hadir'
                    })
                });

                // Reload data absensi
                const absensiResponse = await apiCall('absensi.php');
                absensiData = absensiResponse.data || [];

                messageDiv.className = 'mt-4 bg-green-50 border-l-4 border-green-500 p-4 rounded';
                messageDiv.innerHTML = '<p class="text-green-700 text-sm font-semibold">Absensi berhasil! Status Anda: Hadir</p>';
                messageDiv.classList.remove('hidden');
                absenBtn.disabled = true;
                absenBtn.textContent = 'Sudah Absen';
                absenBtn.className = 'bg-gray-500 text-white px-8 py-3 rounded-lg font-semibold cursor-not-allowed';

                // Reload halaman setelah 1.5 detik
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            }
        } catch (error) {
            messageDiv.className = 'mt-4 bg-red-50 border-l-4 border-red-500 p-4 rounded';
            messageDiv.innerHTML = '<p class="text-red-700 text-sm font-semibold">Error: ' + error.message + '</p>';
            messageDiv.classList.remove('hidden');
        }
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
        document.getElementById('content').innerHTML = renderAbsenMahasiswa();
    }

    init();
  </script>
 </body>
</html>
