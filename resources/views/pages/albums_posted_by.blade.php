@extends('layouts.app')

@php
    $pageTitle = 'Albums - Download Latest Music Albums - Naija Music Albums Mp3 » TrendySongz';

    $description = 'Browse the latest music albums posted by ' . $poster->name . ' on TrendySongz. Explore Nigerian and African albums, discover new releases, and find songs to download.';

    $keywords = 'download latest albums, download album mp3, download latest music albums, download music mp3, latests songs, 2Baba, tekno, tiwa savage, burna boy, zlatan, mi, ycee, flavour,phyno, rema, olamide, d prince, akon, timaya, spinall, neptune, cuppy, kizz daniel, spinall, neptune, kizz daniel naija musics, nigerian songs, dj cuppy, new music, ycee, tekno, download music mp3, akon, ';

    $pageUrl = $albums->currentPage() === 1
        ? route('pages.albums_posted_by', [
            'slug' => \Illuminate\Support\Str::slug($poster->name),
        ])
        : $albums->url($albums->currentPage());
@endphp

@section('title', $pageTitle)

@section('meta')
    <meta name="description" content="{{ $description }}">
    <meta name="keywords" content="{{ $keywords }}">
    <meta name="author" content="{{ $poster->name }}">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
@endsection

@section('canonical')
    <link rel="canonical" href="{{ $pageUrl }}">
@endsection

@section('facebook_meta')
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="TrendySongz">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ $pageUrl }}">
@endsection

@section('twitter_meta')
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $description }}">
@endsection

@section('content')
    <div class="ts-artists-albums">
        <header class="ts-section-heading">
            <h1>Albums Posted by {{ $poster->name }}</h1>

            <p class="ts-albums-subheading">
                Download music albums and EPs posted by {{ $poster->name }}
            </p>
        </header>

        <div class="ts-albums-date">
            <time datetime="{{ now()->toDateString() }}">
                {{ now()->format('M d, Y') }}
            </time>
        </div>

        <div class="ts-artists-albums-list">
            @forelse ($albums as $row)
                @php
                    $albumSlug = \Illuminate\Support\Str::slug(
                        trim(($row->ArtistsName ?? '') . ' ' . $row->title)
                    );
                @endphp

                <a
                    class="ts-artist-album-card"
                    href="{{ route('pages.album_details', [
                        $row->id,
                        $albumSlug,
                    ]) }}"
                >
                    <div class="ts-artist-album-cover">
                        @include('pages.partials.cover', [
                            'src' => $row->cover_url,
                            'folder' => '',
                        ])
                    </div>

                    <div class="ts-artist-album-details">
                        @if ($row->ArtistsName)
                            <span class="ts-artist-album-artist">
                                {{ $row->ArtistsName }}
                            </span>
                        @endif

                        <h2 class="ts-artist-album-title">
                            {{ $row->title }}
                        </h2>

                        @if ($row->featuring)
                            <p class="ts-artist-album-featuring">
                                Featuring {{ $row->featuring }}
                            </p>
                        @endif

                        <div class="ts-artist-album-meta">
                            @if ($row->released_year)
                                <span class="ts-artist-album-meta-row">
                                    Released: {{ $row->released_year }}
                                </span>
                            @endif

                            <span class="ts-artist-album-meta-row">
                                {{ $row->track_count }}
                                {{ $row->track_count == 1 ? 'Track' : 'Tracks' }}
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <p class="ts-artists-albums-empty">
                    No published albums or EPs found for {{ $poster->name }}.
                </p>
            @endforelse
        </div>

        @if ($albums->hasPages())
            <div>
                {{ $albums->onEachSide(1)->links('vendor.pagination.trendysongz') }}
            </div>
        @endif
    </div>
@endsection