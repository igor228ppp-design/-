<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход — Научный Прогресс</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <x-header />

    <main class="container">
        <div class="form-card auth-card">
            <h1 style="font-size: 1.5rem; margin-bottom: 18px;">Вход в аккаунт</h1>

            @if($errors->any())
                <div class="alert alert-error">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="login">Логин</label>
                    <input type="text" id="login" name="login" class="form-control" value="{{ old('login') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Войти</button>
            </form>

            <p style="margin-top: 16px; font-size: 0.9rem; color: var(--muted); text-align: center;">
                Нет аккаунта? <a href="{{ route('register') }}">Зарегистрироваться</a>
            </p>
        </div>
    </main>

    <x-footer />
</body>
</html>
