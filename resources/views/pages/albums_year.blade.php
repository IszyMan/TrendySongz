@extends('layouts.app')

@php
    $pageTitle = "Download Latest {$year} Albums Here | TrendySongz";
    $description = "Download Latest {$year} Albums, Download Latest {$year} Naija Albums, Download {$year} Album mp3, download {$year} music album";
    $keywords = "Download Latest {$year} album, Download Latest {$year} Naija album, Download {$year} album mp3, download {$year} music albums, Download {$year} Naija Music Albums";

    $pageUrl = $albums->currentPage() === 1
        ? route('pages.albums_released_year', ['year' => $year])
        : $albums->url($albums->currentPage());
@endphp

@section('title', $pageTitle)

@section('meta')
    <meta name="description" content="{{ $description }}">
    <meta name="keywords" content="{{ $keywords }}">
    <meta name="author" content="Wilfred Moses">
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
            <h1>Download {{ $year }} Nigerian, Ghana and African music albums</h1>

            <!--<p class="ts-albums-subheading">
                Download {{ $year }} Nigerian, Ghana and African music albums
            </p>-->
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
                    No {{ $year }} albums or EPs found.
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