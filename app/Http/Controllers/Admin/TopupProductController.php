<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TopupProduct;
use App\Models\Game;
use Illuminate\Http\Request;

class TopupProductController extends Controller
{
    public function index(Request $request)
    {
        $query = TopupProduct::with('game');
        
        if ($request->game_id) {
            $query->where('game_id', $request->game_id);
        }
        
        $products = $query->orderBy('game_id')->orderBy('sort_order')->paginate(20);
        $games = Game::where('available_topup', true)->orderBy('name')->get();
        
        return view('admin.topup-products.index', compact('products', 'games'));
    }

    public function create()
    {
        $games = Game::where('available_topup', true)->orderBy('name')->get();
        return view('admin.topup-products.create', compact('games'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'game_id' => 'required|exists:games,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:diamond,uc,membership,pass',
            'price' => 'required|numeric|min:0',
            'modal_price' => 'nullable|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:percent,nominal',
            'discount_value' => 'nullable|numeric|min:0',
            'discount_start' => 'nullable|date',
            'discount_end' => 'nullable|date|after_or_equal:discount_start',
            'is_discount_active' => 'boolean',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer'
        ]);

        if (!$request->has('is_discount_active')) $data['is_discount_active'] = false;
        if (!$request->has('is_popular')) $data['is_popular'] = false;
        if (!$request->has('is_active')) $data['is_active'] = false;
        if (!$request->has('sort_order')) $data['sort_order'] = 0;

        TopupProduct::create($data);

        return redirect()->route('admin.topup-products.index', ['game_id' => $request->game_id])
            ->with('success', 'Produk Top Up berhasil ditambahkan.');
    }

    public function edit(TopupProduct $topup_product)
    {
        $games = Game::where('available_topup', true)->orderBy('name')->get();
        return view('admin.topup-products.edit', [
            'product' => $topup_product,
            'games' => $games
        ]);
    }

    public function update(Request $request, TopupProduct $topup_product)
    {
        $data = $request->validate([
            'game_id' => 'required|exists:games,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:diamond,uc,membership,pass',
            'price' => 'required|numeric|min:0',
            'modal_price' => 'nullable|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:percent,nominal',
            'discount_value' => 'nullable|numeric|min:0',
            'discount_start' => 'nullable|date',
            'discount_end' => 'nullable|date|after_or_equal:discount_start',
            'is_discount_active' => 'boolean',
            'is_popular' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer'
        ]);

        if (!$request->has('is_discount_active')) $data['is_discount_active'] = false;
        if (!$request->has('is_popular')) $data['is_popular'] = false;
        if (!$request->has('is_active')) $data['is_active'] = false;
        if (!$request->has('sort_order')) $data['sort_order'] = 0;

        $topup_product->update($data);

        return redirect()->route('admin.topup-products.index', ['game_id' => $request->game_id])
            ->with('success', 'Produk Top Up berhasil diupdate.');
    }

    public function destroy(TopupProduct $topup_product)
    {
        $gameId = $topup_product->game_id;
        $topup_product->delete();

        return redirect()->route('admin.topup-products.index', ['game_id' => $gameId])
            ->with('success', 'Produk Top Up berhasil dihapus.');
    }
}
