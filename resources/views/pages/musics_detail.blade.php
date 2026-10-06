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

    $coverUrl = $artist->CoverUrl
        && is_file(dirname(base_path()).'/'.$coverPath)
            ? asset($coverPath)
            : null;
@endphp

@section('title', $pageTitle)

@section('meta')
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="keywords" content="{{ $keywords }}">
@endsection

@section('canonical')
    <link rel="canonical" href="{{ $canonicalUrl }}">
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
                            ]) }}" style="text-decoration:none";
                        >
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

                    @if ($artist->CoverUrl)
                        <img
                            src="{{ asset('images/' . ltrim($artist->CoverUrl, '/')) }}"
                            alt="{{ $artist->TrackTitle ?? 'Music cover' }}"
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

                    <div class="ts-audio-box">
                        @if ($mediaUrl)
                            <button
                                type="button"
                                class="ts-main-play"
                                id="ts-track-play"
                                aria-label="Play {{ $title }}"
                            >
                                <i class="fa fa-play" aria-hidden="true"></i>
                            </button>

                            <audio
                                id="ts-track-audio"
                                src="{{ $mediaUrl }}"
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
                            Listen to {{ $artistName }} - {{ $artist->TrackTitle }} Here
                        </span>
                    </div>

                   @if ($mediaUrl)
                        <a
                            class="ts-download-button"
                            href="{{ $mediaUrl }}"
                        >
                            <i class="fa fa-download" aria-hidden="true"></i>
                            Download {{ $artistName }} - {{ $artist->TrackTitle }} Mp3
                        </a>
                    @else
                        <button type="button" class="ts-download-button" disabled>
                            <i class="fa fa-download" aria-hidden="true"></i>
                            Download {{ $artistName }} - {{ $artist->TrackTitle }} Mp3
                        </button>
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
                                <p>{{ strip_tags($paragraph) }}</p>
                            @endif
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
        
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
            </section>
        @endif

    </div>
    
    @include('pages.partials.comments', [
        'postType' => 'music',
        'postId' => (int) $artist->id,
        'postTitle' => $title,
    ])

    @if ($mediaUrl)
        <script>
            const audio = document.getElementById('ts-track-audio');
            const playButton = document.getElementById('ts-track-play');
            const playIcon = playButton.querySelector('i');

            playButton.addEventListener('click', function () {
                if (audio.paused) {
                    audio.play();
                } else {
                    audio.pause();
                }
            });

            audio.addEventListener('play', function () {
                playIcon.classList.replace('fa-play', 'fa-pause');
                playButton.setAttribute('aria-label', 'Pause {{ $title }}');
            });

            function showPlayIcon() {
                playIcon.classList.replace('fa-pause', 'fa-play');
                playButton.setAttribute('aria-label', 'Play {{ $title }}');
            }

            audio.addEventListener('pause', showPlayIcon);
            audio.addEventListener('ended', showPlayIcon);
        </script>
    @endif
@endsection