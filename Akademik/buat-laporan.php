<!doctype html>
<html lang="id" class="h-full">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buat Laporan - SIAKAD</title>
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
    // ==================== BUAT LAPORAN ====================

    function renderBuatLaporan() {
        return `
            ${renderHeader('Buat Laporan')}
            <div class="max-w-6xl mx-auto p-6">
                <div class="mb-4">
                    <button onclick="window.location.href='dashboard-akademik.php?user=${encodeURIComponent(JSON.stringify(currentUser))}&role=${currentRole}'"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                        ← Kembali
                    </button>
                </div>

                <!-- Form Buat Laporan -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Form Buat Laporan</h3>
                    <form id="laporanForm" onsubmit="handleGenerateLaporan(event)" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Mata Kuliah -->
                        <div>
                            <label for="mataKuliahLaporan" class="block text-sm font-semibold text-gray-700 mb-2">Mata Kuliah</label>
                            <select id="mataKuliahLaporan" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 bg-white">
                                <option value="">-- Pilih Mata Kuliah --</option>
                                ${jadwalData.map(j => `<option value="${j.mataKuliah}">${j.mataKuliah}</option>`).join('')}
                            </select>
                        </div>

                        <!-- Prodi -->
                        <div>
                            <label for="prodiLaporan" class="block text-sm font-semibold text-gray-700 mb-2">Program Studi</label>
                            <select id="prodiLaporan" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 bg-white">
                                <option value="">-- Pilih Prodi --</option>
                                <option value="Teknik Informatika">Teknik Informatika</option>
                                <option value="Sistem Informasi">Sistem Informasi</option>
                                <option value="Teknik Elektro">Teknik Elektro</option>
                                <option value="Teknik Mesin">Teknik Mesin</option>
                            </select>
                        </div>

                        <!-- Fakultas -->
                        <div>
                            <label for="fakultasLaporan" class="block text-sm font-semibold text-gray-700 mb-2">Fakultas</label>
                            <select id="fakultasLaporan" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 bg-white">
                                <option value="">-- Pilih Fakultas --</option>
                                <option value="Fakultas Teknik">Fakultas Teknik</option>
                                <option value="Fakultas Ekonomi">Fakultas Ekonomi</option>
                                <option value="Fakultas Hukum">Fakultas Hukum</option>
                            </select>
                        </div>

                        <!-- Semester -->
                        <div>
                            <label for="semesterLaporan" class="block text-sm font-semibold text-gray-700 mb-2">Semester</label>
                            <select id="semesterLaporan" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 bg-white">
                                <option value="">-- Pilih Semester --</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                            </select>
                        </div>

                        <!-- Mahasiswa -->
                        <div>
                            <label for="mahasiswaLaporan" class="block text-sm font-semibold text-gray-700 mb-2">Nama Mahasiswa</label>
                            <select id="mahasiswaLaporan" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 bg-white">
                                <option value="">-- Pilih Mahasiswa --</option>
                                ${usersData.mahasiswa.map(m => `<option value="${m.nama}">${m.nama} (${m.nim})</option>`).join('')}
                            </select>
                        </div>

                        <!-- Dosen -->
                        <div>
                            <label for="dosenLaporan" class="block text-sm font-semibold text-gray-700 mb-2">Nama Dosen</label>
                            <select id="dosenLaporan" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 bg-white">
                                <option value="">-- Pilih Dosen --</option>
                                ${usersData.dosen.map(d => `<option value="${d.nama}">${d.nama}</option>`).join('')}
                            </select>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="md:col-span-2">
                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition-colors shadow-lg hover:shadow-xl">
                                Generate Laporan
                            </button>
                        </div>
                    </form>

                    <!-- Message Area -->
                    <div id="laporanMessage" class="mt-6 hidden"></div>
                </div>
            </div>
        `;
    }

    // Handler untuk generate laporan baru
    async function handleGenerateLaporan(event) {
        event.preventDefault();

        // Ambil data dari form
        const formData = {
            mata_kuliah: document.getElementById('mataKuliahLaporan').value,
            prodi: document.getElementById('prodiLaporan').value,
            fakultas: document.getElementById('fakultasLaporan').value,
            semester: document.getElementById('semesterLaporan').value,
            mahasiswa: document.getElementById('mahasiswaLaporan').value,
            dosen: document.getElementById('dosenLaporan').value,
            tanggal: new Date().toISOString().split('T')[0] // Tanggal hari ini
        };

        try {
            // Buat laporan baru via API
            const response = await apiCall('laporan.php', {
                method: 'POST',
                body: JSON.stringify(formData)
            });

            // Reload laporan data
            const laporanResponse = await apiCall('laporan.php');
            laporanData = laporanResponse.data || [];

            // Tampilkan pesan sukses
            const messageDiv = document.getElementById('laporanMessage');
            messageDiv.className = 'bg-green-50 border-l-4 border-green-500 p-4 rounded';
            messageDiv.innerHTML = `
                <p class="text-green-700 font-semibold mb-2">✓ Laporan berhasil dibuat!</p>
                <p class="text-green-600 text-sm">ID Laporan: ${response.id}</p>
            `;
            messageDiv.classList.remove('hidden');

            // Reset form
            document.getElementById('laporanForm').reset();

            // Auto redirect ke halaman lihat laporan setelah 2 detik
            setTimeout(() => {
                window.location.href = `lihat-laporan.php?user=${encodeURIComponent(JSON.stringify(currentUser))}&role=${currentRole}`;
            }, 2000);
        } catch (error) {
            const messageDiv = document.getElementById('laporanMessage');
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
        document.getElementById('content').innerHTML = renderBuatLaporan();
    }

    init();
  </script>
 </body>
</html>
