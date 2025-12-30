<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Workspace Relationships
    public function workspaces()
    {
        return $this->hasMany(Workspace::class);
    }

    public function workspaceMemberships()
    {
        return $this->hasMany(WorkspaceMember::class);
    }

    public function accessibleWorkspaces()
    {
        return Workspace::accessibleBy($this->id);
    }

    public function workspaceInvitations()
    {
        return $this->hasMany(WorkspaceInvitation::class, 'email', 'email')
            ->where('status', 'pending')
            ->where('expires_at', '>', now());
    }
}
