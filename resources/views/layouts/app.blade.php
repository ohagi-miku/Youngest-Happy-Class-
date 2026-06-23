<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} | @yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <!-- fontawesome cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">


    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div id="app" class="d-flex">
        <nav class="d-flex flex-column align-items-center vh-100 py-4 border-end bg-white"
            style="width: 80px; position: sticky; top: 0;">

            {{-- ロゴ --}}
            <a href="{{ url('/') }}" class="mb-4 text-dark">
                <i class="fa-brands fa-instagram icon-sm"></i>
            </a>

            {{-- ナビアイコン --}}
            @auth
                @if (!request()->is('admin/*'))
                    <ul class="navbar-nav d-flex flex-column align-items-center gap-3 mt-5">

                        {{-- Home --}}
                        <li class="nav-item" title="Home">
                            <a href="{{ route('index') }}" class="nav-link text-dark">
                                <i class="fa-solid fa-house icon-sm"></i>
                            </a>
                        </li>

                        {{-- Search --}}
                        <li class="nav-item" title="Search">
                            <a href="{{ route('search') }}" class="nav-link text-dark">
                                <i class="fa-solid fa-magnifying-glass icon-sm"></i>
                            </a>
                        </li>

                        {{-- Create Post --}}
                        <li class="nav-item" title="Create Post">
                            <a href="{{ route('post.create') }}" class="nav-link text-dark">
                                <i class="fa-solid fa-circle-plus icon-sm"></i>
                            </a>
                        </li>

                        {{-- Account --}}
                        <li class="nav-item dropdown" title="Account">
                            <button class="btn shadow-none nav-link text-dark" data-bs-toggle="dropdown">
                                @if (Auth::user()->avatar)
                                    <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}"
                                        class="rounded-circle avatar-sm">
                                @else
                                    <i class="fa-solid fa-circle-user icon-sm"></i>
                                @endif
                            </button>
                            <div class="dropdown-menu">
                                @can('admin')
                                    <a href="{{ route('admin.users') }}" class="dropdown-item">
                                        <i class="fa-solid fa-user-gear"></i> Admin
                                    </a>
                                    <hr class="dropdown-divider">
                                @endcan
                                <a href="{{ route('profile.show', Auth::user()->id) }}" class="dropdown-item">
                                    <i class="fa-solid fa-circle-user"></i> Profile
                                </a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>

                    </ul>
                @endif
            @endauth

            @guest
                <ul class="navbar-nav d-flex flex-column align-items-center gap-3">
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="{{ route('register') }}">Register</a>
                    </li>
                </ul>
            @endguest

        </nav>

        <main class="flex-grow-1 py-5" style="min-width: 0;">
            <div class="container">
                <div class="row justify-content-center">
                    {{-- [SOON] Admin Menu (col-3) --}}
                    @if (request()->is('admin/*'))
                        {{-- request()->is('pattern')- check if the request URL match the pattern ex: admin/* --}}
                        <div class="col-3">
                            <div class="list-group">
                                <a href="{{ route('admin.users') }}"
                                    class="list-group-item {{ request()->is('admin/users') ? 'active' : '' }}">
                                    <i class="fa-solid fa-users"></i> Users
                                </a>
                                <a href="{{ route('admin.posts') }}"
                                    class="list-group-item {{ request()->is('admin/posts') ? 'active' : '' }}">
                                    <i class="fa-solid fa-newspaper"></i> Posts
                                </a>
                                <a href="{{ route('admin.categories') }}"
                                    class="list-group-item {{ request()->is('admin/categories') ? 'active' : '' }}">
                                    <i class="fa-solid fa-tags"></i> Categories
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="col-9">
                        @yield('content')
                    </div>
                </div>
            </div>
        </main>
    </div>
    
</body>

</html>
