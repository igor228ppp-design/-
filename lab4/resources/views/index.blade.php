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
            <p>Статьи загружаются со связанными моделями Category, User и Role (Eloquent Relationships).</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($texts->isEmpty())
            <div class="empty-state">
                <p>Статей пока нет.</p>
            </div>
        @else
            <div class="news-grid">
                @foreach($texts as $article)
                    <article class="card">
                        <div>
                            <div class="card-meta">
                                <a href="{{ route('category', $article->category->id) }}" class="badge">
                                    {{ $article->category->name }}
                                </a>
                                <span>{{ $article->created_at?->format('d.m.Y H:i') }}</span>
                            </div>
                            <h2>{{ $article->heading }}</h2>
                            <p>{{ $article->text }}</p>
                        </div>
                        <div class="card-footer">
                            <span>Автор: <strong>{{ $article->user->login }}</strong></span>
                            <span class="badge badge-role">{{ $article->user->role->name }}</span>
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
