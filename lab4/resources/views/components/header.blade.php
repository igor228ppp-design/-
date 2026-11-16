<header class="site-header">
    <div class="container header-inner">
        <a href="{{ route('home') }}" class="brand">
            <span class="brand-title">Научный Прогресс</span>
            <span class="brand-subtitle">Новостной портал о науке и технологиях</span>
        </a>

        <nav class="nav-links">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Главная</a>
            <a href="{{ route('category') }}" class="nav-link {{ request()->routeIs('category') ? 'active' : '' }}">Категория</a>

            @auth
                @if(auth()->user()->hasRole('journalist', 'admin'))
                    <a href="{{ route('journalist') }}" class="nav-link {{ request()->routeIs('journalist') ? 'active' : '' }}">Журналист</a>
                @endif

                @if(auth()->user()->hasRole('admin'))
                    <a href="{{ route('admin') }}" class="nav-link {{ request()->routeIs('admin*') ? 'active' : '' }}">Админ</a>
                @endif
            @endauth
        </nav>

        <div class="auth-area">
            @auth
                <span class="user-pill">
                    <strong>{{ auth()->user()->login }}</strong>
                    ({{ auth()->user()->role?->name ?? 'user' }})
                </span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline">Выйти</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}">Вход</a>
                <a href="{{ route('register') }}" class="btn btn-sm btn-primary">Регистрация</a>
            @endauth
        </div>
    </div>
</header>
