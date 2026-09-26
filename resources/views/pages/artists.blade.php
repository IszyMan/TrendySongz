@extends('layouts.app')

@section('title', 'Artists Profiles & Full Album Downloads')

@section('meta')
    <meta
        name="description"
        content="Download all the Latest Music, Videos and Albums from your favorite Artists - Download all Latest Music By Nigerian Artistes, Ghana and African Artistes"
    >
    <meta
        name="keywords"
        content="download latest Nigerian Artistes Music, download music album, latests songs, 2Baba, tekno, tiwa savage, burna boy, zlatan, mi, ycee, flavour, phyno, rema, olamide, d prince, akon, timaya, spinall, neptune, cuppy, kizz daniel, naija musics, nigerian songs"
    >
@endsection

@section('canonical')
    <link rel="canonical" href="{{ url('/artists') }}">
@endsection

@section('content')
    <div class="ts-artists-page">
        <header class="ts-artists-header">
            <div class="ts-section-heading">
                <div>
                    <h1>Artists Profiles &amp; Full Album Downloads</h1>

                    <p class="ts-artists-intro">
                        Download latest music from Nigerian artists,
                        Ghanaian artists and African musicians.
                    </p>
                </div>
            </div>

           
            <time class="ts-artists-date" datetime="{{ now()->toDateString() }}">
                {{ now()->format('M d, Y') }}
            </time>
        </header>

        <section class="ts-artists-section">
            <header class="ts-section-heading">
                <h2>Popular Artists</h2>
            </header>

            <div class="ts-popular-grid">
                @forelse ($popular as $row)
                    @php
                        $artistName = $row->ArtistsName
                            ?: $row->Stage_Name
                            ?: 'Unknown Artist';
                    @endphp

                    <a
                        class="ts-popular-card"
                        href="{{ route(
                            'pages.artists_details',
                            \Illuminate\Support\Str::slug($artistName)
                        ) }}"
                    >
                        <span class="ts-popular-image">
                            @if ($row->ProfilePic)
                                <img
                                    src="{{ asset('images/' . ltrim($row->ProfilePic, '/')) }}"
                                    alt="{{ $artistName }}"
                                    loading="lazy"
                                >
                            @else
                                <span class="ts-artist-placeholder">
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                </span>
                            @endif
                        </span>

                        <span class="ts-popular-info">
                            <span class="ts-artist-label">POPULAR ARTIST</span>

                            <span class="ts-popular-name">
                                {{ $artistName }}
                            </span>

                            @if ($row->RecordLabel)
                                <span class="ts-artist-record-label">
                                    {{ $row->RecordLabel }}
                                </span>
                            @endif

                            <span class="ts-artist-stats">
                                <span>Albums {{ $row->album_count }}</span>
                                <span>Singles {{ $row->single_count }}</span>
                            </span>                            

                            <span class="ts-artist-view">View artist →</span>
                        </span>
                    </a>
                @empty
                    <p>No popular artists found.</p>
                @endforelse
            </div>
        </section>

        @include('pages.partials.artist-country-section', [
            'section' => 'naija',
            'heading' => 'Nigerian Artists',
            'rows' => $naija,
        ])

        @include('pages.partials.artist-country-section', [
            'section' => 'ghana',
            'heading' => 'Ghanaian Artists',
            'rows' => $ghana,
        ])

        @include('pages.partials.artist-country-section', [
            'section' => 'african',
            'heading' => 'African Artists',
            'rows' => $african,
        ])
    </div>

    <script>
        document.addEventListener('click', async function (event) {
            const link = event.target.closest(
                '.ts-artists-section[data-artist-section] ' +
                '.ts-artist-pagination a'
            );

            if (!link) {
                return;
            }

            const section = link.closest('[data-artist-section]');

            if (!section) {
                return;
            }

            event.preventDefault();

            const url = new URL(link.href);
            url.searchParams.set(
                'section',
                section.dataset.artistSection
            );

            try {
                section.setAttribute('aria-busy', 'true');

                const response = await fetch(url.toString(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html',
                    },
                });

                if (!response.ok) {
                    throw new Error('Unable to load artist page.');
                }

                const markup = await response.text();
                const documentFragment = new DOMParser()
                    .parseFromString(markup, 'text/html');

                const replacement = documentFragment.querySelector(
                    '.ts-artists-section[data-artist-section]'
                );

                if (!replacement) {
                    throw new Error('Artist section was not returned.');
                }

                section.replaceWith(replacement);
                window.history.pushState({}, '', url.toString());

                replacement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start',
                });
            } catch (error) {
                window.location.assign(url.toString());
            } finally {
                section.removeAttribute('aria-busy');
            }
        });
    </script>
@endsection