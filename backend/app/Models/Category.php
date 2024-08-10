<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    // The attributes that are mass assignable.
    protected $fillable = [
        'name', 'slug', 'description', 'parent_id', 'image', 'position', 'is_active',
    ];

    // Define the relationship to the parent category
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Define the relationship to the child categories
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Scope to filter active categories
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessor for the image URL
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

}
