<x-app title="Data Pengguna" section_title="Manajemen Pengguna Sistem">
    @if (session('success'))
        <div class="bg-green-500 text-white px-4 py-3 rounded mb-4 shadow-md">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 gap-3 sm:gap-0">
        <h2 class="text-2xl sm:text-3xl font-semibold text-gray-700">Daftar Akun</h2>
        <a href="{{ route('admin.users.create') }}"
            class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow text-center no-underline">
            + Tambah Akun
        </a>
    </div>

    <div class="bg-white border border-gray-300 shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="text-left text-sm font-semibold text-sky-700 bg-sky-100">
                    <tr>
                        <th class="px-4 py-3  border-b border-gray-300">No.</th>
                        <th class="px-4 py-3  border-b border-gray-300">Nama</th>
                        <th class="px-4 py-3  border-b border-gray-300">Username</th>
                        <th class="px-4 py-3  border-b border-gray-300">Role</th>
                        <th class="px-4 py-3  border-b border-gray-300">Detail Terkait</th>
                        <th class="px-4 py-3  border-b border-gray-300 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-800 divide-y divide-gray-200 ">
                    @forelse ($users as $index => $user)
                        <tr class="hidden sm:table-row hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $users->firstItem() + $index }}</td>
                            <td class="px-4 py-3">{{ $user->name }}</td>
                            <td class="px-4 py-3 ">{{ $user->username }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs font-semibold leading-tight rounded-full
                                    @if($user->role == 'admin') bg-red-100 text-red-700 dark:bg-red-700/30 dark:text-red-300 @endif
                                    @if($user->role == 'dosen') bg-sky-100 text-sky-700 dark:bg-sky-700/30 dark:text-sky-300 @endif
                                    @if($user->role == 'mahasiswa') bg-green-100 text-green-700 dark:bg-green-700/30 dark:text-green-300 @endif
                                ">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if($user->mahasiswa)
                                    NIM: {{ $user->mahasiswa->nim }}
                                @elseif($user->dosen)
                                    NIDN: {{ $user->dosen->nidn }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('admin.user.edit', $user) }}"
                                        title="Edit Ruangan"
                                        class="bg-yellow-400 hover:bg-yellow-500 text-gray-800 p-2 rounded inline-flex items-center no-underline">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2.695 14.763l-1.262 3.154a.5.5 0 0 0 .65.65l3.155-1.262a4 4 0 0 0 1.343-.885L17.5 5.5a2.121 2.121 0 0 0-3-3L3.58 13.42a4 4 0 0 0-.885 1.343z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.user.destroy', $user) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
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
                        {{-- Tampilan untuk layar kecil (mobile) --}}
                        <tr class="sm:hidden hover:bg-gray-50 dark:hover:bg-gray-700/30 border-b border-gray-200 dark:border-gray-700">
                            {{-- Colspan disesuaikan dengan jumlah TH di thead (6 kolom) --}}
                            <td colspan="6" class="px-4 py-4">
                                <div class="bg-white dark:bg-gray-800 rounded-lg p-3 space-y-1">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->username }}</p>
                                        </div>
                                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                                            @if($user->role == 'admin') bg-red-100 text-red-700 dark:bg-red-700/30 dark:text-red-300 @endif
                                            @if($user->role == 'dosen') bg-sky-100 text-sky-700 dark:bg-sky-700/30 dark:text-sky-300 @endif
                                            @if($user->role == 'mahasiswa') bg-green-100 text-green-700 dark:bg-green-700/30 dark:text-green-300 @endif
                                        ">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </div>
                                    @if($user->mahasiswa || $user->dosen)
                                    <p class="text-xs"><span class="font-medium text-gray-600 dark:text-gray-400">Terkait:</span>
                                        @if($user->mahasiswa)
                                            NIM {{ $user->mahasiswa->nim }}
                                        @elseif($user->dosen)
                                            NIDN {{ $user->dosen->nidn }}
                                        @endif
                                    </p>
                                    @endif
                                    <div class="flex items-center justify-start space-x-2 pt-2">
                                         <a href="{{ route('admin.user.show', $user->id_user) }}" title="Lihat Detail User" class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded inline-flex items-center no-underline text-xs">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                            Detail
                                        </a>
                                        <a href="{{ route('admin.user.edit', $user->id_user) }}"
                                            title="Edit User"
                                            class="bg-yellow-400 hover:bg-yellow-500 text-gray-800 p-2 rounded inline-flex items-center no-underline text-xs">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                            </svg>
                                            Edit
                                        </a>
                                        @if(Auth::id() != $user->id_user)
                                        <form action="{{ route('admin.user.destroy', $user->id_user) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus pengguna ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                title="Hapus User"
                                                class="bg-red-600 hover:bg-red-700 text-white p-2 rounded inline-flex items-center justify-center no-underline text-xs">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            {{-- Colspan disesuaikan dengan jumlah TH di thead (6 kolom) --}}
                            <td colspan="6" class="text-center py-6 text-gray-500 dark:text-gray-400">
                                Belum ada data pengguna.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
             {{-- Menyesuaikan dengan pagination yang Anda gunakan sebelumnya --}}
            <div class="flex flex-1 justify-between sm:hidden">
                <a href="{{ $users->previousPageUrl() ?? '#' }}"
                    class="relative inline-flex items-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 {{ $users->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                    Sebelumnya
                </a>
                <a href="{{ $users->nextPageUrl() ?? '#' }}"
                    class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 {{ $users->hasMorePages() ? '' : 'opacity-50 cursor-not-allowed' }}">
                    Berikutnya
                </a>
            </div>
            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        Menampilkan
                        <span class="font-medium">{{ $users->firstItem() ?? 0 }}</span>
                        sampai
                        <span class="font-medium">{{ $users->lastItem() ?? 0 }}</span>
                        dari
                        <span class="font-medium">{{ $users->total() ?? 0 }}</span>
                        data
                    </p>
                </div>
                <div>
                    {{ $users->appends(request()->query())->links('vendor.pagination.tailwind') }} {{-- Menggunakan view pagination Tailwind bawaan Laravel jika ada --}}
                </div>
            </div>
        </div>
        @endif
    </div>
</x-app>