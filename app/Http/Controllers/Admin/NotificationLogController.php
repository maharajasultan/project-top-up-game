<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationLog;
use Illuminate\Http\Request;

class NotificationLogController extends Controller
{
    public function index(Request $request)
    {
        $query = NotificationLog::latest();

        if ($request->order_type) {
            $query->where('order_type', $request->order_type);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->where('recipient_email', 'like', "%{$request->search}%")
                  ->orWhere('order_id', 'like', "%{$request->search}%");
        }

        $logs = $query->paginate(50)->withQueryString();

        return view('admin.notification-logs.index', compact('logs'));
    }
}
