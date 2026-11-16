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
            <p>Полное управление статьями (редактирование, блокировка, удаление) и ролями пользователей. Доступ защищён <code>RoleMiddleware</code>.</p>
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

        @if($editingText)
            <div class="form-card" style="margin-bottom: 32px;">
                <h2 style="margin-bottom: 16px;">Редактирование статьи #{{ $editingText->id }}</h2>
                <form action="{{ route('admin.texts.update', $editingText) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="heading">Заголовок</label>
                        <input type="text" id="heading" name="heading" class="form-control"
                               value="{{ old('heading', $editingText->heading) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="categorie_id">Категория</label>
                        <select id="categorie_id" name="categorie_id" class="form-control" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" @selected((int) old('categorie_id', $editingText->categorie_id) === $cat->id)>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="text">Текст статьи</label>
                        <textarea id="text" name="text" class="form-control" required>{{ old('text', $editingText->text) }}</textarea>
                    </div>

                    <div class="actions-row">
                        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                        <a href="{{ route('admin') }}" class="btn btn-secondary">Отмена</a>
                    </div>
                </form>
            </div>
        @endif

        <section class="section-box">
            <h2>Управление статьями</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Заголовок</th>
                        <th>Категория</th>
                        <th>Автор</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($texts as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->heading }}</td>
                            <td>{{ $item->category->name }}</td>
                            <td>{{ $item->user->login }}</td>
                            <td>
                                @if($item->is_blocked)
                                    <span class="badge badge-blocked">Заблокирована</span>
                                @else
                                    <span class="badge badge-active">Опубликована</span>
                                @endif
                            </td>
                            <td>
                                <div class="actions-row">
                                    <a href="{{ route('admin', $item->id) }}" class="btn btn-sm btn-primary">Редактировать</a>

                                    <form action="{{ route('admin.texts.toggle-block', $item) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $item->is_blocked ? 'btn-secondary' : 'btn-warning' }}">
                                            {{ $item->is_blocked ? 'Разблокировать' : 'Заблокировать' }}
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.texts.destroy', $item) }}" method="POST"
                                          onsubmit="return confirm('Удалить эту статью?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">Статей пока нет.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        <section class="section-box">
            <h2>Изменение ролей пользователей</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Логин</th>
                        <th>Текущая роль</th>
                        <th>Изменить роль</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        <tr>
                            <td>{{ $u->id }}</td>
                            <td><strong>{{ $u->login }}</strong></td>
                            <td><span class="badge badge-role">{{ $u->role->name }}</span></td>
                            <td>
                                <form action="{{ route('admin.users.role', $u) }}" method="POST" class="inline-form">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role_id" class="form-control" style="width: auto; min-width: 160px;">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" @selected($u->role_id === $role->id)>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">Сохранить</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </main>

    <x-footer />
</body>
</html>
