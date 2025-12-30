<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workspace extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'settings' => 'array',
    ];

    // Relationships
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function members()
    {
        return $this->hasMany(WorkspaceMember::class);
    }

    public function activeMembers()
    {
        return $this->hasMany(WorkspaceMember::class)->whereNotNull('accepted_at');
    }

    public function collections()
    {
        return $this->hasMany(ApiCollection::class);
    }

    public function invitations()
    {
        return $this->hasMany(WorkspaceInvitation::class);
    }

    public function pendingInvitations()
    {
        return $this->hasMany(WorkspaceInvitation::class)->where('status', 'pending');
    }

    // Tree Structure Helpers
    public function rootCollections()
    {
        return $this->hasMany(ApiCollection::class)->whereNull('parent_id')->orderBy('order');
    }

    public function rootRequests()
    {
        return $this->hasMany(ApiRequest::class)->whereNull('collection_id')->orderBy('order');
    }

    // Scopes
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOwnedBy($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeAccessibleBy($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('user_id', $userId)
              ->orWhere('type', 'public')
              ->orWhereHas('members', function ($m) use ($userId) {
                  $m->where('user_id', $userId)->whereNotNull('accepted_at');
              });
        });
    }

    // Business Logic Methods
    public function isMember($userId)
    {
        if ($this->user_id == $userId) {
            return true;
        }

        return $this->activeMembers()->where('user_id', $userId)->exists();
    }

    public function isOwner($userId)
    {
        return $this->user_id == $userId;
    }

    public function canManage($userId)
    {
        if ($this->isOwner($userId)) {
            return true;
        }

        $member = $this->activeMembers()->where('user_id', $userId)->first();
        return $member && in_array($member->role, ['admin', 'owner']);
    }

    public function getMemberRole($userId)
    {
        if ($this->isOwner($userId)) {
            return 'owner';
        }

        $member = $this->activeMembers()->where('user_id', $userId)->first();
        return $member ? $member->role : null;
    }
}
