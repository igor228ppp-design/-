<?php

namespace App\Http\Controllers;

use App\Models\Text;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NewsController extends Controller
{
    private array $categories = [
        'Технологии',
        'Медицина',
        'Космос',
    ];

    /**
     * 3.1. Главная — все опубликованные статьи от новых к старым + пагинация.
     */
    public function index(): View
    {
        $texts = Text::with('user')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(6);

        return view('index', compact('texts'));
    }

    /**
     * 3.2. Категория / каталог — статьи выбранной категории (по умолчанию «Технологии»).
     */
    public function category(?string $categoryName = 'Технологии'): View
    {
        $texts = Text::with('user')
            ->where('category', $categoryName)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(6);

        return view('category', [
            'texts' => $texts,
            'currentCategory' => $categoryName,
            'categories' => $this->categories,
        ]);
    }

    /**
     * 3.3. Журналист — интерфейс для написания и редактирования статей.
     */
    public function journalist(?int $editId = null): View
    {
        $editingText = $editId ? Text::findOrFail($editId) : null;
        $texts = Text::with('user')->orderByDesc('created_at')->orderByDesc('id')->get();

        return view('journalist', [
            'categories' => $this->categories,
            'editingText' => $editingText,
            'texts' => $texts,
        ]);
    }

    /**
     * Сохранение новой статьи в БД.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'heading' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'text' => ['required', 'string'],
        ]);

        Text::create([
            'heading' => $validated['heading'],
            'category' => $validated['category'],
            'text' => $validated['text'],
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('home')->with('success', 'Статья успешно опубликована и сохранена в базе данных!');
    }

    /**
     * Обновление существующей статьи.
     */
    public function update(Request $request, Text $text): RedirectResponse
    {
        $validated = $request->validate([
            'heading' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'text' => ['required', 'string'],
        ]);

        $text->update($validated);

        return redirect()->route('journalist')->with('success', 'Статья успешно обновлена!');
    }

    /**
     * 4-й раздел: Админ (продолжение из Лабораторной №1).
     */
    public function admin(): View
    {
        $texts = Text::with('user')->orderByDesc('created_at')->get();

        return view('admin', compact('texts'));
    }
}
