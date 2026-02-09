<!doctype html>
<html lang="id" class="h-full">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Jadwal - SIAKAD</title>
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
    // ==================== KELOLA JADWAL ====================

    let editingJadwal = null;

    function renderKelolaJadwal() {
        return `
            ${renderHeader('Kelola Jadwal')}
            <div class="max-w-6xl mx-auto p-6">
                <div class="mb-4">
                    <button onclick="window.location.href='dashboard-akademik.php?user=${encodeURIComponent(JSON.stringify(currentUser))}&role=${currentRole}'"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                        ← Kembali
                    </button>
                </div>

                <!-- Form Tambah/Edit Jadwal -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4" id="formTitle">
                        ${editingJadwal ? 'Edit Jadwal' : 'Tambah Jadwal Baru'}
                    </h3>
                    <form id="jadwalForm" onsubmit="handleSaveJadwal(event)" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="mataKuliah" class="block text-sm font-semibold text-gray-700 mb-2">Mata Kuliah</label>
                            <input type="text" id="mataKuliah" required
                                value="${editingJadwal ? editingJadwal.mataKuliah : ''}"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600">
                        </div>
                        <div>
                            <label for="dosen" class="block text-sm font-semibold text-gray-700 mb-2">Dosen</label>
                            <input type="text" id="dosen" required
                                value="${editingJadwal ? editingJadwal.dosen : ''}"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600">
                        </div>
                        <div>
                            <label for="hari" class="block text-sm font-semibold text-gray-700 mb-2">Hari</label>
                            <select id="hari" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 bg-white">
                                <option value="">Pilih Hari</option>
                                <option value="Senin" ${editingJadwal && editingJadwal.hari === 'Senin' ? 'selected' : ''}>Senin</option>
                                <option value="Selasa" ${editingJadwal && editingJadwal.hari === 'Selasa' ? 'selected' : ''}>Selasa</option>
                                <option value="Rabu" ${editingJadwal && editingJadwal.hari === 'Rabu' ? 'selected' : ''}>Rabu</option>
                                <option value="Kamis" ${editingJadwal && editingJadwal.hari === 'Kamis' ? 'selected' : ''}>Kamis</option>
                                <option value="Jumat" ${editingJadwal && editingJadwal.hari === 'Jumat' ? 'selected' : ''}>Jumat</option>
                            </select>
                        </div>
                        <div>
                            <label for="jam" class="block text-sm font-semibold text-gray-700 mb-2">Jam</label>
                            <input type="text" id="jam" required placeholder="08:00 - 10:00"
                                value="${editingJadwal ? editingJadwal.jam : ''}"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600">
                        </div>
                        <div>
                            <label for="prodi" class="block text-sm font-semibold text-gray-700 mb-2">Program Studi</label>
                            <input type="text" id="prodi" required
                                value="${editingJadwal ? editingJadwal.prodi : ''}"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600">
                        </div>
                        <div>
                            <label for="fakultas" class="block text-sm font-semibold text-gray-700 mb-2">Fakultas</label>
                            <input type="text" id="fakultas" required
                                value="${editingJadwal ? editingJadwal.fakultas : ''}"
                                class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600">
                        </div>
                        <div class="md:col-span-2 flex space-x-3">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors">
                                ${editingJadwal ? 'Update' : 'Simpan'}
                            </button>
                            ${editingJadwal ? `
                                <button type="button" onclick="cancelEdit()"
                                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-semibold transition-colors">
                                    Batal
                                </button>
                            ` : ''}
                        </div>
                    </form>
                </div>

                <!-- Daftar Jadwal -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 bg-blue-600 text-white">
                        <h3 class="text-xl font-bold">Daftar Jadwal</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Mata Kuliah</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Dosen</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Hari</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Jam</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Prodi</th>
                                    <th class="px-6 py-4 text-left font-semibold text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                ${jadwalData.map(j => `
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-gray-800">${j.mataKuliah}</td>
                                        <td class="px-6 py-4 text-gray-600">${j.dosen}</td>
                                        <td class="px-6 py-4 text-gray-600">${j.hari}</td>
                                        <td class="px-6 py-4 text-gray-600">${j.jam}</td>
                                        <td class="px-6 py-4 text-gray-600">${j.prodi}</td>
                                        <td class="px-6 py-4">
                                            <button onclick="editJadwal('${j.id}')"
                                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm mr-2 transition-colors">
                                                Edit
                                            </button>
                                            <button onclick="deleteJadwal('${j.id}')"
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

    async function handleSaveJadwal(event) {
        event.preventDefault();

        const formData = {
            mata_kuliah: document.getElementById('mataKuliah').value,
            dosen: document.getElementById('dosen').value,
            hari: document.getElementById('hari').value,
            jam: document.getElementById('jam').value,
            prodi: document.getElementById('prodi').value,
            fakultas: document.getElementById('fakultas').value
        };

        try {
            if (editingJadwal) {
                // Update jadwal via API
                await apiCall('jadwal.php', {
                    method: 'PUT',
                    body: JSON.stringify({ id: editingJadwal.id, ...formData })
                });
                editingJadwal = null;
            } else {
                // Tambah jadwal baru via API
                await apiCall('jadwal.php', {
                    method: 'POST',
                    body: JSON.stringify(formData)
                });
            }

            // Reload jadwal data
            const jadwalResponse = await apiCall('jadwal.php');
            jadwalData = jadwalResponse.data || [];

            document.getElementById('content').innerHTML = renderKelolaJadwal();
        } catch (error) {
            alert('Error: ' + error.message);
        }
    }

    function editJadwal(id) {
        editingJadwal = jadwalData.find(j => j.id === id);
        document.getElementById('content').innerHTML = renderKelolaJadwal();
    }

    async function deleteJadwal(id) {
        // Konfirmasi hapus dengan cara two-step
        const button = event.target;

        if (button.textContent === 'Hapus') {
            button.textContent = 'Yakin?';
            button.className = 'bg-orange-500 hover:bg-orange-600 text-white px-3 py-1 rounded text-sm transition-colors';

            setTimeout(() => {
                button.textContent = 'Hapus';
                button.className = 'bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition-colors';
            }, 3000);
        } else {
            try {
                await apiCall(`jadwal.php?id=${id}`, {
                    method: 'DELETE'
                });

                // Reload jadwal data
                const jadwalResponse = await apiCall('jadwal.php');
                jadwalData = jadwalResponse.data || [];

                document.getElementById('content').innerHTML = renderKelolaJadwal();
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }
    }

    function cancelEdit() {
        editingJadwal = null;
        document.getElementById('content').innerHTML = renderKelolaJadwal();
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
        document.getElementById('content').innerHTML = renderKelolaJadwal();
    }

    init();
  </script>
 </body>
</html>
