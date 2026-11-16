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
            <p>Интерфейс для написания и редактирования научных статей (этап вёрстки).</p>
        </div>

        <div class="form-card">
            <form action="#" method="POST" onsubmit="event.preventDefault(); alert('На этапе Лабораторной работы №1 реализована только вёрстка формы.');">
                <div class="form-group">
                    <label for="heading">Заголовок статьи</label>
                    <input type="text" id="heading" name="heading" class="form-control" placeholder="Введите заголовок публикации..." required>
                </div>

                <div class="form-group">
                    <label for="category">Категория</label>
                    <select id="category" name="category" class="form-control" required>
                        <option value="Информационные технологии">Информационные технологии</option>
                        <option value="Медицина">Медицина</option>
                        <option value="Космос">Космос</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="text">Полный текст статьи</label>
                    <textarea id="text" name="text" class="form-control" placeholder="Напишите или отредактируйте текст вашей статьи..." required></textarea>
                </div>

                <div class="actions-row">
                    <button type="submit" class="btn btn-primary">Опубликовать статью</button>
                    <button type="reset" class="btn btn-secondary">Очистить форму</button>
                </div>
            </form>
        </div>
    </main>

    <x-footer />
</body>
</html>
