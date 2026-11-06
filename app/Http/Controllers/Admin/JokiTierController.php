<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JokiTier;
use App\Models\JokiType;
use Illuminate\Http\Request;

class JokiTierController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->joki_type_id) {
            return redirect()->route('admin.joki-types.index')->with('error', 'Pilih tipe joki terlebih dahulu untuk melihat tier.');
        }

        $jokiType = JokiType::with('game')->findOrFail($request->joki_type_id);
        $tiers = JokiTier::where('joki_type_id', $jokiType->id)->orderBy('tier_order')->get();
        
        return view('admin.joki-tiers.index', compact('tiers', 'jokiType'));
    }

    public function create(Request $request)
    {
        if (!$request->joki_type_id) {
            return redirect()->route('admin.joki-types.index');
        }
        
        $jokiType = JokiType::findOrFail($request->joki_type_id);
        return view('admin.joki-tiers.create', compact('jokiType'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'joki_type_id' => 'required|exists:joki_types,id',
            'tier_name' => 'required|string|max:255',
            'tier_order' => 'required|integer',
            'price_per_unit' => 'required|numeric|min:0',
            'min_order' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'discount_type' => 'nullable|in:percent,nominal',
            'discount_value' => 'nullable|numeric|min:0',
            'discount_start' => 'nullable|date',
            'discount_end' => 'nullable|date|after_or_equal:discount_start',
            'is_discount_active' => 'boolean',
            'is_active' => 'boolean'
        ]);

        if (!$request->has('is_discount_active')) $data['is_discount_active'] = false;
        if (!$request->has('is_active')) $data['is_active'] = false;

        JokiTier::create($data);

        return redirect()->route('admin.joki-tiers.index', ['joki_type_id' => $request->joki_type_id])
            ->with('success', 'Tier/Paket Joki berhasil ditambahkan.');
    }

    public function edit(JokiTier $joki_tier)
    {
        $jokiType = $joki_tier->jokiType;
        return view('admin.joki-tiers.edit', [
            'tier' => $joki_tier,
            'jokiType' => $jokiType
        ]);
    }

    public function update(Request $request, JokiTier $joki_tier)
    {
        $data = $request->validate([
            'tier_name' => 'required|string|max:255',
            'tier_order' => 'required|integer',
            'price_per_unit' => 'required|numeric|min:0',
            'min_order' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'discount_type' => 'nullable|in:percent,nominal',
            'discount_value' => 'nullable|numeric|min:0',
            'discount_start' => 'nullable|date',
            'discount_end' => 'nullable|date|after_or_equal:discount_start',
            'is_discount_active' => 'boolean',
            'is_active' => 'boolean'
        ]);

        if (!$request->has('is_discount_active')) $data['is_discount_active'] = false;
        if (!$request->has('is_active')) $data['is_active'] = false;

        $joki_tier->update($data);

        return redirect()->route('admin.joki-tiers.index', ['joki_type_id' => $joki_tier->joki_type_id])
            ->with('success', 'Tier/Paket Joki berhasil diupdate.');
    }

    public function destroy(JokiTier $joki_tier)
    {
        $typeId = $joki_tier->joki_type_id;
        $joki_tier->delete();

        return redirect()->route('admin.joki-tiers.index', ['joki_type_id' => $typeId])
            ->with('success', 'Tier/Paket Joki berhasil dihapus.');
    }
}
