@extends('layouts.app')
@section('title', ($row->ArtistsName ?? 'Artist') . ' - ' . $row->TrackTitle . ' | TrendySongz')
@section('content')
@if ($row->ListingType === 'video')
    <div class="ts-video-detail">
        <header class="ts-video-detail-header">
            <h1 class="ts-video-detail-title">{{ $row->ArtistsName }} - {{ $row->TrackTitle }}</h1>
            <div class="ts-video-detail-post-meta">
                Video
                @if ($row->YearOfRelease)
                    · {{ $row->YearOfRelease }}
                @endif
            </div>
        </header>
        <div class="ts-video-detail-info-bar">
            @if ($row->Featuring)
                <span class="ts-video-detail-info-item"><span class="ts-video-detail-info-label">Featuring</span><span class="ts-video-detail-info-value">{{ $row->Featuring }}</span></span>
            @endif
            @if ($row->directedby)
                <span class="ts-video-detail-info-item"><span class="ts-video-detail-info-label">Director</span><span class="ts-video-detail-info-value">{{ $row->directedby }}</span></span>
            @endif
        </div>
        <section class="ts-video-detail-player-section">
            <div class="ts-video-detail-player">
                @if ($row->CoverUrl)
                    <img class="ts-detail-cover" src="{{ asset('images/' . ltrim($row->CoverUrl, '/')) }}" alt="Cover for {{ $row->TrackTitle }}" onerror="this.remove()">
                @endif
                <span class="ts-detail-media-placeholder">Video file awaiting restoration</span>
            </div>
        </section>
        <section class="ts-video-detail-section ts-video-detail-about">
            <h2 class="ts-video-detail-section-heading">About this video</h2>
            <div class="ts-video-detail-about-text">{{ $row->TrackInfo ?: $row->introduction }}</div>
        </section>
    </div>
@else
    <div class="ts-music-detail">
        <h1 class="ts-song-title"><span class="ts-song-artist">{{ $row->ArtistsName }}</span> - {{ $row->TrackTitle }}</h1>
        @if ($row->Featuring)
            <p class="ts-song-featuring">Featuring {{ $row->Featuring }}</p>
        @endif
        <div class="ts-music-details-card">
            <div class="ts-music-header">
                <div class="ts-music-cover">@include('pages.partials.cover', ['src' => $row->CoverUrl, 'folder' => ''])</div>
                <div class="ts-music-content">
                    <h2>{{ $row->TrackTitle }}</h2>
                    <p>{{ $row->ArtistsName }}</p>
                    @if ($row->producedby)
                        <p>Produced by {{ $row->producedby }}</p>
                    @endif
                </div>
            </div>
            <div class="ts-about-song">
                <h2 class="ts-about-heading">About this song</h2>
                <div class="ts-about-text">{{ $row->TrackInfo ?: $row->introduction }}</div>
            </div>
            <p class="ts-detail-media-note">Audio file awaiting restoration.</p>
        </div>
    </div>
@endif
@endsection
