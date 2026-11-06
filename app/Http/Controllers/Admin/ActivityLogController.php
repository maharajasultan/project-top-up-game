<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Admin;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('admin')->latest();

        if ($request->admin_id) {
            $query->where('admin_id', $request->admin_id);
        }

        if ($request->module) {
            $query->where('module', $request->module);
        }

        if ($request->search) {
            $query->where('description', 'like', "%{$request->search}%");
        }

        $logs   = $query->paginate(50)->withQueryString();
        $admins = Admin::orderBy('name')->get();

        $modules = ActivityLog::distinct()->pluck('module')->filter()->sort()->values();

        return view('admin.activity-logs.index', compact('logs', 'admins', 'modules'));
    }
}
