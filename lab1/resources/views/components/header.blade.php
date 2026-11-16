<header class="site-header">
    <div class="container header-inner">
        <a href="{{ route('home') }}" class="brand">
            <span class="brand-title">Научный Прогресс</span>
            <span class="brand-subtitle">Новостной портал о науке и технологиях</span>
        </a>

        <nav class="nav-links">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Главная</a>
            <a href="{{ route('category') }}" class="nav-link {{ request()->routeIs('category') ? 'active' : '' }}">Категория: IT и ИИ</a>
            <a href="{{ route('journalist') }}" class="nav-link {{ request()->routeIs('journalist') ? 'active' : '' }}">Журналист</a>
            <a href="{{ route('admin') }}" class="nav-link {{ request()->routeIs('admin') ? 'active' : '' }}">Админ</a>
        </nav>
    </div>
</header>
