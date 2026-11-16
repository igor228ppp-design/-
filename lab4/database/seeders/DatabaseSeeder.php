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

        // Тестовые пользователи для проверки каждого уровня доступа Middleware
        $admin = User::firstOrCreate(
            ['login' => 'admin'],
            ['password' => Hash::make('password123'), 'role_id' => $adminRole->id]
        );

        $journalist = User::firstOrCreate(
            ['login' => 'journalist'],
            ['password' => Hash::make('password123'), 'role_id' => $journalistRole->id]
        );

        User::firstOrCreate(
            ['login' => 'user'],
            ['password' => Hash::make('password123'), 'role_id' => $userRole->id]
        );

        // Начальные статьи
        if (Text::count() === 0) {
            Text::create([
                'heading' => 'Квантовые процессоры нового поколения преодолели порог коррекции ошибок',
                'text' => 'Исследователи представили архитектуру логических кубитов, способную сохранять когерентность в десять раз дольше предыдущих образцов.',
                'is_blocked' => false,
                'categorie_id' => $techCategory->id,
                'user_id' => $journalist->id,
            ]);

            Text::create([
                'heading' => 'Персонализированная мРНК-терапия показала высокую эффективность в клинических тестах',
                'text' => 'Новая методика синтеза биопрепаратов позволяет адаптировать лечение под индивидуальный геном пациента всего за несколько дней.',
                'is_blocked' => false,
                'categorie_id' => $medCategory->id,
                'user_id' => $journalist->id,
            ]);

            Text::create([
                'heading' => 'Черновик: Требует проверки редакцией',
                'text' => 'Пример заблокированной администратором статьи, которая скрыта с главной страницы и из категорий.',
                'is_blocked' => true,
                'categorie_id' => $spaceCategory->id,
                'user_id' => $admin->id,
            ]);
        }
    }
}
