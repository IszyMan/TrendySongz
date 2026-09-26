@extends('layouts.app')

@php
    $videoTitle = trim(
        ($artist->ArtistsName ?: 'Unknown Artist')
        . ' - '
        . $artist->TrackTitle
    );

    $description = trim(strip_tags(
        $artist->TrackInfo ?: ($artist->introduction ?: '')
    ));

    $metaDescription = trim(
        'Download ' . $videoTitle . '. ' . $description
    );

    $canonicalUrl = route('pages.videos_detail', [
        $artist->id,
        \Illuminate\Support\Str::slug($videoTitle),
    ]);

    $coverUrl = $artist->CoverUrl
        ? asset('images/' . ltrim($artist->CoverUrl, '/'))
        : null;
@endphp

@section('title', $videoTitle . ' | Download Video MP4 » Trendysongz')

@section('meta')
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="keywords" content="{{ $videoTitle }}, {{ $videoTitle }} video, download {{ $videoTitle }}, download {{ $videoTitle }} mp4, {{ $videoTitle }} video download">
@endsection

@section('canonical')
    <link rel="canonical" href="{{ $canonicalUrl }}">
@endsection

@section('facebook_meta')
    <meta property="og:type" content="video.other">
    <meta property="og:site_name" content="TrendySongz">
    <meta property="og:title" content="{{ $videoTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">

    @if ($coverUrl)
        <meta property="og:image" content="{{ $coverUrl }}">
    @endif
@endsection

@section('twitter_meta')
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="@Trendysongz">
    <meta name="twitter:title" content="{{ $videoTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">

    @if ($coverUrl)
        <meta name="twitter:image" content="{{ $coverUrl }}">
    @endif
@endsection

@section('content')
    <div class="ts-video-detail">
        <header class="ts-video-detail-header">
            <h1 class="ts-video-detail-title">
                {{ $videoTitle }}

                @if ($artist->Featuring)
                    <span class="ts-video-detail-featuring">
                        feat. {{ $artist->Featuring }}
                    </span>
                @endif
            </h1>

            <div class="ts-video-detail-post-meta">
                <a
                    class="ts-video-detail-posted-by"
                    href="{{ route(
                        'pages.videos_posted_by',
                        \Illuminate\Support\Str::slug($artist->postedby)
                    ) }}"
                >
                    Posted by:
                    <strong>{{ $artist->postedby }}</strong>
                    <i class="fa fa-check-circle" aria-hidden="true"></i>
                </a>

                @if ($artist->created_at)
                    <span class="ts-video-detail-meta-separator">•</span>

                    <time
                        class="ts-video-detail-date"
                        datetime="{{ \Illuminate\Support\Carbon::parse($artist->created_at)->toDateString() }}"
                    >
                        {{ \Illuminate\Support\Carbon::parse($artist->created_at)->format('M d, Y') }}
                    </time>
                @endif
            </div>
        </header>

        <div class="ts-video-detail-info-bar">
            <div class="ts-video-detail-info-item">
                <span class="ts-video-detail-info-label">Director</span>
                <span class="ts-video-detail-info-value">
                    {{ $artist->directedby ?: '—' }}
                </span>
            </div>

            <div class="ts-video-detail-info-item">
                <span class="ts-video-detail-info-label">Released</span>
                <span class="ts-video-detail-info-value">
                    <a href="{{ route('pages.videos_released_year', $artist->YearOfRelease) }}">
                        {{ $artist->YearOfRelease }}
                    </a>
                </span>
            </div>

            <div class="ts-video-detail-info-item">
                <span class="ts-video-detail-info-label">Country</span>
                <span class="ts-video-detail-info-value">
                    {{ $artist->country_id ? ucfirst($artist->country_id) : '—' }}
                </span>
            </div>

            <div class="ts-video-detail-info-item">
                <span class="ts-video-detail-info-label">Category</span>
                <span class="ts-video-detail-info-value">
                    <a href="{{ route('pages.videos_all') }}">
                        {{ (int) $artist->is_video_comedy === 1 ? 'Comedy Video' : 'Music Video' }}
                    </a>
                </span>
            </div>
        </div>

        <section class="ts-video-detail-player-section">
            <div class="ts-video-detail-player">
                @if ($artist->TrackUrl)
                    <video
                        controls
                        preload="none"
                        @if ($coverUrl) poster="{{ $coverUrl }}" @endif
                    >
                        <source
                            src="{{ 'https://cdn.trendysongz.com/video/' . ltrim($artist->TrackUrl, '/') }}"
                            type="video/mp4"
                        >
                        Your browser does not support video playback.
                    </video>
                @elseif ($coverUrl)
                    <img
                        class="ts-detail-cover"
                        src="{{ $coverUrl }}"
                        alt="{{ $videoTitle }}"
                    >
                @endif
            </div>

            @if ($artist->TrackUrl)
                <div class="ts-video-detail-download-wrap">
                    <a
                        class="ts-video-detail-download"
                        href="{{ 'https://cdn.trendysongz.com/video/' . ltrim($artist->TrackUrl, '/') }}"
                        download
                    >
                        <i class="fa fa-download" aria-hidden="true"></i>
                        Download {{ $artist->TrackTitle }} MP4
                    </a>
                </div>
            @else
                <p class="ts-video-detail-notice">
                    Video file awaiting restoration.
                </p>
            @endif
        </section>

        @if ($artist->introduction)
            <section class="ts-video-detail-section ts-video-detail-about">
                <h2 class="ts-video-detail-section-heading">
                    About this video
                </h2>

                <div class="ts-video-detail-about-text">
                    {!! $artist->introduction !!}
                </div>
            </section>
        @endif

        @if ($artist->TrackInfo || $artist->trackinfo1)
            <section class="ts-video-detail-section ts-video-detail-information">
                <h2 class="ts-video-detail-section-heading">
                    Video information
                </h2>

                @if ($artist->TrackInfo)
                    <div class="ts-video-detail-info-text">
                        {!! $artist->TrackInfo !!}
                    </div>
                @endif

                @if ($artist->trackinfo1)
                    <div class="ts-video-detail-info-text">
                        {!! $artist->trackinfo1 !!}
                    </div>
                @endif
            </section>
        @endif

        @if ($additional_link)
            <section class="ts-video-detail-audio-link">
                <span>Also available:</span>

                <a
                    href="{{ route('pages.musics_detail', [
                        $additional_link->id,
                        \Illuminate\Support\Str::slug(
                            trim(
                                ($additional_link->ArtistsName ?? '')
                                . ' '
                                . ($additional_link->TrackTitle ?? '')
                            )
                        ),
                    ]) }}"
                >
                    Download audio:
                    {{ $additional_link->ArtistsName }}
                    -
                    {{ $additional_link->TrackTitle }}
                    MP3
                </a>
            </section>
        @endif

        @if ($video->isNotEmpty())
            <section class="ts-video-detail-section ts-video-detail-related">
                <h2 class="ts-video-detail-section-heading">
                    More {{ $artist->ArtistsName }} Videos
                </h2>

                <div class="ts-video-detail-related-list">
                    @foreach ($video as $row)
                        <a
                            class="ts-video-detail-related-item"
                            href="{{ route('pages.videos_detail', [
                                $row->id,
                                \Illuminate\Support\Str::slug(
                                    trim(
                                        ($row->ArtistsName ?? '')
                                        . ' '
                                        . ($row->TrackTitle ?? '')
                                    )
                                ),
                            ]) }}"
                        >
                            <div class="ts-video-detail-related-cover">
                                @include('pages.partials.cover', [
                                    'src' => $row->CoverUrl,
                                    'folder' => '',
                                ])

                                <span class="ts-video-detail-related-play">
                                    <i class="fa fa-play" aria-hidden="true"></i>
                                </span>
                            </div>

                            <div class="ts-video-detail-related-info">
                                <h3>{{ $row->ArtistsName }}</h3>
                                <p>{{ $row->TrackTitle }}</p>

                                @if ($row->directedby)
                                    <span>Directed by {{ $row->directedby }}</span>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($audio->isNotEmpty())
            <section class="ts-related-section ts-video-detail-related">
                <h2 class="ts-video-detail-section-heading">
                    Download {{ $artist->ArtistsName }} Other Songs
                </h2>

                <div class="ts-music-list">
                    @foreach ($audio as $row)
                        <article class="ts-music-list-item">
                        <a
                            class="ts-music-list-link"
                            href="{{ route('pages.musics_detail', [
                                $row->id,
                                \Illuminate\Support\Str::slug(
                                    trim(($row->ArtistsName ?? '') . ' ' . ($row->TrackTitle ?? ''))
                                ),
                            ]) }}"
                        >
                                <div class="ts-music-list-cover">
                                    @include('pages.partials.cover', [
                                        'src' => $row->CoverUrl,
                                        'folder' => '',
                                    ])
                                </div>

                                <div class="ts-music-list-info">
                                    <h3 class="ts-music-list-artist">
                                        {{ $row->ArtistsName }}
                                    </h3>

                                    <p class="ts-music-list-title">
                                        {{ $row->TrackTitle }}
                                    </p>

                                    @if ($row->Featuring)
                                        <span class="ts-music-list-featuring">
                                            feat. {{ $row->Featuring }}
                                        </span>
                                    @endif

                                    @if ($row->producedby)
                                        <p class="ts-music-list-producer">
                                            Produced by {{ $row->producedby }}
                                        </p>
                                    @endif
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($music->isNotEmpty())
            <section class="ts-related-section ts-video-detail-related">
                <h2 class="ts-video-detail-section-heading">
                    Download Latest Music MP3 & Videos
                </h2>

                <div class="ts-music-list">
                    @foreach ($music as $row)
                        @php
                            $isVideo = $row->ListingType === 'video';

                            $itemUrl = $isVideo
                                ? route('pages.videos_detail', [
                                    $row->id,
                                    \Illuminate\Support\Str::slug(
                                        trim(
                                            ($row->ArtistsName ?? '')
                                            . ' '
                                            . ($row->TrackTitle ?? '')
                                        )
                                    ),
                                ])
                                : route('pages.musics_detail', [
                                    $row->id,
                                    \Illuminate\Support\Str::slug(
                                        trim(
                                            ($row->ArtistsName ?? '')
                                            . ' '
                                            . ($row->TrackTitle ?? '')
                                        )
                                    ),
                                ]);
                        @endphp

                        <article class="ts-music-list-item">
                            <a class="ts-music-list-link" href="{{ $itemUrl }}">
                                <div class="ts-music-list-cover">
                                    @include('pages.partials.cover', [
                                        'src' => $row->CoverUrl,
                                        'folder' => '',
                                    ])
                                </div>

                                <div class="ts-music-list-info">
                                    <h3 class="ts-music-list-artist">
                                        {{ $row->ArtistsName }}
                                    </h3>

                                    <p class="ts-music-list-title">
                                        {{ $row->TrackTitle }}
                                    </p>

                                    @if ($row->Featuring)
                                        <span class="ts-music-list-featuring">
                                            feat. {{ $row->Featuring }}
                                        </span>
                                    @endif

                                    @if ($row->directedby)
                                        <p class="ts-music-list-producer">
                                            Directed by {{ $row->directedby }}
                                        </p>
                                    @elseif ($row->producedby)
                                        <p class="ts-music-list-producer">
                                            Produced by {{ $row->producedby }}
                                        </p>
                                    @endif
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

    </div>
@endsection