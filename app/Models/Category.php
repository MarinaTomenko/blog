<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {
   
    public function posts() {
        return $this->hasMany(Post::class);
    }

    
    public function parent() {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    
    public function children() {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public static function descendants($parent) {
        static $items = null;
        if (is_null($items)) {
            $items = self::all();
        }
        $ids = [];
        foreach ($items->where('parent_id', $parent) as $item) {
            $ids[] = $item->id;
            $ids = array_merge($ids, self::descendants($item->id));
        }
        return $ids;
    }
}