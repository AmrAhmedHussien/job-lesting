<?php

namespace App\Services;

use App\Models\Job;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class JobFilterService
{
    protected $query;
    protected $filterString;
    protected $allowedOperators = [
        '=', '!=', '>', '<', '>=', '<=', 'LIKE', 'IN', 'HAS_ANY', 'IS_ANY', 'EXISTS'
    ];
    protected $logicalOperators = ['AND', 'OR'];
    
    protected $textFields = ['title', 'description', 'company_name', 'job_type'];
    protected $numericFields = ['salary_min', 'salary_max'];
    protected $booleanFields = ['is_remote'];
    protected $dateFields = ['published_at', 'created_at', 'updated_at'];
    protected $enumFields = ['job_type', 'status'];

    public function __construct()
    {
        $this->query = Job::query();
    }


    public function apply(?string $filterString): Builder
    {
        if (empty($filterString)) {
            return $this->query;
        }

        $this->filterString = $filterString;
        
        $this->parseFilterString($filterString);
        
        return $this->query;
    }

    protected function parseFilterString(string $filterString): void
    {
        if (Str::startsWith($filterString, '(') && Str::endsWith($filterString, ')') && $this->hasBalancedParentheses(substr($filterString, 1, -1))) {
            $filterString = substr($filterString, 1, -1);
        }
        
        $conditions = $this->splitByLogicalOperator($filterString);
        
        if (count($conditions) > 1) {
            $this->applyLogicalConditions($conditions);
        } else {
            $this->applySingleCondition($filterString);
        }
    }

    protected function splitByLogicalOperator(string $filterString): array
    {
        $result = [];
        $currentCondition = '';
        $parenthesesLevel = 0;
        $tokens = preg_split('/(AND|OR)/', $filterString, -1, PREG_SPLIT_DELIM_CAPTURE);

        foreach ($tokens as $index => $token) {
            $token = trim($token);
            if (empty($token)) continue;
            
            if (in_array($token, $this->logicalOperators)) {
                if ($parenthesesLevel === 0 && !empty($currentCondition)) {
                    $result[] = ['condition' => trim($currentCondition)];
                    $result[] = ['operator' => $token];
                    $currentCondition = '';
                } else {
                    $currentCondition .= " $token ";
                }
            } else {
                $parenthesesLevel += substr_count($token, '(') - substr_count($token, ')');
                $currentCondition .= $token;
            }
        }
        
        if (!empty($currentCondition)) {
            $result[] = ['condition' => trim($currentCondition)];
        }
        
        return $result;
    }
    

    protected function applyLogicalConditions(array $conditions): void
    {
        $this->query->where(function ($query) use ($conditions) {
            $this->query = $query;
            
            $this->applySingleCondition($conditions[0]['condition']);
            
            for ($i = 1; $i < count($conditions); $i += 2) {
                $operator = $conditions[$i]['operator'];
                $condition = $conditions[$i+1]['condition'];
                
                if ($operator === 'AND') {
                    $this->query->where(function ($query) use ($condition) {
                        $this->query = $query;
                        $this->applySingleCondition($condition);
                    });
                } elseif ($operator === 'OR') {
                    $this->query->orWhere(function ($query) use ($condition) {
                        $this->query = $query;
                        $this->applySingleCondition($condition);
                    });
                }
            }
        });
    }
    

    protected function applySingleCondition(string $condition): void
    {
        if (Str::startsWith($condition, '(') && Str::endsWith($condition, ')') && $this->hasBalancedParentheses(substr($condition, 1, -1))) {
            $this->parseFilterString(substr($condition, 1, -1));
            return;
        }

        if (Str::contains($condition, 'attribute:')) {
            $this->applyAttributeFilter($condition);
            return;
        }
        
        if (Str::contains($condition, 'city:') || Str::contains($condition, 'state:') || Str::contains($condition, 'country:')) {
            $this->applyLocationFilter($condition);
            return;
        }
        
        foreach (['languages', 'locations', 'categories'] as $relation) {
            if (Str::contains($condition, $relation) && (
                Str::contains($condition, 'HAS_ANY') || 
                Str::contains($condition, 'IS_ANY') || 
                Str::contains($condition, 'EXISTS') ||
                Str::contains($condition, '=')
            )) {
                $this->applyRelationshipFilter($condition, $relation);
                return;
            }
        }
        
        $this->applyFieldFilter($condition);
    }

    protected function applyFieldFilter(string $condition): void
    {

        if (preg_match('/^([a-zA-Z_]+)\s*(>=|<=|>|<)\s*(.+)$/', $condition, $matches)) {
            $field = trim($matches[1]);
            $operator = $matches[2];
            $value = trim($matches[3]);
            
            if (in_array($field, $this->numericFields)) {
                $this->query->filterByNumeric($field, $operator, (float) $value);
                return;
            }
        }
        
 
        foreach ($this->allowedOperators as $operator) {
            if (Str::contains($condition, $operator)) {
                list($field, $value) = explode($operator, $condition, 2);
                $field = trim($field);
                $value = trim($value);
                
                if (Str::startsWith($value, '(') && Str::endsWith($value, ')')) {
                    $value = substr($value, 1, -1);
                }
                
                if (in_array($field, $this->textFields)) {
                    $this->query->filterByText($field, $operator, $value);
                } elseif (in_array($field, $this->numericFields)) {
                    $this->query->filterByNumeric($field, $operator, (float) $value);
                } elseif (in_array($field, $this->booleanFields)) {
                    $this->query->filterByBoolean($field, $value === 'true' || $value === '1');
                } elseif (in_array($field, $this->dateFields)) {
                    $this->query->filterByDate($field, $operator, $value);
                } elseif (in_array($field, $this->enumFields)) {
                    $this->query->filterByEnum($field, $operator, $value);
                } else {
                    if ($operator === 'IN') {
                        $values = explode(',', $value);
                        $this->query->whereIn($field, $values);
                    } else {
                        $this->query->where($field, $operator, $value);
                    }
                }
                
                return;
            }
        }
    }
    

    protected function applyLocationFilter(string $condition): void 
    {
        preg_match('/(city|state|country):([a-zA-Z_]+)([=!<>]+|LIKE|IN)(.+)/', $condition, $matches);
        
        if (count($matches) < 5) {
            return;
        }
        
        $field = $matches[1];
        $value = trim($matches[4]);
        $operator = $matches[3];
        
        if (Str::startsWith($value, '(') && Str::endsWith($value, ')')) {
            $value = substr($value, 1, -1);
        }
        
        if ($operator === 'IN') {
            $values = explode(',', $value);
            $this->query->whereHas('locations', function ($query) use ($field, $values) {
                $query->whereIn($field, $values);
            });
        } else if ($operator === 'LIKE') {
            $this->query->whereHas('locations', function ($query) use ($field, $value) {
                $query->where($field, 'LIKE', "%$value%");
            });
        } else {
            $this->query->whereHas('locations', function ($query) use ($field, $operator, $value) {
                $query->where($field, $operator, $value);
            });
        }
    }
    

    protected function applyRelationshipFilter(string $condition, string $relation): void
    {

        if ($relation === 'locations' && preg_match('/^locations\.([a-zA-Z_]+)\s*(IS_ANY|=|!=|<|>|<=|>=)\s*\((.+)\)$/', $condition, $matches)) {
            $field = $matches[1];
            $operator = $matches[2];
            $values = $this->extractValues($matches[3]);
            
            if ($operator === 'IS_ANY') {
                $this->query->filterByLocations('IS_ANY', $values, $field);
                return;
            }
        }

        if (Str::contains($condition, 'HAS_ANY')) {
            list(, $values) = explode('HAS_ANY', $condition, 2);
            $values = $this->extractValues($values);
            
            switch ($relation) {
                case 'languages':
                    $this->query->filterByLanguages('HAS_ANY', $values);
                    break;
                case 'categories':
                    $this->query->filterByCategories('HAS_ANY', $values);
                    break;
                case 'locations':
                    $this->query->whereHas('locations', function ($query) use ($values) {
                        $query->whereIn('locations.id', $values);
                    });
                    break;
            }
        } elseif (Str::contains($condition, 'IS_ANY')) {
            list($relationPart, $values) = explode('IS_ANY', $condition, 2);
            $values = $this->extractValues($values);
            
            // Check if we have a dotted notation like locations.city
            $field = 'name';
            if (Str::contains($relationPart, '.')) {
                list($relationName, $field) = explode('.', trim($relationPart));
            }
            
            switch ($relation) {
                case 'languages':
                    $this->query->filterByLanguages('IS_ANY', $values);
                    break;
                case 'categories':
                    $this->query->filterByCategories('IS_ANY', $values);
                    break;
                case 'locations':
                    $this->query->filterByLocations('IS_ANY', $values, $field);
                    break;
            }
        } elseif (Str::contains($condition, 'EXISTS')) {
            switch ($relation) {
                case 'languages':
                    $this->query->filterByLanguages('EXISTS', []);
                    break;
                case 'categories':
                    $this->query->filterByCategories('EXISTS', []);
                    break;
                case 'locations':
                    $this->query->filterByLocations('EXISTS', []);
                    break;
            }
        } elseif (Str::contains($condition, '=')) {
            list($relationPart, $value) = explode('=', $condition, 2);
            $value = trim($value);
            
            // Check if we have a dotted notation like locations.city
            $field = 'name';
            if (Str::contains($relationPart, '.')) {
                list($relationName, $field) = explode('.', trim($relationPart));
            }
            
            switch ($relation) {
                case 'languages':
                    $this->query->filterByLanguages('=', [$value]);
                    break;
                case 'categories':
                    $this->query->filterByCategories('=', [$value]);
                    break;
                case 'locations':
                    $this->query->filterByLocations('=', [$value], $field);
                    break;
            }
        }
    }
    

    protected function applyAttributeFilter(string $condition): void
    {
        if (preg_match('/^attribute:([a-zA-Z_]+)\s*(>=|<=|>|<|=|!=|IN|LIKE)\s*(.+)$/', $condition, $matches)) {
            $attributeName = $matches[1];
            $operator = $matches[2];
            $value = trim($matches[3]);
            
            if (Str::startsWith($value, '(') && Str::endsWith($value, ')')) {
                $value = substr($value, 1, -1);
            }
            
            // Convert value to numeric if it's a comparison for years_experience
            if ($attributeName === 'years_experience' && in_array($operator, ['>=', '<=', '>', '<'])) {
                $value = (float)$value;
            }
            
            $this->query->filterByAttribute($attributeName, $operator, $value);
        }
    }
    

    protected function extractValues(string $valuesString): array
    {
        $valuesString = trim($valuesString);
        
        if (Str::startsWith($valuesString, '(') && Str::endsWith($valuesString, ')')) {
            $valuesString = substr($valuesString, 1, -1);
        }
        
        return array_map('trim', explode(',', $valuesString));
    }
    
    protected function hasBalancedParentheses(string $string): bool
    {
        $level = 0;
        for ($i = 0; $i < strlen($string); $i++) {
            if ($string[$i] === '(') {
                $level++;
            } elseif ($string[$i] === ')') {
                $level--;
                if ($level < 0) {
                    return false;
                }
            }
        }
        return $level === 0;
    }
} 