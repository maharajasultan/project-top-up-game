<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name'               => 'nullable|string|max:100',
            'whatsapp_number'         => 'nullable|string|max:20',
            'instagram_url'           => 'nullable|max:255',
            'midtrans_client_key'     => 'nullable|string|max:255',
            'midtrans_server_key'     => 'nullable|string|max:255',
            'midtrans_is_production'  => 'nullable|in:0,1',
            'midtrans_env'            => 'nullable|in:sandbox,production',
            'mail_host'               => 'nullable|string|max:255',
            'mail_port'               => 'nullable|integer',
            'mail_username'           => 'nullable|string|max:255',
            'mail_password'           => 'nullable|string|max:255',
            'mail_from_address'       => 'nullable|email|max:255',
            'mail_from_name'          => 'nullable|string|max:100',
            'work_start_hour'         => 'nullable|integer|min:0|max:23',
            'work_end_hour'           => 'nullable|integer|min:0|max:23',
        ]);

        $keys = [
            'site_name',
            'whatsapp_number',
            'instagram_url',
            'midtrans_client_key',
            'midtrans_server_key',
            'midtrans_is_production',
            'midtrans_env',
            'mail_host',
            'mail_port',
            'mail_username',
            'mail_password',
            'mail_from_address',
            'mail_from_name',
            'work_start_hour',
            'work_end_hour',
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $request->input($key)]
                );
            }
        }

        return redirect()->route('admin.settings.index')->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
