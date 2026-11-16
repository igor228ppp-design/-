<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Разграничение доступа к разделам сайта по ролям пользователей:
     * - Обычный пользователь (user): главная и категории
     * - Журналист (journalist): главная, категории и раздел журналиста
     * - Администратор (admin): любой раздел сайта, включая админку
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login')->withErrors([
                'login' => 'Пожалуйста, войдите в аккаунт для доступа к этому разделу.',
            ]);
        }

        $user->loadMissing('role');

        if (! $user->hasRole(...$roles)) {
            abort(403, 'У вас недостаточно прав для доступа к данному разделу.');
        }

        return $next($request);
    }
}
