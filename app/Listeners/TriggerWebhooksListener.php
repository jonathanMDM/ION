<?php

namespace App\Listeners;

use App\Jobs\SendWebhookJob;
use App\Models\Webhook;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class TriggerWebhooksListener
{
    /**
     * Handle the event.
     */
    public function handle($event, $eventName = null): void
    {
        // Si el evento viene del EventDispatcher, el nombre puede venir como segundo argumento o inferirse
        // Para simplificar, asumiremos que pasamos el nombre del evento explícitamente o lo inferimos
        
        // Mapeo de clases de evento a nombres de string (si usáramos eventos personalizados)
        // Pero aquí vamos a usar un enfoque más genérico o llamar a este listener manualmente desde el modelo/controlador
        
        // NOTA: Para que esto funcione automáticamente con eventos de Eloquent, necesitaríamos mapearlos en EventServiceProvider
        // O usar un Observer.
        
        // Vamos a asumir que $eventName es pasado o que $event tiene una propiedad que nos dice qué es.
        
        // Si $event es un modelo Eloquent (e.g. Asset) y estamos en un Observer:
        // Pero aquí estamos en un Listener genérico.
        
        // Vamos a simplificar: Este listener recibirá un objeto con propiedad `eventName` y `data`.
    }

    public function trigger($eventName, $data)
    {
        $webhooks = Webhook::where('is_active', true)->get();

        foreach ($webhooks as $webhook) {
            if (in_array($eventName, $webhook->events ?? [])) {
                SendWebhookJob::dispatch($webhook->url, [
                    'event' => $eventName,
                    'data' => $data,
                    'timestamp' => now()->toIso8601String(),
                ], $webhook->secret);
            }
        }
    }
}
