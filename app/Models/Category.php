<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    public function businesses()
    {
        return $this->hasMany(Business::class);
    }

    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function allCategoryIds()
    {
        $ids = [$this->id];

        if ($this->relationLoaded('subcategories')) {
            foreach ($this->subcategories as $sub) {
                $ids[] = $sub->id;
            }
        } else {
            $ids = array_merge($ids, $this->subcategories()->pluck('id')->toArray());
        }

        return $ids;
    }

    public function totalBusinessesCount()
    {
        if ($this->relationLoaded('subcategories')) {
            $subCount = $this->subcategories->sum('businesses_count');
        } else {
            $subCount = $this->subcategories()->withCount('businesses')->get()->sum('businesses_count');
        }

        return ($this->businesses_count ?? 0) + $subCount;
    }
}
