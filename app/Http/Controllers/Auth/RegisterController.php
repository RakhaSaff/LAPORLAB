<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'tanggal_lahir'     => ['required', 'date'],
            'nim_nip'           => ['required', 'string', 'max:50'],
            'jenis_kelamin'     => ['required', 'in:L,P'],
            'alamat'            => ['required', 'string'],
            'no_hp'             => ['required', 'string', 'max:20'],
            'email'             => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'prodi'             => ['required', 'string', 'max:100'],
            'password'          => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Generate username otomatis dari name
        $baseUsername = strtolower(str_replace(' ', '_', $data['name']));
        $username = $baseUsername;
        $i = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . '_' . $i;
            $i++;
        }

        $user = User::create([
            'name'              => $data['name'],
            'username'          => $username,
            'tanggal_lahir'     => $data['tanggal_lahir'],
            'nim_nip'           => $data['nim_nip'],
            'jenis_kelamin'     => $data['jenis_kelamin'],
            'alamat'            => $data['alamat'],
            'no_hp'             => $data['no_hp'],
            'email'             => $data['email'],
            'prodi'             => $data['prodi'],
            'password'          => Hash::make($data['password']),
        ]);

        auth()->login($user);

        return redirect()->route('dashboard');
    }
}