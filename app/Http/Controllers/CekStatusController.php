<?php

namespace App\Http\Controllers;

use App\Models\OrderTopup;
use App\Models\OrderJoki;
use Illuminate\Http\Request;

class CekStatusController extends Controller
{
    public function check(Request $request)
    {
        $request->validate([
            'invoice' => 'required|string|max:50',
        ]);

        $invoice = trim($request->invoice);
        
        $orderTopup = OrderTopup::with(['game', 'product'])->where('invoice_number', $invoice)->first();
        if ($orderTopup) {
            return response()->json([
                'success' => true,
                'type' => 'topup',
                'order' => $orderTopup
            ]);
        }

        $orderJoki = OrderJoki::with(['game', 'jokiType', 'jokiTier'])->where('invoice_number', $invoice)->first();
        if ($orderJoki) {
            return response()->json([
                'success' => true,
                'type' => 'joki',
                'order' => $orderJoki
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Pesanan dengan nomor invoice tersebut tidak ditemukan.'
        ], 404);
    }
}
