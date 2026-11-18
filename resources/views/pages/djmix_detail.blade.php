@extends('layouts.app')

@php
    $djName = $dj->dj_name ?: 'TrendySongz DJ';
    $mixTitle = $dj->mix_title ?: 'DJ Mixtape';
    $title = $djName . ' - ' . $mixTitle;


    $canonicalUrl = \App\Support\DjMixUrl::detail($dj);

    $description = \Illuminate\Support\Str::limit(
        trim(strip_tags(($dj->description1 ?: $dj->details) ?: $title)),
        160
    );

    $postedDate = $dj->created_at
        ? \Illuminate\Support\Carbon::parse($dj->created_at)
        : null;

    $coverUrl = $dj->cover_url
        ? asset('images/dj/' . ltrim($dj->cover_url, '/'))
        : null;

        $audioFilename = trim((string) ($dj->track_url ?? ''));
    $audioPath = 'media/djmixs/'.$audioFilename;

    $audioUrl = $audioFilename !== ''
        && basename($audioFilename) === $audioFilename
        && ! str_contains($audioFilename, '\\')
        && \Illuminate\Support\Facades\Storage::disk('public')
            ->exists($audioPath)
            ? asset('storage/'.$audioPath)
            : null;    
@endphp

@section('title', 'Download Mix: ' . $title . ' Mix')

@section('meta')
    <meta name="description" content="{{ $description }}">
    <meta name="keywords" content="{{ $title }} mix, {{ $title }}, download {{ $title }}, latest DJ mix">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
@endsection

@section('canonical')
    <link rel="canonical" href="{{ $canonicalUrl }}">
@endsection

@section('facebook_meta')
    <meta property="og:type" content="music.song">
    <meta property="og:site_name" content="TrendySongz">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">

    @if ($coverUrl)
        <meta property="og:image" content="{{ $coverUrl }}">
    @endif
@endsection

@section('twitter_meta')
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="@TrendySongz_">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ $description }}">

    @if ($coverUrl)
        <meta name="twitter:image" content="{{ $coverUrl }}">
    @endif
@endsection

@section('content')
    <article class="ts-music-detail">
        <header class="ts-music-header">
            <h1 class="ts-music-title">
                {{ $title }} | Download DJ Mix
            </h1>

            <div class="ts-music-meta">
                <span>
                    Posted By: {{ $dj->posted_user ?: 'TrendySongz' }}
                    <i class="fa fa-check-circle" aria-hidden="true"></i>
                </span>

                @if ($postedDate)
                    <span class="ts-meta-separator">|</span>

                    <time
                        class="ts-music-date"
                        datetime="{{ now()->toDateString() }}" class="post-date"
                    >
                                        {{ now()->format('M d, Y') }}
                    </time>
                @endif
            </div>
        </header>

        <section class="ts-music-details-card">
            <div class="ts-music-info-bar">
                <div class="ts-info-item">
                    <span class="ts-info-label">DJ</span>

                    <span class="ts-info-value" title="{{ $djName }}">
                        {{ $djName }}
                    </span>
                </div>

                @if ($dj->released_year)
                    <div class="ts-info-item">
                        <span class="ts-info-label">Released</span>

                        <span class="ts-info-value">
                            {{ $dj->released_year }}
                        </span>
                    </div>
                @endif

                <div class="ts-info-item">
                    <span class="ts-info-label">Category</span>

                    <span class="ts-info-value">
                        <a href="{{ route('pages.djmix') }}">DJ Mix</a>
                    </span>
                </div>

                @if (filled($dj->duration ?? null))
                    <div class="ts-info-item">
                        <span class="ts-info-label">Duration</span>

                        <span class="ts-info-value">
                            {{ $dj->duration }}
                        </span>
                    </div>
                @endif
            </div>

            <div class="ts-music-body">
                <div class="ts-music-cover">
                    @include('pages.partials.cover', [
                        'src' => $dj->cover_url,
                        'folder' => 'dj/',
                    ])
                </div>

                <div class="ts-music-content">
                    <h2 class="ts-song-artist">{{ $djName }}</h2>

                    <div class="ts-song-title-row">
                        <h3 class="ts-song-title">{{ $mixTitle }}</h3>
                    </div>

                    <!--<div class="ts-audio-box">
                        @if ($audioUrl)
                            <button
                                type="button"
                                class="ts-main-play"
                                id="ts-djmix-play"
                                aria-label="Play {{ $title }}"
                            >
                                <i class="fa fa-play" aria-hidden="true"></i>
                            </button>

                            <audio
                                id="ts-djmix-audio"
                                src="{{ $audioUrl }}"
                                preload="none"
                            ></audio>
                        @else
                            <button
                                type="button"
                                class="ts-main-play"
                                aria-label="Audio unavailable"
                                disabled
                            >
                                <i class="fa fa-play" aria-hidden="true"></i>
                            </button>
                        @endif

                        <span class="ts-audio-heading">
                            Listen to {{ $title }} Here
                        </span>
                    </div>

                    @if ($audioUrl)
                        <a
                            class="ts-download-button"
                            href="{{ $audioUrl }}"
                            download="{{ $audioFilename }}"
                        >
                            <i class="fa fa-download" aria-hidden="true"></i>
                            Download {{ $title }}
                        </a>
                    @else
                        <button
                            type="button"
                            class="ts-download-button"
                            disabled
                        >
                            <i class="fa fa-download" aria-hidden="true"></i>
                            Download {{ $title }}
                        </button>
                    @endif
                </div>-->
            </div>
        </section>

        @if (filled($dj->description1) || filled($dj->description2))
            <section class="ts-about-song">
                <div class="ts-about-song-inner">
                    <h2 class="ts-about-heading">About This DJ Mix</h2>

                    <div class="ts-about-text">
                        @if (filled($dj->description1))
                            <p>{!! nl2br(e(strip_tags($dj->description1))) !!}</p>
                        @endif

                        @if (filled($dj->description2))
                            <p>{!! nl2br(e(strip_tags($dj->description2))) !!}</p>
                        @endif
                    </div>
                </div>
            </section>
        @endif
        
        @include('pages.partials.share', [
            'shareUrl' => \App\Support\DjMixUrl::detail($dj),
            'shareTitle' => $title,
        ])

        @if ($djOtherMixes->isNotEmpty())
            <section class="ts-djmix-section">
                <header class="ts-section-heading">
                    <h2>Other Mixtapes by {{ $djName }}</h2>
                </header>

                <div class="ts-djmix-list">
                    @foreach ($djOtherMixes as $mix)
                        @php
                            $relatedDjName = $mix->dj_name ?: $djName;
                            $relatedTitle = $mix->mix_title ?: 'DJ Mixtape';

                        @endphp

                        <a
                            class="ts-djmix-card"
                            href="{{ \App\Support\DjMixUrl::detail($mix) }}"
                        >
                            <span class="ts-djmix-cover">
                                @include('pages.partials.cover', [
                                    'src' => $mix->cover_url,
                                    'folder' => 'dj',
                                ])

                                <span class="ts-djmix-play" aria-hidden="true">
                                    <i class="fa fa-play"></i>
                                </span>
                            </span>

                            <span class="ts-djmix-info">
                                <span class="ts-djmix-label">DJ MIXTAPE</span>

                                <span class="ts-djmix-dj">
                                    {{ $relatedDjName }}
                                </span>

                                <span class="ts-djmix-title">
                                    {{ $relatedTitle }}
                                </span>

                                @if (filled($mix->duration ?? null))
                                    <span class="ts-djmix-duration">
                                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                                        {{ $mix->duration }}
                                    </span>
                                @endif

                                <span class="ts-djmix-action">
                                    View mixtape
                                    <i class="fa fa-arrow-right" aria-hidden="true"></i>
                                </span>
                            </span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($otherDjMixes->isNotEmpty())
            <section class="ts-djmix-section">
                <header class="ts-section-heading">
                    <h2>Mixtapes by Other DJs</h2>
                </header>

                <div class="ts-djmix-list">
                    @foreach ($otherDjMixes as $mix)
                        @php
                            $relatedDjName = $mix->dj_name ?: 'TrendySongz DJ';
                            $relatedTitle = $mix->mix_title ?: 'DJ Mixtape';

                        @endphp

                        <a
                            class="ts-djmix-card"
                            href="{{ \App\Support\DjMixUrl::detail($mix) }}"
                        >
                            <span class="ts-djmix-cover">
                                @include('pages.partials.cover', [
                                    'src' => $mix->cover_url,
                                    'folder' => 'dj',
                                ])

                                <span class="ts-djmix-play" aria-hidden="true">
                                    <i class="fa fa-play"></i>
                                </span>
                            </span>

                            <span class="ts-djmix-info">
                                <span class="ts-djmix-label">DJ MIXTAPE</span>

                                <span class="ts-djmix-dj">
                                    {{ $relatedDjName }}
                                </span>

                                <span class="ts-djmix-title">
                                    {{ $relatedTitle }}
                                </span>

                                @if (filled($mix->duration ?? null))
                                    <span class="ts-djmix-duration">
                                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                                        {{ $mix->duration }}
                                    </span>
                                @endif

                                <span class="ts-djmix-action">
                                    View mixtape
                                    <i class="fa fa-arrow-right" aria-hidden="true"></i>
                                </span>
                            </span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </article>
    
    @include('pages.partials.comments', [
        'postType' => 'djmix',
        'postId' => (int) $dj->id,
        'postTitle' => $title,
    ])


    @if ($audioUrl)
        <script>
            const djAudio = document.getElementById('ts-djmix-audio');
            const djPlayButton = document.getElementById('ts-djmix-play');
            const djPlayIcon = djPlayButton.querySelector('i');

            djPlayButton.addEventListener('click', function () {
                if (djAudio.paused) {
                    djAudio.play();
                } else {
                    djAudio.pause();
                }
            });

            djAudio.addEventListener('play', function () {
                djPlayIcon.classList.replace('fa-play', 'fa-pause');
                djPlayButton.setAttribute(
                    'aria-label',
                    'Pause DJ mix'
                );
            });

            function showDjPlayIcon() {
                djPlayIcon.classList.replace('fa-pause', 'fa-play');
                djPlayButton.setAttribute(
                    'aria-label',
                    'Play DJ mix'
                );
            }

            djAudio.addEventListener('pause', showDjPlayIcon);
            djAudio.addEventListener('ended', showDjPlayIcon);
        </script>
    @endif
    
    <script data-cfasync="false" src="//d2f9q8zryik75d.cloudfront.net/?rzqfd=1724803"></script>

@endsection