<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiCollection extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    // Relationships
    public function workspace()
    {
        return $this->belongsTo(Workspace::class, 'workspace_id', 'id');
    }

    public function requests()
    {
        return $this->hasMany(ApiRequest::class, 'collection_id')->orderBy('order');
    }

    public function parent()
    {
        return $this->belongsTo(ApiCollection::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ApiCollection::class, 'parent_id')->orderBy('order');
    }
}
