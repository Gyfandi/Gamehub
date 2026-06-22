<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPublisherController extends Controller
{
    public function index()
    {
        $publishers = Publisher::withCount('games')->orderBy('name')->get();
        return view('admin.publishers', compact('publishers'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100', 'description' => 'nullable|string']);
        Publisher::create($request->only('name', 'description'));
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Create Publisher',
            'description' => 'Added publisher: ' . $request->name,
        ]);
        return back()->with('success', 'Publisher added.');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:100', 'description' => 'nullable|string']);
        $publisher = Publisher::findOrFail($id);
        $publisher->update($request->only('name', 'description'));
        return back()->with('success', 'Publisher updated.');
    }

    public function destroy($id)
    {
        Publisher::findOrFail($id)->delete();
        return back()->with('success', 'Publisher deleted.');
    }
}
