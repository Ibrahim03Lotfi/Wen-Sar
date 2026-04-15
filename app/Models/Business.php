<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Business extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'english_name',
        'description',
        'logo',
        'images',
        'district_id',
        'sub_area_id',
        'category_id',
        'owner_id',
        'phone',
        'opening_time',
        'closing_time',
        'address',
        'google_maps_link',
        'social_links',
        'is_featured',
        'featured_rank',
        'status',
        'approved_at',
        'contract_ends_at',
        'contract_duration_days',
        'approved_by',
    ];

    protected $casts = [
        'images' => 'array',
        'social_links' => 'array',
        'is_featured' => 'boolean',
        'approved_at' => 'datetime',
        'contract_ends_at' => 'datetime',
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

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function isFavoritedBy($user)
    {
        if (!$user) {
            return false;
        }
        return $this->favoritedBy()->where('user_id', $user->id)->exists();
    }

    public function approvedBy()
    {
        return $this->belongsTo(Manager::class, 'approved_by');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved')
                     ->where(function($q) {
                         $q->whereNull('contract_ends_at')
                           ->orWhere('contract_ends_at', '>', now());
                     });
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function isApproved()
    {
        return $this->status === 'approved' && 
               ($this->contract_ends_at === null || $this->contract_ends_at > now());
    }

    public function daysUntilExpiry()
    {
        if (!$this->contract_ends_at) {
            return null;
        }
        return now()->diffInDays($this->contract_ends_at, false);
    }
}
