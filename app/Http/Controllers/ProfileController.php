<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user->mahasiswa) {
            abort(403, 'Access denied.');
        }

        $mahasiswa = $user->mahasiswa;
        return view('profile', compact('mahasiswa'));
    }
}
