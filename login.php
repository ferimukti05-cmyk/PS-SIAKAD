<!doctype html>
<html lang="id" class="h-full">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistem Informasi Akademik dan Absensi - Login</title>
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
  <div class="min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">
        <div class="text-center mb-8">
            <div class="bg-blue-600 text-white w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">SIAKAD</h1>
            <p class="text-gray-600">Sistem Informasi Akademik & Absensi</p>
        </div>

        <form id="loginForm" onsubmit="handleLogin(event)" class="space-y-6">
            <div>
                <label for="userId" class="block text-sm font-semibold text-gray-700 mb-2">ID Pengguna</label>
                <input type="text" id="userId" required
                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 transition-colors"
                    placeholder="Masukkan ID Anda">
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                <input type="password" id="password" required
                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 transition-colors"
                    placeholder="Masukkan Password">
            </div>

            <div>
                <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">Role</label>
                <select id="role" required
                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-600 transition-colors bg-white">
                    <option value="">Pilih Role</option>
                    <option value="mahasiswa">Mahasiswa</option>
                    <option value="dosen">Dosen</option>
                    <option value="akademik">Akademik</option>
                </select>
            </div>

            <div id="loginError" class="hidden bg-red-50 border-l-4 border-red-500 p-4 rounded">
                <p class="text-red-700 text-sm"></p>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition-colors duration-200 shadow-lg hover:shadow-xl">
                Login
            </button>
        </form>

    </div>
  </div>
 </body>
</html>
