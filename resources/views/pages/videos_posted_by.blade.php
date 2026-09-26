@extends('layouts.app')

@section('title', 'Videos Posted by ' . $poster->name . ' | TrendySongz')

@section('meta')
    <meta name="description" content="Browse music videos posted by {{ $poster->name }} on TrendySongz.">
@endsection

@section('canonical')
    <link rel="canonical" href="{{ route('pages.videos_posted_by', \Illuminate\Support\Str::slug($poster->name)) }}">
@endsection

@section('content')
    <div class="ts-videos-page">
        <header class="ts-video-page-heading">
            <h1>Videos Posted by {{ $poster->name }}</h1>
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
                            <i class="fa fa-play"></i>
                        </span>
                    </div>

                    <div class="ts-video-list-info">
                        <span class="ts-video-list-artist">
                            {{ $row->ArtistsName }}
                        </span>

                        <span class="ts-video-list-title">
                            {{ $row->TrackTitle }}
                        </span>
                    </div>
                </a>
            @empty
                <p class="ts-videos-empty">No videos found.</p>
            @endforelse
        </div>

        <div class="ts-video-pagination">
            {{ $videos->links() }}
        </div>
    </div>
@endsection