<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogActivity
{
    protected static function bootLogActivity()
    {
        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(function ($model) use ($event) {
                $model->logActivity($event);
            });
        }
    }

    protected function logActivity($event)
    {
        $changes = null;

        if ($event === 'updated') {
            $changes = [
                'before' => array_intersect_key($this->getOriginal(), $this->getDirty()),
                'after' => $this->getDirty(),
            ];
        } elseif ($event === 'created') {
            $changes = $this->getAttributes();
        } elseif ($event === 'deleted') {
            $changes = $this->getAttributes();
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $event,
            'model' => class_basename($this),
            'model_id' => $this->id,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'changes' => $changes,
        ]);
    }
}
