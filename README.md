# Лабораторные работы №1–4 по теме «Веб-сайт» (Laravel)

Проект новостного портала **«Научный Прогресс»** на фреймворке Laravel, разделённый на **4 отдельные папки** (`lab1`, `lab2`, `lab3`, `lab4`), где каждая следующая лабораторная работа является прямым продолжением предыдущей.

---

## Структура репозитория

### 1. `lab1/` — Лабораторная работа №1: Вёрстка и Blade-компоненты
- **Шапка и подвал** вынесены в отдельные Blade-компоненты:
  - `resources/views/components/header.blade.php` (`<x-header />`)
  - `resources/views/components/footer.blade.php` (`<x-footer />`)
  - вызываются отдельно на каждой странице.
- Реализованы **4 раздела**:
  - **Главная** (`resources/views/index.blade.php`) — лента всех новостей сайта.
  - **Категория** (`resources/views/category.blade.php`) — вывод одной подкатегории («Информационные технологии»).
  - **Журналист** (`resources/views/journalist.blade.php`) — свёрстанная форма создания/редактирования статьи.
  - **Админ** (`resources/views/admin.blade.php`) — свёрстанная таблица управления статьями (редактирование, блокировка, удаление) и форма смены ролей пользователей.
- Маршруты настроены в `routes/web.php`, стили в `public/css/style.css`.

---

### 2. `lab2/` — Лабораторная работа №2: База данных, статьи и авторизация (продолжение ЛР №1)
- Подключено хранение данных в БД:
  - `database/migrations/0001_01_01_000000_create_users_table.php` — таблица пользователей (`login` уникальный, хешированный `password`).
  - `database/migrations/2026_11_16_000001_create_texts_table.php` — таблица статей с привязкой к автору (`user_id`).
- **Регистрация и вход** (`app/Http/Controllers/AuthController.php`, `resources/views/auth/login.blade.php`, `register.blade.php`):
  - Регистрация строго по полям: `login`, `password`, `password_confirmation`.
  - Вход по `login` и `password`, хеширование пароля (`Hash::make`), выход из аккаунта (`POST /logout`).
- **Создание и вывод статей** (`app/Http/Controllers/NewsController.php`):
  - В разделе «Журналист» реализовано создание и редактирование статей с сохранением в БД.
  - На **Главной** выводятся все статьи от новых к старым с пагинацией, категорией и логином автора.
  - В разделе **Категория** выводятся статьи выбранной категории (по умолчанию «Технологии», с возможностью переключения по остальным категориям).

---

### 3. `lab3/` — Лабораторная работа №3: Роли, категории и связи БД (продолжение ЛР №2)
- Спроектированы **4 взаимосвязанные таблицы** с индексами и ссылочной целостностью:
  1. `roles` (`0001_01_00_000001_create_roles_table.php`) — справочник ролей (`id`, `name`).
  2. `categories` (`0001_01_00_000002_create_categories_table.php`) — справочник категорий (`id`, `name`).
  3. `users` (`0001_01_01_000000_create_users_table.php`) — пользователи с уникальным `login` и обязательным проиндексированным внешним ключом `role_id` (`restrictOnDelete` — запрет удаления назначенной роли).
  4. `texts` (`2026_11_16_000001_create_texts_table.php`) — статьи (`id`, `heading`, `text`, `categorie_id` с `restrictOnDelete`, `user_id` с `cascadeOnDelete`, `timestamps`).
- Настроены **Eloquent Relationships** во всех моделях (`app/Models/Role.php`, `Category.php`, `User.php`, `Text.php`): `hasMany` и `belongsTo`.

---

### 4. `lab4/` — Лабораторная работа №4: Middleware, права доступа и Админка (продолжение ЛР №3)
- Создан и зарегистрирован `RoleMiddleware` (`app/Http/Middleware/RoleMiddleware.php`, псевдоним `role` в `bootstrap/app.php`):
  - **Обычный пользователь (`user`)** — доступ к Главной и Категориям.
  - **Журналист (`journalist`)** — доступ к Главной, Категориям и разделу «Журналист».
  - **Администратор (`admin`)** — доступ ко всем разделам сайта, включая «Админку».
- Реализован полный функционал раздела **Админ** (`app/Http/Controllers/AdminController.php`, `resources/views/admin.blade.php`):
  - Редактирование любых статей;
  - Блокировка и разблокировка статей (`is_blocked`, заблокированные скрываются из публичной ленты);
  - Удаление статей;
  - Изменение ролей пользователей через форму (`PATCH /admin/users/{user}/role`).

---

## Быстрый запуск любой из лабораторных работ локально

Перейдите в нужную папку (`lab1`, `lab2`, `lab3` или `lab4`) и выполните:

```bash
cd lab4
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan serve
cd C:\labs\lab4
composer install
copy .env.example .env
type nul > database\database.sqlite
php artisan key:generate
php artisan migrate --seed
php artisan serve
(Команды copy и type nul > работают в стандартной консоли cmd и консоли OSPanel. Если вводишь в PowerShell — вместо type nul > ... пиши New-Item database\database.sqlite -Force).
```

Тестовые аккаунты (для `lab3` и `lab4` после `--seed`, пароль везде `password123`):
- Администратор: логин `admin` / пароль `password123`
- Журналист: логин `journalist` / пароль `password123`
- Обычный пользователь: логин `user` / пароль `password123`
