<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Все посты блога</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="container">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Мой блог</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Автор</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Портфолио</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Контакты</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" name="search" type="search" placeholder="Поиск" aria-label="Поиск">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Поиск</button>
            </form>
        </div>
    </nav>

    @extends('layouts.site')

@section('content')
    <h1 class="mt-2 mb-3">Все посты блога</h1>
    <div class="row">
        @foreach ($posts as $post)
            <div class="col-6 mb-4">
                <div class="card">
                    <div class="card-header"><h3>{{ $post->title }}</h3></div>
                    <div class="card-body">
                        <img src="{{ $posts->image ?? asset('img/default.jpg') }}" alt="" class="img-fluid">
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
@endsection
</div>
</body>
</html>