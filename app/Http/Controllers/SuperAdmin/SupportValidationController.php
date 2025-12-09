<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;

class SupportValidationController extends Controller
{
    public function index()
    {
        return view('superadmin.support.validation');
    }

    public function validateCustomer(Request $request)
    {
        $request->validate([
            'validation_type' => 'required|in:nit,email',
            'validation_value' => 'required|string',
        ]);

        $company = null;
        $user = null;

        if ($request->validation_type === 'nit') {
            $company = Company::where('nit', $request->validation_value)->first();
            if ($company) {
                // Find the main admin for this company
                $user = User::where('company_id', $company->id)
                            ->where('role', 'admin')
                            ->first();
            }
        } else {
            $user = User::where('email', $request->validation_value)->first();
            if ($user) {
                $company = $user->company;
            }
        }

        if (!$company) {
            return back()->with('error', 'No se encontró ninguna empresa o usuario con esos datos.')->withInput();
        }

        return view('superadmin.support.validation', compact('company', 'user'));
    }
}
