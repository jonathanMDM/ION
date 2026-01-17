<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Factura de Suscripción - {{ $company->name }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; }
        .header { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .company-info { text-align: right; }
        .details { margin-top: 40px; width: 100%; border-collapse: collapse; }
        .details th { background: #f8f8f8; border-bottom: 1px solid #ddd; padding: 10px; text-align: left; }
        .details td { padding: 10px; border-bottom: 1px solid #eee; }
        .total { margin-top: 30px; text-align: right; font-size: 1.2em; font-weight: bold; }
        .footer { margin-top: 50px; font-size: 0.8em; color: #777; text-align: center; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <div>
                @php
                    $logoPath = public_path('img/logo-horizontal.png');
                @endphp
                @if(file_exists($logoPath))
                    <img src="{{ $logoPath }}" alt="ION Inventory" style="width: 150px; margin-bottom: 10px;">
                @endif
            </div>
            <div class="company-info">
                <p><strong>Fecha:</strong> {{ $payment_date }}</p>
                <p><strong>Factura #:</strong> {{ $invoice_number }}</p>
                <p style="color: #10B981; font-weight: bold;">PAGADO</p>
            </div>
        </div>

        <hr>

        <div style="margin-top: 20px;">
            <p><strong>Facturar a:</strong></p>
            <p>{{ $company->name }}<br>
               NIT: {{ $company->nit }}<br>
               {{ $company->email }}</p>
        </div>

        <table class="details">
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th>Periodo</th>
                    <th>Monto</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Suscripción Plataforma ION - Plan {{ ucfirst($period_type) }}</td>
                    <td>{{ $period_start }} a {{ $period_end }}</td>
                    <td>${{ number_format($amount, 2) }} {{ $currency }}</td>
                </tr>
            </tbody>
        </table>

        <div class="total">
            Total Pagado: ${{ number_format($amount, 2) }} {{ $currency }}
        </div>

        <div class="footer">
            <p>Gracias por confiar en ION Inventory para la gestión de sus activos.</p>
        </div>
    </div>
</body>
</html>
