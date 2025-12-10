<?php
namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\SupportRequest;
use Illuminate\Http\Request;

class SuperadminSupportController extends Controller
{
    public function index()
    {
        $supportRequests = SupportRequest::with('user', 'company')->latest()->paginate(20);
        return view('superadmin.support.index', compact('supportRequests'));
    }

    public function show(SupportRequest $supportRequest)
    {
        $supportRequest->load('user', 'company', 'messages');
        return view('superadmin.support.show', compact('supportRequest'));
    }

    public function updateStatus(Request $request, SupportRequest $supportRequest)
    {
        $supportRequest->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Estado actualizado');
    }
}
