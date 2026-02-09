<!doctype html>
<html lang="id" class="h-full">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buat Absensi - SIAKAD</title>
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
    // ==================== BUAT ABSENSI ====================

    function renderBuatAbsensi() {
        const dsn = currentUser;
        const jadwalDsn = jadwalData.filter(j => j.dosen === dsn.nama);

        return `
            ${renderHeader('Buat Absensi')}
            <div class="max-w-6xl mx-auto p-6">
                <div class="mb-4">
                    <button onclick="window.location.href='dashboard-dosen.php?user=${encodeURIComponent(JSON.stringify(currentUser))}&role=${currentRole}'"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                        ← Kembali
                    </button>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Pilih Mata Kuliah</h3>
                    <select id="selectedJadwal" onchange="loadMahasiswaForAbsensi()"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600">
                        <option value="">-- Pilih Mata Kuliah --</option>
                        ${jadwalDsn.map(j => `
                            <option value="${j.id}">${j.mataKuliah} - ${j.hari} ${j.jam}</option>
                        `).join('')}
                    </select>
                </div>

                <div id="mahasiswaList" class="hidden">
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Daftar Mahasiswa</h3>
                        <div id="mahasiswaContent"></div>
                        <button onclick="simpanAbsensi()"
                            class="mt-6 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                            Simpan Absensi
                        </button>
                        <div id="saveMessage" class="mt-4 hidden"></div>
                    </div>
                </div>
            </div>
        `;
    }

    function loadMahasiswaForAbsensi() {
        const jadwalId = document.getElementById('selectedJadwal').value;

        if (!jadwalId) {
            document.getElementById('mahasiswaList').classList.add('hidden');
            return;
        }

        const jadwal = jadwalData.find(j => j.id === jadwalId);
        const mahasiswaList = usersData.mahasiswa.filter(m => m.prodi === jadwal.prodi);

        const content = mahasiswaList.map(m => `
            <div class="flex items-center justify-between p-4 border-b border-gray-200 hover:bg-gray-50">
                <div>
                    <div class="font-semibold text-gray-800">${m.nama}</div>
                    <div class="text-sm text-gray-600">${m.nim}</div>
                </div>
                <div class="flex items-center space-x-4">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="status-${m.nim}" value="Hadir" checked
                            class="w-4 h-4 text-green-600 focus:ring-green-500">
                        <span class="text-sm text-gray-700">Hadir</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="status-${m.nim}" value="Tidak Hadir"
                            class="w-4 h-4 text-red-600 focus:ring-red-500">
                        <span class="text-sm text-gray-700">Tidak Hadir</span>
                    </label>
                </div>
            </div>
        `).join('');

        document.getElementById('mahasiswaContent').innerHTML = content;
        document.getElementById('mahasiswaList').classList.remove('hidden');
    }

    async function simpanAbsensi() {
        const jadwalId = document.getElementById('selectedJadwal').value;
        const jadwal = jadwalData.find(j => j.id === jadwalId);
        const mahasiswaList = usersData.mahasiswa.filter(m => m.prodi === jadwal.prodi);
        const today = new Date().toISOString().split('T')[0];

        try {
            // Prepare bulk absensi data
            const absensiData = mahasiswaList.map(m => {
                const status = document.querySelector(`input[name="status-${m.nim}"]:checked`).value;
                return {
                    jadwal_id: jadwalId,
                    nim: m.nim,
                    nama: m.nama,
                    tanggal: today,
                    status: status
                };
            });

            // Send bulk absensi via API
            await apiCall('absensi.php', {
                method: 'POST',
                body: JSON.stringify(absensiData)
            });

            const messageDiv = document.getElementById('saveMessage');
            messageDiv.className = 'bg-green-50 border-l-4 border-green-500 p-4 rounded';
            messageDiv.innerHTML = '<p class="text-green-700 font-semibold">Absensi berhasil disimpan!</p>';
            messageDiv.classList.remove('hidden');

            setTimeout(() => {
                window.location.href = `dashboard-dosen.php?user=${encodeURIComponent(JSON.stringify(currentUser))}&role=${currentRole}`;
            }, 1500);
        } catch (error) {
            const messageDiv = document.getElementById('saveMessage');
            messageDiv.className = 'bg-red-50 border-l-4 border-red-500 p-4 rounded';
            messageDiv.innerHTML = '<p class="text-red-700 font-semibold">Error: ' + error.message + '</p>';
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
        document.getElementById('content').innerHTML = renderBuatAbsensi();
    }

    init();
  </script>
 </body>
</html>
