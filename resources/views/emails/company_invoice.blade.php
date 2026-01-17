<x-mail::message>
# 🎉 Pago Recibido - {{ $company->name }}

Estimado cliente,

Hemos recibido exitosamente su pago de suscripción para **ION Inventory**.

## 📋 Detalles del Pago

- **Número de Factura:** {{ $invoice->invoice_number }}
- **Monto Pagado:** ${{ number_format($invoice->amount, 2) }} {{ $invoice->currency }}
- **Periodo Cubierto:** {{ $invoice->period_start->format('d/m/Y') }} al {{ $invoice->period_end->format('d/m/Y') }}
- **Estado:** ✅ PAGADO

Su suscripción está activa hasta el **{{ $invoice->period_end->format('d/m/Y') }}**.

Adjunto a este correo encontrará su factura oficial en formato PDF.

<x-mail::button :url="config('app.url')" color="success">
Acceder a ION Inventory
</x-mail::button>

Si tiene alguna pregunta sobre su factura, no dude en contactarnos.

Gracias por confiar en ION Inventory,<br>
**El equipo de {{ config('app.name') }}**
</x-mail::message>
