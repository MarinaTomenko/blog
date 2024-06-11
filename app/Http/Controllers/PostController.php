<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Controllers\Controller;
use Resources\Views\Layouts\Create;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function search(Request $request) {
        $search = $request->input('search', '');
        
        $search = iconv_substr($search, 0, 64);
       
        $search = preg_replace('#[^0-9a-zA-ZА-Яа-яёЁ]#u', ' ', $search);
       
        $search = preg_replace('#\s+#u', ' ', $search);
        if (empty($search)) {
            return view('posts.search');
        }
        $posts = Post::select('posts.*', 'users.name as author')
            ->join('users', 'posts.author_id', '=', 'users.id')
            ->where('posts.title', 'like', '%'.$search.'%') // поиск по заголовку поста
            ->orWhere('posts.body', 'like', '%'.$search.'%') // поиск по тексту поста
            ->orWhere('users.name', 'like', '%'.$search.'%') // поиск по автору поста
            ->orderBy('posts.created_at', 'desc')
            ->paginate(4)
            ->appends(['search' => $request->input('search')]);;
        return view('posts.search', compact('posts'));
    }


    public function index()
    {
        $posts = Post::select('posts.*', 'users.name as author')
            ->join('users', 'posts.author_id', '=', 'users.id')
            ->orderBy('posts.created_at', 'desc')
            ->paginate(4);
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $post = new Post();
        $post->author_id = rand(1, 4);
        $post->title = $request->input('title');
        $post->excerpt = $request->input('excerpt');
        $post->body = $request->input('body');
        $this->uploadImage($request, $post);
        $post->save();
        return redirect()
            ->route('post.index')
            ->with('success', 'Новый пост успешно создан');
    }
    


    /**
     * Display the specified resource.
     */
    public function show($id) {
        $post = Post::select('posts.*', 'users.name as author')
            ->join('users', 'posts.author_id', '=', 'users.id')
            ->find($id);
        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id) {
        $post = Post::find($id);
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {
        $post = Post::find($id);
        $post->title = $request->input('title');
        $post->excerpt = $request->input('excerpt');
        $post->body = $request->input('body');
        $this->uploadImage($request, $post);
        $post->update();
        return redirect()
            ->route('post.show', compact('id'))
            ->with('success', 'Пост успешно отредактирован');
    }


    private function uploadImage(Request $request, Post $post) {
        // если надо удалить старое изображение
        if ($request->input('remove')) {
            if (!empty($post->image)) {
                $name = basename($post->image);
                if (Storage::exists('public/image/image/' . $name)) {
                    Storage::delete('public/image/image/' . $name);
                }
                $post->image = null;
            }
            if (!empty($post->thumb)) {
                $name = basename($post->thumb);
                if (Storage::exists('public/image/thumb/' . $name)) {
                    Storage::delete('public/image/thumb/' . $name);
                }
                $post->thumb = null;
            }
            // здесь сложнее, мы не знаем, какое у файла расширение
            if (!empty($name)) {
                $images = Storage::files('public/image/source');
                $base = pathinfo($name, PATHINFO_FILENAME);
                foreach ($images as $img) {
                    $temp = pathinfo($img, PATHINFO_FILENAME);
                    if ($temp == $base) {
                        Storage::delete($img);
                        break;
                    }
                }
            }
        }
        // если было загружено новое изображение
        $source = $request->file('image');
        if ($source) {
            $ext = str_replace('jpeg', 'jpg', $source->extension());
            // уникальное имя файла, под которым сохраним его в storage/image/source
            $name = md5(uniqid());
            Storage::putFileAs('public/image/source', $source, $name. '.' . $ext);
            // создаем jpg изображение для с страницы поста размером 1200x400, качество 100%
            $image = Image::make($source)
                ->resizeCanvas(1200, 400, 'center', false, 'dddddd')
                ->encode('jpg', 100);
            // сохраняем это изображение под именем $name.jpg в директории public/image/image
            Storage::put('public/image/image/' . $name . '.jpg', $image);
            $image->destroy();
            $post->image = Storage::url('public/image/image/' . $name . '.jpg');
            // создаем jpg изображение для списка постов блога размером 600x200, качество 100%
            $thumb = Image::make($source)
                ->resizeCanvas(600, 200, 'center', false, 'dddddd')
                ->encode('jpg', 100);
            // сохраняем это изображение под именем $name.jpg в директории public/image/thumb
            Storage::put('public/image/thumb/' . $name . '.jpg', $thumb);
            $thumb->destroy();
            $post->thumb = Storage::url('public/image/thumb/' . $name . '.jpg');
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
