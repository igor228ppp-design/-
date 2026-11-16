<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Role;
use App\Models\Text;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Справочник ролей
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $journalistRole = Role::firstOrCreate(['name' => 'journalist']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Справочник категорий
        $techCategory = Category::firstOrCreate(['name' => 'Технологии']);
        $medCategory = Category::firstOrCreate(['name' => 'Медицина']);
        $spaceCategory = Category::firstOrCreate(['name' => 'Космос']);

        // Тестовые учётные записи пользователей
        $admin = User::firstOrCreate(
            ['login' => 'admin'],
            ['password' => Hash::make('password123'), 'role_id' => $adminRole->id]
        );

        $journalist = User::firstOrCreate(
            ['login' => 'journalist'],
            ['password' => Hash::make('password123'), 'role_id' => $journalistRole->id]
        );

        $reader = User::firstOrCreate(
            ['login' => 'reader'],
            ['password' => Hash::make('password123'), 'role_id' => $userRole->id]
        );

        // Тестовые статьи (texts) со связями на categories и users
        if (Text::count() === 0) {
            Text::create([
                'heading' => 'Квантовые процессоры нового поколения преодолели порог коррекции ошибок',
                'text' => 'Исследователи представили архитектуру логических кубитов, способную сохранять когерентность в десять раз дольше предыдущих образцов.',
                'categorie_id' => $techCategory->id,
                'user_id' => $journalist->id,
            ]);

            Text::create([
                'heading' => 'Персонализированная мРНК-терапия показала высокую эффективность в клинических тестах',
                'text' => 'Новая методика синтеза биопрепаратов позволяет адаптировать лечение под индивидуальный геном пациента всего за несколько дней.',
                'categorie_id' => $medCategory->id,
                'user_id' => $journalist->id,
            ]);

            Text::create([
                'heading' => 'Орбитальный телескоп обнаружил водяной пар в атмосфере экзопланеты земного типа',
                'text' => 'Спектральный анализ атмосферы планеты в обитаемой зоне красного карлика подтвердил наличие устойчивых облачных структур.',
                'categorie_id' => $spaceCategory->id,
                'user_id' => $admin->id,
            ]);
        }
    }
}
