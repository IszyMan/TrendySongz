@extends('layouts.app')

@section('title', 'Video - Download Latest Videos - Music Videos and Comedy Videos')

@section('meta')
    <meta name="description" content="Download Latest Videos - Music Videos and Comedy Videos and mp4, Naija Music Videos, Latest Comedy Videos, Latest Music Videos">
    <meta name="keywords" content="download latest video, latest video, latest naija music video, latest naija video, download latest naija video, Latest Comedy Videos, download comedy video,">
@endsection

@section('canonical')
    <link rel="canonical" href="{{ url('/download-latest-videos') }}">
@endsection

@section('facebook_meta')
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="TrendySongz">
    <meta property="og:title" content="Video - Download Latest Videos - Music Videos and Comedy Videos">
    <meta property="og:description" content="Download Latest Videos - Music Videos and Comedy Videos and mp4, Naija Music Videos, Latest Comedy Videos, Latest Music Videos">
    <meta property="og:url" content="{{ url('/download-latest-videos') }}">
@endsection

@section('twitter_meta')
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="Video - Download Latest Videos - Music Videos and Comedy Videos">
    <meta name="twitter:description" content="Download Latest Videos - Music Videos and Comedy Videos and mp4, Naija Music Videos, Latest Comedy Videos, Latest Music Videos">
@endsection

@section('content')
    <div class="ts-videos-page">
        <header class="ts-video-page-heading">
            <h1>Videos - Download Latest Naija Videos Here</h1>

            <time class="post-date" datetime="{{ now()->toDateString() }}">
                {{ now()->format('M d, Y') }}
            </time>
        </header>

        <div class="ts-video-list">
            @forelse ($videos as $row)
                <a
                    class="ts-video-list-item"
                    href="{{ route('pages.videos_detail', [
                        $row->id,
                        \Illuminate\Support\Str::slug(
                            trim(($row->ArtistsName ?? '') . ' ' . ($row->TrackTitle ?? ''))
                        ),
                    ]) }}"
                >
                    <div class="ts-video-list-cover">
                        @include('pages.partials.cover', [
                            'src' => $row->CoverUrl,
                            'folder' => '',
                        ])

                        <span class="ts-video-list-play" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M8 5v14l11-7z"></path>
                            </svg>
                        </span>
                    </div>

                    <div class="ts-video-list-info">
                        @if ($row->ArtistsName)
                            <span class="ts-video-list-artist">
                                {{ $row->ArtistsName }}
                            </span>
                        @endif

                        <span class="ts-video-list-title">
                            {{ $row->TrackTitle }}
                        </span>

                        @if ($row->Featuring)
                            <span class="ts-video-list-featuring">
                                <span>Featuring:</span> {{ $row->Featuring }}
                            </span>
                        @endif

                        @if ($row->directedby)
                            <span class="ts-video-list-directed">
                                <span>Directed by:</span> {{ $row->directedby }}
                            </span>
                        @endif
                    </div>
                </a>
            @empty
                <p class="ts-videos-empty">No videos found.</p>
            @endforelse
        </div>

        @if ($videos instanceof \Illuminate\Contracts\Pagination\Paginator)
            <div class="ts-video-pagination">
                {{ $videos->links() }}
            </div>
        @endif
    </div>
@endsection