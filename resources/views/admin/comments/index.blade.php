@extends('admin.layout')

@section('title', 'Comments')

@section('content')
    <section class="ts-admin-comments">
        <header class="ts-admin-comments-heading">
            <div>
                <h1>Comments</h1>
                <p>Review comments before they appear on your website.</p>
            </div>

            <span class="ts-admin-pending-label">
                {{ number_format($pendingCount) }} pending
            </span>
        </header>

        <nav class="ts-admin-comment-tabs" aria-label="Comment status">
            @foreach ([
                'pending' => 'Pending',
                'approved' => 'Approved',
                'all' => 'All comments',
            ] as $value => $label)
                <a
                    href="{{ route('admin.comments.index', [
                        'status' => $value,
                        'search' => $search,
                    ]) }}"
                    class="{{ $status === $value ? 'ts-is-active' : '' }}"
                    @if ($status === $value) aria-current="page" @endif
                >
                    {{ $label }}
                </a>
            @endforeach
        </nav>

        <form
            class="ts-admin-comment-search"
            method="GET"
            action="{{ route('admin.comments.index') }}"
        >
            <input type="hidden" name="status" value="{{ $status }}">

            <label class="ts-comment-sr" for="ts-admin-search">
                Search comments
            </label>

            <input
                id="ts-admin-search"
                type="search"
                name="search"
                value="{{ $search }}"
                maxlength="100"
                placeholder="Search name, comment or post title..."
            >

            <button type="submit">Search</button>
        </form>

        @forelse ($comments as $comment)
            @php
                $isApproved = (int) $comment->is_allowed === 1;

                $path = (string) ($comment->post_path ?? '');

                $safePath = $path !== ''
                    && str_starts_with($path, '/')
                    && ! str_starts_with($path, '//')
                    && ! str_contains($path, '\\')
                    && ! preg_match('/[\x00-\x20\x7F]/', $path);
            @endphp

            <article class="ts-admin-comment-card">
                <header>
                    <strong>{{ $comment->name ?: 'Guest' }}</strong>

                    <span
                        class="ts-admin-comment-state{{ $isApproved ? ' ts-is-approved' : '' }}"
                    >
                        {{ $isApproved ? 'Approved' : 'Pending' }}
                    </span>
                </header>

                <p class="ts-admin-comment-context">
                    @if ($comment->post_type && $comment->post_id)
                        {{ ucfirst($comment->post_type) }}
                        #{{ $comment->post_id }}

                        @if ($comment->post_title)
                            ·

                            @if ($safePath)
                                <a
                                    href="{{ $path }}"
                                    target="_blank"
                                    rel="noopener"
                                >
                                    {{ $comment->post_title }}
                                </a>
                            @else
                                {{ $comment->post_title }}
                            @endif
                        @endif
                    @else
                        Legacy comment
                        · Blog ID: {{ $comment->blog_id }}
                        · Category: {{ $comment->category_id ?: 'not specified' }}
                    @endif
                </p>

                <p class="ts-admin-comment-body">{{ $comment->coments }}</p>

                <div class="ts-admin-comment-details">
                    <time
                        datetime="{{ \Illuminate\Support\Carbon::parse($comment->created_at)->toIso8601String() }}"
                    >
                        {{ \Illuminate\Support\Carbon::parse($comment->created_at)->format('M j, Y · g:i a') }}
                    </time>

                    @if ($comment->email)
                        <span>Email: {{ $comment->email }}</span>
                    @endif

                    <span>
                        {{ number_format((int) $comment->likes_count) }} likes
                    </span>
                </div>

                <div class="ts-admin-comment-actions">
                    <form
                        method="POST"
                        action="{{ route('admin.comments.update', [
                            'comment' => $comment->id,
                        ]) }}"
                    >
                        @csrf
                        @method('PATCH')

                        <input
                            type="hidden"
                            name="is_allowed"
                            value="{{ $isApproved ? '0' : '1' }}"
                        >

                        <button
                            type="submit"
                            class="{{ $isApproved ? 'ts-admin-neutral' : 'ts-admin-approve' }}"
                        >
                            {{ $isApproved
                                ? 'Move to pending'
                                : 'Approve comment' }}
                        </button>
                    </form>

                    <form
                        method="POST"
                        action="{{ route('admin.comments.destroy', [
                            'comment' => $comment->id,
                        ]) }}"
                        onsubmit="return confirm('Delete this comment permanently?');"
                    >
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="ts-admin-delete">
                            Delete
                        </button>
                    </form>
                </div>
            </article>
        @empty
            <p class="ts-admin-comment-empty">No comments found.</p>
        @endforelse

        @if ($comments->hasPages())
            <nav
                class="ts-admin-comment-pagination"
                aria-label="Comment pages"
            >
                @if ($comments->previousPageUrl())
                    <a href="{{ $comments->previousPageUrl() }}">
                        ← Previous
                    </a>
                @endif

                <span>
                    Page {{ $comments->currentPage() }}
                    of {{ $comments->lastPage() }}
                </span>

                @if ($comments->nextPageUrl())
                    <a href="{{ $comments->nextPageUrl() }}">
                        Next →
                    </a>
                @endif
            </nav>
        @endif
    </section>
@endsection