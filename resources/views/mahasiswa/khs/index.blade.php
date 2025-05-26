<x-app title="Kartu Hasil Studi (KHS)" section_title="Ringkasan Kartu Hasil Studi">
    @if (session('error'))
        <div class="mb-4 rounded-lg bg-red-100 px-6 py-5 text-base text-red-700 dark:bg-red-900 dark:text-red-100">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-600 dark:text-gray-400">Nama Mahasiswa:</p>
                <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $mahasiswa->nama }} ({{ $mahasiswa->nim }})</p>
            </div>
            <div>
                <p class="text-gray-600 dark:text-gray-400">Indeks Prestasi Kumulatif (IPK):</p>
                <p class="font-bold text-2xl text-sky-600 dark:text-sky-400">{{ number_format($ipk, 2) }}</p>
                 <p class="text-xs text-gray-500 dark:text-gray-400">Total SKS Kumulatif: {{ $totalSksKumulatif }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="text-left text-xs font-semibold text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700/50 uppercase">
                    <tr>
                        <th class="px-4 py-3 border-b border-gray-300 dark:border-gray-600">Semester Ke-</th>
                        <th class="px-4 py-3 border-b border-gray-300 dark:border-gray-600 text-center">Total SKS Diambil</th>
                        <th class="px-4 py-3 border-b border-gray-300 dark:border-gray-600 text-center">Indeks Prestasi Semester (IPS)</th>
                        <th class="px-4 py-3 border-b border-gray-300 dark:border-gray-600 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700 dark:text-gray-200 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($hasilPerSemester as $semester => $data)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                            <td class="px-4 py-3 whitespace-nowrap font-medium">Semester {{ $data['semester'] }}</td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">{{ $data['total_sks'] }}</td>
                            <td class="px-4 py-3 text-center whitespace-nowrap font-semibold">{{ number_format($data['ips'], 2) }}</td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <a href="{{ route('mahasiswa.khs.show', $data['semester']) }}"
                                   class="text-sky-600 hover:text-sky-800 dark:text-sky-400 dark:hover:text-sky-300 font-medium hover:underline">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-6 text-gray-500 dark:text-gray-400">
                                Belum ada data Kartu Hasil Studi yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app>