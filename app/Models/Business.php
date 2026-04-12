<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Business extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'logo',
        'images',
        'district_id',
        'sub_area_id',
        'category_id',
        'owner_id',
        'phone',
        'address',
        'social_links',
        'is_featured',
        'featured_rank'
    ];

    protected $casts = [
        'images' => 'array',
        'social_links' => 'array',
        'is_featured' => 'boolean',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function subArea()
    {
        return $this->belongsTo(SubArea::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function averageRating()
    {
        // Use already loaded reviews if available for real-time calculation
        if ($this->relationLoaded('reviews')) {
            $avg = $this->reviews->avg('rating');
            return $avg ?: 0;
        }
        return $this->reviews()->avg('rating') ?: 0;
    }
}
