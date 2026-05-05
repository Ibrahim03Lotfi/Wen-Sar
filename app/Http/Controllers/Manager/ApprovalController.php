<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    public function pending()
    {
        $pendingBusinesses = Business::where('status', 'pending')
            ->with(['owner', 'category', 'district', 'subArea'])
            ->latest()
            ->paginate(20);

        return view('manager.approvals.pending', compact('pendingBusinesses'));
    }

    public function show(Business $business)
    {
        $business->load(['owner', 'category', 'district', 'subArea']);
        return view('manager.approvals.show', compact('business'));
    }

    public function approve(Request $request, Business $business)
    {
        $business->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::guard('manager')->id(),
        ]);

        return redirect()->route('manager.approvals.pending')
            ->with('success', __('Business approved successfully.'));
    }

    public function reject(Request $request, Business $business)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $business->update([
            'status' => 'rejected',
        ]);

        // Optionally delete the business or keep it for record
        // For now, we'll just mark as rejected but could delete:
        // $business->delete();

        return redirect()->route('manager.approvals.pending')
            ->with('success', __('Business has been rejected.'));
    }

}
