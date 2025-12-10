<?php
namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class SupportValidationController extends Controller
{
    public function index()
    {
        return view('superadmin.support-validation.index');
    }

    public function validateCustomer(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $company = Company::where('email', $validated['email'])->first();

        if ($company) {
            return redirect()->back()->with([
                'success' => 'Cliente encontrado',
                'company' => $company
            ]);
        }

        return redirect()->back()->with('error', 'Cliente no encontrado');
    }
}
