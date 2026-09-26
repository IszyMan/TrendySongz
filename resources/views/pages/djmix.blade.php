@extends('layouts.app')

@section('title', 'Dj Mix - Download Latest Naija DJ Mix - DJ Mixtape | TrendySongz')

@section('meta')
    <meta name="description" content="Download Latest Naija DJ Mix. Find the latest Nigerian DJ mixtapes and stream or download DJ mixes on TrendySongz.">
    <meta name="keywords" content="download dj mix, latest dj mix, latest naija dj mix, latest dj mixtape, latest dj mix download, dj kaywise mix, dj baddo mix, dj spinall mix, dj neptune mix, dj sose mix, dj big n mix">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
@endsection

@section('canonical')
    <link rel="canonical" href="{{ $mixes->currentPage() > 1 ? $mixes->url($mixes->currentPage()) : url('/djmix') }}">
@endsection

@section('content')
    <div class="ts-djmix-page">
        <header class="ts-section-heading">
            <div>
                <h2>Dj Mix - Download Latest Naija Dj Mix/mixtapes</h2>
                <p class="ts-djmix-subheading">
                    Listen to and discover the latest DJ mixtapes.
                </p>
            </div>
        </header>

        <div class="ts-djmix-date">
            <time class="post-date" datetime="{{ now()->toDateString() }}">
                {{ now()->format('M d, Y') }}
            </time>
        </div>

        @if ($mixes->count())
            <div class="ts-djmix-list">
                @foreach ($mixes as $mix)
                    @php
                        $djName = $mix->dj_name ?: 'TrendySongz DJ';

                        $mixSlug = $mix->slug
                            ?: \Illuminate\Support\Str::slug($djName . ' ' . $mix->mix_title);
                    @endphp

                    <a
                        class="ts-djmix-card"
                        href="{{ route('pages.djmix_detail', [$mix->id, $mixSlug]) }}"
                        aria-label="View {{ $djName }} - {{ $mix->mix_title }}"
                    >
                        <div class="ts-djmix-cover">
                            @include('pages.partials.cover', [
                                'src' => $mix->cover_url,
                                'folder' => '',
                            ])

                            <span class="ts-djmix-play" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M8 5v14l11-7z" />
                                </svg>
                            </span>
                        </div>

                        <div class="ts-djmix-info">
                            <span class="ts-djmix-label">DJ MIXTAPE</span>

                            <h3 class="ts-djmix-dj">{{ $djName }}</h3>

                            <p class="ts-djmix-title">
                                {{ $mix->mix_title }}
                            </p>

                            @if (filled($mix->duration ?? null))
                                <span class="ts-djmix-duration">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                         aria-hidden="true">
                                        <circle cx="12" cy="12" r="9" />
                                        <path d="M12 7v5l3 2" />
                                    </svg>

                                    {{ $mix->duration }}
                                </span>
                            @endif

                            <span class="ts-djmix-action">
                                View mixtape

                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                     aria-hidden="true">
                                    <path d="M5 12h14M13 6l6 6-6 6" />
                                </svg>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>

            @if ($mixes->hasPages())
                <div class="ts-djmix-pagination">
                    {{ $mixes->links('vendor.pagination.trendysongz') }}
                </div>
            @endif
        @else
            <div class="ts-djmix-empty">
                <p>No DJ mixes found.</p>
            </div>
        @endif
    </div>
@endsection