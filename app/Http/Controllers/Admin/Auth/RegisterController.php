<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('admin.auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'username'      => ['required', 'string', 'max:50', 'unique:admins'],
            'name'          => ['required', 'string', 'max:255'],
            'nip'           => ['required', 'string', 'max:50', 'unique:admins'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'jabatan'       => ['required', 'string', 'max:100'],
            'email'         => ['required', 'email', 'unique:admins'],
            'password'      => ['required', 'confirmed', 'min:8'],
        ]);

        Admin::create([
            'username'      => $data['username'],
            'name'          => $data['name'],
            'nip'           => $data['nip'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'jabatan'       => $data['jabatan'],
            'email'         => $data['email'],
            'password'      => Hash::make($data['password']),
        ]);

        return redirect('/admin/login')->with('success', 'Akun admin berhasil dibuat. Silakan login.');
    }
}
