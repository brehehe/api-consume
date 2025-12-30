<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WorkspaceInvitation extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invitation) {
            if (empty($invitation->token)) {
                $invitation->token = Str::random(64);
            }
            if (empty($invitation->expires_at)) {
                $invitation->expires_at = now()->addDays(7);
            }
        });
    }

    // Relationships
    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    // Methods
    public function accept()
    {
        if ($this->isExpired()) {
            $this->status = 'expired';
            $this->save();
            return false;
        }

        $this->status = 'accepted';
        $this->save();

        return true;
    }

    public function reject()
    {
        $this->status = 'rejected';
        $this->save();
    }

    public function isExpired()
    {
        return $this->expires_at < now() || $this->status === 'expired';
    }

    public function isPending()
    {
        return $this->status === 'pending' && !$this->isExpired();
    }
}
