@extends('layouts.app')

@section('title', 'Download ' . $year . ' Videos Here | TrendySongz')

@section('meta')
    <meta name="description" content="Download Latest Videos - Latest Music Videos, Latest Nigerian Music Videos mp4, Download Latest Naija Music Videos mp4">
    <meta name="keywords" content="download latest videos, latest naija music videos, download latest naija music video, download latest naija video, latest naija video">
@endsection

@section('canonical')
    <link rel="canonical" href="{{ route('pages.videos_released_year', $year) }}">
@endsection

@section('facebook_meta')
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="TrendySongz">
    <meta property="og:title" content="Download {{ $year }} Videos Here | TrendySongz">
    <meta property="og:description" content="Download Latest Videos - Latest Music Videos, Latest Nigerian Music Videos mp4, Download Latest Naija Music Videos mp4">
    <meta property="og:url" content="{{ route('pages.videos_released_year', $year) }}">
@endsection

@section('twitter_meta')
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="Download {{ $year }} Videos Here | TrendySongz">
    <meta name="twitter:description" content="Download Latest Videos - Latest Music Videos, Latest Nigerian Music Videos mp4, Download Latest Naija Music Videos mp4">
@endsection

@section('content')
    <div class="ts-videos-page">
        <header class="ts-video-page-heading">
            <h1>Download {{ $year }} Naija Videos Released Here</h1>

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
                            trim(
                                ($row->ArtistsName ?? '')
                                . ' '
                                . ($row->TrackTitle ?? '')
                            )
                        ),
                    ]) }}"
                >
                    <div class="ts-video-list-cover">
                        @include('pages.partials.cover', [
                            'src' => $row->CoverUrl,
                            'folder' => '',
                        ])

                        <span class="ts-video-list-play" aria-hidden="true">
                            <i class="fa fa-play"></i>
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
                <p class="ts-videos-empty">
                    No videos found for {{ $year }}.
                </p>
            @endforelse
        </div>

        @if ($videos->hasPages())
            <div class="ts-video-pagination">
                {{ $videos->links() }}
            </div>
        @endif
    </div>
@endsection