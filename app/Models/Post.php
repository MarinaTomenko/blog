<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model {
    protected $primaryKey = 'post_id';

    public function tags() {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    
    public function category() {
        return $this->belongsTo(Category::class);
    }

    
    public function user() {
        return $this->belongsTo(User::class);
    }

    protected $perPage = 5;

    public function scopePublished($builder) {
        return $builder->whereNotNull('published_by');
    }

    
}   
