<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users', ['users' => User::orderBy('name')->get()]);
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role'     => 'required|in:admin,operator',
        ]);

        $data['password'] = Hash::make($data['password']);
        User::create($data);

        return back()->with('success', 'Usuario creado.');
    }

    public function destroy(User $user)
    {
        abort_if($user->id === auth()->id, 403, 'No puedes eliminarte a ti mismo.');

        $user->delete();

        return back()->with('success', 'Usuario eliminado.');
    }
}
