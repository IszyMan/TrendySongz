@extends('layouts.app')

@php
    $pageTitle = 'Download Latest Songs Posted by ' . $poster->name . ' | TrendySongz';

    $description = 'Download the latest songs posted by ' . $poster->name
        . ' on TrendySongz. Browse and download music MP3s.';

    $keywords = 'songs posted by ' . $poster->name
        . ', download music mp3, latest songs, TrendySongz';

    $pageUrl = $rows->url($rows->currentPage());
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
            <h1>Download Latest Songs Posted By {{ $poster->name }}</h1>

            <time class="post-date" datetime="{{ now()->toDateString() }}">
                {{ now()->format('M d, Y') }}
            </time>
        </header>

        @include('pages.partials.music-listing', [
            'rows' => $rows,
            'emptyMessage' => 'No published songs found for ' . $poster->name . '.'
        ])

        
        
    </div>
@endsection