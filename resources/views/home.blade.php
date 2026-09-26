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
                    @include('pages.partials.music-card')
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
                    @include('pages.partials.video-card')
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
                <a class="ts-section-link" href="{{ route('pages.album') }}">View all →</a>
            </header>

            <div class="ts-albums-grid">
                @forelse ($albums as $row)
                    @include('pages.partials.album-card')
                @empty
                    <p>No albums found.</p>
                @endforelse
            </div>
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