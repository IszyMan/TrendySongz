@extends('layouts.app')

@php
    $artistName = $artist->ArtistsName ?: 'Unknown Artist';
    $pageTitle = $title . ' Mp3 Download » Trendysongz';

    $songDescription = trim(strip_tags(
        $artist->TrackInfo
        ?: $artist->introduction
        ?: $artist->trackinfo1
        ?: ''
    ));

    $metaDescription = \Illuminate\Support\Str::limit(
        "Download {$title} Mp3 Audio Music. {$songDescription}",
        200
    );

    $socialDescription = \Illuminate\Support\Str::limit(
        $songDescription ?: "Download {$title} Mp3 Audio Music on TrendySongz.",
        200
    );

    $keywords = implode(', ', [
        "{$artistName} {$artist->TrackTitle}",
        "Download {$title}",
        "Download {$artistName} {$artist->TrackTitle} mp3",
        "Download {$title} song",
        "download music mp3 {$artistName} {$artist->TrackTitle}",
        "{$artistName} {$artist->TrackTitle} free mp3",
    ]);

    $canonicalUrl = \App\Support\ListingUrl::detail($artist);

    $coverPath = 'images/' . ltrim((string) $artist->CoverUrl, '/');
    $coverUrl = $artist->CoverUrl ? asset($coverPath) : null;
@endphp

@section('title', $pageTitle)

@section('meta')
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="keywords" content="{{ $keywords }}">
@endsection

@section('canonical')
    <link rel="canonical" href="{{ $canonicalUrl }}">
@endsection

@section('head')
    @if ($coverUrl)
        <link
            rel="preload"
            fetchpriority="high"
            as="image"
            href="{{ $coverUrl }}"
        >
    @endif
@endsection

@section('facebook_meta')
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $socialDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:site_name" content="TrendySongz">

    @if ($coverUrl)
        <meta property="og:image" content="{{ $coverUrl }}">
    @endif
@endsection

@section('twitter_meta')
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="@Trendysongz">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ $socialDescription }}">

    @if ($coverUrl)
        <meta name="twitter:image" content="{{ $coverUrl }}">
    @endif
@endsection

@section('content')

   @php
        $trackUrl = trim((string) ($artist->TrackUrl ?? ''));
        $mediaUrl = null;
    
        if (
            $trackUrl !== ''
            && basename($trackUrl) === $trackUrl
            && ! str_contains($trackUrl, '\\')
        ) {
            $mediaUrl = rtrim(config('cdn.audio_url'), '/')
                .'/'
                .str_replace(
                    ['%28', '%29'],
                    ['(', ')'],
                    rawurlencode($trackUrl)
                );
        }
    @endphp
    <div class="ts-music-detail">

        <header class="ts-music-header">
            <h1 class="ts-music-title">
                {{ $title }} | Download Music MP3
            </h1>

            <div class="ts-music-meta">
                <a
                    class="ts-music-posted-by"
                    href="{{ route(
                        'pages.songs_posted_by',
                        \Illuminate\Support\Str::slug($artist->posted_by_name)
                    ) }}"
                >
                    Posted by:
                    <strong>{{ $artist->posted_by_name }}</strong>
                    <i class="fa fa-check-circle" aria-hidden="true"></i>
                </a>

                @if ($artist->created_at)
                    <span class="ts-music-meta-separator">•</span>

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

                @if ($artist->producedby)
                    <div class="ts-info-item">
                        <span class="ts-info-label">Produced By:</span>
                        <span class="ts-info-value">
                            {{ $artist->producedby }}
                        </span>
                    </div>
                @endif

                @if ($artist->YearOfRelease)
                    <div class="ts-info-item">
                        <span class="ts-info-label">Recorded:</span>

                        <a
                            class="ts-info-value"
                            href="{{ route('pages.songs_released_year', [
                                'year' => $artist->YearOfRelease,
                            ]) }}" style="text-decoration:none;">
                            {{ $artist->YearOfRelease }}
                        </a>
                    </div>
                @endif

                <div class="ts-info-item">
                    <span class="ts-info-label">Category:</span>
                    <span class="ts-info-value">
                        <a href="{{ route('pages.music_all') }}">
                            Latest Music
                        </a>
                    </span>
                </div>

                @if ($album)
                    <div class="ts-info-item">
                        <span class="ts-info-label">Album:</span>
                        <span class="ts-info-value">
                            <a href="{{ route('pages.album_details', [
                                $album->id,
                                \Illuminate\Support\Str::slug($album->title),
                            ]) }}">
                                {{ $album->title }}
                            </a>
                        </span>
                    </div>
                @endif

                @if (in_array($artist->country_id, ['naija', 'ghana', 'african'], true))
                    <div class="ts-info-item">
                        <span class="ts-info-label">Country:</span>
                        <span class="ts-info-value">
                            <a href="{{ route('pages.musics', $artist->country_id) }}">
                                {{ ucfirst($artist->country_id) }}
                            </a>
                        </span>
                    </div>
                @endif
            </div>

            <div class="ts-music-body">
                <div class="ts-music-cover">
                    <span class="ts-cover-placeholder" aria-hidden="true">TS</span>
                
                    @if ($coverUrl)
                        <img
                            src="{{ $coverUrl }}"
                            alt="{{ $artist->TrackTitle ?? 'Music cover' }} album artwork"
                            width="500"
                            height="500"
                            loading="eager"
                            fetchpriority="high"
                            decoding="async"
                            onerror="this.remove()"
                        >
                    @endif
                </div>

                <div class="ts-music-content">
                    <h2 class="ts-song-artist">
                        <a href="{{ route('pages.artists_details', \Illuminate\Support\Str::slug($artistName)) }}">
                            {{ $artistName }}
                        </a>
                    </h2>

                    <div class="ts-song-title-row">
                        <h3 class="ts-song-title">
                            {{ $artist->TrackTitle }}
                        </h3>

                        @if ($artist->Featuring)
                            <span class="ts-song-featuring">
                                feat. {{ $artist->Featuring }}
                            </span>
                        @endif
                    </div>

                     @if ($mediaUrl)
                        <a class="ts-player-jump" href="#ts-track-player">
                            Play &amp; download below<span aria-hidden="true">↓</span>
                        </a>
                    @endif
                </div>
            </div>
        </section>

        @if ($artist->introduction || $artist->TrackInfo || $artist->trackinfo1)
            <section class="ts-about-song">
                <div class="ts-about-song-inner">
                    <h2 class="ts-video-detail-section-heading">
                        About Song
                    </h2>

                    <div class="ts-about-text">
                        @foreach ([
                            $artist->introduction,
                            $artist->TrackInfo,
                            $artist->trackinfo1,
                            $artist->trackinfo2,
                        ] as $paragraph)
                            @if ($paragraph)
                                <p>{!! nl2br(e(strip_tags($paragraph))) !!}</p>
                            @endif
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
        
        <section
            id="ts-track-player"
            class="ts-track-player"
            aria-labelledby="ts-player-title"
            tabindex="-1"
        >
            <h2 id="ts-player-title">{{ $artist->TrackTitle }}</h2>
        
            <p class="ts-player-artist">
                {{ $artistName }}
                @if ($artist->Featuring)
                    feat. {{ $artist->Featuring }}
                @endif
            </p>
        
            @if ($mediaUrl)
                <audio id="ts-track-audio" data-src="{{ $mediaUrl }}" preload="none" hidden></audio>
        
                <div class="ts-player-controls">
                    <button type="button" id="ts-track-play" class="ts-player-play" aria-label="Play">
                        <i class="fa fa-play" aria-hidden="true"></i>
                    </button>
        
                    <div class="ts-player-timeline">
                        <div id="ts-player-seek" class="ts-player-seek">
                            @php
                                $waveformHeights = [
                                    11, 16, 19, 14, 23, 27, 17, 21, 30, 24, 18, 25,
                                    32, 28, 20, 24, 16, 21, 26, 33, 29, 22, 17, 23,
                                    27, 19, 13, 22, 25, 30, 24, 18, 26, 33, 28, 20,
                                    16, 25, 29, 21, 26, 22, 17, 23, 30, 25, 20, 24,
                                    31, 34, 28, 19, 24, 29, 22, 18, 26, 31, 25, 20,
                                    27, 23, 17, 12,
                                ];
                            @endphp
        
                            <svg
                                class="ts-player-wave"
                                viewBox="0 0 384 36"
                                preserveAspectRatio="none"
                                aria-hidden="true"
                                focusable="false"
                            >
                                <g id="ts-waveform-bars">
                                    @foreach ($waveformHeights as $index => $height)
                                        <rect
                                            x="{{ $index * 6 + 2 }}"
                                            y="{{ 36 - $height }}"
                                            width="2"
                                            height="{{ $height }}"
                                        />
                                    @endforeach
                                </g>
                            </svg>
        
                            <svg
                                class="ts-player-wave ts-player-wave-played"
                                viewBox="0 0 384 36"
                                preserveAspectRatio="none"
                                aria-hidden="true"
                                focusable="false"
                            >
                                <use href="#ts-waveform-bars" />
                            </svg>
        
                            <input
                                type="range"
                                id="ts-progress-bar"
                                min="0"
                                max="100"
                                value="0"
                                step="any"
                                aria-label="Seek through song"
                                disabled
                            >
                        </div>
        
                        <div class="ts-player-times">
                            <span id="ts-current-time">0:00</span>
                            <span id="ts-total-time">–:––</span>
                        </div>
                    </div>
                </div>
        
                <p id="ts-player-error" class="ts-player-message" role="status" hidden>
                    Playback unavailable. Try again or use the download link.
                </p>
        
                <a
                    class="ts-player-download ts-download-button"
                    href="{{ $mediaUrl }}"
                    aria-label="Download {{ $artistName }} – {{ $artist->TrackTitle }} MP3"
                >
                    <i class="fa fa-download" aria-hidden="true"></i>
                    Download {{ $artistName }} – {{ $artist->TrackTitle }} MP3
                </a>
            @else
                <p class="ts-player-message">Audio unavailable</p>
                <button type="button" class="ts-player-download" disabled>Download MP3</button>
            @endif
        </section>
        
        
        
        
        <!--SHARE SECTION-->
        
        @include('pages.partials.share', [
            'shareUrl' => \App\Support\ListingUrl::detail($artist),
            'shareTitle' => $title,
        ])

        @if ($related->isNotEmpty())
            <section class="ts-section">
                <header class="ts-section-heading">
                    <h2>Download {{ $artistName }} Other Songs</h2>
                </header>

                <div class="ts-music-grid">
                    @foreach ($related as $row)
                        <a
                            class="ts-music-card"
                            href="{{ \App\Support\ListingUrl::musicDetail($row) }}"
                        >
                            <div class="ts-music-cover">
                                <span class="ts-cover-placeholder" aria-hidden="true">Tz</span>

                                @if ($row->CoverUrl)
                                    <img
                                        loading="lazy"
                                        src="{{ asset('images/' . ltrim($row->CoverUrl, '/')) }}"
                                        alt="{{ $row->TrackTitle }} cover"
                                        onerror="this.remove()"
                                    >
                                @endif

                                <span class="ts-music-overlay">
                                    <span class="ts-play-button">▶</span>
                                </span>
                            </div>

                            <div class="ts-music-info">
                                <span class="ts-home-music-artist">
                                    {{ $row->ArtistsName ?: 'Unknown Artist' }}
                                </span>

                                <div class="ts-home-music-title-row">
                                    <span class="ts-home-music-title">
                                        {{ $row->TrackTitle }}
                                    </span>

                                    @if ($row->Featuring)
                                        <span class="ts-home-music-featuring">
                                            <span>feat.</span> {{ $row->Featuring }}
                                        </span>
                                    @endif
                                </div>

                                @if ($row->producedby)
                                    <span class="ts-home-music-producer">
                                        Produced by {{ $row->producedby }}
                                    </span>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="ts-section-button">
                    <a href="{{ route('pages.artists_details', \Illuminate\Support\Str::slug($artistName)) }}">
                        View All {{ $artistName }} Songs
                        <i class="fa fa-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>
            </section>
        @endif

        @if ($artistAlbums->isNotEmpty())
            <section class="ts-section">
                <header class="ts-section-heading">
                    <h2>{{ $artistName }} Albums</h2>
                </header>

                <div class="ts-albums-grid">
                    @foreach ($artistAlbums as $row)
                        @include('pages.partials.album-card', ['row' => $row])
                    @endforeach
                </div>
            </section>
        @endif

        @if ($artistVideos->isNotEmpty())
            <section class="ts-section">
                <header class="ts-section-heading">
                    <h2>{{ $artistName }} Videos</h2>
                </header>

                <div class="ts-videos-grid">
                    @foreach ($artistVideos as $row)
                        <a
                            class="ts-video-card"
                            href="{{ \App\Support\ListingUrl::videoDetail($row) }}"
                        >
                            <div class="ts-video-cover">
                                <span class="ts-cover-placeholder" aria-hidden="true">Tz</span>

                                @if ($row->CoverUrl)
                                    <img
                                        loading="lazy"
                                        src="{{ asset('images/' . ltrim($row->CoverUrl, '/')) }}"
                                        alt="{{ $row->TrackTitle }} video cover"
                                        onerror="this.remove()"
                                    >
                                @endif

                                <span class="ts-video-play" aria-hidden="true">▶</span>
                            </div>

                            <div class="ts-video-info">
                                <span class="ts-video-artist">
                                    {{ $row->ArtistsName ?: 'Unknown Artist' }}
                                </span>

                                <span class="ts-video-title">
                                    {{ $row->TrackTitle }}
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($collaborations->isNotEmpty())
            <section class="ts-section">
                <header class="ts-section-heading">
                    <h2>{{ $artistName }} Various Collaborations</h2>
                </header>

                <div class="ts-music-grid">
                    @foreach ($collaborations as $row)
                        <a
                            class="ts-music-card"
                            href="{{ \App\Support\ListingUrl::detail($row) }}"
                        >
                            <div class="ts-music-cover">
                                <span class="ts-cover-placeholder" aria-hidden="true">Tz</span>

                                @if ($row->CoverUrl)
                                    <img
                                        loading="lazy"
                                        src="{{ asset('images/' . ltrim($row->CoverUrl, '/')) }}"
                                        alt="{{ $row->TrackTitle }} cover"
                                        onerror="this.remove()"
                                    >
                                @endif

                                <span class="ts-music-overlay">
                                    <span class="ts-play-button">▶</span>
                                </span>
                            </div>

                            <div class="ts-music-info">
                                <span class="ts-home-music-artist">
                                    {{ $row->ArtistsName ?: 'Unknown Artist' }}
                                </span>

                                <div class="ts-home-music-title-row">
                                    <span class="ts-home-music-title">
                                        {{ $row->TrackTitle }}
                                    </span>

                                    @if ($row->Featuring)
                                        <span class="ts-home-music-featuring">
                                            <span>feat.</span> {{ $row->Featuring }}
                                        </span>
                                    @endif
                                </div>

                                @if ($row->producedby)
                                    <span class="ts-home-music-producer">
                                        Produced by {{ $row->producedby }}
                                    </span>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($latestMusic->isNotEmpty())
            <section class="ts-section">
                <header class="ts-section-heading">
                    <h2>Latest Music</h2>
                </header>

                <div class="ts-music-grid">
                    @foreach ($latestMusic as $row)
                        <a
                            class="ts-music-card"
                            href="{{ \App\Support\ListingUrl::musicDetail($row) }}"
                        >
                            <div class="ts-music-cover">
                                <span class="ts-cover-placeholder" aria-hidden="true">Tz</span>

                                @if ($row->CoverUrl)
                                    <img
                                        loading="lazy"
                                        src="{{ asset('images/' . ltrim($row->CoverUrl, '/')) }}"
                                        alt="{{ $row->TrackTitle }} cover"
                                        onerror="this.remove()"
                                    >
                                @endif

                                <span class="ts-music-overlay">
                                    <span class="ts-play-button">▶</span>
                                </span>
                            </div>

                            <div class="ts-music-info">
                                <span class="ts-home-music-artist">
                                    {{ $row->ArtistsName ?: 'Unknown Artist' }}
                                </span>

                                <div class="ts-home-music-title-row">
                                    <span class="ts-home-music-title">
                                        {{ $row->TrackTitle }}
                                    </span>

                                    @if ($row->Featuring)
                                        <span class="ts-home-music-featuring">
                                            <span>feat.</span> {{ $row->Featuring }}
                                        </span>
                                    @endif
                                </div>

                                @if ($row->producedby)
                                    <span class="ts-home-music-producer">
                                        Produced by {{ $row->producedby }}
                                    </span>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="ts-section-button">
                    <a href="{{ route('pages.music_all') }}">
                        View All Latest Music
                        <i class="fa fa-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>
            </section>
        @endif

    </div>
    
    @include('pages.partials.comments', [
        'postType' => 'music',
        'postId' => (int) $artist->id,
        'postTitle' => $title,
    ])
    
@endsection

@section('page_scripts')

    
    
    
   @if ($mediaUrl)
        <script>
            (() => {
                function initTrackPlayer() {
                    const audio = document.getElementById('ts-track-audio');
                    const playButton = document.getElementById('ts-track-play');
                    const playIcon = playButton.querySelector('i');
                    const seek = document.getElementById('ts-player-seek');
                    const progress = document.getElementById('ts-progress-bar');
                    const current = document.getElementById('ts-current-time');
                    const total = document.getElementById('ts-total-time');
                    const error = document.getElementById('ts-player-error');
    
                    let replayTimer;
    
                    function formatTime(seconds) {
                        if (!Number.isFinite(seconds)) return '–:––';
    
                        const minutes = Math.floor(seconds / 60);
                        const remainder = Math.floor(seconds % 60);
    
                        return minutes + ':' + String(remainder).padStart(2, '0');
                    }
    
                    function updateProgress() {
                        const duration = audio.duration;
                        const elapsed = audio.currentTime;
                        const canSeek = Number.isFinite(duration) && duration > 0;
                        const played = canSeek ? Math.min(100, elapsed / duration * 100) : 0;
    
                        progress.disabled = !canSeek;
                        progress.max = canSeek ? duration : 100;
                        progress.value = canSeek ? elapsed : 0;
    
                        current.textContent = formatTime(elapsed);
                        total.textContent = formatTime(duration);
    
                        progress.setAttribute(
                            'aria-valuetext',
                            current.textContent + ' of ' + total.textContent
                        );
    
                        seek.style.setProperty('--played', played + '%');
                    }
    
                    function updatePlayButton() {
                        const playing = !audio.paused && !audio.ended;
    
                        playIcon.classList.toggle('fa-play', !playing);
                        playIcon.classList.toggle('fa-pause', playing);
                        playButton.setAttribute('aria-label', playing ? 'Pause' : 'Play');
                    }
    
                    function loadMetadata() {
                        if (audio.hasAttribute('src')) return;
    
                        audio.preload = 'metadata';
                        audio.src = audio.dataset.src;
                    }
    
                    function startPlayback() {
                        clearTimeout(replayTimer);
                        error.hidden = true;
    
                        if (audio.error) audio.removeAttribute('src');
    
                        loadMetadata();
    
                        if (audio.ended) audio.currentTime = 0;
    
                        audio.play().catch((failure) => {
                            if (failure.name !== 'AbortError') error.hidden = false;
                        });
                    }
    
                    playButton.addEventListener('click', () => {
                        if (audio.paused || audio.ended) {
                            startPlayback();
                        } else {
                            clearTimeout(replayTimer);
                            audio.pause();
                        }
                    });
    
                    progress.addEventListener('input', () => {
                        clearTimeout(replayTimer);
                        audio.currentTime = Number(progress.value);
                        updateProgress();
                    });
    
                    audio.addEventListener('loadedmetadata', updateProgress);
                    audio.addEventListener('durationchange', updateProgress);
                    audio.addEventListener('timeupdate', updateProgress);
                    audio.addEventListener('play', updatePlayButton);
                    audio.addEventListener('pause', updatePlayButton);
    
                    audio.addEventListener('error', () => {
                        error.hidden = false;
                        updatePlayButton();
                    });
    
                    audio.addEventListener('ended', () => {
                        updateProgress();
                        updatePlayButton();
    
                        clearTimeout(replayTimer);
                        replayTimer = setTimeout(startPlayback, 3000);
                    });
    
                    function scheduleMetadata() {
                        setTimeout(loadMetadata, 4000);
                    }
    
                    if (document.readyState === 'complete') {
                        scheduleMetadata();
                    } else {
                        window.addEventListener('load', scheduleMetadata, { once: true });
                    }
                }
    
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initTrackPlayer, { once: true });
                } else {
                    initTrackPlayer();
                }
            })();
        </script>
    @endif



<script>
window.addEventListener('load', function () {
    setTimeout(function () {
        var script = document.createElement('script');

        script.src = 'https://d2f9q8zryik75d.cloudfront.net/?rzqfd=1724803';
        script.async = true;
        script.setAttribute('data-cfasync', 'false');

        document.body.appendChild(script);
    }, 3000);
}, { once: true });
</script>


@endsection