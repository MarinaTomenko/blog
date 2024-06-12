<?php

namespace App\Http\Controllers;

use App\Category;
use App\Comment;
use App\Post;
use App\Tag;
use App\User;
use Illuminate\Http\Request;
//use App\Http\Requests\CommentRequest;


class BlogController extends Controller {

    public function __construct() {
        $this->middleware('perm:create-comment')->only('comment');
    }

    
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


    public function comment(CommentRequest $request) {
        $request->merge(['user_id' => auth()->user()->id]);
        $message = 'Комментарий добавлен, будет доступен после проверки';
        if (auth()->user()->hasPermAnyWay('publish-comment')) {
            $request->merge(['published_by' => auth()->user()->id]);
            $message = 'Комментарий добавлен и уже доступен для просмотра';
        }
        $comment = Comment::create($request->all());
        // комментариев может быть много, поэтому есть пагинация; надо
        // перейти на последнюю страницу — новый комментарий будет там
        $page = $comment->post->comments()->published()->paginate()->lastPage();
        return redirect()
            ->route('blog.post', ['post' => $comment->post->slug, 'page' => $page])
            ->withFragment('comment-list')
            ->with('success', $message);
    }



    public function search(Request $request) {
        $search = $request->input('query');
        $posts = Post::search($search)->paginate()->withQueryString();
        return view('blog.search', compact('posts', 'search'));
    }

}