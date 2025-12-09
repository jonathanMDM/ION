<?php

namespace App\Observers;

use App\Models\Asset;
use App\Models\Webhook;
use App\Jobs\SendWebhookJob;

class AssetObserver
{
    /**
     * Handle the Asset "created" event.
     */
    public function created(Asset $asset): void
    {
        $this->triggerWebhooks('asset.created', $asset->toArray());
    }

    /**
     * Handle the Asset "updated" event.
     */
    public function updated(Asset $asset): void
    {
        $this->triggerWebhooks('asset.updated', $asset->toArray());
    }

    /**
     * Handle the Asset "deleted" event.
     */
    public function deleted(Asset $asset): void
    {
        //
    }

    /**
     * Handle the Asset "restored" event.
     */
    public function restored(Asset $asset): void
    {
        //
    }

    /**
     * Handle the Asset "force deleted" event.
     */
    public function forceDeleted(Asset $asset): void
    {
        //
    }

    protected function triggerWebhooks($event, $data)
    {
        $webhooks = Webhook::where('is_active', true)->get();

        foreach ($webhooks as $webhook) {
            if (in_array($event, $webhook->events ?? [])) {
                SendWebhookJob::dispatch($webhook->url, [
                    'event' => $event,
                    'data' => $data,
                    'timestamp' => now()->toIso8601String(),
                ], $webhook->secret);
            }
        }
    }
}
