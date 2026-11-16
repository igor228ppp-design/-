<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кабинет журналиста — Научный Прогресс</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <x-header />

    <main class="container">
        <div class="page-header">
            <h1>Раздел «Журналист»</h1>
            <p>Публикация статей с привязкой к таблицам <code>categories</code> и <code>users</code>.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="form-card" style="margin-bottom: 32px;">
            <h2 style="margin-bottom: 18px;">
                {{ $editingText ? 'Редактирование статьи #' . $editingText->id : 'Новая статья' }}
            </h2>

            <form action="{{ $editingText ? route('texts.update', $editingText) : route('texts.store') }}" method="POST">
                @csrf
                @if($editingText)
                    @method('PUT')
                @endif

                <div class="form-group">
                    <label for="heading">Заголовок (heading)</label>
                    <input type="text" id="heading" name="heading" class="form-control"
                           value="{{ old('heading', $editingText?->heading) }}"
                           placeholder="Введите заголовок публикации..." required>
                </div>

                <div class="form-group">
                    <label for="categorie_id">Категория (внешний ключ на таблицу categories)</label>
                    <select id="categorie_id" name="categorie_id" class="form-control" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                @selected((int) old('categorie_id', $editingText?->categorie_id) === $cat->id)>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="text">Текст статьи (text)</label>
                    <textarea id="text" name="text" class="form-control"
                              placeholder="Введите полный текст статьи..." required>{{ old('text', $editingText?->text) }}</textarea>
                </div>

                <div class="actions-row">
                    <button type="submit" class="btn btn-primary">
                        {{ $editingText ? 'Сохранить изменения' : 'Опубликовать статью' }}
                    </button>
                    @if($editingText)
                        <a href="{{ route('journalist') }}" class="btn btn-secondary">Отмена</a>
                    @endif
                </div>
            </form>
        </div>

        <section class="section-box">
            <h2>Опубликованные статьи</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Заголовок</th>
                        <th>Категория</th>
                        <th>Автор</th>
                        <th>Действие</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($texts as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->heading }}</td>
                            <td><span class="badge">{{ $item->category->name }}</span></td>
                            <td>{{ $item->user->login }}</td>
                            <td>
                                <a href="{{ route('journalist', $item->id) }}" class="btn btn-sm btn-primary">Редактировать</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">Статей пока нет.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </main>

    <x-footer />
</body>
</html>
