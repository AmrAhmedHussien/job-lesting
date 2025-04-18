<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Location extends Model
{
    protected $fillable = ['city', 'country', 'state'];
    
    public function jobs(): BelongsToMany
    {
        return $this->belongsToMany(Job::class, 'job_location');
    }
}
