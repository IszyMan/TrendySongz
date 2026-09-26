@extends('layouts.app')

@php
    $articleTitle = trim($blog->title);

    $pageTitle = $articleTitle . ' - TrendySongz';

    $metaDescription = \Illuminate\Support\Str::limit(
        trim(preg_replace(
            '/\s+/u',
            ' ',
            strip_tags($blog->intro ?: $articleTitle)
        )),
        160
    );

    $canonicalUrl = route('pages.celebritynews_details', [
        $blog->id,
        \Illuminate\Support\Str::slug($articleTitle),
    ]);

    $imageUrl = $blog->photo
        ? asset('images/blog/' . ltrim($blog->photo, '/'))
        : null;

    $postedDate = $blog->created_at
        ? \Illuminate\Support\Carbon::parse($blog->created_at)
        : null;
@endphp

@section('title', $pageTitle)

@section('meta')
    <meta name="description" content="{{ $metaDescription }}">
    <meta
        name="keywords"
        content="{{ $articleTitle }}, music news, Nigerian music news, celebrity news, entertainment news"
    >
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
@endsection

@section('canonical')
    <link rel="canonical" href="{{ $canonicalUrl }}">
@endsection

@section('facebook_meta')
    <meta property="og:type" content="article">
    <meta property="og:site_name" content="TrendySongz">
    <meta property="og:title" content="{{ $articleTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">

    @if ($imageUrl)
        <meta property="og:image" content="{{ $imageUrl }}">
    @endif
@endsection

@section('twitter_meta')
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@TrendySongz_">
    <meta name="twitter:title" content="{{ $articleTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">

    @if ($imageUrl)
        <meta name="twitter:image" content="{{ $imageUrl }}">
    @endif
@endsection

@section('content')
    <div class="ts-blog-page">
        <article class="ts-blog-detail">
            <header class="ts-blog-header">
                <h1 class="ts-blog-title">{{ $articleTitle }}</h1>

                <div class="ts-blog-meta">
                    <span>
                        Posted by:
                        {{ $blog->posted_by_name ?: 'TrendySongz' }}
                    </span>

                    @if ($postedDate)
                        <span aria-hidden="true">•</span>

                        <time datetime="{{ $postedDate->toDateString() }}">
                            {{ $postedDate->format('M j, Y') }}
                        </time>
                    @endif
                </div>
            </header>

            @if ($imageUrl)
                <figure class="ts-blog-featured-image">
                    <img src="{{ $imageUrl }}" alt="{{ $articleTitle }}">
                </figure>
            @endif

            @if (filled($blog->intro))
                <div class="ts-blog-intro">
                    {!! nl2br(e(strip_tags($blog->intro))) !!}
                </div>
            @endif

            <div class="ts-blog-body">
                @foreach ([
                    'description',
                    'desc2',
                    'desc3',
                    'desc4',
                    'desc5',
                    'desc6',
                    'desc7',
                    'desc8',
                    'desc9',
                    'desc10',
                    'Desc11',
                    'Desc12',
                    'Desc13',
                    'Desc14',
                    'Desc15',
                    'Desc16',
                    'Desc17',
                    'Desc18',
                    'Desc19',
                    'Desc20',
                    'Desc21',
                    'Desc22',
                    'Desc23',
                    'Desc24',
                    'Desc25',
                    'Desc26',
                    'Desc27',
                    'Desc28',
                    'Desc29',
                    'Desc30',
                ] as $field)
                    @if (filled($blog->{$field} ?? null))
                        <p>{!! nl2br(e(strip_tags($blog->{$field}))) !!}</p>
                    @endif
                @endforeach
            </div>

            <p class="ts-blog-source">Source: TrendySongz</p>
        </article>


        @if ($related_blog->isNotEmpty())
            <section class="ts-blog-related">
                <h2>Related Music News</h2>

                <div class="ts-blog-related-list">
                    @foreach ($related_blog as $related)
                        <a
                            class="ts-blog-related-card"
                            href="{{ route('pages.celebritynews_details', [
                                $related->id,
                                \Illuminate\Support\Str::slug($related->title),
                            ]) }}"
                        >
                            @if ($related->photo)
                                <img
                                    src="{{ asset('images/blog/' . ltrim($related->photo, '/')) }}"
                                    alt=""
                                    loading="lazy"
                                >
                            @endif

                            <span>{{ $related->title }}</span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection