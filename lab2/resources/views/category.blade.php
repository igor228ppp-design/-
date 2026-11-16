<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Категория: {{ $currentCategory }} — Научный Прогресс</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <x-header />

    <main class="container">
        <div class="page-header">
            <h1>Категория: {{ $currentCategory }}</h1>
            <p>Отображаются опубликованные статьи из базы данных по выбранной категории.</p>
        </div>

        <div class="category-tabs">
            @foreach($categories as $cat)
                <a href="{{ route('category', $cat) }}"
                   class="category-tab {{ $currentCategory === $cat ? 'active' : '' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>

        @if($texts->isEmpty())
            <div class="empty-state">
                <p>В категории «{{ $currentCategory }}» пока нет опубликованных статей.</p>
            </div>
        @else
            <div class="news-grid">
                @foreach($texts as $article)
                    <article class="card">
                        <div>
                            <div class="card-meta">
                                <span class="badge">{{ $article->category }}</span>
                                <span>{{ $article->created_at?->format('d.m.Y H:i') }}</span>
                            </div>
                            <h2>{{ $article->heading }}</h2>
                            <p>{{ $article->text }}</p>
                        </div>
                        <div class="card-footer">
                            <span>Автор: <strong>{{ $article->user?->login ?? 'Гость' }}</strong></span>
                            <span>{{ $article->category }}</span>
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
