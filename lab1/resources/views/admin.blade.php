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
            <p>Управление публикациями (редактирование, блокировка, удаление) и изменение ролей пользователей (этап вёрстки).</p>
        </div>

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
                    <tr>
                        <td>1</td>
                        <td>Квантовые процессоры нового поколения преодолели порог коррекции ошибок</td>
                        <td>Информационные технологии</td>
                        <td>Алексей Смирнов</td>
                        <td><span class="badge badge-active">Опубликована</span></td>
                        <td>
                            <div class="actions-row">
                                <button type="button" class="btn btn-sm btn-primary">Редактировать</button>
                                <button type="button" class="btn btn-sm btn-warning">Заблокировать</button>
                                <button type="button" class="btn btn-sm btn-danger">Удалить</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Персонализированная мРНК-терапия показала высокую эффективность</td>
                        <td>Медицина</td>
                        <td>Мария Иванова</td>
                        <td><span class="badge badge-active">Опубликована</span></td>
                        <td>
                            <div class="actions-row">
                                <button type="button" class="btn btn-sm btn-primary">Редактировать</button>
                                <button type="button" class="btn btn-sm btn-warning">Заблокировать</button>
                                <button type="button" class="btn btn-sm btn-danger">Удалить</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Черновик: Непроверенные данные по термоядерному синтезу</td>
                        <td>Космос</td>
                        <td>Дмитрий Орлов</td>
                        <td><span class="badge badge-blocked">Заблокирована</span></td>
                        <td>
                            <div class="actions-row">
                                <button type="button" class="btn btn-sm btn-primary">Редактировать</button>
                                <button type="button" class="btn btn-sm btn-secondary">Разблокировать</button>
                                <button type="button" class="btn btn-sm btn-danger">Удалить</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="section-box">
            <h2>Изменение ролей пользователей</h2>
            <form action="#" method="POST" onsubmit="event.preventDefault(); alert('На этапе Лабораторной работы №1 реализована только вёрстка.');">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Пользователь</th>
                            <th>Текущая роль</th>
                            <th>Назначить роль</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>admin</td>
                            <td><span class="badge badge-role">Администратор</span></td>
                            <td>
                                <div class="inline-form">
                                    <select class="form-control">
                                        <option value="admin" selected>Администратор</option>
                                        <option value="journalist">Журналист</option>
                                        <option value="user">Пользователь</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">Сохранить</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>alex_smirnov</td>
                            <td><span class="badge badge-role">Журналист</span></td>
                            <td>
                                <div class="inline-form">
                                    <select class="form-control">
                                        <option value="admin">Администратор</option>
                                        <option value="journalist" selected>Журналист</option>
                                        <option value="user">Пользователь</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">Сохранить</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>ivan_reader</td>
                            <td><span class="badge badge-role">Пользователь</span></td>
                            <td>
                                <div class="inline-form">
                                    <select class="form-control">
                                        <option value="admin">Администратор</option>
                                        <option value="journalist">Журналист</option>
                                        <option value="user" selected>Пользователь</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">Сохранить</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </section>
    </main>

    <x-footer />
</body>
</html>
