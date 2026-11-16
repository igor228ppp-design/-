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
            <h1>Панель администратора</h1>
            <p>Просмотр статей из БД (полное управление ролями и правами реализуется в следующих работах).</p>
        </div>

        <section class="section-box">
            <h2>Статьи в базе данных</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Заголовок</th>
                        <th>Категория</th>
                        <th>Автор</th>
                        <th>Дата публикации</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($texts as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->heading }}</td>
                            <td>{{ $item->category }}</td>
                            <td>{{ $item->user?->login ?? 'Гость' }}</td>
                            <td>{{ $item->created_at?->format('d.m.Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">В базе данных пока нет статей.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </main>

    <x-footer />
</body>
</html>
