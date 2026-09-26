@extends('layouts.app')

@section('title', 'Download Mix: ' . $dj->dj_name . '-' . $dj->mix_title . ' Mix ')

@section('meta')
    <meta name="description" content="Download {{ $dj->dj_name }} {{ $dj->mix_title }} Mix. {{ $dj->description1 }} {{ $dj->description2 }} "/>
    <meta name="keywords" content="{{ $dj->dj_name }} {{ $dj->mix_title }} mix, {{ $dj->dj_name }} {{ $dj->mix_title }}, download {{ $dj->dj_name }} {{ $dj->mix_title }}, {{ $dj->dj_name }} {{ $dj->mix_title }} download, ">
@endsection

@section('facebook_meta')
    <meta property="og:image" content="https://trendysongz.com/images/{{ $dj->cover_url }}">
    <meta property="og:description" content="Download {{ $dj->dj_name }} - {{ $dj->mix_title }} | {{ $dj->description1 }}">
@endsection

@section('twitter_meta')
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@TrendySongz_" />
    <meta name="twitter:title" content="{{ $dj->dj_name }} - {{ $dj->mix_title }}" />
    <meta name="twitter:description" content=" {{ $dj->description1 }}" />
    <meta name="twitter:image" content="https://trendysongz.com/images/{{ $dj->cover_url }}" />
@endsection

@section('canonical')
    <link
        rel="canonical"
        href="{{ route('pages.djmix_detail', [
            $id,
            strtolower(str_replace(')', '', str_replace('(', '', str_replace(' ', '-', $dj->dj_name)))),
            strtolower(str_replace(')', '', str_replace('(', '', str_replace(' ', '-', $dj->mix_title))))
        ]) }}"
    />
@endsection

@section('content')

<div class="ts-music-detail">

    {{-- =====================================================
         DJ MIX HEADER
    ====================================================== --}}
    <header class="ts-music-header">

        <h1 class="ts-music-title">
            {{ $dj->dj_name }} - {{ $dj->mix_title }} | Download Mix MP3
        </h1>

        <div class="ts-music-meta">

            <a href="{{ route('pages.djmix_posted_by', [
                strtolower(str_replace(')', '', str_replace('(', '', str_replace(' ', '-', $dj->posted_user))))
            ]) }}">
                Posted by: {{ $dj->posted_user }}
                <span class="fa fa-check-circle verified"></span>
            </a>

            <span class="ts-meta-separator">•</span>

            <time
                class="ts-music-date"
                datetime="{{ Carbon\Carbon::now()->toIso8601String() }}"
            >
                {{ Carbon\Carbon::now()->format('M d, Y') }}
            </time>

        </div>

    </header>


    {{-- =====================================================
         DJ MIX MAIN CARD
    ====================================================== --}}
    <section class="ts-music-details-card">

        {{-- =================================================
             INFORMATION BAR
        ================================================== --}}
        <div class="ts-music-info-bar">

            <div class="ts-info-item">
                <span class="ts-info-label">DJ</span>

                <span class="ts-info-value">
                    <a href="{{ route('pages.dj_details', [
                        strtolower(str_replace(')', '', str_replace('(', '', str_replace(' ', '-', $dj->dj_name))))
                    ]) }}">
                        {{ $dj->dj_name }}
                    </a>
                </span>
            </div>


            @if(!empty($dj->released_year))

                <div class="ts-info-item">

                    <span class="ts-info-label">Recorded</span>

                    <span class="ts-info-value">
                        <a href="{{ route('pages.djmix_released_year', $dj->released_year) }}">
                            {{ $dj->released_year }}
                        </a>
                    </span>

                </div>

            @else

                <div class="ts-info-item">

                    <span class="ts-info-label">Recorded</span>

                    <span class="ts-info-value">
                        —
                    </span>

                </div>

            @endif


            <div class="ts-info-item">

                <span class="ts-info-label">Category</span>

                <span class="ts-info-value">
                    <a href="{{ route('pages.djmix') }}">
                        Latest Mix
                    </a>
                </span>

            </div>


            <div class="ts-info-item">

                <span class="ts-info-label">Type</span>

                <span class="ts-info-value">
                    DJ Mix
                </span>

            </div>

        </div>


        {{-- =================================================
             COVER + MIX CONTENT
        ================================================== --}}
        <div class="ts-music-body">

            {{-- COVER --}}
            <div class="ts-music-cover">

                <img
                    src="{{ asset('/images/dj/' . $dj->cover_url) }}"
                    alt="{{ $dj->dj_name }} - {{ $dj->mix_title }}"
                    loading="eager"
                >

            </div>


            {{-- MIX CONTENT --}}
            <div class="ts-music-content">

                {{-- DJ NAME --}}
                <h2 class="ts-song-artist">

                    <a
                        href="{{ route('pages.dj_details', [
                            strtolower(str_replace(')', '', str_replace('(', '', str_replace(' ', '-', $dj->dj_name))))
                        ]) }}"
                    >
                        {{ $dj->dj_name }}
                    </a>

                </h2>


                {{-- MIX TITLE --}}
                <div class="ts-song-title-row">

                    <h3 class="ts-song-title">
                        {{ $dj->mix_title }}
                    </h3>

                </div>


                {{-- AUDIO PLAYER --}}
                @if(!empty($dj->track_url))

                    <div class="ts-audio-box">

                        <audio
                            id="myaudio"
                            class="ts-audio-element"
                            preload="metadata"
                        >
                            <source
                                src="{{ 'https://cdn.trendysongz.com/dj/' . $dj->track_url }}"
                                type="audio/mpeg"
                            >
                        </audio>


                        {{-- LISTEN --}}
                        <div class="ts-audio-heading">

                            <button
                                type="button"
                                class="ts-main-play"
                                id="tsPlayButton"
                                aria-label="Play mix"
                                aria-pressed="false"
                            >
                                <i
                                    class="fa fa-play"
                                    id="tsPlayIcon"
                                    aria-hidden="true"
                                ></i>
                            </button>

                            <span id="tsPlayLabel">
                                Listen to this mix
                            </span>

                        </div>


                        {{-- PROGRESS --}}
                        <div class="ts-audio-progress-row">

                            <span
                                class="ts-audio-time"
                                id="tsCurrentTime"
                            >
                                0:00
                            </span>

                            <input
                                type="range"
                                id="tsAudioProgress"
                                class="ts-audio-progress"
                                min="0"
                                max="100"
                                value="0"
                                step="0.1"
                                aria-label="Mix progress"
                            >

                            <span
                                class="ts-audio-time"
                                id="tsDuration"
                            >
                                0:00
                            </span>

                        </div>


                        {{-- DOWNLOAD --}}
                        <a
                            id="a1"
                            href="{{ 'https://cdn.trendysongz.com.com/dj/' . $dj->track_url }}"
                            class="ts-download-button"
                            download
                        >
                            <i class="fa fa-download"></i>
                            Download Mix MP3
                        </a>

                    </div>

                @endif

            </div>

        </div>

    </section>


    {{-- =====================================================
         ABOUT THE MIX
    ====================================================== --}}
    @if(
        !empty($dj->description1) ||
        !empty($dj->back_cover) ||
        !empty($dj->description2)
    )

        <section class="ts-about-song">

            <div class="ts-about-song-inner">

                <h2 class="ts-about-heading">
                    About the Mix
                </h2>


                <div class="ts-about-text">

                    @if(!empty($dj->description1))
                        {!! $dj->description1 !!}
                    @endif


                    @if(!empty($dj->back_cover))

                        <div class="ts-djmix-back-cover">

                            <img
                                src="{{ asset('/images/dj/' . $dj->back_cover) }}"
                                alt="{{ $dj->dj_name }} - {{ $dj->mix_title }} back cover"
                                loading="lazy"
                            >

                        </div>

                    @endif


                    @if(!empty($dj->description2))
                        {!! $dj->description2 !!}
                    @endif

                </div>

            </div>

        </section>

    @endif

</div>


<input
    type="hidden"
    value="{{ $dj->id }}"
    id="dj_id"
>



<!-- =========================================================
     SHARE SONG
========================================================= -->

<div class="ts-share-section">

    <div class="ts-share-heading">
        <span class="ts-share-heading-line"></span>

        <span class="ts-share-heading-text">
            SHARE
        </span>

        <span class="ts-share-heading-line"></span>
    </div>


    <p class="ts-share-description">
        Share “{{ $dj->dj_name }} - {{ $dj->mix_title }}” with your friends
    </p>


    <div class="ts-share-actions">

        {{-- COPY LINK --}}
        <button
            type="button"
            class="ts-share-copy"
            id="tsCopyShareButton"
            aria-label="Copy song link"
        >
            <span class="ts-copy-icon">
                <i class="fa fa-link" aria-hidden="true"></i>
            </span>

            <span
                class="ts-copy-label"
                id="tsCopyLabel"
            >
                Copy link
            </span>
        </button>


        {{-- ADDTOANY --}}
        <div class="a2a_kit ts-share-socials">

            <a class="a2a_button_whatsapp" title="Share on WhatsApp" aria-label="Share on WhatsApp" ></a>

            <a class="a2a_button_facebook"  title="Share on Facebook" aria-label="Share on Facebook" ></a>

            <a class="a2a_button_x" title="Share on X" aria-label="Share on X" ></a>

            <a class="a2a_button_email" title="Share by Email" aria-label="Share by Email" ></a>

        </div>

    </div>

</div>
<script async src="https://static.addtoany.com/menu/page.js"></script>

<!-- End of ADD TO Any-->


{{-- =========================================================
     OTHER MIXES BY SAME DJ
========================================================= --}}
@if($djmix && $djmix->count())

<section class="ts-related-section">

    <div class="ts-related-heading">

        <h2>
            Other Mixes By {{ $dj->dj_name }}
        </h2>

    </div>


    <div class="ts-music-list">

        @foreach($djmix as $row)

            @php

                $djSlug = strtolower(
                    str_replace(
                        ')',
                        '',
                        str_replace(
                            '(',
                            '',
                            str_replace(
                                ' ',
                                '-',
                                $row->dj_name
                            )
                        )
                    )
                );

                $mixSlug = strtolower(
                    str_replace(
                        ')',
                        '',
                        str_replace(
                            '(',
                            '',
                            str_replace(
                                ' ',
                                '-',
                                $row->mix_title
                            )
                        )
                    )
                );

            @endphp


            <article class="ts-music-list-item">

                <a
                    href="{{ route('pages.djmix_detail', [
                        $row->id,
                        $djSlug,
                        $mixSlug
                    ]) }}"
                    class="ts-music-list-link"
                >

                    <div class="ts-music-list-cover">

                        <img
                            src="{{ asset('/images/dj/' . $row->cover_url) }}"
                            alt="{{ $row->dj_name }} - {{ $row->mix_title }}"
                            loading="lazy"
                        >

                    </div>


                    <div class="ts-music-list-info">

                        <h3 class="ts-music-list-artist">
                            {{ $row->dj_name }}
                        </h3>

                        <p class="ts-music-list-title">
                            {{ $row->mix_title }}
                        </p>

                    </div>


                    <div class="ts-music-list-actions">

                        <span class="ts-music-list-action">
                            <i class="fa fa-play"></i>
                        </span>

                        <span class="ts-music-list-action">
                            <i class="fa fa-download"></i>
                        </span>

                    </div>

                </a>

            </article>

        @endforeach

    </div>

</section>

@endif


{{-- =========================================================
     OTHER LATEST MIXES
========================================================= --}}
@if($otherdjmix && $otherdjmix->count())

<section class="ts-related-section">

    <div class="ts-related-heading">

        <h2>
            Download Other Latest Mixes
        </h2>

    </div>


    <div class="ts-music-list">

        @foreach($otherdjmix as $row)

            @php

                $djSlug = strtolower(
                    str_replace(
                        ')',
                        '',
                        str_replace(
                            '(',
                            '',
                            str_replace(
                                ' ',
                                '-',
                                $row->dj_name
                            )
                        )
                    )
                );

                $mixSlug = strtolower(
                    str_replace(
                        ')',
                        '',
                        str_replace(
                            '(',
                            '',
                            str_replace(
                                ' ',
                                '-',
                                $row->mix_title
                            )
                        )
                    )
                );

            @endphp


            <article class="ts-music-list-item">

                <a
                    href="{{ route('pages.djmix_detail', [
                        $row->id,
                        $djSlug,
                        $mixSlug
                    ]) }}"
                    class="ts-music-list-link"
                >

                    <div class="ts-music-list-cover">

                        <img
                            src="{{ asset('/images/dj/' . $row->cover_url) }}"
                            alt="{{ $row->dj_name }} - {{ $row->mix_title }}"
                            loading="lazy"
                        >

                    </div>


                    <div class="ts-music-list-info">

                        <h3 class="ts-music-list-artist">
                            {{ $row->dj_name }}
                        </h3>

                        <p class="ts-music-list-title">
                            {{ $row->mix_title }}
                        </p>

                    </div>


                    <div class="ts-music-list-actions">

                        <span class="ts-music-list-action">
                            <i class="fa fa-play"></i>
                        </span>

                        <span class="ts-music-list-action">
                            <i class="fa fa-download"></i>
                        </span>

                    </div>

                </a>

            </article>

        @endforeach

    </div>

</section>

@endif


    {{-- =========================================================
         COMMENTS SECTION
        ========================================================= --}}
    
    <section class="ts-comments-section" id="comments">
    
        {{-- =====================================================
             COMMENTS HEADER
        ====================================================== --}}
    
        <div class="ts-comments-heading">
    
            <div class="ts-comments-heading-left">
    
                <span class="ts-comments-icon">
                    <i class="fa fa-comments"></i>
                </span>
    
                <div>
                    <h3>Comments</h3>
    
                    <span>
                        {{ $comment_count }}
                        {{ $comment_count == 1 ? 'Comment' : 'Comments' }}
                    </span>
                </div>
    
            </div>
    
        </div>
    
    
        {{-- =====================================================
             EXISTING COMMENTS
        ====================================================== --}}
    
        <div class="ts-comments-list">
    
            @forelse($comment as $comments)
    
                <div class="ts-comment-item">
    
                    <div class="ts-comment-avatar">
                        <i class="fa fa-user"></i>
                    </div>
    
                    <div class="ts-comment-content">
    
                        <div class="ts-comment-top">
                            <strong>
                                {{ $comments->name }}
                            </strong>
                        </div>
    
                        <div class="ts-comment-text">
                            {{ $comments->coments }}
                        </div>
    
                    </div>
    
                </div>
    
            @empty
    
                <div class="ts-no-comments">
    
                    <div class="ts-no-comments-icon">
                        <i class="fa fa-comment-o"></i>
                    </div>
    
                    <strong>No comments yet</strong>
    
                    <span>
                        Be the first to share your thoughts.
                    </span>
    
                </div>
    
            @endforelse
    
        </div>
    
    
        {{-- =====================================================
             REUSABLE COMMENT FORM
        ====================================================== --}}
    
        @include('comments.form', [
            'contentId'   => $dj->id,
            'categoryId'  => 6,
            'contentType' => 'DJ mix'
        ])
    
    </section>


{{-- =========================================================
     AUDIO + SHARE + COUNTERS
========================================================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const audio = document.getElementById('myaudio');
    const playButton = document.getElementById('tsPlayButton');
    const playIcon = document.getElementById('tsPlayIcon');
    const playLabel = document.getElementById('tsPlayLabel');

    const progress = document.getElementById('tsAudioProgress');
    const currentTime = document.getElementById('tsCurrentTime');
    const duration = document.getElementById('tsDuration');

    const downloadLink = document.getElementById('a1');
    const djId = document.getElementById('dj_id')?.value;


    /* =====================================================
       FORMAT TIME
    ===================================================== */

    function formatTime(seconds) {

        if (!Number.isFinite(seconds)) {
            return '0:00';
        }

        const minutes = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);

        return minutes + ':' + String(secs).padStart(2, '0');
    }


    /* =====================================================
       PLAY / PAUSE
    ===================================================== */

    if (audio && playButton) {

        playButton.addEventListener('click', function () {

            if (audio.paused) {

                audio.play();

            } else {

                audio.pause();

            }

        });


        audio.addEventListener('play', function () {

            playIcon.className = 'fa fa-pause';

            playLabel.textContent = 'Pause mix';

            playButton.setAttribute('aria-label', 'Pause mix');

            playButton.setAttribute('aria-pressed', 'true');

        });


        audio.addEventListener('pause', function () {

            playIcon.className = 'fa fa-play';

            playLabel.textContent = 'Listen to this mix';

            playButton.setAttribute('aria-label', 'Play mix');

            playButton.setAttribute('aria-pressed', 'false');

        });


        audio.addEventListener('loadedmetadata', function () {

            duration.textContent = formatTime(audio.duration);

        });


        audio.addEventListener('timeupdate', function () {

            if (!audio.duration) {
                return;
            }

            const percentage =
                (audio.currentTime / audio.duration) * 100;

            progress.value = percentage;

            currentTime.textContent =
                formatTime(audio.currentTime);

            duration.textContent =
                formatTime(audio.duration);

        });


        progress.addEventListener('input', function () {

            if (!audio.duration) {
                return;
            }

            audio.currentTime =
                (progress.value / 100) * audio.duration;

        });


        /*
         * Same repeat behaviour as Music Details:
         * when the mix ends, wait 2 seconds and play again.
         */
        audio.addEventListener('ended', function () {

            progress.value = 0;

            currentTime.textContent = '0:00';

            playIcon.className = 'fa fa-play';

            playLabel.textContent = 'Listen to this mix';

            playButton.setAttribute('aria-label', 'Play mix');

            playButton.setAttribute('aria-pressed', 'false');


            setTimeout(function () {

                audio.currentTime = 0;

                audio.play().catch(function () {});

            }, 2000);

        });

    }

    
</script>



@endsection

