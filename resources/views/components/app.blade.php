<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'SIAKAD' }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans antialiased h-full">
        <div class="min-h-screen bg-slate-50 h-screen flex">
            <aside class="h-full w-20 flex flex-col space-y-10 items-center justify-center bg-sky-900 text-gray-800 fixed">
                @auth
                    @php
                        $role = Auth::user()->role;
                    @endphp

                    @if($role === 'admin')
                        {{-- Menu Dashboard --}}
                        <a href="{{ '/admin/dashboard' }}" class="{{ request()->is('admin/dashboard') ? 'bg-white text-gray-800' : 'text-white hover:bg-white hover:text-gray-800' }} rounded-md no-underline" aria-current="page" title="Dashboard" aria-label="Dashboard">
                            <div class="h-12 w-18 flex flex-col items-center justify-center rounded-lg transition duration-150 group">
                                <svg class="{{ request()->is('admin/dashboard') ? 'text-gray-800' : 'group-hover:text-gray-800' }} w-6 h-6 text-inherit transition" fill="currentColor" viewBox="0 0 1920 1920">
                                    <path d="M833.935 1063.327c28.913 170.315 64.038 348.198 83.464 384.79 27.557 51.84 92.047 71.944 144 44.387 51.84-27.558 71.717-92.273 44.16-144.113-19.426-36.593-146.937-165.46-271.624-285.064Zm-43.821-196.405c61.553 56.923 370.899 344.81 415.285 428.612 56.696 106.842 15.811 239.887-91.144 296.697-32.64 17.28-67.765 25.411-102.325 25.411-78.72 0-154.955-42.353-194.371-116.555-44.386-83.802-109.102-501.346-121.638-584.245-3.501-23.717 8.245-47.21 29.365-58.277 21.346-11.294 47.096-8.02 64.828 8.357ZM960.045 281.99c529.355 0 960 430.757 960 960 0 77.139-8.922 153.148-26.654 225.882l-10.39 43.144h-524.386v-112.942h434.258c9.487-50.71 14.231-103.115 14.231-156.084 0-467.125-380.047-847.06-847.059-847.06-467.125 0-847.059 379.935-847.059 847.06 0 52.97 4.744 105.374 14.118 156.084h487.454v112.942H36.977l-10.39-43.144C8.966 1395.137.044 1319.128.044 1241.99c0-529.243 430.645-960 960-960Zm542.547 390.686 79.85 79.85-112.716 112.715-79.85-79.85 112.716-112.715Zm-1085.184 0L530.123 785.39l-79.85 79.85L337.56 752.524l79.849-79.85Zm599.063-201.363v159.473H903.529V471.312h112.942Z" fill-rule="evenodd"></path>
                                </svg>
                                <span class="{{ request()->is('admin/dashboard') ? 'text-gray-800' : 'group-hover:text-gray-800' }} text-xs mt-1 text-inherit  transition">Dashboard</span>
                            </div>
                        </a>

                        {{-- Menu Admin Dropdown --}}
                        <div x-data="{ openDropdown: null }" class="w-full gap-10 flex flex-col items-center relative">
                            {{-- Dropdown: Master Data --}}
                            @php
                                $isMasterActive = request()->is('admin/dosen*') || request()->is('admin/mahasiswa*') || request()->is('admin/tahun-akademik*') || request()->is('admin/mata-kuliah*') || request()->is('admin/ruangan*');
                            @endphp
                            <div class="relative w-full flex flex-col items-center">
                                <button @click="openDropdown === 'master' ? openDropdown = null : openDropdown = 'master'" class="h-12 w-18 flex items-center justify-center rounded-lg {{ $isMasterActive ? 'bg-white text-gray-800' : 'text-white hover:bg-white hover:text-gray-800' }}">
                                    <div class="h-12 w-18 flex flex-col items-center justify-center rounded-lg transition duration-150 group">
                                        <svg class="w-6 h-6 group-hover:text-gray-800 transition {{ $isMasterActive ? 'text-gray-800' : 'group-hover:text-gray-800' }}" fill="currentColor" viewBox="0 0 1024 1024">
                                            <path d="M916.918857 496.566857H70.509714v330.532572a36.571429 36.571429 0 0 0 36.571429 36.571428h699.977143a36.571429 36.571429 0 1 1 0 73.142857H73.142857a73.142857 73.142857 0 0 1-73.142857-73.142857V94.281143a73.142857 73.142857 0 0 1 73.142857-73.142857h251.611429a73.142857 73.142857 0 0 1 52.077714 21.796571l111.908571 113.590857a36.571429 36.571429 0 0 0 26.038858 10.898286H914.285714a73.142857 73.142857 0 0 1 73.142857 73.142857v521.508572a35.254857 35.254857 0 0 1-70.509714 0v-265.508572z m0-73.142857v-146.285714a36.571429 36.571429 0 0 0-36.571428-36.571429H501.321143a73.142857 73.142857 0 0 1-52.150857-21.796571l-111.908572-113.590857a36.571429 36.571429 0 0 0-25.965714-10.898286H107.081143a36.571429 36.571429 0 0 0-36.571429 36.571428v292.571429h846.409143z" fill="evenodd"></path>
                                        </svg>
                                        <span class="text-xs mt-1 transition {{ $isMasterActive ? 'text-gray-800' : 'group-hover:text-gray-800' }}">Master</span>
                                    </div>
                                </button>
                                <div x-show="openDropdown === 'master'"
                                    x-transition
                                    @click.away="openDropdown = null"
                                    class="absolute left-full top-0 ml-2 w-48 bg-white text-gray-800 rounded-lg shadow-lg p-2 z-50 ">
                                    <span class="text-sm font-semibold block px-2 py-1 no-underline text-gray-800">Manajemen Data</span>
                                    <a href="{{ route('admin.dosen.index') }}" class="block px-4 py-1 hover:bg-sky-100 no-underline text-gray-800">Dosen</a>
                                    <a href="/admin/mahasiswa" class="block px-4 py-1 hover:bg-sky-100 no-underline text-gray-800">Mahasiswa</a>
                                    <a href="/admin/tahun-akademik" class="block px-4 py-1 hover:bg-sky-100 no-underline text-gray-800">Tahun Akademik</a>
                                    <a href="/admin/mata-kuliah" class="block px-4 py-1 hover:bg-sky-100 no-underline text-gray-800">Mata Kuliah</a>
                                    <a href="/admin/ruangan" class="block px-4 py-1 hover:bg-sky-100 no-underline text-gray-800">Ruangan</a>
                                </div>
                            </div>

                            @php
                                $isAkademikActive = request()->is('admin/jadwal-kuliah*') || request()->is('admin/krs*') || request()->is('admin/khs*');
                            @endphp
                            {{-- Dropdown: Akademik --}}
                            <div class="relative w-full flex flex-col items-center">
                                <button @click="openDropdown === 'akademik' ? openDropdown = null : openDropdown = 'akademik'" class="h-12 w-18 flex items-center justify-center rounded-lg {{ $isAkademikActive ? 'bg-white text-gray-800' : 'text-white hover:bg-white hover:text-gray-800' }}">
                                    <div class="flex flex-col items-center justify-center rounded-lg transition duration-150 group">
                                        <svg class="w-6 h-6 group-hover:text-gray-800 transition {{ $isAkademikActive ? 'text-gray-800' : 'group-hover:text-gray-800' }}" fill="currentColor" viewBox="0 0 1024 1024">
                                            <path d="M863.963429 136.045714h-185.051429v-61.732571a61.732571 61.732571 0 0 0-61.805714-61.732572H370.322286a61.732571 61.732571 0 0 0-61.732572 61.732572v61.732571H123.465143C55.296 136.045714 0 191.268571 0 259.437714v555.446857c0 68.169143 55.296 123.392 123.465143 123.392h740.498286c68.169143 0 123.465143-55.222857 123.465142-123.392V259.437714a123.392 123.392 0 0 0-123.465142-123.392z m-493.714286-30.866285c0-17.042286 13.824-30.866286 30.939428-30.866286h185.051429c17.042286 0 30.866286 13.897143 30.866286 30.866286v30.866285H370.322286v-30.866285z m555.446857 709.705142a61.805714 61.805714 0 0 1-61.805714 61.659429H123.465143a61.659429 61.659429 0 0 1-61.732572-61.659429V475.428571h312.905143c-2.779429 10.020571-4.169143 20.406857-4.388571 30.793143a123.465143 123.465143 0 0 0 246.857143 0 122.88 122.88 0 0 0-4.388572-30.866285h313.051429v339.382857h-0.073143z m-493.714286-308.589714c0-11.264 3.291429-21.723429 8.557715-30.866286h106.349714a60.928 60.928 0 0 1 8.557714 30.866286 61.732571 61.732571 0 0 1-123.465143 0z m493.714286-92.525714H61.659429v-154.331429c0-34.084571 27.574857-61.659429 61.732571-61.659428h740.498286c34.084571 0 61.805714 27.574857 61.805714 61.659428v154.331429z"></path>
                                        </svg>
                                        <span class="{{ $isAkademikActive ? 'text-gray-800' : 'group-hover:text-gray-800' }} text-xs mt-1 transition">Akademik</span>
                                    </div>
                                </button>
                                <div x-show="openDropdown === 'akademik'"
                                    x-transition
                                    @click.away="openDropdown = null"
                                    class="absolute left-full top-0 ml-2 w-52 bg-white text-gray-800 rounded-lg shadow-lg p-2 z-50">
                                    <span class="text-sm font-semibold block px-2 py-1 no-underline text-gray-800">Manajemen Akademik</span>
                                    <a href="/admin/jadwal-kuliah" class="block px-4 py-1 hover:bg-sky-100 no-underline text-gray-800">Jadwal Kuliah</a>
                                    <a href="/admin/krs" class="block px-4 py-1 hover:bg-sky-100 no-underline text-gray-800">KRS Mahasiswa</a>
                                    <a href="/admin/khs" class="block px-4 py-1 hover:bg-sky-100 no-underline text-gray-800">KHS / Nilai</a>
                                </div>
                            </div>

                            <a href="{{ '/admin/user' }}" class="{{ request()->is('admin/user') ? 'bg-white text-gray-800' : 'text-white hover:bg-white hover:text-gray-800' }} rounded-md no-underline" aria-current="page" title="Pengguna" aria-label="Pengguna">
                                <div class="h-12 w-18 flex flex-col items-center justify-center rounded-lg transition duration-150 group">
                                    <svg class="{{ request()->is('admin/user') ? 'text-gray-800' : 'group-hover:text-gray-800' }} w-6 h-6 text-inherit transition" fill="currentColor" viewBox="0 0 512 512">
                                        <path d="M256,265.308c73.252,0,132.644-59.391,132.644-132.654C388.644,59.412,329.252,0,256,0 c-73.262,0-132.643,59.412-132.643,132.654C123.357,205.917,182.738,265.308,256,265.308z"></path>
                                        <path class="st0" d="M425.874,393.104c-5.922-35.474-36-84.509-57.552-107.465c-5.829-6.212-15.948-3.628-19.504-1.427 c-27.04,16.672-58.782,26.399-92.819,26.399c-34.036,0-65.778-9.727-92.818-26.399c-3.555-2.201-13.675-4.785-19.505,1.427 c-21.55,22.956-51.628,71.991-57.551,107.465C71.573,480.444,164.877,512,256,512C347.123,512,440.427,480.444,425.874,393.104z" class="st0"></path>
                                    </svg>
                                    <span class="{{ request()->is('admin/user') ? 'text-gray-800' : 'group-hover:text-gray-800' }} text-xs mt-1 text-inherit  transition">Pengguna</span>
                                </div>
                            </a>
                        </div>

                    @elseif($role === 'dosen')
                        {{-- Menu Dashboard --}}
                        <a href="{{ '/dosen/dashboard' }}" class="{{ request()->is('dosen/dashboard') ? 'bg-white text-gray-800' : 'text-white hover:bg-white hover:text-gray-800' }} rounded-md no-underline" aria-current="page" title="Dashboard" aria-label="Dashboard">
                            <div class="h-12 w-18 flex flex-col items-center justify-center rounded-lg transition duration-150 group">
                                <svg class="{{ request()->is('dosen/dashboard') ? 'text-gray-800' : 'group-hover:text-gray-800' }} w-6 h-6 transition" fill="currentColor" viewBox="0 0 1920 1920">
                                    <path d="M833.935 1063.327c28.913 170.315 64.038 348.198 83.464 384.79 27.557 51.84 92.047 71.944 144 44.387 51.84-27.558 71.717-92.273 44.16-144.113-19.426-36.593-146.937-165.46-271.624-285.064Zm-43.821-196.405c61.553 56.923 370.899 344.81 415.285 428.612 56.696 106.842 15.811 239.887-91.144 296.697-32.64 17.28-67.765 25.411-102.325 25.411-78.72 0-154.955-42.353-194.371-116.555-44.386-83.802-109.102-501.346-121.638-584.245-3.501-23.717 8.245-47.21 29.365-58.277 21.346-11.294 47.096-8.02 64.828 8.357ZM960.045 281.99c529.355 0 960 430.757 960 960 0 77.139-8.922 153.148-26.654 225.882l-10.39 43.144h-524.386v-112.942h434.258c9.487-50.71 14.231-103.115 14.231-156.084 0-467.125-380.047-847.06-847.059-847.06-467.125 0-847.059 379.935-847.059 847.06 0 52.97 4.744 105.374 14.118 156.084h487.454v112.942H36.977l-10.39-43.144C8.966 1395.137.044 1319.128.044 1241.99c0-529.243 430.645-960 960-960Zm542.547 390.686 79.85 79.85-112.716 112.715-79.85-79.85 112.716-112.715Zm-1085.184 0L530.123 785.39l-79.85 79.85L337.56 752.524l79.849-79.85Zm599.063-201.363v159.473H903.529V471.312h112.942Z" fill-rule="evenodd"></path>
                                </svg>
                                <span class="{{ request()->is('dosen/dashboard') ? 'text-gray-800' : 'group-hover:text-gray-800' }} text-xs mt-1  transition">Dashboard</span>
                            </div>
                        </a>

                        <div x-data="{ openDropdown: null }" class="w-full gap-10 flex flex-col items-center relative">
                            {{-- Dropdown: Mahasiswa --}}
                            @php
                                $isMahasiswaActive = request()->is('dosen/mahasiswa*') || request()->is('dosen/absensi*') || request()->is('dosen/nilai*');
                            @endphp
                            <div class="relative w-full flex flex-col items-center">
                                <button @click="openDropdown === 'mahasiswa' ? openDropdown = null : openDropdown = 'mahasiswa'" class="h-12 w-18 flex items-center justify-center rounded-lg {{ $isMahasiswaActive ? 'bg-white text-gray-800' : 'text-white hover:bg-white hover:text-gray-800' }}">
                                    <div class="h-12 w-18 flex flex-col items-center justify-center rounded-lg transition duration-150 group">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 group-hover:text-gray-800 transition {{ $isMahasiswaActive ? 'text-gray-800' : ' group-hover:text-gray-800' }}" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-xs mt-1 {{ $isMahasiswaActive ? 'text-gray-800' : ' group-hover:text-gray-800' }}">Mahasiswa</span>
                                    </div>
                                </button>
                                <div x-show="openDropdown === 'mahasiswa'"
                                    x-transition
                                    @click.away="openDropdown = null"
                                    class="absolute left-full top-0 ml-2 w-48 bg-white text-gray-800 rounded-lg shadow-lg p-2 z-50">
                                    <span class="text-sm font-semibold block px-2 py-1 no-underline text-gray-800">Manajemen Mahasiswa</span>
                                    <a href="/dosen/absensi" class="block px-4 py-1 hover:bg-sky-100 no-underline text-gray-800">Absensi</a>
                                    <a href="/dosen/nilai" class="block px-4 py-1 hover:bg-sky-100 no-underline text-gray-800">Input Nilai</a>
                                </div>
                            </div>
                        </div>

                        {{-- Menu Jadwal --}}
                        <a href="{{ '/dosen/jadwal-mengajar' }}" class="{{ request()->is('dosen/jadwal-mengajar') ? 'bg-white text-gray-800' : 'text-white hover:bg-white hover:text-gray-800' }} rounded-md no-underline" aria-current="page" title="Jadwal Mengajar" aria-label="Jadwal Mengajar">
                            <div class="h-12 w-18 flex flex-col items-center justify-center rounded-lg transition duration-150 group">
                                <svg xmlns="http://www.w3.org/2000/svg" class="{{ request()->is('dosen/jadwal-mengajar') ? 'text-gray-800' : 'group-hover:text-gray-800' }} w-6 h-6 transition" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19,4H17V3a1,1,0,0,0-2,0V4H9V3A1,1,0,0,0,7,3V4H5A3,3,0,0,0,2,7V19a3,3,0,0,0,3,3H19a3,3,0,0,0,3-3V7A3,3,0,0,0,19,4Zm1,15a1,1,0,0,1-1,1H5a1,1,0,0,1-1-1V12H20Zm0-9H4V7A1,1,0,0,1,5,6H7V7A1,1,0,0,0,9,7V6h6V7a1,1,0,0,0,2,0V6h2a1,1,0,0,1,1,1Z"></path>
                                </svg>
                                <span class="{{ request()->is('dosen/jadwal-mengajar') ? 'text-gray-800' : 'group-hover:text-gray-800' }} text-xs mt-1 transition">Jadwal</span>
                            </div>
                        </a>

                    @elseif($role === 'mahasiswa')
                        {{-- Menu Dashboard --}}
                        <a href="{{ '/mahasiswa/dashboard' }}" class="{{ request()->is('mahasiswa/dashboard') ? 'bg-white text-gray-800' : 'text-white hover:bg-white hover:text-gray-800' }} rounded-md no-underline" aria-current="page" title="Dashboard" aria-label="Dashboard">
                            <div class="h-12 w-18 flex flex-col items-center justify-center rounded-lg transition duration-150 group">
                                <svg class="{{ request()->is('mahasiswa/dashboard') ? 'text-gray-800' : 'group-hover:text-gray-800' }} w-6 h-6 transition" fill="currentColor" viewBox="0 0 1920 1920">
                                    <path d="M833.935 1063.327c28.913 170.315 64.038 348.198 83.464 384.79 27.557 51.84 92.047 71.944 144 44.387 51.84-27.558 71.717-92.273 44.16-144.113-19.426-36.593-146.937-165.46-271.624-285.064Zm-43.821-196.405c61.553 56.923 370.899 344.81 415.285 428.612 56.696 106.842 15.811 239.887-91.144 296.697-32.64 17.28-67.765 25.411-102.325 25.411-78.72 0-154.955-42.353-194.371-116.555-44.386-83.802-109.102-501.346-121.638-584.245-3.501-23.717 8.245-47.21 29.365-58.277 21.346-11.294 47.096-8.02 64.828 8.357ZM960.045 281.99c529.355 0 960 430.757 960 960 0 77.139-8.922 153.148-26.654 225.882l-10.39 43.144h-524.386v-112.942h434.258c9.487-50.71 14.231-103.115 14.231-156.084 0-467.125-380.047-847.06-847.059-847.06-467.125 0-847.059 379.935-847.059 847.06 0 52.97 4.744 105.374 14.118 156.084h487.454v112.942H36.977l-10.39-43.144C8.966 1395.137.044 1319.128.044 1241.99c0-529.243 430.645-960 960-960Zm542.547 390.686 79.85 79.85-112.716 112.715-79.85-79.85 112.716-112.715Zm-1085.184 0L530.123 785.39l-79.85 79.85L337.56 752.524l79.849-79.85Zm599.063-201.363v159.473H903.529V471.312h112.942Z" fill-rule="evenodd"></path>
                                </svg>
                                <span class="{{ request()->is('mahasiswa/dashboard') ? 'text-gray-800' : 'group-hover:text-gray-800' }} text-xs mt-1 transition">Dashboard</span>
                            </div>
                        </a>
                        <a href="{{ '/mahasiswa/jadwal' }}" class="{{ request()->is('mahasiswa/jadwal') ? 'bg-white text-gray-800' : 'text-white hover:bg-white hover:text-gray-800' }} rounded-md no-underline" aria-current="page" title="Jadwal" aria-label="Jadwal">
                            <div class="h-12 w-18 flex flex-col items-center justify-center rounded-lg transition duration-150 group">
                                <svg xmlns="http://www.w3.org/2000/svg" class="{{ request()->is('mahasiswa/jadwal') ? 'text-gray-800' : 'group-hover:text-gray-800' }} w-6 h-6 transition" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19,4H17V3a1,1,0,0,0-2,0V4H9V3A1,1,0,0,0,7,3V4H5A3,3,0,0,0,2,7V19a3,3,0,0,0,3,3H19a3,3,0,0,0,3-3V7A3,3,0,0,0,19,4Zm1,15a1,1,0,0,1-1,1H5a1,1,0,0,1-1-1V12H20Zm0-9H4V7A1,1,0,0,1,5,6H7V7A1,1,0,0,0,9,7V6h6V7a1,1,0,0,0,2,0V6h2a1,1,0,0,1,1,1Z"></path>
                                </svg>
                                <span class="{{ request()->is('mahasiswa/jadwal') ? 'text-gray-800' : 'group-hover:text-gray-800' }} text-xs mt-1 transition">Jadwal</span>
                            </div>
                        </a>
                        <a href="{{ '/mahasiswa/krs' }}" class="{{ request()->is('mahasiswa/krs') ? 'bg-white text-gray-800' : 'text-white hover:bg-white hover:text-gray-800' }} rounded-md no-underline" aria-current="page" title="KRS" aria-label="KRS">
                            <div class="h-12 w-18 flex flex-col items-center justify-center rounded-lg transition duration-150 group">
                                <svg class="{{ request()->is('mahasiswa/krs') ? 'text-gray-800' : 'group-hover:text-gray-800' }} w-6 h-6 transition" fill="currentColor"  viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="{{ request()->is('mahasiswa/krs') ? 'text-gray-800' : 'group-hover:text-gray-800' }} text-xs mt-1transition">KRS</span>
                            </div>
                        </a>

                        {{-- Menu KHS / Nilai --}}
                        <a href="{{ '/mahasiswa/khs' }}" class="{{ request()->is('mahasiswa/khs') ? 'bg-white text-gray-800' : 'text-white hover:bg-white hover:text-gray-800' }} rounded-md no-underline" aria-current="page" title="Nilai" aria-label="Nilai">
                            <div class="h-12 w-18 flex flex-col items-center justify-center rounded-lg transition duration-150 group">
                                <svg viewBox="0 0 76 76" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="{{ request()->is('mahasiswa/khs') ? 'text-gray-800' : 'group-hover:text-gray-800' }}  w-6 h-6 transition">
                                    <path d="M 47.4578,53.8333L 39.4751,53.8333L 37.3851,47.3311L 25.4184,47.3311L 23.3502,53.8333L 15.4111,53.8333L 27.2327,21.3222L 35.9047,21.3222L 47.4578,53.8333 Z M 35.433,40.8289L 32.0223,30.0523C 31.7562,29.2347 31.5723,28.2599 31.4707,27.1278L 31.2893,27.1278C 31.2312,28.0809 31.0401,29.0243 30.716,29.958L 27.2399,40.8289L 35.433,40.8289 Z M 46.3125,34.8333L 52.25,34.8333L 52.25,28.8958L 58.5833,28.8958L 58.5833,34.8333L 64.5208,34.8333L 64.5208,41.1667L 58.5833,41.1667L 58.5833,47.1042L 52.25,47.1042L 52.25,41.1667L 46.3125,41.1667L 46.3125,34.8333 Z "></path>
                                </svg>
                                <span class="{{ request()->is('mahasiswa/khs') ? 'text-gray-800' : 'group-hover:text-gray-800' }} text-xs mt-1 transition">KHS</span>
                            </div>
                        </a>
                    @endif
                @endauth
            </aside>

            <div class="flex flex-col flex-1">
                <header class="h-16 w-full flex items-center justify-end px-5 space-x-10 bg-sky-900 text-white fixed">
                    <h5>{{ Auth::user()->name }}</h5>
                    <div x-data="{ open: false }" class="relative ml-3">
                        <div>
                            <button type="button" @click="open = ! open" class="relative flex rounded-full bg-gray-800 text-sm focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800 focus:outline-none" id="user-menu-button" aria-expanded="true" aria-haspopup="true">
                                <span class="absolute -inset-1.5"></span>
                                <span class="sr-only">Open user menu</span>
                                @auth
                                    @php
                                        $foto = Auth::user()->profil->foto ?? null;
                                    @endphp
                                    @if($foto)
                                        <img class="h-12 w-12 rounded-full object-cover" src="{{ asset('storage/' . $foto) }}" alt="{{ Auth::user()->name }}">
                                    @else
                                        <img class="h-12 w-12 rounded-full object-cover" src="{{ asset('img/default-profile.png') }}" alt="Default profile">
                                    @endif
                                @else
                                    <img class="h-8 w-8 rounded-full object-cover" src="{{ asset('img/default-profile.png') }}" alt="Guest profile">
                                @endauth
                            </button>
                        </div>

                        <div x-show="open"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            @click.away="open = false"
                            class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                            role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 no-underline" role="menuitem" tabindex="-1" id="user-menu-item-0">Profil Anda</a>
                            <form method="POST" action="{{ route('auth.logout') }}">
                                @csrf
                                @method('DELETE')
                                <a href="{{ route('auth.logout') }}" 
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 no-underline" role="menuitem" tabindex="-1" id="user-menu-item-2">Keluar</a>
                            </form>
                        </div>
                    </div>
                </header>
                <main class="mt-16 p-20 md:ml-20 transition-all bg-slate-50">
                        {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>