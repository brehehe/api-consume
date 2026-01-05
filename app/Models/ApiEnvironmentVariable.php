<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiEnvironmentVariable extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_secret' => 'boolean',
        'enabled' => 'boolean',
    ];

    // Relationships
    public function environment()
    {
        return $this->belongsTo(ApiEnvironment::class, 'environment_id');
    }

    // Validation
    public static function boot()
    {
        parent::boot();

        static::saving(function ($variable) {
            // Validate key format: only alphanumeric and underscores
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $variable->key)) {
                throw new \InvalidArgumentException('Variable key must contain only alphanumeric characters and underscores.');
            }
        });
    }
}
