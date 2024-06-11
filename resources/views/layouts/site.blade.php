<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="container">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="{{ route('blog.index') }}">Веб-разработка</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Авторы</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Создать</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Контакты</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0" action="{{ route('post.search') }}">
            <input class="form-control mr-sm-2" type="search" name="search"
           placeholder="Найти пост..." aria-label="Поиск">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Поиск</button>
            </form>
        </div>
    </nav>

    @yield('content')
</div>
</body>
</html>