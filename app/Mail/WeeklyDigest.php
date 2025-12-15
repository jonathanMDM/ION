<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WeeklyDigest extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $companyName;
    public $weekStart;
    public $weekEnd;
    public $stats;
    public $recentAssets;
    public $lowStockAssets;
    public $upcomingMaintenances;

    /**
     * Create a new message instance.
     */
    public function __construct(
        string $userName,
        string $companyName,
        string $weekStart,
        string $weekEnd,
        array $stats,
        $recentAssets,
        $lowStockAssets,
        $upcomingMaintenances
    ) {
        $this->userName = $userName;
        $this->companyName = $companyName;
        $this->weekStart = $weekStart;
        $this->weekEnd = $weekEnd;
        $this->stats = $stats;
        $this->recentAssets = $recentAssets;
        $this->lowStockAssets = $lowStockAssets;
        $this->upcomingMaintenances = $upcomingMaintenances;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📊 Resumen Semanal - ION Inventory',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.weekly-digest',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

