<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель администратора — Научный Прогресс</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <x-header />

    <main class="container">
        <div class="page-header">
            <h1>Структура и связи базы данных (Админ)</h1>
            <p>Обзор взаимосвязанных таблиц <code>roles</code>, <code>categories</code>, <code>users</code> и <code>texts</code>.</p>
        </div>

        <section class="section-box">
            <h2>Пользователи и их роли (users &rarr; roles)</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Логин (уникальный)</th>
                        <th>Роль (role_id)</th>
                        <th>Кол-во статей автора</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        <tr>
                            <td>{{ $u->id }}</td>
                            <td><strong>{{ $u->login }}</strong></td>
                            <td><span class="badge badge-role">{{ $u->role->name }} (#{{ $u->role_id }})</span></td>
                            <td>{{ $u->texts_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        <section class="section-box">
            <h2>Справочник категорий (categories &rarr; texts)</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название категории</th>
                        <th>Кол-во статей</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $c)
                        <tr>
                            <td>{{ $c->id }}</td>
                            <td>{{ $c->name }}</td>
                            <td>{{ $c->texts_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        <section class="section-box">
            <h2>Статьи (texts)</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Заголовок (heading)</th>
                        <th>Категория (categorie_id)</th>
                        <th>Автор (user_id)</th>
                        <th>Создано</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($texts as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->heading }}</td>
                            <td>{{ $item->category->name }}</td>
                            <td>{{ $item->user->login }} ({{ $item->user->role->name }})</td>
                            <td>{{ $item->created_at?->format('d.m.Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </main>

    <x-footer />
</body>
</html>
