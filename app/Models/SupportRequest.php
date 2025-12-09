<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportRequest extends Model
{
    protected $fillable = [
        'user_id',
        'company_id',
        'subject',
        'category',
        'message',
        'attachment_path',
        'attachment_name',
        'status',
        'admin_notes',
        'user_response',
        'resolved_at',
        'user_responded_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
        'user_responded_at' => 'datetime',
    ];

    /**
     * Get the user that created the support request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the company associated with the support request.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the status badge color.
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'yellow',
            'in_progress' => 'blue',
            'resolved' => 'green',
            'closed' => 'gray',
            default => 'gray',
        };
    }

    /**
     * Get the status label.
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Pendiente',
            'in_progress' => 'En Progreso',
            'resolved' => 'Resuelto',
            'closed' => 'Cerrado',
            default => 'Desconocido',
        };
    }
    /**
     * Get the messages for the support request.
     */
    public function messages()
    {
        return $this->hasMany(SupportMessage::class)->orderBy('created_at', 'asc');
    }

    /**
     * Mark the request as resolved by user.
     */
    public function resolveByUser()
    {
        $this->update([
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);
    }
}
