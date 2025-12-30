<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiRequest extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'headers' => 'array',
        'query_params' => 'array',
        'auth_data' => 'array',
        'last_response' => 'array',
        'form_data' => 'array',
        'form_urlencoded_data' => 'array',
    ];

    // Relationships
    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    public function collection()
    {
        return $this->belongsTo(ApiCollection::class);
    }

    // Helper methods
    public function getFullUrl()
    {
        $url = $this->url;

        if (!empty($this->query_params) && is_array($this->query_params)) {
            $enabledParams = collect($this->query_params)->filter(fn($p) => $p['enabled'] ?? false);
            if ($enabledParams->count() > 0) {
                $queryString = http_build_query($enabledParams->pluck('value', 'key')->toArray());
                $url .= (str_contains($url, '?') ? '&' : '?') . $queryString;
            }
        }

        return $url;
    }

    public function getEnabledHeaders()
    {
        if (empty($this->headers) || !is_array($this->headers)) {
            return [];
        }

        return collect($this->headers)
            ->filter(fn($h) => $h['enabled'] ?? false)
            ->pluck('value', 'key')
            ->toArray();
    }
}
