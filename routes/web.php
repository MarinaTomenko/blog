<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginRegisterController;
use App\Http\Controllers\PostController as PController;

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
Route::get('/', PController::class . '@index')->name('blog.index');

Route::get('post/index', PController::class . '@index')->name('post.index');
Route::get('post/search', PController::class . '@search')->name('post.search');
Route::get('post/create', PController::class . '@create')->name('post.create');
Route::post('post/store', PController::class . '@store')->name('post.store');
Route::get('post/show/{id}', PController::class . '@show')->name('post.show');
Route::get('post/edit/{id}', PController::class . '@edit')->name('post.edit');
Route::patch('post/update/{id}', PController::class . '@update')->name('post.update');


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

/*
Route::group([
    'as' => 'admin.',
    'prefix' => 'admin', 
    'namespace' => 'Admin', 
    'middleware' => ['auth'] 
], function () {
    
    Route::resource('post', AdminPostController::class, ['except' => ['create', 'store']]);
   
    Route::get('post/category/{category}', AdminPostController::class . '@category')
        ->name('post.category');
   
    Route::get('post/enable/{post}', AdminPostController::class . '@enable')
        ->name('post.enable');
    
    Route::get('post/disable/{post}', AdminPostController::class . '@disable')
        ->name('post.disable');
});
*/


Route::prefix('admin')->group(function() {
   //Route::get('/post/index', IndexController::class)->name('admin.post.index');
    Route::get('/post/index', [PostController::class, 'index'])
        ->name('admin.post.index');
});

/*
 * Панель управления: CRUD-операции над постами, категориями, тегами
 */
Route::group([
    'as' => 'admin.', // имя маршрута, например admin.index
    'prefix' => 'admin', // префикс маршрута, например admin/index
    'namespace' => 'Admin', // пространство имен контроллеров
    'middleware' => ['auth'] // один или несколько посредников
], function () {
    /*
     * Главная страница панели управления
     */
//    Route::get('index', 'IndexController')->name('index');
    /*+
     * CRUD-операции над постами блога
     */
    Route::resource('post', PostController::class, ['except' => ['create', 'store']]);
    // доп.маршрут для показа постов категории
    Route::get('post/category/{category}', 'PostController@category')
        ->name('post.category');
    // доп.маршрут, чтобы разрешить публикацию поста
    Route::get('post/enable/{post}', 'PostController@enable')
        ->name('post.enable');
    // доп.маршрут, чтобы запретить публикацию поста
    Route::get('post/disable/{post}', 'PostController@disable')
        ->name('post.disable');
});