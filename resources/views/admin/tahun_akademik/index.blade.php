<x-app title="Tahun Akademik" section_title="Manajemen Tahun Akademik">
    @if (session('success'))
        <div class="bg-green-500 text-white px-4 py-3 rounded mb-4 shadow-md">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-500 text-white px-4 py-3 rounded mb-4 shadow-md">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 gap-3 sm:gap-0">
        <h2 class="text-2xl sm:text-3xl font-semibold text-gray-700">Daftar Tahun Akademik</h2>
        <a href="{{ route('admin.tahun-akademik.create') }}" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow no-underline text-center sm:text-left">
            + Tambah Tahun Akademik
        </a>
    </div>

    <div class="bg-white border border-gray-300 shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="text-left text-sm font-medium text-sky-700 bg-sky-100">
                    <tr>
                        <th class="px-4 py-3 border-b border-gray-300">No</th>
                        <th class="px-4 py-3 border-b border-gray-300">Tahun Akademik</th>
                        <th class="px-4 py-3 border-b border-gray-300">Semester</th>
                        <th class="px-4 py-3 border-b border-gray-300 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-800 divide-y divide-gray-200">
                    @forelse ($tahunAkademiks as $index => $ta)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $tahunAkademiks->firstItem() + $index }}</td>
                            <td class="px-4 py-3">{{ $ta->tahun }}</td>
                            <td class="px-4 py-3">{{ $ta->semester }}</td>
                            <td class="px-4 py-3 text-center">
                                @if ($ta->status_aktif)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-500">
                                Belum ada data tahun akademik.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
            <div class="flex flex-1 justify-between sm:hidden">
                <a href="{{ $tahunAkademiks->previousPageUrl() ?? '#' }}"
                    class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 {{ $tahunAkademiks->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                    Previous
                </a>
                <a href="{{ $tahunAkademiks->nextPageUrl() ?? '#' }}"
                    class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 {{ $tahunAkademiks->hasMorePages() ? '' : 'opacity-50 cursor-not-allowed' }}">
                    Next
                </a>
            </div>

            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Menampilkan
                        <span class="font-medium">{{ $tahunAkademiks->firstItem() ?? 0 }}</span>
                        sampai
                        <span class="font-medium">{{ $tahunAkademiks->lastItem() ?? 0 }}</span>
                        dari
                        <span class="font-medium">{{ $tahunAkademiks->total() ?? 0 }}</span>
                        data
                    </p>
                </div>
                <div>
                    <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                        {{-- Previous --}}
                        <a href="{{ $tahunAkademiks->previousPageUrl() ?? '#' }}"
                            class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-gray-300 ring-inset hover:bg-gray-50 {{ $tahunAkademiks->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                            <span class="sr-only">Previous</span>
                            <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                            </svg>
                        </a>

                        {{-- Dynamic Page --}}
                        @foreach ($tahunAkademiks->getUrlRange(1, $tahunAkademiks->lastPage()) as $page => $url)
                            @if ($page == $tahunAkademiks->currentPage())
                                <a href="{{ $url }}" aria-current="page" class="relative z-10 inline-flex items-center bg-sky-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 no-underline">{{ $page }}</a>
                            @else
                                <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-gray-300 ring-inset hover:bg-gray-50 focus:z-20 no-underline">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Next --}}
                        <a href="{{ $tahunAkademiks->nextPageUrl() ?? '#' }}"
                            class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-gray-300 ring-inset hover:bg-gray-50 {{ $tahunAkademiks->hasMorePages() ? '' : 'opacity-50 cursor-not-allowed' }}">
                            <span class="sr-only">Next</span>
                            <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</x-app>