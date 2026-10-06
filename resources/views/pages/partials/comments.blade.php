@php
    $commentKey = 'comments-' . $postType . '-' . (int) $postId;

    $commentToken = \App\Support\CommentTarget::token(
        $postType,
        (int) $postId,
        (string) $postTitle
    );

    $approvedComments = \Illuminate\Support\Facades\DB::table('comments')
        ->where('post_type', $postType)
        ->where('post_id', (int) $postId)
        ->where('is_allowed', 1)
        ->orderByDesc('id')
        ->paginate(
            10,
            ['id', 'name', 'coments', 'likes_count', 'created_at'],
            'comments_page'
        )
        ->withQueryString()
        ->fragment($commentKey);
@endphp

<section
    class="ts-comments"
    id="{{ $commentKey }}"
    aria-labelledby="{{ $commentKey }}-heading"
>
    <div class="ts-comments-list">
        <header class="ts-comments-header">
            <span class="ts-comments-eyebrow">JOIN THE CONVERSATION</span>

            <h2 id="{{ $commentKey }}-heading">
                Comments
                <span>{{ number_format($approvedComments->total()) }}</span>
            </h2>
        </header>

        @forelse ($approvedComments as $comment)
            @php
                $likeKey = 'ts-comment-like:' . hash(
                    'sha256',
                    session()->getId() . ':' . $comment->id
                );

                $hasLiked = \Illuminate\Support\Facades\Cache::has($likeKey);
            @endphp

            <article class="ts-comment">
                <span class="ts-comment-avatar" aria-hidden="true">
                    {{ mb_strtoupper(
                        mb_substr($comment->name ?: 'Guest', 0, 1)
                    ) }}
                </span>

                <div class="ts-comment-content">
                    <header class="ts-comment-meta">
                        <strong>{{ $comment->name ?: 'Guest' }}</strong>

                       <!-- <time
                            datetime="{{ \Illuminate\Support\Carbon::parse($comment->created_at)->toIso8601String() }}"
                        >
                            {{ \Illuminate\Support\Carbon::parse($comment->created_at)->format('M j, Y') }}
                        </time>-->
                    </header>

                    <p>{{ $comment->coments }}</p>

                   <!-- <div class="ts-comment-reactions">
                        <button
                            type="button"
                            class="ts-comment-like{{ $hasLiked ? ' ts-has-liked' : '' }}"
                            data-like-url="{{ route('comments.like', [
                                'comment' => $comment->id,
                            ]) }}"
                            data-csrf="{{ csrf_token() }}"
                            aria-label="Like this comment"
                            aria-pressed="{{ $hasLiked ? 'true' : 'false' }}"
                            @if ($hasLiked) disabled @endif
                        >
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true"
                            >
                                <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8L12 21l8.8-8.6a5.5 5.5 0 0 0 0-7.8Z"/>
                            </svg>
                        </button>

                        <span class="ts-comment-like-count" aria-live="polite">
                            {{ number_format((int) $comment->likes_count) }}
                        </span>

                        <span
                            class="ts-comment-like-error"
                            role="status"
                            hidden
                        ></span>
                    </div>-->
                </div>
            </article>
        @empty
            <p class="ts-comments-empty">
                No comments yet. Share your thoughts below.
            </p>
        @endforelse

        @if ($approvedComments->hasPages())
            <nav class="ts-comments-pagination" aria-label="Comment pages">
                @if ($approvedComments->previousPageUrl())
                    <a href="{{ $approvedComments->previousPageUrl() }}">
                        ← Previous
                    </a>
                @endif

                <span>
                    Page {{ $approvedComments->currentPage() }}
                    of {{ $approvedComments->lastPage() }}
                </span>

                @if ($approvedComments->nextPageUrl())
                    <a href="{{ $approvedComments->nextPageUrl() }}">
                        Next →
                    </a>
                @endif
            </nav>
        @endif
    </div>

    <form
        class="ts-comment-form"
        action="{{ route('comments.store') }}"
        method="POST"
        data-ts-comment-form
    >
        @csrf

        <input type="hidden" name="target" value="{{ $commentToken }}">

        <h3>Leave a comment</h3>

        <p class="ts-comment-intro">
            Share your thoughts. Comments are reviewed before appearing.
        </p>

        <div class="ts-comment-fields">
            <label for="{{ $commentKey }}-name">
                Your name

                <input
                    id="{{ $commentKey }}-name"
                    name="name"
                    type="text"
                    required
                    minlength="2"
                    maxlength="191"
                    autocomplete="name"
                    placeholder="Enter your name"
                    value="{{ old('name') }}"
                >
            </label>

            <label for="{{ $commentKey }}-email">
                Email <span class="ts-comment-optional">(optional)</span>

                <input
                    id="{{ $commentKey }}-email"
                    name="email"
                    type="email"
                    maxlength="250"
                    autocomplete="email"
                    placeholder="Your email address"
                    value="{{ old('email') }}"
                >
            </label>
        </div>

        <label for="{{ $commentKey }}-message">
            Your comment

            <textarea
                id="{{ $commentKey }}-message"
                name="message"
                required
                minlength="2"
                maxlength="3000"
                rows="4"
                placeholder="Write your comment..."
            >{{ old('message') }}</textarea>
        </label>

        <div class="ts-comment-trap" aria-hidden="true">
            <label for="{{ $commentKey }}-website">
                Leave this field empty

                <input
                    id="{{ $commentKey }}-website"
                    name="website"
                    type="text"
                    tabindex="-1"
                    autocomplete="off"
                >
            </label>
        </div>

        <div class="ts-comment-footer">
            <p>
                Comments require approval. No links, please.
                Your email stays private.
            </p>

            <button type="submit">Submit comment</button>
        </div>

        <div
            class="ts-comment-feedback"
            data-ts-comment-feedback
            data-state="{{ $errors->any() ? 'error' : 'success' }}"
            role="status"
            aria-live="polite"
            tabindex="-1"
            @if (! $errors->any() && ! session('comment_status')) hidden @endif
        >
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            @else
                {{ session('comment_status') }}
            @endif
        </div>
    </form>
</section>