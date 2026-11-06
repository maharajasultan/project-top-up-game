<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JokiType;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JokiTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = JokiType::with('game');
        
        if ($request->game_id) {
            $query->where('game_id', $request->game_id);
        }
        
        $jokiTypes = $query->orderBy('game_id')->orderBy('sort_order')->get();
        $games = Game::where('available_joki', true)->orderBy('name')->get();
        
        return view('admin.joki-types.index', compact('jokiTypes', 'games'));
    }

    public function create()
    {
        $games = Game::where('available_joki', true)->orderBy('name')->get();
        return view('admin.joki-types.create', compact('games'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'game_id' => 'required|exists:games,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit_name' => 'required|string|max:50',
            'min_order' => 'required|integer|min:1',
            'is_flat_price' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer'
        ]);

        $data['slug'] = Str::slug($request->name);
        if (!$request->has('is_flat_price')) $data['is_flat_price'] = false;
        if (!$request->has('is_active')) $data['is_active'] = false;
        if (!$request->has('sort_order')) $data['sort_order'] = 0;

        JokiType::create($data);

        return redirect()->route('admin.joki-types.index', ['game_id' => $request->game_id])
            ->with('success', 'Tipe Joki berhasil ditambahkan.');
    }

    public function edit(JokiType $joki_type)
    {
        $games = Game::where('available_joki', true)->orderBy('name')->get();
        return view('admin.joki-types.edit', [
            'type' => $joki_type,
            'games' => $games
        ]);
    }

    public function update(Request $request, JokiType $joki_type)
    {
        $data = $request->validate([
            'game_id' => 'required|exists:games,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit_name' => 'required|string|max:50',
            'min_order' => 'required|integer|min:1',
            'is_flat_price' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer'
        ]);

        $data['slug'] = Str::slug($request->name);
        if (!$request->has('is_flat_price')) $data['is_flat_price'] = false;
        if (!$request->has('is_active')) $data['is_active'] = false;
        if (!$request->has('sort_order')) $data['sort_order'] = 0;

        $joki_type->update($data);

        return redirect()->route('admin.joki-types.index', ['game_id' => $request->game_id])
            ->with('success', 'Tipe Joki berhasil diupdate.');
    }

    public function destroy(JokiType $joki_type)
    {
        $gameId = $joki_type->game_id;
        $joki_type->delete();

        return redirect()->route('admin.joki-types.index', ['game_id' => $gameId])
            ->with('success', 'Tipe Joki berhasil dihapus.');
    }
}
