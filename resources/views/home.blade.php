 @extends('layouts.app')

@section('title', 'TrendySongz - Nigerian Top Music Download Website for Trending Songs')

@section('meta')
    <meta name="description" content="Download all the latest Nigerian music, videos, DJ mixes, albums and trending songs on TrendySongz.">
    <meta name="keywords" content="latest Nigerian music, Nigerian songs, download Nigerian music, Naija music, Nigerian albums, Nigerian DJ mix, Nigerian music videos">
@endsection

@section('canonical')
    <link rel="canonical" href="{{ url('/') }}">
@endsection

@section('facebook_meta')
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="TrendySongz">
    <meta property="og:title" content="TrendySongz - Nigerian Top Music Download Website for Trending Songs">
    <meta property="og:description" content="Download all the latest Nigerian music, videos, DJ mixes, albums and trending songs on TrendySongz.">
    <meta property="og:url" content="{{ url('/') }}">
@endsection

@section('twitter_meta')
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="TrendySongz - Nigerian Top Music Download Website for Trending Songs">
    <meta name="twitter:description" content="Download all the latest Nigerian music, videos, DJ mixes, albums and trending songs on TrendySongz.">
@endsection

@section('content')
    <div class="ts-home">
        <header class="ts-home-heading">
            <h1>Latest Nigerian Music</h1>

            <time datetime="{{ now()->toDateString() }}" class="post-date">
                {{ now()->format('M d, Y') }}
            </time>
            
        </header>

        <section class="ts-section">            

            <div class="ts-music-grid">
                @forelse ($music as $row)
                    <a class="ts-music-card"  href="{{ \App\Support\ListingUrl::musicDetail($row) }}">
                        <div class="ts-music-cover">
                            <img
                                src="{{ !empty($row->CoverUrl)
                                    ? asset('images/' . ltrim($row->CoverUrl, '/'))
                                    : asset('images/placeholder.jpg') }}"
                                alt="{{ $row->TrackTitle }} cover"
                                loading="lazy"
                                onerror="this.onerror=null;this.src='{{ asset('images/placeholder.jpg') }}';"
                            >

                            <span class="ts-music-overlay">
                                <span class="ts-play-button">▶</span>
                            </span>
                        </div>

                        <div class="ts-music-info">
                            <span class="ts-home-music-artist">
                                {{ $row->ArtistsName ?: 'Unknown Artist' }}
                            </span>

                            <div class="ts-home-music-title-row">
                                <span class="ts-home-music-title">{{ $row->TrackTitle }}</span>

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
                @empty
                    <p>More music is coming soon.</p>
                @endforelse
            </div>

            <p class="ts-section-button">
                <a href="{{ route('pages.musics', 'naija') }}">View all music →</a>
            </p>
        </section>

        <section class="ts-section ts-videos-section">
            <header class="ts-section-heading">
                <h2>Latest Videos</h2>
            </header>

            <div class="ts-videos-grid">
                @forelse ($videos as $row)
                    <a class="ts-video-card" href="{{ \App\Support\ListingUrl::videoDetail($row) }}">
                        <div class="ts-video-cover">
                            <span class="ts-cover-placeholder" aria-hidden="true">
                                TS
                            </span>
            
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
                @empty
                    <p>More videos are coming soon.</p>
                @endforelse
            </div>

            <p class="ts-section-button">
                <a href="{{ route('pages.videos_all') }}">View all videos →</a>
            </p>
        </section>

        <section class="ts-section ts-latest-albums">
            <header class="ts-section-heading">
                <h2>Latest Albums</h2>
            </header>
        
            <div class="ts-albums-grid">
                @forelse ($albums as $row)
                    <a
                        class="ts-album-card"
                        href="{{ \App\Support\AlbumUrl::detail($row) }}"
                        style="color: inherit; text-decoration: none;"
                    >
                        <div class="ts-album-cover">
                            <span class="ts-cover-placeholder" aria-hidden="true">
                                TS
                            </span>
        
                            @if ($row->cover_url)
                                <img
                                    loading="lazy"
                                    src="{{ asset('images/' . ltrim($row->cover_url, '/')) }}"
                                    alt="{{ $row->title }} album cover"
                                    onerror="this.remove()"
                                >
                            @endif
                        </div>
        
                        <div class="ts-album-details">
                            <div class="ts-album-artist">
                                {{ $row->ArtistsName ?: $row->Stage_Name }}
                            </div>
                        
                            <span class="ts-album-title">
                                {{ $row->title }}
                            </span>
                        
                            <div class="ts-album-meta-row">
                                {{ $row->released_year }}
                            </div>
                        </div>
                    </a>
                @empty
                    <p>No albums found.</p>
                @endforelse
            </div>
            
             <p class="ts-section-button">
                <a href="{{ route('pages.album') }}">View all Albums →</a>
            </p>
        </section>

        <section class="ts-section ts-djmix-section">
            <header class="ts-section-heading">
                <h2>Latest DJ Mixes</h2>
            </header>

            <div class="ts-djmix-list">
                @forelse ($mixes as $row)
                    @include('pages.partials.mix-card')
                @empty
                    <p>No DJ mixes found.</p>
                @endforelse
            </div>

            <p class="ts-section-button">
                <a href="{{ route('pages.djmix') }}">View all mixes →</a>
            </p>
        </section>

        <section class="ts-section ts-music-news-page">
            <header class="ts-section-heading">
                <h2>Music News</h2>
            </header>

            <div class="ts-music-news-list">
                @forelse ($news as $row)
                    @include('pages.partials.news-card')
                @empty
                    <p>No news found.</p>
                @endforelse
            </div>

            <p class="ts-section-button">
                <a href="{{ route('pages.celebrity_news') }}">View all news →</a>
            </p>
        </section>
    </div>
@endsection