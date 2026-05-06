<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryBusinessRanking extends Model
{
    protected $fillable = ['category_id', 'business_id', 'rank', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
