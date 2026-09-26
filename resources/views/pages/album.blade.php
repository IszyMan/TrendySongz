@extends('layouts.app')

@section('title', 'Album Download - Download Latest Nigerian Music Albums Mp3')

@section('meta')
    <meta name="description" content="Download All the Latest Naija Music albums, download albums, download latest album, latest music albums, download album, download album mp3">
    <meta name="keywords" content="Latest Naija Music albums, download albums, download naija music album download latest album, latest music albums, download album, download album mp3">
@endsection

@section('canonical')
    <link
        rel="canonical"
        href="{{ $albums->currentPage() === 1
            ? route('pages.album')
            : $albums->url($albums->currentPage()) }}"
    >
@endsection

@section('facebook_meta')
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="TrendySongz">
    <meta property="og:title" content="Album Download - Download Latest Nigerian Music Albums Mp3">
    <meta property="og:description" content="Download All the Latest Naija Music albums, download albums, download latest album, latest music albums, download album, download album mp3">
    <meta property="og:url" content="{{ route('pages.album') }}">
@endsection

@section('twitter_meta')
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="Album Download - Download Latest Nigerian Music Albums Mp3">
    <meta name="twitter:description" content="Download All the Latest Naija Music albums, download albums, download latest album, latest music albums, download album, download album mp3">
@endsection

@section('content')
    <div class="ts-artists-albums">
        <header class="ts-section-heading">
            <h1>Artists Albums</h1>

            <p class="ts-albums-subheading">
                Download Latest Nigerian Music Albums- Nigerian, Ghana and African
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
                        trim(
                            ($row->ArtistsName ?? '')
                            . ' '
                            . $row->title
                        )
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
                    No albums or EPs found.
                </p>
            @endforelse
        </div>

        @if ($albums->hasPages())
            <div >
                {{ $albums->onEachSide(1)->links('vendor.pagination.trendysongz') }}
            </div>
        @endif
    </div>
@endsection