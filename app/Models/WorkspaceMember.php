<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkspaceMember extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'accepted_at' => 'datetime',
    ];

    // Relationships
    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    // Scopes
    public function scopeAccepted($query)
    {
        return $query->whereNotNull('accepted_at');
    }

    public function scopePending($query)
    {
        return $query->whereNull('accepted_at');
    }

    // Methods
    public function accept()
    {
        $this->accepted_at = now();
        $this->save();
    }
}
