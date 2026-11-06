<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\TopupProduct;
use App\Models\JokiType;
use App\Models\JokiTier;
use App\Models\OrderTopup;
use App\Models\OrderJoki;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct()
    {
        \Midtrans\Config::$serverKey   = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;
    }

    public function processTopup(Request $request)
    {
        $request->validate([
            'game_slug'      => 'required|string',
            'product_id'     => 'required|integer',
            'user_id'        => 'required|string',
            'server_id'      => 'nullable|string',
            'username'       => 'required|string',
            'email'          => 'required|email',
            'phone'          => 'required|string',
            'payment_method' => 'nullable|string',
        ]);

        // Lookup game & produk dari database
        $game    = Game::where('slug', $request->game_slug)->where('is_active', true)->firstOrFail();
        $product = TopupProduct::where('id', $request->product_id)
                               ->where('game_id', $game->id)
                               ->where('is_active', true)
                               ->firstOrFail();

        $amount  = (int) $product->final_price;
        $invoice = 'TP-' . date('YmdHis') . rand(100, 999);

        $order = OrderTopup::create([
            'invoice_number'  => $invoice,
            'user_id'         => auth()->id(), // null jika guest
            'game_id'         => $game->id,
            'product_id'      => $product->id,
            'user_game_id'    => $request->user_id,
            'server_id'       => $request->server_id,
            'username_result' => $request->username,
            'email'           => $request->email,
            'phone'           => $request->phone,
            'original_price'  => $product->price,
            'discount_amount' => $product->is_on_discount ? ($product->price - $product->final_price) : 0,
            'discount_type'   => $product->discount_type ?? 'nominal',
            'final_price'     => $amount,
            'status'          => 'pending',
            'expired_at'      => now()->addMinutes(15),
        ]);

        $params = [
            'transaction_details' => [
                'order_id'     => $invoice,
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => $request->username,
                'email'      => $request->email,
                'phone'      => $request->phone,
            ],
            'item_details' => [
                [
                    'id'       => $product->id,
                    'price'    => $amount,
                    'quantity' => 1,
                    'name'     => $product->name . ' - ' . $game->name,
                ]
            ],
        ];

        if ($request->filled('payment_method')) {
            $params['enabled_payments'] = [$request->payment_method];
        }

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $order->update(['snap_token' => $snapToken]);

            return response()->json([
                'success'    => true,
                'snap_token' => $snapToken,
                'invoice'    => $invoice
            ]);
        } catch (\Exception $e) {
            // Rollback order jika Midtrans gagal
            $order->delete();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function processJoki(Request $request)
    {
        $request->validate([
            'game_slug'      => 'required|string',
            'joki_type_id'   => 'required|integer',
            'joki_tier_id'   => 'nullable|integer',
            'qty'            => 'required|integer|min:1',
            'login_method'   => 'required|string',
            'email_akun'     => 'required|string',
            'password_akun'  => 'required|string',
            'price'          => 'required|numeric|min:0',
            'email'          => 'required|email',
            'phone'          => 'required|string',
            'payment_method' => 'nullable|string',
        ]);

        // Lookup game & joki type dari database
        $game      = Game::where('slug', $request->game_slug)->where('is_active', true)->firstOrFail();
        $jokiType  = JokiType::where('id', $request->joki_type_id)->where('game_id', $game->id)->firstOrFail();

        // Ambil tier jika disertakan
        $jokiTier  = null;
        if ($request->filled('joki_tier_id')) {
            $jokiTier = JokiTier::where('id', $request->joki_tier_id)
                                ->where('joki_type_id', $jokiType->id)
                                ->first();
        }

        // Harga per unit dari tier yang dipilih, fallback ke request price
        $pricePerUnit = $jokiTier ? (int) $jokiTier->price_per_unit : 0;
        $amount       = $pricePerUnit > 0 ? $pricePerUnit * (int) $request->qty : (int) $request->price;
        $invoice      = 'JK-' . date('YmdHis') . rand(100, 999);

        $order = OrderJoki::create([
            'invoice_number'          => $invoice,
            'user_id'                 => auth()->id(), // null jika guest
            'game_id'                 => $game->id,
            'joki_type_id'            => $jokiType->id,
            'joki_tier_id'            => $jokiTier?->id ?? ($jokiType->tiers()->first()?->id ?? null),
            'login_method_id'         => null, // Bisa di-extend
            'account_email'           => $request->email_akun,
            'account_password'        => bcrypt($request->password_akun),
            'v2l_status'              => 'yes',
            'qty'                     => $request->qty,
            'original_price_per_unit' => $pricePerUnit ?: $amount,
            'discount_amount'         => 0,
            'discount_type'           => 'nominal',
            'final_total_price'       => $amount,
            'customer_email'          => $request->email,
            'customer_phone'          => $request->phone,
            'status'                  => 'pending_payment',
            'expired_at'              => now()->addMinutes(15),
        ]);

        $tierName = $jokiTier ? $jokiTier->tier_name : '';
        $params = [
            'transaction_details' => [
                'order_id'     => $invoice,
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => 'Customer',
                'email'      => $request->email,
                'phone'      => $request->phone,
            ],
            'item_details' => [
                [
                    'id'       => $jokiType->id,
                    'price'    => $amount,
                    'quantity' => 1,
                    'name'     => $jokiType->name . ($tierName ? " ({$tierName})" : '') . ' x' . $request->qty,
                ]
            ],
        ];

        if ($request->filled('payment_method')) {
            $params['enabled_payments'] = [$request->payment_method];
        }

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $order->update(['snap_token' => $snapToken]);

            return response()->json([
                'success'    => true,
                'snap_token' => $snapToken,
                'invoice'    => $invoice
            ]);
        } catch (\Exception $e) {
            $order->delete();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
