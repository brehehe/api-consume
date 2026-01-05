<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiEnvironment extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    public function variables()
    {
        return $this->hasMany(ApiEnvironmentVariable::class, 'environment_id');
    }

    public function enabledVariables()
    {
        return $this->hasMany(ApiEnvironmentVariable::class, 'environment_id')->where('enabled', true);
    }

    // Business Logic Methods
    public function activate()
    {
        // Deactivate all other environments in the workspace
        $this->workspace->environments()->where('id', '!=', $this->id)->update(['is_active' => false]);

        // Activate this environment
        $this->update(['is_active' => true]);
    }

    public function getVariablesArray(): array
    {
        $variables = [];

        foreach ($this->enabledVariables as $variable) {
            $variables[$variable->key] = $variable->value;
        }

        return $variables;
    }
}
