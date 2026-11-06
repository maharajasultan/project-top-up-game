<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JokiLoginMethod;
use App\Models\Game;
use Illuminate\Http\Request;

class JokiLoginMethodController extends Controller
{
    public function index(Request $request)
    {
        $query = JokiLoginMethod::with('game');
        
        if ($request->game_id) {
            $query->where('game_id', $request->game_id);
        }
        
        $methods = $query->orderBy('game_id')->orderBy('sort_order')->get();
        $games = Game::where('available_joki', true)->orderBy('name')->get();
        
        return view('admin.joki-login-methods.index', compact('methods', 'games'));
    }

    public function create()
    {
        $games = Game::where('available_joki', true)->orderBy('name')->get();
        return view('admin.joki-login-methods.create', compact('games'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'game_id' => 'required|exists:games,id',
            'method_name' => 'required|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer'
        ]);

        if (!$request->has('is_active')) $data['is_active'] = false;
        if (!$request->has('sort_order')) $data['sort_order'] = 0;

        JokiLoginMethod::create($data);

        return redirect()->route('admin.joki-login-methods.index', ['game_id' => $request->game_id])
            ->with('success', 'Metode Login Joki berhasil ditambahkan.');
    }

    public function edit(JokiLoginMethod $joki_login_method)
    {
        $games = Game::where('available_joki', true)->orderBy('name')->get();
        return view('admin.joki-login-methods.edit', [
            'method' => $joki_login_method,
            'games' => $games
        ]);
    }

    public function update(Request $request, JokiLoginMethod $joki_login_method)
    {
        $data = $request->validate([
            'game_id' => 'required|exists:games,id',
            'method_name' => 'required|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer'
        ]);

        if (!$request->has('is_active')) $data['is_active'] = false;
        if (!$request->has('sort_order')) $data['sort_order'] = 0;

        $joki_login_method->update($data);

        return redirect()->route('admin.joki-login-methods.index', ['game_id' => $request->game_id])
            ->with('success', 'Metode Login Joki berhasil diupdate.');
    }

    public function destroy(JokiLoginMethod $joki_login_method)
    {
        $gameId = $joki_login_method->game_id;
        $joki_login_method->delete();

        return redirect()->route('admin.joki-login-methods.index', ['game_id' => $gameId])
            ->with('success', 'Metode Login Joki berhasil dihapus.');
    }
}
