<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latestFirst();

        if ($request->module) {
            $query->where('module', $request->module);
        }
        if ($request->action) {
            $query->where('action', $request->action);
        }
        if ($request->user_search) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', "%{$request->user_search}%");
            });
        }
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(30);
        $modules = ActivityLog::distinct()->pluck('module')->filter()->sort()->values();

        return view('admin.activity-logs.index', compact('logs', 'modules'));
    }

    public function destroy(ActivityLog $activityLog)
    {
        $activityLog->delete();
        return back()->with('success', 'Log berhasil dihapus.');
    }

    public function clearAll()
    {
        ActivityLog::truncate();
        return redirect()->route('admin.activity-logs.index')->with('success', 'Semua log berhasil dihapus.');
    }
}
