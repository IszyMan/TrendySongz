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
    
    <style>
/* Admin menu badge */

.ts-admin-comments-menu {
    display: flex !important;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}

.ts-admin-comments-menu.ts-is-active {
    background: #168044;
    color: #fff;
}

.ts-comment-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 22px;
    min-height: 22px;
    padding: 2px 7px;
    border-radius: 20px;
    background: #dc2626;
    color: #fff;
    font: 700 11px/1.4 Arial, sans-serif;
}

/* Admin moderation */

.ts-admin-comments {
    max-width: 980px;
    color: #1f2937;
    font: 14px/1.6 Arial, sans-serif;
}

.ts-admin-comments,
.ts-admin-comments * {
    box-sizing: border-box;
}

.ts-admin-comments-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 20px;
}

.ts-admin-comments-heading h1 {
    margin: 0;
    font-size: 25px;
}

.ts-admin-comments-heading p {
    margin: 4px 0 0;
    color: #6b7280;
    font-size: 13px;
}

.ts-admin-pending-label {
    padding: 6px 11px;
    border-radius: 20px;
    background: #fff4de;
    color: #8a5b10;
    font-size: 12px;
    font-weight: 700;
}

.ts-admin-comment-tabs {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 16px;
}

.ts-admin-comment-tabs a {
    padding: 7px 12px;
    border-radius: 7px;
    background: #eef1ee;
    color: #59635c;
    text-decoration: none;
}

.ts-admin-comment-tabs a.ts-is-active {
    background: #167b42;
    color: #fff;
}

.ts-admin-comment-search {
    display: flex;
    gap: 8px;
    margin-bottom: 18px;
}

.ts-admin-comment-search input {
    width: 100%;
    min-width: 0;
    padding: 10px 12px;
    border: 1px solid #d9ded9;
    border-radius: 7px;
    font: inherit;
}

.ts-admin-comments button {
    padding: 8px 12px;
    border: 0;
    border-radius: 6px;
    font: 700 12px/1.5 Arial, sans-serif;
    cursor: pointer;
}

.ts-admin-comment-search button {
    background: #26372c;
    color: #fff;
}

.ts-admin-comment-card {
    margin-bottom: 14px;
    padding: 20px;
    border: 1px solid #e2e7e2;
    border-radius: 12px;
    background: #fff;
}

.ts-admin-comment-card header {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.ts-admin-comment-card strong {
    overflow-wrap: anywhere;
}

.ts-admin-comment-state {
    padding: 3px 8px;
    border-radius: 20px;
    background: #fff2d6;
    color: #875909;
    font-size: 10px;
    font-weight: 700;
}

.ts-admin-comment-state.ts-is-approved {
    background: #e7f6ec;
    color: #187b42;
}

.ts-admin-comment-context {
    margin: 8px 0;
    color: #758074;
    font-size: 12px;
    overflow-wrap: anywhere;
}

.ts-admin-comment-context a {
    color: #168044;
    text-decoration: none;
}

.ts-admin-comment-body {
    margin: 12px 0;
    font-size: 14px;
    white-space: pre-wrap;
    overflow-wrap: anywhere;
}

.ts-admin-comment-details {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    color: #899185;
    font-size: 11px;
    overflow-wrap: anywhere;
}

.ts-admin-comment-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 15px;
}

.ts-admin-comment-actions form {
    margin: 0;
}

.ts-admin-approve {
    background: #168044;
    color: #fff;
}

.ts-admin-neutral {
    background: #edf0ed;
    color: #415043;
}

.ts-admin-delete {
    background: #fff0f0;
    color: #b52727;
}

.ts-admin-comment-empty {
    padding: 20px;
    border: 1px dashed #d9dfd9;
    border-radius: 10px;
    color: #687468;
}

.ts-admin-comments button:focus-visible,
.ts-admin-comments a:focus-visible {
    outline: 2px solid #168044;
    outline-offset: 3px;
}

/* Admin pagination */

.ts-admin-comment-pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 14px;
    flex-wrap: wrap;
    margin-top: 18px;
    font-size: 12px;
}

.ts-admin-comment-pagination a {
    color: #168044;
    font-weight: 700;
    text-decoration: none;
}

/* Accessible hidden label */

.ts-comment-sr {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

/* Admin mobile styles */

@media (max-width: 480px) {
    .ts-admin-comment-card {
        padding: 16px;
    }

    .ts-admin-comments-heading h1 {
        font-size: 22px;
    }
}
</style>
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


                    <details
                        @if (request()->routeIs('admin.albums.*'))
                            open
                        @endif
                    >
                        <summary>Albums</summary>

                        <a
                            href="{{ route('admin.albums.index') }}"
                            @if (
                                request()->routeIs(
                                    'admin.albums.index',
                                    'admin.albums.edit'
                                )
                            )
                                class="active"
                                aria-current="page"
                            @endif
                        >
                            All albums
                        </a>

                        <a
                            href="{{ route('admin.albums.create') }}"
                            @if (request()->routeIs('admin.albums.create'))
                                class="active"
                                aria-current="page"
                            @endif
                        >
                            Add album
                        </a>
                    </details>


                    <details
                        @if (request()->routeIs('admin.djs.*'))
                            open
                        @endif>
                        <summary>DJs</summary>

                        <a
                            href="{{ route('admin.djs.index') }}"
                            @if (
                                request()->routeIs(
                                    'admin.djs.index',
                                    'admin.djs.edit'
                                )
                            )
                                class="active"
                                aria-current="page"
                            @endif
                        >
                            All DJs
                        </a>

                        <a
                            href="{{ route('admin.djs.create') }}"
                            @if (request()->routeIs('admin.djs.create'))
                                class="active"
                                aria-current="page"
                            @endif
                        >
                            Add DJ
                        </a>
                    </details>


                    <details
                        @if (request()->routeIs('admin.dj-mixes.*'))
                            open
                        @endif
                    >
                        <summary>DJ Mixes</summary>

                        <a
                            href="{{ route('admin.dj-mixes.index') }}"
                            @if (
                                request()->routeIs(
                                    'admin.dj-mixes.index',
                                    'admin.dj-mixes.edit'
                                )
                            )
                                class="active"
                                aria-current="page"
                            @endif
                        >
                            All DJ mixes
                        </a>

                        <a
                            href="{{ route('admin.dj-mixes.create') }}"
                            @if (request()->routeIs('admin.dj-mixes.create'))
                                class="active"
                                aria-current="page"
                            @endif
                        >
                            Add DJ mix
                        </a>
                    </details>
                   
                @endcan


                @can('manage-blogs')
                    <details
                        @if (request()->routeIs('admin.blogs.*'))
                            open
                        @endif
                    >
                        <summary>Blog</summary>

                        <a
                            href="{{ route('admin.blogs.index') }}"
                            @if (
                                request()->routeIs(
                                    'admin.blogs.index',
                                    'admin.blogs.edit'
                                )
                            )
                                class="active"
                                aria-current="page"
                            @endif
                        >
                            All blog posts
                        </a>

                        <a
                            href="{{ route('admin.blogs.create') }}"
                            @if (request()->routeIs('admin.blogs.create'))
                                class="active"
                                aria-current="page"
                            @endif
                        >
                            Add blog post
                        </a>
                    </details>
                @endcan
                
                
                @can('manage-users')
                    @php
                        $pendingCommentsCount = \Illuminate\Support\Facades\DB::table('comments')
                            ->where(function ($query) {
                                $query->whereNull('is_allowed')
                                    ->orWhere('is_allowed', '<>', 1);
                            })
                            ->count();
                    @endphp
                
                    <a
                        href="{{ route('admin.comments.index') }}"
                        class="ts-admin-comments-menu{{ request()->routeIs('admin.comments.*') ? ' ts-is-active' : '' }}"
                        @if (request()->routeIs('admin.comments.*'))
                            aria-current="page"
                        @endif
                    >
                        <span>Comments</span>
                
                        @if ($pendingCommentsCount > 0)
                            <span
                                class="ts-comment-badge"
                                aria-label="{{ $pendingCommentsCount }} unapproved comments"
                            >
                                {{ number_format($pendingCommentsCount) }}
                            </span>
                        @endif
                    </a>
                @endcan


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