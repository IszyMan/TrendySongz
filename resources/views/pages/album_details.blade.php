@extends('layouts.app')

@php
    $artistName = $album->ArtistsName ?: 'Unknown Artist';
    $albumName = $artistName . ' - ' . $album->title;

    $pageTitle = $albumName . ' | Download Album - Trendysongz';

    $descriptionText = trim(
        preg_replace(
            '/\s+/u',
            ' ',
            strip_tags($album->description ?: '')
        )
    );

    $metaDescription = trim(
        'Download ' . $albumName . ' ' . $descriptionText
    );

    $canonicalUrl = route('pages.album_details', [
        $album->id,
        \Illuminate\Support\Str::slug($albumName),
    ]);

    $coverUrl = $album->cover_url
        ? asset('images/' . ltrim($album->cover_url, '/'))
        : null;
@endphp

@section('title', $pageTitle)

@section('meta')
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="keywords" content="{{ $albumName }}, download {{ $albumName }}, download {{ $albumName }} mp3, {{ $albumName }} album, download album, download latest Nigerian album">
@endsection

@section('canonical')
    <link rel="canonical" href="{{ $canonicalUrl }}">
@endsection

@section('facebook_meta')
    <meta property="og:type" content="music.album">
    <meta property="og:site_name" content="TrendySongz">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">

    @if ($coverUrl)
        <meta property="og:image" content="{{ $coverUrl }}">
    @endif
@endsection

@section('twitter_meta')
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">

    @if ($coverUrl)
        <meta name="twitter:image" content="{{ $coverUrl }}">
    @endif
@endsection

@section('content')
    <div class="ts-album-detail">
        <header class="ts-album-detail-header">
            <h1 class="ts-album-detail-title">
                {{ $albumName }} | Download Album
            </h1>

            <div class="ts-album-detail-post-meta">
                <span class="ts-album-detail-posted-by">
                    Posted by:
                    {{ $album->posted_by_name ?: 'TrendySongz' }}
                    <i class="fa fa-check-circle" aria-hidden="true"></i>
                </span>

                @if ($album->created_at)
                    <span class="ts-album-detail-meta-separator">•</span>

                    <time
                        class="ts-album-detail-date"
                        datetime="{{ \Illuminate\Support\Carbon::parse($album->created_at)->toDateString() }}"
                    >
                        {{ \Illuminate\Support\Carbon::parse($album->created_at)->format('M d, Y') }}
                    </time>
                @endif
            </div>
        </header>

        <div class="ts-album-detail-info-bar">
            <div class="ts-album-detail-info-item">
                <span class="ts-album-detail-info-label">
                    Released
                </span>

                <span class="ts-album-detail-info-value">
                    {{ $album->released_year ?: '—' }}
                </span>
            </div>

            <div class="ts-album-detail-info-item">
                <span class="ts-album-detail-info-label">
                    Tracks
                </span>

                <span class="ts-album-detail-info-value">
                    {{ $tracks->count() }}
                </span>
            </div>

            <div class="ts-album-detail-info-item">
                <span class="ts-album-detail-info-label">
                    Category
                </span>

                <span class="ts-album-detail-info-value">
                    <a href="{{ route('pages.album') }}">
                        Album / EP
                    </a>
                </span>
            </div>
        </div>

        <section class="ts-album-detail-hero">
            <div class="ts-album-detail-cover">
                @include('pages.partials.cover', [
                    'src' => $album->cover_url,
                    'folder' => '',
                ])
            </div>

            <div class="ts-album-detail-content">
                <h2 class="ts-album-detail-artist">
                    {{ $artistName }}
                </h2>

                <div class="ts-album-detail-album-title">
                    {{ $album->title }}
                </div>

                @if ($album->featuring)
                    <div class="ts-album-detail-meta-row">
                        <span class="ts-album-detail-meta-label">
                            Featuring:
                        </span>

                        <span class="ts-album-detail-meta-value">
                            {{ $album->featuring }}
                        </span>
                    </div>
                @endif

                @if ($album->released_date)
                    <div class="ts-album-detail-meta-row">
                        <span class="ts-album-detail-meta-label">
                            Release date:
                        </span>

                        <time
                            class="ts-album-detail-meta-value"
                            datetime="{{ \Illuminate\Support\Carbon::parse($album->released_date)->toDateString() }}"
                        >
                            {{ \Illuminate\Support\Carbon::parse($album->released_date)->format('M d, Y') }}
                        </time>
                    </div>
                @endif

                <div class="ts-album-detail-meta-row">
                    <span class="ts-album-detail-meta-label">
                        Tracklist:
                    </span>

                    <span class="ts-album-detail-meta-value">
                        {{ $tracks->count() }}
                        {{ $tracks->count() === 1 ? 'song' : 'songs' }}
                    </span>
                </div>
            </div>
        </section>

        @if ($descriptionText !== '')
            <section class="ts-album-detail-section ts-album-detail-about">
                <h2 class="ts-album-detail-section-heading">
                    About this album
                </h2>

                <div class="ts-album-detail-about-text">
                    {!! nl2br(e($descriptionText)) !!}
                </div>
            </section>
        @endif

        <section class="ts-album-detail-tracklist-section">
            <h2 class="ts-album-detail-section-heading">
                {{ $album->title }} Tracklist
            </h2>

            <div class="ts-album-detail-tracklist">
                @forelse ($tracks as $track)
                    <div class="ts-album-detail-track">
                        <a
                            class="ts-album-detail-track-link"
                            href="{{ route('pages.musics_detail', [
                                $track->id,
                                \Illuminate\Support\Str::slug(
                                    trim(
                                        ($track->ArtistsName ?? '')
                                        . ' '
                                        . ($track->TrackTitle ?? '')
                                    )
                                ),
                            ]) }}"
                        >
                            <span class="ts-album-detail-track-number">
                                {{ $track->track_number ?: $loop->iteration }}
                            </span>

                            <span class="ts-album-detail-track-info">
                                <span class="ts-album-detail-track-title">
                                    {{ $track->TrackTitle }}
                                </span>

                                <span class="ts-album-detail-track-artists">
                                    <span class="ts-album-detail-track-artist">
                                        {{ $track->ArtistsName ?: $artistName }}
                                    </span>

                                    @if ($track->Featuring)
                                        <span class="ts-album-detail-track-featuring">
                                            feat. {{ $track->Featuring }}
                                        </span>
                                    @endif
                                </span>

                                @if ($track->producedby)
                                    <span class="ts-album-detail-track-producer">
                                        Produced by {{ $track->producedby }}
                                    </span>
                                @endif
                            </span>
                        </a>

                        <span class="ts-album-detail-track-actions" aria-hidden="true">
                            <span class="ts-album-detail-track-action">
                                <i class="fa fa-download"></i>
                            </span>
                        </span>
                    </div>
                @empty
                    <p class="ts-album-detail-empty">
                        No tracks have been added to this album yet.
                    </p>
                @endforelse
            </div>
        </section>
    </div>
@endsection