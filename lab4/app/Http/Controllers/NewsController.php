<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Text;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NewsController extends Controller
{
    /**
     * Главная — все опубликованные (незаблокированные) новости.
     * Доступна всем пользователям.
     */
    public function index(): View
    {
        $texts = Text::with(['category', 'user.role'])
            ->where('is_blocked', false)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(6);

        return view('index', compact('texts'));
    }

    /**
     * Категория — незаблокированные новости выбранной категории.
     * Доступна всем пользователям.
     */
    public function category(?int $categoryId = null): View
    {
        $categories = Category::orderBy('id')->get();
        $currentCategory = $categoryId
            ? Category::findOrFail($categoryId)
            : ($categories->first() ?? Category::firstOrCreate(['name' => 'Технологии']));

        $texts = Text::with(['category', 'user'])
            ->where('categorie_id', $currentCategory->id)
            ->where('is_blocked', false)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(6);

        return view('category', compact('texts', 'categories', 'currentCategory'));
    }

    /**
     * Журналист — написание и редактирование статей.
     * Доступен только ролям journalist и admin через RoleMiddleware.
     */
    public function journalist(?int $editId = null): View
    {
        $categories = Category::orderBy('name')->get();
        $editingText = $editId ? Text::findOrFail($editId) : null;

        $query = Text::with(['category', 'user'])->orderByDesc('created_at')->orderByDesc('id');
        if (! Auth::user()->hasRole('admin')) {
            $query->where('user_id', Auth::id());
        }
        $texts = $query->get();

        return view('journalist', compact('categories', 'editingText', 'texts'));
    }

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
            'is_blocked' => false,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('home')->with('success', 'Статья успешно опубликована!');
    }

    public function update(Request $request, Text $text): RedirectResponse
    {
        if (! Auth::user()->hasRole('admin') && $text->user_id !== Auth::id()) {
            abort(403, 'Вы можете редактировать только свои статьи.');
        }

        $validated = $request->validate([
            'heading' => ['required', 'string', 'max:255'],
            'categorie_id' => ['required', 'exists:categories,id'],
            'text' => ['required', 'string'],
        ]);

        $text->update($validated);

        return redirect()->route('journalist')->with('success', 'Статья успешно обновлена!');
    }
}
