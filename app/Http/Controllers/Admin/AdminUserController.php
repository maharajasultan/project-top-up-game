<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index()
    {
        $admins = Admin::latest()->paginate(10);
        return view('admin.admin-users.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admin-users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'password' => 'required|string|min:8|confirmed',
        ]);

        Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin', // default role
        ]);

        return redirect()->route('admin.admin-users.index')->with('success', 'Admin berhasil ditambahkan.');
    }

    public function edit(Admin $adminUser)
    {
        return view('admin.admin-users.edit', compact('adminUser'));
    }

    public function update(Request $request, Admin $adminUser)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('admins')->ignore($adminUser->id)],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $adminUser->name = $request->name;
        $adminUser->email = $request->email;
        
        if ($request->filled('password')) {
            $adminUser->password = Hash::make($request->password);
        }
        
        $adminUser->save();

        return redirect()->route('admin.admin-users.index')->with('success', 'Admin berhasil diperbarui.');
    }

    public function destroy(Admin $adminUser)
    {
        // Don't allow deleting the last admin
        if (Admin::count() <= 1) {
            return back()->with('error', 'Tidak dapat menghapus admin terakhir.');
        }

        // Don't allow deleting yourself
        if (auth()->guard('admin')->id() === $adminUser->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $adminUser->delete();
        return redirect()->route('admin.admin-users.index')->with('success', 'Admin berhasil dihapus.');
    }
}
