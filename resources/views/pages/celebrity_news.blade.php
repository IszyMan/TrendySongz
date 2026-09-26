@extends('layouts.app')

@php
    $pageTitle = 'Music News - Top Naija Celebrity News, Entertainment gists & Updates';
    $pageDescription = 'Latest Celebrity and Hot Gist and Entertainment Updates';

    $canonicalUrl = $rows->currentPage() > 1
        ? $rows->url($rows->currentPage())
        : url('/music-news');
@endphp

@section('title', $pageTitle)

@section('meta')
    <meta name="description" content="{{ $pageDescription }}">
    <meta
        name="keywords"
        content="music news, Naija celebrity news, Nigerian entertainment news, latest music news, celebrity gist, entertainment updates"
    >
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
@endsection

@section('canonical')
    <link rel="canonical" href="{{ $canonicalUrl }}">
@endsection

@section('facebook_meta')
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="TrendySongz">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
@endsection

@section('twitter_meta')
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
@endsection

@section('content')
    <div class="ts-music-news-page">
        <header class="ts-section-heading">
            <div>
                <h1 class="ts-visually-hidden">Music News</h1>               

                <p class="ts-music-news-subheading">
                    Latest celebrity news, entertainment gist and music updates.
                </p>
            </div>

            <div class="ts-music-news-date">
                <time datetime="{{ now()->toDateString() }}">
                    {{ now()->format('M j, Y') }}
                </time>
                
            </div>
        </header>

        

        <div class="ts-music-news-list">
            @forelse ($rows as $row)
                @php
                    $postUrl = route('pages.celebritynews_details', [
                        $row->id,
                        \Illuminate\Support\Str::slug($row->title),
                    ]);

                    $intro = \Illuminate\Support\Str::limit(
                        trim(preg_replace(
                            '/\s+/u',
                            ' ',
                            strip_tags($row->intro ?: $row->description ?: '')
                        )),
                        400
                    );

                    $postDate = $row->created_at
                        ? \Illuminate\Support\Carbon::parse($row->created_at)
                        : null;
                @endphp

                <article class="ts-music-news-card">
                    <a class="ts-music-news-card-link" href="{{ $postUrl }}">
                        @if ($row->photo)
                            <span class="ts-music-news-image">
                                <img
                                    src="{{ asset('images/blog/' . ltrim($row->photo, '/')) }}"
                                    alt="{{ $row->title }}"
                                    loading="lazy"
                                >
                            </span>
                        @endif

                        <span class="ts-music-news-content">
                            <span class="ts-music-news-title">
                                {{ $row->title }}
                            </span>

                            @if ($intro !== '')
                                <span class="ts-music-news-intro">
                                    {{ $intro }}
                                </span>
                            @endif
                        </span>

                        <span class="ts-music-news-meta">
                            <span>
                                Posted by {{ $row->posted_by_name ?: 'Wilfred Moses' }}
                            </span>

                            @if ($postDate)
                                <span class="ts-music-news-meta-separator">•</span>

                                <time
                                    class="ts-music-news-post-date"
                                    datetime="{{ $postDate->toDateString() }}"
                                >
                                    {{ $postDate->format('M j, Y') }}
                                </time>
                            @endif
                        </span>

                        <span class="ts-music-news-read-more">
                            Continue Reading
                            <i
                                class="fa fa-arrow-right ts-music-news-arrow"
                                aria-hidden="true"
                            ></i>
                        </span>
                    </a>
                </article>
            @empty
                <p>No music news has been published yet.</p>
            @endforelse
        </div>

        @if ($rows->hasPages())
            <div class="ts-list-pagination">
                {{ $rows->links('vendor.pagination.trendysongz') }}
            </div>
        @endif
    </div>
@endsection