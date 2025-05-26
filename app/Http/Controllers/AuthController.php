<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(){
        return view('auth.login');
    }
    
public function authenticate(Request $request){
    $credentials = $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
        'role' => 'required|in:admin,mahasiswa,dosen'
    ]);

    $user = User::where('username', $credentials['username'])
                            ->where('role', $credentials['role'])
                            ->first();

    if ($user && Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
        $request->session()->regenerate();

        switch ($credentials['role']) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'mahasiswa':
                return redirect()->route('mahasiswa.dashboard');
            case 'dosen':
                return redirect()->route('dosen.dashboard');
        }
    }

    return back()->withErrors([
        'username' => 'Username/Password Anda Salah / Tidak Terdaftar.',
    ])->onlyInput('username');
}


    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
