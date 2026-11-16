<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Категория: {{ $currentCategory->name }} — Научный Прогресс</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <x-header />

    <main class="container">
        <div class="page-header">
            <h1>Категория: {{ $currentCategory->name }}</h1>
            <p>Выборка статей через внешний ключ <code>categorie_id</code> на справочник <code>categories</code>.</p>
        </div>

        <div class="category-tabs">
            @foreach($categories as $cat)
                <a href="{{ route('category', $cat->id) }}"
                   class="category-tab {{ $currentCategory->id === $cat->id ? 'active' : '' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        @if($texts->isEmpty())
            <div class="empty-state">
                <p>В категории «{{ $currentCategory->name }}» пока нет опубликованных статей.</p>
            </div>
        @else
            <div class="news-grid">
                @foreach($texts as $article)
                    <article class="card">
                        <div>
                            <div class="card-meta">
                                <span class="badge">{{ $article->category->name }}</span>
                                <span>{{ $article->created_at?->format('d.m.Y H:i') }}</span>
                            </div>
                            <h2>{{ $article->heading }}</h2>
                            <p>{{ $article->text }}</p>
                        </div>
                        <div class="card-footer">
                            <span>Автор: <strong>{{ $article->user->login }}</strong></span>
                            <span>ID категории: {{ $article->categorie_id }}</span>
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
