@php
    $shareUrl = $shareUrl ?? $canonicalUrl ?? url()->current();
    $shareTitle = $shareTitle ?? 'TrendySongz';
    $shareMessage = $shareMessage
        ?? 'Share “' . $shareTitle . '” with your friends';
@endphp

<section class="ts-share-block" aria-label="Share this page">
    <h2 class="ts-share-heading">
        <span>SHARE</span>
    </h2>

    <p class="ts-share-description">
        {{ $shareMessage }}
    </p>

    <div class="ts-share-actions">
        <button
            type="button"
            class="ts-share-copy"
            data-share-url="{{ $shareUrl }}"
        >
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                aria-hidden="true"
            >
                <path d="M10 13a5 5 0 0 0 7 .1l3-3a5 5 0 0 0-7-7l-2 2" />
                <path d="M14 11a5 5 0 0 0-7-.1l-3 3a5 5 0 0 0 7 7l2-2" />
            </svg>

            <span class="ts-share-copy-label">Copy link</span>
        </button>

        <div
            class="a2a_kit a2a_kit_size_32 ts-share-socials"
            data-a2a-url="{{ $shareUrl }}"
            data-a2a-title="{{ $shareTitle }}"
        >
            <a
                class="a2a_button_facebook"
                href="https://www.addtoany.com/share"
                aria-label="Share on Facebook"
                title="Facebook"
            ></a>

            <a
                class="a2a_button_x"
                href="https://www.addtoany.com/share"
                aria-label="Share on X"
                title="X"
            ></a>

            <a
                class="a2a_button_whatsapp"
                href="https://www.addtoany.com/share"
                aria-label="Share on WhatsApp"
                title="WhatsApp"
            ></a>

            <a
                class="a2a_button_telegram"
                href="https://www.addtoany.com/share"
                aria-label="Share on Telegram"
                title="Telegram"
            ></a>
        </div>
    </div>

    <p class="ts-share-status" role="status" aria-live="polite"></p>
</section>

@once
    <link rel="stylesheet" href="{{ asset('css/share.css') }}">

    <script src="{{ asset('js/share.js') }}" defer></script>
    <script src="https://static.addtoany.com/menu/page.js" defer></script>
@endonce