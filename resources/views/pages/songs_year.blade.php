@extends('layouts.app')

@php
    $pageTitle = "Download {$year} Music Mp3 Here | TrendySongz";

    $description = "Download Latest {$year} Songs, Download Latest {$year} Naija Songs, Download {$year} Music mp3, download {$year} music ,latests songs, download latest Naija music, Latest Naija songs,  2Baba, tekno, tiwa savage, burna boy, zlatan, mi, ycee, flavour,phyno, rema, olamide, d prince, akon, timaya, spinall, neptune, cuppy, kizz daniel naija musics, nigerian songs";

    $keywords = "Download Latest {$year} Songs, Download Latest {$year} Naija Songs, Download {$year} Music mp3, download {$year} music, Download {$year} Songs, Download {$year} naija music, download music mp3, latests songs, latest naija songs, latest naija music, 2Baba, tekno, tiwa savage, burna boy, zlatan, mi, ycee, flavour,phyno, rema, olamide, d prince, akon, timaya, spinall, neptune, cuppy, kizz daniel, spinall, neptune, kizz daniel naija musics, nigerian songs, dj cuppy, new music, ycee, tekno, download music mp3, akon, ";

    $pageUrl = $rows->url($rows->currentPage());
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
            <h1>
                Download {{ $year }} Songs, Download {{ $year }} Music Mp3,
                Download {{ $year }} Naija Music
            </h1>

            <time class="post-date" datetime="{{ now()->toDateString() }}">
                {{ now()->format('M d, Y') }}
            </time>
        </header>

      <!--  <p>
            Download {{ $year }} Songs here, Download {{ $year }} Naija Music Mp3,
            download {{ $year }} music here for popular Artistes like Davido,
            Burna Boy, Wizkid, Peruzzi, Mayorkun, etc.
        </p> -->

        @include('pages.partials.music-listing', [
            'rows' => $rows,
            'emptyMessage' => 'No published ' . $year . ' songs found.'
        ])

        
    </div>
@endsection