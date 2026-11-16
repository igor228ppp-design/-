<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация — Научный Прогресс</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <x-header />

    <main class="container">
        <div class="form-card auth-card">
            <h1 style="font-size: 1.5rem; margin-bottom: 18px;">Регистрация</h1>

            @if($errors->any())
                <div class="alert alert-error">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('register.submit') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="login">Логин</label>
                    <input type="text" id="login" name="login" class="form-control" value="{{ old('login') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Подтверждение пароля</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Зарегистрироваться</button>
            </form>

            <p style="margin-top: 16px; font-size: 0.9rem; color: var(--muted); text-align: center;">
                Уже есть аккаунт? <a href="{{ route('login') }}">Войти</a>
            </p>
        </div>
    </main>

    <x-footer />
</body>
</html>
