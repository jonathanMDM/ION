<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $url;
    public $payload;
    public $secret;

    /**
     * Create a new job instance.
     */
    public function __construct($url, $payload, $secret = null)
    {
        $this->url = $url;
        $this->payload = $payload;
        $this->secret = $secret;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $headers = [
                'Content-Type' => 'application/json',
                'User-Agent' => 'Paladin-Inventory-Webhook/1.0',
            ];

            if ($this->secret) {
                $signature = hash_hmac('sha256', json_encode($this->payload), $this->secret);
                $headers['X-Paladin-Signature'] = $signature;
            }

            $response = Http::withHeaders($headers)->post($this->url, $this->payload);

            if ($response->failed()) {
                Log::error("Webhook failed to {$this->url}: " . $response->body());
            } else {
                Log::info("Webhook sent to {$this->url}");
            }
        } catch (\Exception $e) {
            Log::error("Webhook exception for {$this->url}: " . $e->getMessage());
        }
    }
}
