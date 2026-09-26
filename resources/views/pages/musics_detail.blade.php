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

    $canonicalUrl = route('pages.musics_detail', [
        $artist->id,
        \Illuminate\Support\Str::slug($title),
    ]);

    $coverPath = 'images/' . ltrim((string) $artist->CoverUrl, '/');

    $coverUrl = $artist->CoverUrl && is_file(public_path($coverPath))
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
    <div class="ts-music-detail">

        <header class="ts-music-header">
            <h1 class="ts-music-title">
                {{ $title }} | Download Music MP3
            </h1>

            <div class="ts-music-meta">
                <span>
                    Posted By: {{ $artist->posted_by_name ?: 'TrendySongz' }}
                </span>

                @if ($artist->created_at)
                    <span class="ts-meta-separator">|</span>

                    <time class="ts-music-date"
                          datetime="{{ \Illuminate\Support\Carbon::parse($artist->created_at)->toDateString() }}">
                        {{ \Illuminate\Support\Carbon::parse($artist->created_at)->format('M j, Y') }}
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
                        <span class="ts-info-value">
                            {{ $artist->YearOfRelease }} 
                        </span>
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
                    @include('pages.partials.cover', [
                        'src' => $artist->CoverUrl,
                        'folder' => '',
                    ])
                </div>

                <div class="ts-music-content">
                    <h2 class="ts-song-artist">{{ $artistName }}</h2>

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
                        <button type="button"
                                class="ts-main-play"
                                aria-label="Play {{ $title }}"
                                title="Audio file awaiting restoration"
                                disabled>
                            <i class="fa fa-play" aria-hidden="true"></i>
                        </button>

                        <span class="ts-audio-heading">
                            Listen to {{ $artistName }} - {{ $artist->TrackTitle }} Here
                        </span>
                    </div>

                    <button type="button"
                            class="ts-download-button"
                            title="Download available when the audio file is restored"
                            disabled>
                        <i class="fa fa-download" aria-hidden="true"></i>
                        Download {{ $artist->TrackTitle }} Mp3
                    </button>
                </div>
            </div>
        </section>

        @if ($artist->introduction || $artist->TrackInfo || $artist->trackinfo1)
            <section class="ts-about-song">
                <div class="ts-about-song-inner">
                    <h2 class="ts-about-heading">
                        About Song
                    </h2>

                    <div class="ts-about-text">
                        @foreach ([
                            $artist->introduction,
                            $artist->TrackInfo,
                            $artist->trackinfo1,
                        ] as $paragraph)
                            @if ($paragraph)
                                <p>{{ strip_tags($paragraph) }}</p>
                            @endif
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        @if ($related->isNotEmpty())
            <section class="ts-section">
                <header class="ts-section-heading">
                    <h2>Download {{ $artistName }} Other Songs</h2>
                </header>

                <div class="ts-music-grid">
                    @foreach ($related as $row)
                        @include('pages.partials.music-card', ['row' => $row])
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
                        @include('pages.partials.video-card', ['row' => $row])
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
                        @include('pages.partials.music-card', ['row' => $row])
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
                        @include('pages.partials.music-card', ['row' => $row])
                    @endforeach
                </div>
            </section>
        @endif

     

    </div>
@endsection