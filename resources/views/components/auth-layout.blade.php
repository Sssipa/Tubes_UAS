@props(["title", "section_title" => "Sistem Informasi Akademik", "section_description" => "Login with your account"])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/fill/style.css">
    <title>{{ $title }}</title>
</head>

<body class=" relative bg-cover bg-center" style="background-image: url('{{ asset('img/bg1.jpg') }}');">
    <div class="inset-0  bg-black bg-opacity-50 z-0"></div>
    <main class="relative z-10 flex items-center justify-center min-h-screen px-4">
        <div class="flex flex-col md:flex-row max-w-7xl w-full gap-6">
            
            <!-- Login Panel -->
            <div class="w-full flex items-center justify-center bg-white p-10 md:w-1/2">
                <div class="w-full max-w-sm space-y-6">
                    <div class="text-center">
                        <h1 class="text-2xl font-bold">{{ $section_title }}</h1>
                        <p class="text-sm mt-1 text-zinc-500">{{ $section_description }}</p>
                    </div>
                    {{ $slot }}
                </div>
            </div>

            <!-- Tata Cara Login Panel -->
            <div class="w-full md:w-1/2 space-y-4">
                <div class="bg-white p-12">
                    <h2 class="text-xl font-semibold mb-3 flex items-center gap-2">
                        <i class="ph ph-info text-lg"></i>
                        Panduan Penggunaan Sistem & Informasi Penting
                    </h2>

                    <div class="bg-sky-100 text-sky-900 rounded p-4 space-y-3 text-sm">
                        <p class="font-semibold flex items-center gap-2">
                            <i class="ph ph-check-circle text-sky-600 text-lg"></i>
                            Etika Penggunaan Sistem Akademik
                        </p>
                        <ul class="list-none space-y-2">
                            <li class="flex items-start gap-2">
                                <i class="ph ph-circle-dashed text-sky-600 mt-1"></i>
                                Gunakan sistem ini secara bertanggung jawab dan sesuai keperluan akademik.
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="ph ph-lock-key text-sky-600 mt-1"></i>
                                Jangan mencoba mengakses data yang bukan milik Anda.
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="ph ph-camera-slash text-sky-600 mt-1"></i>
                                Hindari menyebarkan screenshot informasi pribadi tanpa izin.
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="ph ph-check-square-offset text-sky-600 mt-1"></i>
                                Periksa kembali data sebelum menyimpan atau mengirimnya.
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="ph ph-bug-beetle text-sky-600 mt-1"></i>
                                Laporkan bug atau kesalahan sistem dengan sopan.
                            </li>
                        </ul>
                    </div>


                    <div class="bg-rose-100 text-rose-900 rounded p-4 text-sm mt-4">
                        <p class="font-semibold flex items-center gap-2 mb-2">
                            <i class="ph ph-warning-circle text-rose-600 text-lg"></i>
                            Larangan & Tindakan Tegas
                        </p>
                        <ul class="list-none space-y-2">
                            <li class="flex items-start gap-2">
                                <i class="ph ph-user-switch text-rose-600 mt-1"></i>
                                Dilarang menggunakan akun orang lain untuk login.
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="ph ph-database text-rose-600 mt-1"></i>
                                Manipulasi data akademik akan diproses sesuai peraturan kampus.
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="ph ph-shield-slash text-rose-600 mt-1"></i>
                                Penyalahgunaan sistem akan dikenakan sanksi administratif.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </main>
</body>
</html>
