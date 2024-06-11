@extends('layouts.app')

@extends('layouts.site')

@section('content')
    <h1 class="mt-2 mb-3">Результаты поиска</h1>
    @if (isset($posts) && count($posts))
        <div class="row">
            @foreach ($posts as $post)
                <div class="col-6 mb-4">
                    <div class="card">
                        <div class="card-header"><h3>{{ $post->title }}</h3></div>
                        <div class="card-body">
                            <img src="{{ $post->thumb ?? asset('img/default.jpg') }}" alt="" class="img-fluid">
                            <p class="mt-3 mb-0">{{ $post->excerpt }}</p>
                        </div>
                        <div class="card-footer">
                            <div class="clearfix">
                                <span class="float-left">
                                    Автор: {{ $post->author }}
                                    <br>
                                    Дата: {{ date_format($post->created_at, 'd.m.Y H:i') }}
                                </span>
                                <a href="#" class="btn btn-dark float-right">Читать дальше</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{ $posts->links() }}
    @else
        <p>По вашему запросу ничего не найдено</p>
    @endif
@endsection

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Веб-разработка</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>
</head>
<body>
<div class="container">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <!-- ... -->
    </nav>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible mt-4" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Закрыть">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ $message }}
        </div>
    @endif

    @yield('content')


<a href="{{ route('post.show', ['id' => $post->post_id]) }}" class="btn btn-dark float-right">Читать дальше</a>
</div>
</body>
</html>

