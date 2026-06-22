<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::withCount(['libraries', 'reviews', 'transactions'])->orderByDesc('created_at')->get();
        return view('admin.users', compact('users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['role' => 'required|in:admin,buyer']);
        User::findOrFail($id)->update(['role' => $request->role]);
        return back()->with('success', 'User role updated.');
    }
}
