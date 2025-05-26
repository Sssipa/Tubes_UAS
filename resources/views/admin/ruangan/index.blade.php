{{-- filepath: resources/views/admin/ruangan/index.blade.php --}}
<x-app title="Data Ruangan" section_title="Data Ruangan">
    @if (session('success'))
        <div class="bg-green-500 text-white px-4 py-3 rounded mb-4 shadow-md">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 gap-3 sm:gap-0">
        <h2 class="text-2xl sm:text-3xl font-semibold text-gray-700">Daftar Ruangan</h2>
        <a href="{{ route('admin.ruangan.create') }}"
            class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow text-center no-underline">
            + Tambah Ruangan
        </a>
    </div>

    <div class="bg-white border border-gray-300 shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="text-left text-sm font-medium text-sky-700 bg-sky-100">
                    <tr>
                        <th class="px-4 py-3 border-b border-gray-300">Kode</th>
                        <th class="px-4 py-3 border-b border-gray-300">Nama</th>
                        <th class="px-4 py-3 border-b border-gray-300">Kapasitas</th>
                        <th class="px-4 py-3 border-b border-gray-300 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-800 divide-y divide-gray-200">
                    @forelse ($ruangans as $ruangan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $ruangan->kode }}</td>
                            <td class="px-4 py-3">{{ $ruangan->nama }}</td>
                            <td class="px-4 py-3">{{ $ruangan->kapasitas }}</td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('admin.ruangan.edit', $ruangan) }}"
                                        title="Edit Ruangan"
                                        class="bg-yellow-400 hover:bg-yellow-500 text-gray-800 p-2 rounded inline-flex items-center no-underline">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2.695 14.763l-1.262 3.154a.5.5 0 0 0 .65.65l3.155-1.262a4 4 0 0 0 1.343-.885L17.5 5.5a2.121 2.121 0 0 0-3-3L3.58 13.42a4 4 0 0 0-.885 1.343z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.ruangan.destroy', $ruangan) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            title="Hapus Ruangan"
                                            class="bg-red-600 hover:bg-red-700 text-white p-2 rounded inline-flex items-center justify-center no-underline">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 0 0 6 3.75H4.5a.75.75 0 0 0 0 1.5h11a.75.75 0 0 0 0-1.5H14A2.75 2.75 0 0 0 11.25 1h-2.5ZM10 4A22.75 22.75 0 0 0 7.5 15.25V4.125C8.33 4.042 9.16 4 10 4h0ZM5.75 6.5V15.25a1.25 1.25 0 0 0 1.25 1.25h6.5a1.25 1.25 0 0 0 1.25-1.25V6.5h-9Z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-500">
                                Belum ada data ruangan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
            <div class="flex flex-1 justify-between sm:hidden">
                <a href="{{ $ruangans->previousPageUrl() ?? '#' }}"
                    class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 {{ $ruangans->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                    Previous
                </a>
                <a href="{{ $ruangans->nextPageUrl() ?? '#' }}"
                    class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 {{ $ruangans->hasMorePages() ? '' : 'opacity-50 cursor-not-allowed' }}">
                    Next
                </a>
            </div>

            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Menampilkan
                        <span class="font-medium">{{ $ruangans->firstItem() ?? 0 }}</span>
                        sampai
                        <span class="font-medium">{{ $ruangans->lastItem() ?? 0 }}</span>
                        dari
                        <span class="font-medium">{{ $ruangans->total() ?? 0 }}</span>
                        data
                    </p>
                </div>
                <div>
                    <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                        {{-- Previous --}}
                        <a href="{{ $ruangans->previousPageUrl() ?? '#' }}"
                            class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-gray-300 ring-inset hover:bg-gray-50 {{ $ruangans->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                            <span class="sr-only">Previous</span>
                            <svg class="size-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                            </svg>
                        </a>

                        {{-- Dynamic Page --}}
                        @foreach ($ruangans->getUrlRange(1, $ruangans->lastPage()) as $page => $url)
                            @if ($page == $ruangans->currentPage())
                                <a href="{{ $url }}" aria-current="page" class="relative z-10 inline-flex items-center bg-sky-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 no-underline">{{ $page }}</a>
                            @else
                                <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-gray-300 ring-inset hover:bg-gray-50 focus:z-20 no-underline">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Next --}}
                        <a href="{{ $ruangans->nextPageUrl() ?? '#' }}"
                            class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-gray-300 ring-inset hover:bg-gray-50 {{ $ruangans->hasMorePages() ? '' : 'opacity-50 cursor-not-allowed' }}">
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