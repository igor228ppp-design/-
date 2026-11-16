<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Role;
use App\Models\Text;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NewsController extends Controller
{
    /**
     * Главная — все статьи с жадной загрузкой связей (category, user.role).
     */
    public function index(): View
    {
        $texts = Text::with(['category', 'user.role'])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(6);

        return view('index', compact('texts'));
    }

    /**
     * Категория — вывод статей выбранной категории из таблицы categories.
     */
    public function category(?int $categoryId = null): View
    {
        $categories = Category::orderBy('id')->get();
        $currentCategory = $categoryId
            ? Category::findOrFail($categoryId)
            : ($categories->first() ?? Category::firstOrCreate(['name' => 'Технологии']));

        $texts = Text::with(['category', 'user'])
            ->where('categorie_id', $currentCategory->id)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(6);

        return view('category', compact('texts', 'categories', 'currentCategory'));
    }

    /**
     * Журналист — создание и редактирование статей со связями на categories и users.
     */
    public function journalist(?int $editId = null): View
    {
        $categories = Category::orderBy('name')->get();
        $editingText = $editId ? Text::findOrFail($editId) : null;
        $texts = Text::with(['category', 'user'])->orderByDesc('created_at')->orderByDesc('id')->get();

        return view('journalist', compact('categories', 'editingText', 'texts'));
    }

    /**
     * Сохранение статьи: внешний ключ user_id и categorie_id обязательны (ссылочная целостность).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'heading' => ['required', 'string', 'max:255'],
            'categorie_id' => ['required', 'exists:categories,id'],
            'text' => ['required', 'string'],
        ]);

        Text::create([
            'heading' => $validated['heading'],
            'categorie_id' => $validated['categorie_id'],
            'text' => $validated['text'],
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('home')->with('success', 'Статья успешно сохранена и связана с категорией и автором!');
    }

    /**
     * Обновление статьи.
     */
    public function update(Request $request, Text $text): RedirectResponse
    {
        $validated = $request->validate([
            'heading' => ['required', 'string', 'max:255'],
            'categorie_id' => ['required', 'exists:categories,id'],
            'text' => ['required', 'string'],
        ]);

        $text->update($validated);

        return redirect()->route('journalist')->with('success', 'Статья успешно обновлена!');
    }

    /**
     * Админ — просмотр связей таблиц roles, users, categories, texts.
     */
    public function admin(): View
    {
        $texts = Text::with(['category', 'user.role'])->orderByDesc('created_at')->get();
        $users = User::with('role')->withCount('texts')->orderBy('id')->get();
        $roles = Role::withCount('users')->orderBy('id')->get();
        $categories = Category::withCount('texts')->orderBy('id')->get();

        return view('admin', compact('texts', 'users', 'roles', 'categories'));
    }
}
