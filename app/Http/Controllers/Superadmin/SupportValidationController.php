<?php
namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;

class SupportValidationController extends Controller
{
    public function index()
    {
        return view('superadmin.support.validation');
    }

    public function validateCustomer(Request $request)
    {
        $validated = $request->validate([
            'validation_type' => 'required|in:nit,email',
            'validation_value' => 'required|string',
        ]);

        $company = null;
        $user = null;

        // Search by NIT or Email
        if ($validated['validation_type'] === 'nit') {
            $company = Company::where('nit', $validated['validation_value'])->first();
        } else {
            // Search by email - could be company email or user email
            $company = Company::where('email', $validated['validation_value'])->first();
            
            // If not found by company email, try user email
            if (!$company) {
                $user = User::where('email', $validated['validation_value'])->first();
                if ($user) {
                    $company = $user->company;
                }
            }
        }

        if (!$company) {
            return redirect()->back()->with('error', 'Cliente no encontrado. Verifique el NIT o correo electrónico.');
        }

        // Get admin user
        if (!$user) {
            $user = $company->users()->where('role', 'admin')->first();
        }

        return view('superadmin.support.validation', [
            'company' => $company,
            'user' => $user
        ]);
    }
}
