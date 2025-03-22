<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Attribute extends Model
{
    protected $fillable = ['name', 'type', 'options'];

    protected $casts = ['options' => 'array'];

    public function jobs(): BelongsToMany
    {
        return $this->belongsToMany(Job::class, 'job_attribute_value')->withPivot('value');
    }
}
