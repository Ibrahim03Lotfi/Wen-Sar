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
        'landline',
        'phones',
        'landlines',
        'opening_time',
        'closing_time',
        'business_hours',
        'address',
        'google_maps_link',
        'social_links',
        'is_featured',
        'featured_rank',
        'status',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'images' => 'array',
        'social_links' => 'array',
        'business_hours' => 'array',
        'phones' => 'array',
        'landlines' => 'array',
        'is_featured' => 'boolean',
        'approved_at' => 'datetime',
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
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function formattedLandline()
    {
        return $this->landline;
    }

    public function allPhones()
    {
        $phones = [];
        if ($this->phone) {
            $phones[] = $this->phone;
        }
        if ($this->phones) {
            foreach ($this->phones as $p) {
                if ($p && !in_array($p, $phones)) {
                    $phones[] = $p;
                }
            }
        }
        return $phones;
    }

    public function allLandlines()
    {
        $landlines = [];
        if ($this->landline) {
            $landlines[] = $this->landline;
        }
        if ($this->landlines) {
            foreach ($this->landlines as $l) {
                if ($l && !in_array($l, $landlines)) {
                    $landlines[] = $l;
                }
            }
        }
        return $landlines;
    }

    public function primaryPhone()
    {
        $phones = $this->allPhones();
        return $phones[0] ?? null;
    }
}
