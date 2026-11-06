<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class GameController extends Controller
{
    public function index()
    {
        $games = Game::orderBy('sort_order')->get();
        return view('admin.games.index', compact('games'));
    }

    public function create()
    {
        return view('admin.games.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'has_server_id' => 'boolean',
            'available_topup' => 'boolean',
            'available_joki' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer'
        ]);

        $data = $request->except('banner');
        $data['slug'] = Str::slug($request->name);
        
        if ($request->has('has_server_id')) $data['has_server_id'] = true; else $data['has_server_id'] = false;
        if ($request->has('available_topup')) $data['available_topup'] = true; else $data['available_topup'] = false;
        if ($request->has('available_joki')) $data['available_joki'] = true; else $data['available_joki'] = false;
        if ($request->has('is_active')) $data['is_active'] = true; else $data['is_active'] = false;
        if (!$request->has('sort_order')) $data['sort_order'] = 0;

        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->store('games', 'public');
            $data['banner'] = $path;
        }

        Game::create($data);

        return redirect()->route('admin.games.index')->with('success', 'Game berhasil ditambahkan.');
    }

    public function edit(Game $game)
    {
        return view('admin.games.edit', compact('game'));
    }

    public function update(Request $request, Game $game)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'has_server_id' => 'boolean',
            'available_topup' => 'boolean',
            'available_joki' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer'
        ]);

        $data = $request->except('banner');
        $data['slug'] = Str::slug($request->name);
        
        if ($request->has('has_server_id')) $data['has_server_id'] = true; else $data['has_server_id'] = false;
        if ($request->has('available_topup')) $data['available_topup'] = true; else $data['available_topup'] = false;
        if ($request->has('available_joki')) $data['available_joki'] = true; else $data['available_joki'] = false;
        if ($request->has('is_active')) $data['is_active'] = true; else $data['is_active'] = false;
        if (!$request->has('sort_order')) $data['sort_order'] = 0;

        if ($request->hasFile('banner')) {
            if ($game->banner) {
                Storage::disk('public')->delete($game->banner);
            }
            $path = $request->file('banner')->store('games', 'public');
            $data['banner'] = $path;
        }

        $game->update($data);

        return redirect()->route('admin.games.index')->with('success', 'Game berhasil diupdate.');
    }

    public function destroy(Game $game)
    {
        if ($game->banner) {
            Storage::disk('public')->delete($game->banner);
        }
        $game->delete();

        return redirect()->route('admin.games.index')->with('success', 'Game berhasil dihapus.');
    }
}
