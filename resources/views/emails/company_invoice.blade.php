<x-mail::message>
# Hola, {{ $company->name }}

Se ha registrado un nuevo pago de su suscripción en **ION Inventory**.

**Detalles del Pago:**
- **Factura:** {{ $invoice->invoice_number }}
- **Monto:** ${{ number_format($invoice->amount, 2) }} {{ $invoice->currency }}
- **Periodo:** {{ $invoice->period_start->format('d/m/Y') }} al {{ $invoice->period_end->format('d/m/Y') }}

Adjunto a este correo encontrará su factura en formato PDF.

<x-mail::button :url="config('app.url')">
Ir a ION Inventory
</x-mail::button>

Gracias por su confianza,<br>
El equipo de {{ config('app.name') }}
</x-mail::message>
