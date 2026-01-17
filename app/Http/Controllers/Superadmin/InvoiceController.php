<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Invoice;
use App\Mail\CompanyInvoiceMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function create(Company $company)
    {
        return view('superadmin.invoices.create', compact('company'));
    }

    public function store(Request $request, Company $company)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'period_type' => 'required|in:monthly,yearly,other',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'notes' => 'nullable|string',
        ]);

        // Generate unique invoice number
        $invoiceNumber = 'INV-' . strtoupper(substr($company->name, 0, 3)) . '-' . now()->format('YmdHis');

        // Create Invoice record
        $invoice = Invoice::create([
            'company_id' => $company->id,
            'invoice_number' => $invoiceNumber,
            'amount' => $validated['amount'],
            'payment_date' => $validated['payment_date'],
            'period_type' => $validated['period_type'],
            'period_start' => $validated['period_start'],
            'period_end' => $validated['period_end'],
            'notes' => $validated['notes'],
            'status' => 'paid',
        ]);

        // Generate PDF
        $pdf = Pdf::loadView('invoices.pdf', [
            'company' => $company,
            'invoice_number' => $invoiceNumber,
            'amount' => $validated['amount'],
            'payment_date' => $validated['payment_date'],
            'period_type' => $validated['period_type'],
            'period_start' => $validated['period_start'],
            'period_end' => $validated['period_end'],
            'currency' => 'COP',
        ]);

        // Save PDF to storage
        $pdfPath = 'invoices/' . $invoiceNumber . '.pdf';
        Storage::put('public/' . $pdfPath, $pdf->output());
        $invoice->update(['pdf_path' => $pdfPath]);

        // Send Email
        try {
            Mail::to($company->email)->send(new CompanyInvoiceMail($invoice, storage_path('app/public/' . $pdfPath)));
        } catch (\Exception $e) {
            \Log::error('Error sending invoice email: ' . $e->getMessage());
        }

        // Update company subscription expiration
        $company->update([
            'subscription_expires_at' => $validated['period_end']
        ]);

        return redirect()->route('superadmin.companies.show', $company->id)
            ->with('success', 'Factura generada y enviada exitosamente.');
    }
}
