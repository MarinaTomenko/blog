<?php

namespace App\Http\Controllers;

use App\Category;
use App\Comment;
use App\Post;
use App\Tag;
use App\User;
use Illuminate\Http\Request;

class BlogController extends Controller {
    
    public function index() {
        $posts = Post::published()
            ->with('user')->with('tags')
            ->orderByDesc('created_at')
            ->paginate();
        return view('blog.index', compact('posts'));
    }

    
    public function post(Post $post) {
        $comments = $post->comments()
            ->published()
            ->orderBy('created_at')
            ->paginate();
        return view('blog.post', compact('post', 'comments'));
    }


    public function category(Category $category) {
        $descendants = array_merge(Category::descendants($category->id), [$category->id]);
        $posts = Post::whereIn('category_id', $descendants)
            ->published()
            ->with('user')->with('tags')
            ->orderByDesc('created_at')
            ->paginate();
        return view('blog.category', compact('category', 'posts'));
    }
   
    public function author(User $user) {
        $posts = $user->posts()
            ->published()
            ->with('user')->with('tags')
            ->orderByDesc('created_at')
            ->paginate();
        return view('blog.author', compact('user', 'posts'));
    }


    public function tag(Tag $tag) {
        $posts = $tag->posts()
            ->published()
            ->with('user')->with('tags')
            ->orderByDesc('created_at')
            ->paginate();
        return view('blog.tag', compact('tag', 'posts'));
    }
}