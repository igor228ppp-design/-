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
            <h1>Все новости науки и технологий</h1>
            <p>Актуальные материалы о достижениях в сфере информационных технологий, медицины и космоса.</p>
        </div>

        <div class="news-grid">
            <article class="card">
                <div>
                    <div class="card-meta">
                        <span class="badge">Информационные технологии</span>
                        <span>16 ноября 2026</span>
                    </div>
                    <h2>Квантовые процессоры нового поколения преодолели порог коррекции ошибок</h2>
                    <p>Исследователи представили архитектуру логических кубитов, способную сохранять когерентность в десять раз дольше предыдущих образцов, открывая путь к практическим вычислениям.</p>
                </div>
                <div class="card-footer">
                    <span>Автор: Алексей Смирнов</span>
                    <span>Читать далее &rarr;</span>
                </div>
            </article>

            <article class="card">
                <div>
                    <div class="card-meta">
                        <span class="badge">Медицина</span>
                        <span>15 ноября 2026</span>
                    </div>
                    <h2>Персонализированная мРНК-терапия показала высокую эффективность в клинических тестах</h2>
                    <p>Новая методика синтеза биопрепаратов позволяет адаптировать лечение под индивидуальный геном пациента всего за несколько дней в условиях клиники.</p>
                </div>
                <div class="card-footer">
                    <span>Автор: Мария Иванова</span>
                    <span>Читать далее &rarr;</span>
                </div>
            </article>

            <article class="card">
                <div>
                    <div class="card-meta">
                        <span class="badge">Космос</span>
                        <span>14 ноября 2026</span>
                    </div>
                    <h2>Орбитальный телескоп обнаружил водяной пар в атмосфере экзопланеты земного типа</h2>
                    <p>Спектральный анализ атмосферы планеты в обитаемой зоне красного карлика подтвердил наличие устойчивых облачных структур и водяного пара.</p>
                </div>
                <div class="card-footer">
                    <span>Автор: Дмитрий Орлов</span>
                    <span>Читать далее &rarr;</span>
                </div>
            </article>

            <article class="card">
                <div>
                    <div class="card-meta">
                        <span class="badge">Информационные технологии</span>
                        <span>13 ноября 2026</span>
                    </div>
                    <h2>Нейроморфные чипы снизили энергопотребление систем искусственного интеллекта на 80%</h2>
                    <p>Инженеры разработали процессор, имитирующий работу синапсов человеческого мозга, что позволяет запускать сложные модели локально на автономных устройствах.</p>
                </div>
                <div class="card-footer">
                    <span>Автор: Алексей Смирнов</span>
                    <span>Читать далее &rarr;</span>
                </div>
            </article>
        </div>
    </main>

    <x-footer />
</body>
</html>
