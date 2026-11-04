<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.profil', compact('admin'));
    }

    public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'name'         => 'required|string|max:255',
            'jabatan'      => 'nullable|string|max:255',
            'email'        => 'required|email|unique:admins,email,' . $admin->id,
            'password'     => 'nullable|min:6|confirmed',
        ]);

        $admin->name    = $request->name;
        $admin->jabatan = $request->jabatan;
        $admin->email   = $request->email;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}