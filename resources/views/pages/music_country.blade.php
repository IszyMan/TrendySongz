@extends('layouts.app')

@php
    $countryName = ucfirst($country);

    $pageTitle = "Download Latest {$countryName} Music Mp3 Here | TrendySongz";

    $description = "download music mp3,latests songs, download latest Naija music, download latest {$countryName} music , Latest Naija songs,  2Baba, tekno, tiwa savage, burna boy, zlatan, mi, ycee, flavour,phyno, rema, olamide, d prince, akon, timaya, spinall, neptune, cuppy, kizz daniel naija musics, nigerian songs";

    $keywords = "download music mp3, latests songs, latest naija songs, download {$countryName} music,  latest naija music, download latest music, 2Baba, tekno, tiwa savage, burna boy, zlatan, mi, ycee, flavour,phyno, rema, olamide, d prince, akon, timaya, spinall, neptune, cuppy, kizz daniel, spinall, neptune, kizz daniel naija musics, nigerian songs, dj cuppy, new music, ycee, tekno, download music mp3, akon, ";

    $pageUrl = $rows->url($rows->currentPage());
    $displayDate = now('Africa/Lagos');
@endphp

@section('title', $pageTitle)

@section('meta')
    <meta name="description" content="{{ $description }}">
    <meta name="keywords" content="{{ $keywords }}">
@endsection

@section('canonical')
    <link rel="canonical" href="{{ $pageUrl }}">

    @if ($rows->currentPage() > 1)
        <link rel="prev" href="{{ $rows->previousPageUrl() }}">
    @endif

    @if ($rows->hasMorePages())
        <link rel="next" href="{{ $rows->nextPageUrl() }}">
    @endif
@endsection

@section('facebook_meta')
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ $pageUrl }}">
    <meta property="og:site_name" content="TrendySongz">
@endsection

@section('twitter_meta')
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $description }}">
@endsection

@section('content')
    <div class="ts-music-page">
        <div class="ts-music-page">
            <header class="ts-music-page-heading">
                <h1>Download Latest {{ $countryName }} Music Mp3</h1>

                <time class="post-date"
                    datetime="{{ $displayDate->toDateString() }}">
                    {{ $displayDate->format('M j, Y') }}
                </time>
            </header>

        <div class="ts-music-list">
            @forelse ($rows as $row)
                <article class="ts-music-list-item">
                    <a class="ts-music-list-link"
                       href="{{ route('pages.musics_detail', [
                           $row->id,
                           \Illuminate\Support\Str::slug(
                               ($row->ArtistsName ?? '') . ' ' . $row->TrackTitle
                           ),
                       ]) }}">

                        <span class="ts-music-list-cover">
                            @include('pages.partials.cover', [
                                'src' => $row->CoverUrl,
                                'folder' => '',
                            ])
                        </span>

                        <span class="ts-music-list-info">
                            <span class="ts-music-list-artist">
                                {{ $row->ArtistsName ?: 'Unknown Artist' }}
                            </span>

                            <span class="ts-music-list-title">
                                {{ $row->TrackTitle }}
                            </span>

                            @if ($row->Featuring)
                                <span class="ts-music-list-featuring">
                                    feat. {{ $row->Featuring }}
                                </span>
                            @endif
                        </span>

                        <span class="ts-music-list-action">›</span>
                    </a>
                </article>
            @empty
                <p>No published {{ $countryName }} music found.</p>
            @endforelse
        </div>

        <div class="ts-list-pagination">
            {{ $rows->links() }}
        </div>
    </div>
@endsection