@extends('layouts.app')

@php
    $artistName = $artist->ArtistsName
        ?: $artist->Stage_Name
        ?: 'Artist';

    $artistSlug = \Illuminate\Support\Str::slug($artistName);

    $canonicalUrl = route('pages.artists_details', $artistSlug);

    $pageTitle = 'Download Latest ' . $artistName
        . ' Songs, Music, Albums, Biography, Profile, All Music, Videos - TrendySongz';

    $metaDescription = 'Download ' . $artistName
        . ' Songs, Download Latest ' . $artistName
        . ' Songs, Download Latest ' . $artistName
        . ' Album, Download Latest ' . $artistName
        . ' Music, Read ' . $artistName
        . ' Biography and view their full Discography on TrendySongz';

    $profileImage = $artist->ProfilePic
        ? asset('images/' . ltrim($artist->ProfilePic, '/'))
        : null;
@endphp

@section('title', $pageTitle)

@section('meta')
    <meta name="description" content="{{ $metaDescription }}">
    <meta
        name="keywords"
        content="{{ $artistName }} songs, download {{ $artistName }} songs, download latest {{ $artistName }} songs, download {{ $artistName }} albums, {{ $artistName }} biography"
    >
@endsection

@section('canonical')
    <link rel="canonical" href="{{ $canonicalUrl }}">
@endsection

@section('facebook_meta')
    <meta property="og:type" content="profile">
    <meta property="og:site_name" content="TrendySongz">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">

    @if ($profileImage)
        <meta property="og:image" content="{{ $profileImage }}">
    @endif
@endsection

@section('twitter_meta')
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $artistName }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">

    @if ($profileImage)
        <meta name="twitter:image" content="{{ $profileImage }}">
    @endif
@endsection

@section('content')
    <div class="ts-artist-detail">
        <h1 class="ts-artist-page-title">
            Download Latest {{ $artistName }} Songs, Albums, Biography, All Music, and Videos on TrendySongz
        </h1>

            <time class="post-date" datetime="{{ now()->toDateString() }}" >
                {{ now()->format('M d, Y') }}
            </time>

        <section class="ts-artist-section ts-artist-profile-section">
            <div class="ts-artist-profile-card">
                <div class="ts-artist-profile-image">
                    @if ($profileImage)
                        <img src="{{ $profileImage }}" alt="{{ $artistName }}">
                    @else
                        <div class="ts-artist-profile-placeholder">
                            <i class="fa fa-user" aria-hidden="true"></i>
                        </div>
                    @endif
                </div>

                <div class="ts-artist-profile-info">
                    @if ($artist->Fullname)
                        <div class="ts-artist-profile-row">
                            <span class="ts-artist-profile-label">Full Name</span>
                            <span class="ts-artist-profile-value">
                                {{ $artist->Fullname }}
                            </span>
                        </div>
                    @endif

                    @if ($artist->Stage_Name)
                        <div class="ts-artist-profile-row">
                            <span class="ts-artist-profile-label">Stage Name</span>
                            <span class="ts-artist-profile-value">
                                {{ $artist->Stage_Name }}
                            </span>
                        </div>
                    @endif

                    

                    @if ($artist->Genres)
                        <div class="ts-artist-profile-row">
                            <span class="ts-artist-profile-label">Genre</span>
                            <span class="ts-artist-profile-value">
                                {{ $artist->Genres }}
                            </span>
                        </div>
                    @endif

                    @if ($artist->RecordLabel)
                        <div class="ts-artist-profile-row">
                            <span class="ts-artist-profile-label">Record Label</span>
                            <span class="ts-artist-profile-value">
                                {{ $artist->RecordLabel }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        @if (filled($artist->ArtistsProfile))
            <section class="ts-artist-section">
                <header class="ts-artist-section-heading">
                    <h2>{{ $artistName }} Biography</h2>
                </header>

                <div class="ts-artist-about-text">
                    {!! nl2br(e(strip_tags($artist->Place_Birth))) !!}
                </div>
            </section>
        @endif

        <section class="ts-artist-section">
            <header class="ts-artist-section-heading">
                <h2>{{ $artistName }} Albums and EPs</h2>
            </header>

            <div class="ts-artist-albums">
                @forelse ($albums as $album)
                    @php
                        $tracks = $albumTracks->get($album->id, collect());
                        $panelId = 'artist-album-tracks-' . $album->id;
                    @endphp

                    <div class="ts-artist-album">
                        <button
                            type="button"
                            class="ts-artist-album-header"
                            aria-expanded="false"
                            aria-controls="{{ $panelId }}"
                        >
                            <span class="ts-artist-album-cover">
                                @include('pages.partials.cover', [
                                    'src' => $album->cover_url,
                                    'folder' => '',
                                ])
                            </span>

                            <span class="ts-artist-album-header-info">
                                <span class="ts-artist-album-title">
                                    {{ $album->title }}
                                </span>

                                <span class="ts-artist-album-meta">
                                    <span class="ts-artist-album-meta-item">
                                        <span class="ts-artist-album-meta-label">
                                            Tracks
                                        </span>
                                        <span class="ts-artist-album-meta-value">
                                            {{ $tracks->count() }}
                                        </span>
                                    </span>

                                    @if ($album->released_year)
                                        <span class="ts-artist-album-meta-item">
                                            <span class="ts-artist-album-meta-label">
                                                Released
                                            </span>
                                            <span class="ts-artist-album-meta-value">
                                                {{ $album->released_year }}
                                            </span>
                                        </span>
                                    @endif
                                </span>
                            </span>

                            <span class="ts-artist-album-toggle" aria-hidden="true">
                                <i class="fa fa-chevron-down"></i>
                            </span>
                        </button>

                        <div
                            id="{{ $panelId }}"
                            class="ts-artist-album-tracks"
                        >
                            @forelse ($tracks as $track)
                                <a
                                    class="ts-artist-track"
                                    href="{{ route('pages.musics_detail', [
                                        $track->id,
                                        \Illuminate\Support\Str::slug(
                                            $artistName . ' ' . $track->TrackTitle
                                        ),
                                    ]) }}"
                                >
                                    <span class="ts-artist-track-number">
                                        {{ $track->track_number ?: $loop->iteration }}
                                    </span>

                                    <span class="ts-artist-track-info">
                                        <span class="ts-artist-track-title-row">
                                            <span class="ts-artist-track-title">
                                                {{ $track->TrackTitle }}
                                            </span>

                                            @if ($track->Featuring)
                                                <span class="ts-artist-track-featuring">
                                                    feat. {{ $track->Featuring }}
                                                </span>
                                            @endif
                                        </span>

                                        @if ($track->producedby)
                                            <span class="ts-artist-track-credit">
                                                Produced by {{ $track->producedby }}
                                            </span>
                                        @endif
                                    </span>

                                    <span class="ts-artist-track-arrow" aria-hidden="true">
                                        <i class="fa fa-arrow-right"></i>
                                    </span>
                                </a>
                            @empty
                                <p class="ts-artist-no-tracks">
                                    No tracks have been added to this album yet.
                                </p>
                            @endforelse

                            <a
                                href="{{ route('pages.album_details', [
                                    $album->id,
                                    \Illuminate\Support\Str::slug(
                                        $artistName . ' ' . $album->title
                                    ),
                                ]) }}"
                            >
                                View {{ $album->title }} album
                            </a>
                        </div>
                    </div>
                @empty
                    <p>No albums found for {{ $artistName }}.</p>
                @endforelse
            </div>
        </section>

        <section class="ts-artist-section">
            <header class="ts-artist-section-heading">
                <h2>{{ $artistName }} Singles</h2>
            </header>

            <div class="ts-artist-singles">
                @forelse ($singles as $track)
                    <a
                        class="ts-artist-track ts-artist-single"
                        href="{{ route('pages.musics_detail', [
                            $track->id,
                            \Illuminate\Support\Str::slug(
                                $artistName . ' ' . $track->TrackTitle
                            ),
                        ]) }}"
                    >
                        <span class="ts-artist-track-number">
                            {{ $loop->iteration }}
                        </span>

                        <span class="ts-artist-track-info">
                            <span class="ts-artist-track-title-row">
                                <span class="ts-artist-track-title">
                                    {{ $track->TrackTitle }}
                                </span>

                                @if ($track->Featuring)
                                    <span class="ts-artist-track-featuring">
                                        feat. {{ $track->Featuring }}
                                    </span>
                                @endif
                            </span>

                            @if ($track->producedby)
                                <span class="ts-artist-track-credit">
                                    Produced by {{ $track->producedby }}
                                </span>
                            @endif
                        </span>

                        <span class="ts-artist-track-arrow" aria-hidden="true">
                            <i class="fa fa-arrow-right"></i>
                        </span>
                    </a>
                @empty
                    <p>No singles found for {{ $artistName }}.</p>
                @endforelse
            </div>
        </section>

        @if ($videos->isNotEmpty())
            <section class="ts-artist-section">
                <header class="ts-artist-section-heading">
                    <h2>{{ $artistName }} Videos</h2>
                </header>

                <div class="ts-artist-info-list">
                    @foreach ($videos as $video)
                        <div class="ts-artist-info-item">
                            <div class="ts-artist-info-item-text">
                                <div class="ts-artist-info-item-title">
                                    <a href="{{ route('pages.videos_detail', [
                                        $video->id,
                                        \Illuminate\Support\Str::slug(
                                            $artistName . ' ' . $video->TrackTitle
                                        ),
                                    ]) }}">
                                        {{ $artistName }} - {{ $video->TrackTitle }}
                                    </a>
                                </div>

                                @if ($video->directedby)
                                    <div class="ts-artist-info-text">
                                        Directed by {{ $video->directedby }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($featuredSongs->isNotEmpty())
            <section class="ts-artist-section">
                <header class="ts-artist-section-heading">
                    <h2>Songs Featuring {{ $artistName }}</h2>
                </header>

                <div class="ts-artist-info-list">
                    @foreach ($featuredSongs as $track)
                        <div class="ts-artist-info-item">
                            <div class="ts-artist-info-item-text">
                                <div class="ts-artist-info-item-title">
                                    <a href="{{ route('pages.musics_detail', [
                                        $track->id,
                                        \Illuminate\Support\Str::slug(
                                            ($track->primary_artist ?: 'Artist')
                                            . ' '
                                            . $track->TrackTitle
                                        ),
                                    ]) }}">
                                        {{ $track->primary_artist ?: 'Artist' }}
                                        - {{ $track->TrackTitle }}
                                    </a>
                                </div>

                                <div class="ts-artist-info-text">
                                    feat. {{ $track->Featuring }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>

    <script>
        document.addEventListener('click', function (event) {
            const button = event.target.closest(
                '.ts-artist-album-header'
            );

            if (!button) {
                return;
            }

            const album = button.closest('.ts-artist-album');

            if (!album) {
                return;
            }

            const isOpen = album.classList.toggle('is-open');

            button.setAttribute(
                'aria-expanded',
                isOpen ? 'true' : 'false'
            );
        });
    </script>
@endsection