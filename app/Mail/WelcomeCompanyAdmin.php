<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Company;
use App\Models\User;

class WelcomeCompanyAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $company;
    public $user;
    public $temporaryPassword;
    public $loginUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Company $company, User $user, string $temporaryPassword)
    {
        $this->company = $company;
        $this->user = $user;
        $this->temporaryPassword = $temporaryPassword;
        $this->loginUrl = route('login');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 Bienvenido a ION Inventory - ' . $this->company->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome-company-admin',
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
