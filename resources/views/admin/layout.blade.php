<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link
        rel="icon"
        href="{{ asset('images/Trendysongz-favicon.ico') }}"
        type="image/x-icon"
    >
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <title>@yield('title', 'Admin') | TrendySongz</title>
</head>
<body>
    <div class="admin-shell">
        <aside class="admin-side">
            <a class="admin-brand" href="{{ route('admin.dashboard') }}">
                TRENDYSONGZ Admin
            </a>

            <nav class="admin-nav" aria-label="Admin menu">
                <a
                    href="{{ route('admin.dashboard') }}"
                    @if (request()->routeIs('admin.dashboard'))
                        class="active"
                        aria-current="page"
                    @endif
                >
                    Dashboard
                </a>

                @can('manage-content')
                    <details @if (request()->routeIs('admin.listings.*')) open @endif>
                        <summary>Listing</summary>

                        <a
                            href="{{ route('admin.listings.index') }}"
                            @if (request()->routeIs('admin.listings.index', 'admin.listings.edit'))
                                class="active"
                                aria-current="page"
                            @endif
                        >
                            All listings
                        </a>

                        <a
                            href="{{ route('admin.listings.create') }}"
                            @if (request()->routeIs('admin.listings.create'))
                                class="active"
                                aria-current="page"
                            @endif
                        >
                            Add listing
                        </a>
                    </details>

                    <details @if (request()->routeIs('admin.artists.*')) open @endif>
                        <summary>Artistes</summary>

                        <a
                            href="{{ route('admin.artists.index') }}"
                            @if (request()->routeIs('admin.artists.index'))
                                class="active"
                                aria-current="page"
                            @endif
                        >
                            All artistes
                        </a>

                        <a
                            href="{{ route('admin.artists.create') }}"
                            @if (request()->routeIs('admin.artists.create'))
                                class="active"
                                aria-current="page"
                            @endif
                        >
                            Add artiste
                        </a>
                    </details>

                    @foreach (['DJs', 'Albums', 'DJ Mixes'] as $menu)
                        <details>
                            <summary>{{ $menu }}</summary>
                            <span class="admin-pending">
                                Forms are next in the build
                            </span>
                        </details>
                    @endforeach
                @endcan

                <details>
                    <summary>Blog</summary>
                    <span class="admin-pending">
                        Blog forms are next in the build
                    </span>
                </details>

                @can('manage-users')
                    <details @if (request()->routeIs('admin.users.*')) open @endif>
                        <summary>Users</summary>

                        <a
                            href="{{ route('admin.users.index') }}"
                            @if (request()->routeIs('admin.users.index'))
                                class="active"
                                aria-current="page"
                            @endif
                        >
                            All users
                        </a>

                        <a
                            href="{{ route('admin.users.create') }}"
                            @if (request()->routeIs('admin.users.create'))
                                class="active"
                                aria-current="page"
                            @endif
                        >
                            Add user
                        </a>
                    </details>

                    <details>
                        <summary>Settings</summary>
                        <span class="admin-pending">
                            Settings are next in the build
                        </span>
                    </details>
                @endcan
            </nav>
        </aside>

        <main class="admin-content">
            <header class="admin-top">
                <p>Signed in as {{ auth()->user()->name }}</p>

                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf

                    <button class="admin-logout" type="submit">
                        Log out
                    </button>
                </form>
            </header>

            @if (session('status'))
                <div class="admin-alert" role="status">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="admin-error" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>