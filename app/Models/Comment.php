<?php

class Comment extends Model {
    
    protected $perPage = 5;
    
 
    public function scopePublished($builder) {
        return $builder->whereNotNull('published_by');
    }
    
}