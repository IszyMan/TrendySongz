@extends('layouts.app')

@php
    $pageTitle = 'Download Latest  Music Mp3 Here | TrendySongz';

    $description = 'Discover and download the latest Naija songs on TrendySongz. Browse new Nigerian and African music, explore your favorite artists, and find fresh MP3 releases.';

    $keywords = 'download music mp3, latests songs, latest naija songs, download  music,  latest naija music, download latest music, 2Baba, tekno, tiwa savage, burna boy, zlatan, mi, ycee, flavour,phyno, rema, olamide, d prince, akon, timaya, spinall, neptune, cuppy, kizz daniel, spinall, neptune, kizz daniel naija musics, nigerian songs, dj cuppy, new music, ycee, tekno, download music mp3, akon, ';

    $pageUrl = $rows->url($rows->currentPage());
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
        <header class="ts-music-page-heading">
            <h1>Latest Music</h1>

            <time class="post-date" datetime="{{ now()->toDateString() }}">
                {{ now()->format('M d, Y') }}
            </time>
        </header>

        @include('pages.partials.music-listing', [
            'rows' => $rows,
            'emptyMessage' => 'No published music found.'
        ])

        
    </div>
@endsection