<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная — Научный Прогресс</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <x-header />

    <main class="container">
        <div class="page-header">
            <h1>Все опубликованные статьи</h1>
            <p>Материалы из базы данных (сортировка от новых к старым).</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($texts->isEmpty())
            <div class="empty-state">
                <p>Статей пока нет. Перейдите в раздел <a href="{{ route('journalist') }}">«Журналист»</a>, чтобы добавить первую публикацию.</p>
            </div>
        @else
            <div class="news-grid">
                @foreach($texts as $article)
                    <article class="card">
                        <div>
                            <div class="card-meta">
                                <a href="{{ route('category', $article->category) }}" class="badge">{{ $article->category }}</a>
                                <span>{{ $article->created_at?->format('d.m.Y H:i') }}</span>
                            </div>
                            <h2>{{ $article->heading }}</h2>
                            <p>{{ $article->text }}</p>
                        </div>
                        <div class="card-footer">
                            <span>Автор: <strong>{{ $article->user?->login ?? 'Гость' }}</strong></span>
                            <span>Категория: {{ $article->category }}</span>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="pagination-wrap">
                {{ $texts->links() }}
            </div>
        @endif
    </main>

    <x-footer />
</body>
</html>
