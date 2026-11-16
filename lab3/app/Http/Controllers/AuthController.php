<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'login' => ['required', 'string', 'max:255', 'unique:users,login'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'login.required' => 'Укажите логин.',
            'login.unique' => 'Пользователь с таким логином уже существует.',
            'password.required' => 'Укажите пароль.',
            'password.min' => 'Пароль должен содержать минимум 6 символов.',
            'password.confirmed' => 'Подтверждение пароля не совпадает.',
        ]);

        // Новым зарегистрированным пользователям по умолчанию назначается роль user
        $defaultRole = Role::firstOrCreate(['name' => 'user']);

        $user = User::create([
            'login' => $validated['login'],
            'password' => Hash::make($validated['password']),
            'role_id' => $defaultRole->id,
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Регистрация прошла успешно!');
    }

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('home'))->with('success', 'Вы успешно вошли в аккаунт.');
        }

        return back()->withErrors([
            'login' => 'Неверный логин или пароль.',
        ])->onlyInput('login');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Вы вышли из аккаунта.');
    }
}
