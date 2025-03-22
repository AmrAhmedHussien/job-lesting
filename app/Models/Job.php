<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Job extends Model
{
    protected $fillable = [
        'title',
        'description',
        'company_name',
        'salary_min',
        'salary_max',
        'is_remote',
        'job_type',
        'status',
        'published_at',
    ];

    protected $casts = [
        'is_remote' => 'boolean',
        'salary_min' => 'float',
        'salary_max' => 'float',
        'published_at' => 'datetime',
        'status' => 'string',
    ];

    /**
     * The valid status values.
     *
     * @var array
     */
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_ARCHIVED = 'archived';

    /**
     * Get the valid status options.
     *
     * @return array
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_DRAFT,
            self::STATUS_PUBLISHED,
            self::STATUS_ARCHIVED,
        ];
    }

    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class,'job_language');
    }
    
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class,'job_category');
    }

    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(Location::class,'job_location');
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class,'job_attribute_value')->withPivot('value');
    }

    public function scopeFilterByText(Builder $query, string $field, string $operator, string $value): Builder
    {
        if ($operator === 'LIKE') {
            return $query->where($field, 'LIKE', "%{$value}%");
        }
        
        return $query->where($field, $operator, $value);
    }
    
    public function scopeFilterByNumeric(Builder $query, string $field, string $operator, float $value): Builder
    {
        return $query->where($field, $operator, $value);
    }
    
    public function scopeFilterByBoolean(Builder $query, string $field, bool $value): Builder
    {
        return $query->where($field, $value);
    }
    
    public function scopeFilterByEnum(Builder $query, string $field, string $operator, $value): Builder
    {
        if ($operator === 'IN') {
            return $query->whereIn($field, is_array($value) ? $value : explode(',', $value));
        }
        
        return $query->where($field, $operator, $value);
    }
    
    public function scopeFilterByDate(Builder $query, string $field, string $operator, string $value): Builder
    {
        return $query->where($field, $operator, $value);
    }
    
    public function scopeFilterByLanguages(Builder $query, string $operator, $values): Builder
    {
        $values = is_array($values) ? $values : explode(',', $values);
        
        if ($operator === 'HAS_ANY') {
            return $query->whereHas('languages', function ($q) use ($values) {
                $q->whereIn('languages.id', $values);
            });
        } elseif ($operator === 'IS_ANY') {
            return $query->whereHas('languages', function ($q) use ($values) {
                $q->whereIn('languages.name', $values);
            });
        } elseif ($operator === '=') {
            return $query->whereHas('languages', function ($q) use ($values) {
                $q->where('languages.name', $values[0]);
            });
        } elseif ($operator === 'EXISTS') {
            return $query->whereHas('languages');
        }
        
        return $query;
    }
    
    public function scopeFilterByCategories(Builder $query, string $operator, $values): Builder
    {
        $values = is_array($values) ? $values : explode(',', $values);
        
        if ($operator === 'HAS_ANY') {
            return $query->whereHas('categories', function ($q) use ($values) {
                $q->whereIn('categories.id', $values);
            });
        } elseif ($operator === 'IS_ANY') {
            return $query->whereHas('categories', function ($q) use ($values) {
                $q->whereIn('categories.name', $values);
            });
        } elseif ($operator === '=') {
            return $query->whereHas('categories', function ($q) use ($values) {
                $q->where('categories.name', $values[0]);
            });
        } elseif ($operator === 'EXISTS') {
            return $query->whereHas('categories');
        }
        
        return $query;
    }
    
    public function scopeFilterByLocations(Builder $query, string $operator, $values, string $field = 'city'): Builder
    {
        $values = is_array($values) ? $values : explode(',', $values);
        
        if ($operator === 'HAS_ANY') {
            return $query->whereHas('locations', function ($q) use ($values) {
                $q->whereIn('locations.id', $values);
            });
        } elseif ($operator === 'IS_ANY') {
            return $query->whereHas('locations', function ($q) use ($values, $field) {
                $q->whereIn('locations.' . $field, $values);
            });
        } elseif ($operator === '=') {
            return $query->whereHas('locations', function ($q) use ($values, $field) {
                $q->where('locations.' . $field, $values[0]);
            });
        } elseif ($operator === 'EXISTS') {
            return $query->whereHas('locations');
        }
        
        return $query;
    }
    
    public function scopeFilterByLocationsCity(Builder $query, string $operator, $values): Builder
    {
        return $this->scopeFilterByLocations($query, $operator, $values, 'city');
    }
    
    public function scopeFilterByLocationsState(Builder $query, string $operator, $values): Builder
    {
        return $this->scopeFilterByLocations($query, $operator, $values, 'state');
    }
    
    public function scopeFilterByLocationsCountry(Builder $query, string $operator, $values): Builder
    {
        return $this->scopeFilterByLocations($query, $operator, $values, 'country');
    }
    
    public function scopeFilterByAttribute(Builder $query, string $attributeName, string $operator, $value): Builder
    {
        return $query->whereHas('attributes', function ($q) use ($attributeName, $operator, $value) {
            $q->where('attributes.name', $attributeName);
            
            if ($operator === 'IN') {
                $values = is_array($value) ? $value : explode(',', $value);
                $q->whereIn('job_attribute_value.value', $values);
            } else {
                $q->where('job_attribute_value.value', $operator, $value);
            }
        });
    }
    
    /**
     * Scope to filter only published jobs
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }
    
    /**
     * Scope to filter only draft jobs
     */
    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_DRAFT);
    }
    
    /**
     * Scope to filter only archived jobs
     */
    public function scopeArchived(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ARCHIVED);
    }
}
