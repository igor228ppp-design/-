<?php

namespace Database\Seeders;

use App\Models\Text;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $journalist = User::firstOrCreate(
            ['login' => 'alex_smirnov'],
            ['password' => Hash::make('password123')]
        );

        $author2 = User::firstOrCreate(
            ['login' => 'maria_ivanova'],
            ['password' => Hash::make('password123')]
        );

        if (Text::count() === 0) {
            Text::create([
                'heading' => 'Квантовые процессоры нового поколения преодолели порог коррекции ошибок',
                'category' => 'Технологии',
                'text' => 'Исследователи представили архитектуру логических кубитов, способную сохранять когерентность в десять раз дольше предыдущих образцов, открывая путь к практическим вычислениям.',
                'user_id' => $journalist->id,
            ]);

            Text::create([
                'heading' => 'Персонализированная мРНК-терапия показала высокую эффективность в клинических тестах',
                'category' => 'Медицина',
                'text' => 'Новая методика синтеза биопрепаратов позволяет адаптировать лечение под индивидуальный геном пациента всего за несколько дней в условиях клиники.',
                'user_id' => $author2->id,
            ]);

            Text::create([
                'heading' => 'Орбитальный телескоп обнаружил водяной пар в атмосфере экзопланеты земного типа',
                'category' => 'Космос',
                'text' => 'Спектральный анализ атмосферы планеты в обитаемой зоне красного карлика подтвердил наличие устойчивых облачных структур и водяного пара.',
                'user_id' => $author2->id,
            ]);

            Text::create([
                'heading' => 'Нейроморфные чипы снизили энергопотребление систем искусственного интеллекта на 80%',
                'category' => 'Технологии',
                'text' => 'Инженеры разработали процессор, имитирующий работу синапсов человеческого мозга, что позволяет запускать сложные модели локально на автономных устройствах.',
                'user_id' => $journalist->id,
            ]);
        }
    }
}
