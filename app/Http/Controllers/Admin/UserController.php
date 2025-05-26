<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Http\Request; // Tetap dibutuhkan untuk store dan update
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() // Menghapus Request $request dari parameter
    {
        // Menghapus logika pencarian
        $users = User::with(['mahasiswa', 'dosen'])
                     ->orderBy('name', 'asc') // Tetap urutkan berdasarkan nama
                     ->paginate(10); // Menyesuaikan jumlah item per halaman jika perlu

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $usedMahasiswaIds = User::whereNotNull('mahasiswa_id')->pluck('mahasiswa_id')->all();
        $mahasiswas = Mahasiswa::whereNotIn('id_mahasiswa', $usedMahasiswaIds)->orderBy('nama')->get();

        $usedDosenIds = User::whereNotNull('dosen_id')->pluck('dosen_id')->all();
        $dosens = Dosen::whereNotIn('id_dosen', $usedDosenIds)->orderBy('nama')->get();

        return view('admin.users.create', compact('mahasiswas', 'dosens'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Asumsi Anda telah mengatasi masalah 'deleted_at' atau tidak menggunakannya
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => ['required', 'confirmed'],
            'role' => ['required', Rule::in(['admin', 'dosen', 'mahasiswa'])],
            'mahasiswa_id' => [
                Rule::requiredIf(fn () => $request->input('role') === 'mahasiswa'),
                'nullable',
                'exists:mahasiswas,id_mahasiswa',
                Rule::unique('users', 'mahasiswa_id')
            ],
            'dosen_id' => [
                Rule::requiredIf(fn () => $request->input('role') === 'dosen'),
                'nullable',
                'exists:dosens,id_dosen',
                Rule::unique('users', 'dosen_id')
            ],
        ],[
            'mahasiswa_id.required_if' => 'Pilihan mahasiswa wajib diisi jika role adalah mahasiswa.',
            'mahasiswa_id.unique' => 'Mahasiswa ini sudah memiliki akun user.',
            'dosen_id.required_if' => 'Pilihan dosen wajib diisi jika role adalah dosen.',
            'dosen_id.unique' => 'Dosen ini sudah memiliki akun user.',
        ]);

        $data = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'mahasiswa_id' => null,
            'dosen_id' => null,
        ];

        if ($validated['role'] === 'mahasiswa' && isset($validated['mahasiswa_id'])) {
            $data['mahasiswa_id'] = $validated['mahasiswa_id'];
        } elseif ($validated['role'] === 'dosen' && isset($validated['dosen_id'])) {
            $data['dosen_id'] = $validated['dosen_id'];
        }

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        $usedMahasiswaIds = User::whereNotNull('mahasiswa_id')->where('id_user', '!=', $user->id_user)->pluck('mahasiswa_id')->all();
        $mahasiswas = Mahasiswa::whereNotIn('id_mahasiswa', $usedMahasiswaIds)->orderBy('nama')->get();

        $usedDosenIds = User::whereNotNull('dosen_id')->where('id_user', '!=', $user->id_user)->pluck('dosen_id')->all();
        $dosens = Dosen::whereNotIn('id_dosen', $usedDosenIds)->orderBy('nama')->get();

        return view('admin.users.edit', compact('user', 'mahasiswas', 'dosens'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id_user, 'id_user')],
            'password' => ['nullable', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            'role' => ['required', Rule::in(['admin', 'dosen', 'mahasiswa'])],
            'mahasiswa_id' => [
                Rule::requiredIf(fn () => $request->input('role') === 'mahasiswa'),
                'nullable',
                'exists:mahasiswas,id_mahasiswa',
                Rule::unique('users', 'mahasiswa_id')->ignore($user->id_user, 'id_user') // Hapus ->whereNull('deleted_at')
            ],
            'dosen_id' => [
                Rule::requiredIf(fn () => $request->input('role') === 'dosen'),
                'nullable',
                'exists:dosens,id_dosen',
                Rule::unique('users', 'dosen_id')->ignore($user->id_user, 'id_user') // Hapus ->whereNull('deleted_at')
            ],
        ],[
            'mahasiswa_id.required_if' => 'Pilihan mahasiswa wajib diisi jika role adalah mahasiswa.',
            'mahasiswa_id.unique' => 'Mahasiswa ini sudah memiliki akun user lain.',
            'dosen_id.required_if' => 'Pilihan dosen wajib diisi jika role adalah dosen.',
            'dosen_id.unique' => 'Dosen ini sudah memiliki akun user lain.',
        ]);

        $data = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'role' => $validated['role'],
            'mahasiswa_id' => null,
            'dosen_id' => null,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        if ($validated['role'] === 'mahasiswa' && isset($validated['mahasiswa_id'])) {
            $data['mahasiswa_id'] = $validated['mahasiswa_id'];
        } elseif ($validated['role'] === 'dosen' && isset($validated['dosen_id'])) {
            $data['dosen_id'] = $validated['dosen_id'];
        }


        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if (Auth::id() == $user->id_user) {
            return redirect()->route('admin.users.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        try {
            $user->delete();
            return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('admin.users.index')->with('error', 'Gagal menghapus user. User mungkin masih terkait dengan data penting lainnya.');
        }
    }
}
