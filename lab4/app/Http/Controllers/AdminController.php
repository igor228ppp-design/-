<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Role;
use App\Models\Text;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Панель администратора: список статей для редактирования/блокировки/удаления
     * и список пользователей с формой изменения ролей.
     */
    public function index(?int $editId = null): View
    {
        $texts = Text::with(['category', 'user.role'])->orderByDesc('created_at')->orderByDesc('id')->get();
        $users = User::with('role')->orderBy('id')->get();
        $roles = Role::orderBy('id')->get();
        $categories = Category::orderBy('name')->get();
        $editingText = $editId ? Text::findOrFail($editId) : null;

        return view('admin', compact('texts', 'users', 'roles', 'categories', 'editingText'));
    }

    /**
     * Редактирование любой статьи администратором.
     */
    public function updateText(Request $request, Text $text): RedirectResponse
    {
        $validated = $request->validate([
            'heading' => ['required', 'string', 'max:255'],
            'categorie_id' => ['required', 'exists:categories,id'],
            'text' => ['required', 'string'],
        ]);

        $text->update($validated);

        return redirect()->route('admin')->with('success', 'Статья #' . $text->id . ' успешно отредактирована.');
    }

    /**
     * Блокировка / разблокировка статьи.
     */
    public function toggleBlock(Text $text): RedirectResponse
    {
        $text->update([
            'is_blocked' => ! $text->is_blocked,
        ]);

        $status = $text->is_blocked ? 'заблокирована' : 'разблокирована';

        return redirect()->route('admin')->with('success', "Статья #{$text->id} {$status}.");
    }

    /**
     * Удаление статьи из базы данных.
     */
    public function destroyText(Text $text): RedirectResponse
    {
        $id = $text->id;
        $text->delete();

        return redirect()->route('admin')->with('success', "Статья #{$id} удалена.");
    }

    /**
     * Форма изменения роли пользователя.
     */
    public function updateUserRole(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        $user->update([
            'role_id' => $validated['role_id'],
        ]);

        return redirect()->route('admin')->with('success', "Роль пользователя «{$user->login}» успешно обновлена.");
    }
}
