<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginRegisterController;
use App\Http\Controllers\PostController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

//Route::get('/', 'PostController@index');
//Route::get('blog-app',[PostController::class, 'index']);
Route::get('/', PostController::class . '@index')->name('blog.index');

Route::get('post/index', PostController::class . '@index')->name('post.index');
Route::get('post/search', PostController::class . '@search')->name('post.search');
Route::get('post/create', PostController::class . '@create')->name('post.create');
Route::post('post/store', PostController::class . '@store')->name('post.store');
Route::get('post/show/{id}', PostController::class . '@show')->name('post.show');
Route::get('post/edit/{id}', PostController::class . '@edit')->name('post.edit');
Route::patch('post/update/{id}', PostController::class . '@update')->name('post.update');


Route::controller(LoginRegisterController::class)->group(function() {
    Route::get('/register', 'register')->name('register');
    Route::post('/store', 'store')->name('store');
    Route::get('/login', 'login')->name('login');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::post('/logout', 'logout')->name('logout');
});

Route::group([
    'as' => 'blog.', // имя маршрута, например blog.index
    'prefix' => 'blog', // префикс маршрута, например blog/index
], function () {
    // главная страница (все посты)
    Route::get('index', [BlogController::class, 'index'])
        ->name('index');
    // категория блога (посты категории)
    Route::get('category/{category:slug}', [BlogController::class, 'category'])
        ->name('category');
    // тег блога (посты с этим тегом)
    Route::get('tag/{tag:slug}', [BlogController::class, 'tag'])
        ->name('tag');
    // автор блога (посты этого автора)
    Route::get('author/{user}', [BlogController::class, 'author'])
        ->name('author');
    // страница просмотра поста блога
    Route::get('post/{post:slug}', [BlogController::class, 'post'])
        ->name('post');
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
