@extends('layouts.app')
@section('title',  $title. ' Mp3 Download » Trendysongz')
@section('meta')
    <meta name="keywords" content=" {{ $artist->ArtistsName }} {{ $artist->TrackTitle }}, Download {{ $artist->ArtistsName }} {{ $artist->Featuring  }} {{ $artist->TrackTitle }}, Download {{ $artist->ArtistsName }} {{ $artist->TrackTitle }} mp3 , Download {{ $artist->ArtistsName }} {{ $artist->Featuring  }} {{ $artist->TrackTitle }} song , download music mp3 {{ $artist->ArtistsName }} {{ $artist->TrackTitle }}, Download {{ $title }} ,  {{ $artist->ArtistsName }} {{ $artist->TrackTitle }} free mp3    ">
    <meta name="description"  content="  Download {{ $title }} Mp3 Audio Music.  {{ $artist->TrackInfo }}  "/>
@endsection

@section('facebook_meta')
    <meta property="og:image" content="https://Trendysongz.com/images/{{$artist->CoverUrl}}">
    <meta property="og:description" content="Download {{ $artist->ArtistsName }} - {{ $artist->TrackTitle }} | {{ $artist->TrackInfo }}">
@endsection
@section('canonical')
<link rel="canonical" href="{{ route('pages.musics_detail',[$id,strtolower(str_replace(')','',str_replace('(','',str_replace(' ', '-', $artist->ArtistsName)))), strtolower(str_replace(')','',str_replace('(','',str_replace(' ', '-',  $artist->Title)))) ]) }}" />
@endsection
@section('twitter_meta')
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@Trendysongz" />
<meta name="twitter:title" content="{{ $artist->ArtistsName }} - {{ $artist->TrackTitle }}" />
  
<meta name="twitter:description" content=" {{ $artist->TrackInfo }}" />
<meta name="twitter:image" content="https://Trendysongz.com/images/{{ $artist->CoverUrl }}" />
 
@endsection


@section('content')

<div class="ts-music-detail">

    {{-- =====================================================
         MUSIC HEADER
    ====================================================== --}}
    <header class="ts-music-header">

        <h1 class="ts-music-title">
            {{ $title }}
        </h1>

        <div class="ts-music-meta">

            <a href="{{ route(
                'pages.musics_posted_by',
                [strtolower(str_replace(' ', '-', $artist->postedby))]
            ) }}">
                Posted by: {{ $artist->postedby }}
                <span class="fa fa-check-circle verified"></span>
            </a>

            <span class="ts-meta-separator">•</span>

            <time
                class="ts-music-date"
                datetime="{{ Carbon\Carbon::now()->toIso8601String() }}"
            >
                {{ Carbon\Carbon::now()->format('M d, Y') }}
            </time>

        </div>

    </header>


    {{-- =====================================================
         MAIN MUSIC CARD
    ====================================================== --}}
    <section class="ts-music-details-card">


        {{-- =================================================
             RELEASE INFORMATION BAR
        ================================================== --}}
        <div class="ts-music-info-bar">
            
            @if(!empty($artist->producedby))

                <div class="ts-info-item">

                    <span class="ts-info-label">
                        Produced:
                    </span>

                    <span class="ts-info-value">
                        {{ $artist->producedby }}
                    </span>

                </div>

            @endif

            @if(!empty($artist->YearOfRelease))

                <div class="ts-info-item">

                    <span class="ts-info-label">
                        Released:
                    </span>

                    <span class="ts-info-value">

                        <a href="{{ route(
                            'pages.musics_released_year',
                            $artist->YearOfRelease
                        ) }}">
                            {{ $artist->YearOfRelease }}
                        </a>

                    </span>

                </div>

            @endif
            
            
            @if($artist->album_id > 0)

                @php
                    $albumTitle = \App\Models\album::where(
                        'id',
                        $artist->album_id
                    )->value('title');
                @endphp

                <div class="ts-info-item">

                    <span class="ts-info-label">
                        Album:
                    </span>

                    <span class="ts-info-value">

                        <a href="{{ route(
                            'pages.album_details',
                            [
                                $artist->album_id,
                                strtolower(
                                    str_replace(
                                        ' ',
                                        '-',
                                        $artist->Stage_Name
                                    )
                                ),
                                strtolower(
                                    str_replace(
                                        ' ',
                                        '-',
                                        $albumTitle
                                    )
                                )
                            ]
                        ) }}">
                            {{ $albumTitle }}
                        </a>

                    </span>

                </div>

            @endif


            <div class="ts-info-item">

                <span class="ts-info-label">
                    Country:
                </span>

                <span class="ts-info-value">

                    <a href="{{ route(
                        'pages.musics',
                        [$artist->country_id]
                    ) }}">
                        {{ strtoupper($artist->country_id) }}
                    </a>

                </span>

            </div>


            <div class="ts-info-item">

                <span class="ts-info-label">
                    Category:
                </span>

                <span class="ts-info-value">

                    @if($artist->isgospel == '1' && $artist->ishighlife == '0')

                        <a href="{{ route(
                            'pages.musics',
                            ''
                        ) }}">
                            Latest Music
                        </a>

                    @elseif($artist->ishighlife == '1')

                        <a href="{{ route(
                            'pages.musics',
                            'highlife'
                        ) }}">
                            Highlife
                        </a>

                    @else

                        <a href="{{ route(
                            'pages.musics',
                            'gospel'
                        ) }}">
                            Gospel
                        </a>

                    @endif

                </span>

            </div>

        </div>


        {{-- =================================================
             COVER + SONG DETAILS + PLAYER
        ================================================== --}}
        <div class="ts-music-body">


            {{-- =================================================
                 COVER ART
            ================================================== --}}
            <div class="ts-music-cover">

                <img
                    src="{{ asset('images/' . $artist->CoverUrl) }}"
                    alt="{{ $title }}"
                    loading="eager"
                >

            </div>


            {{-- =================================================
                 SONG INFORMATION
            ================================================== --}}
            <div class="ts-music-content">

                <h2 class="ts-song-artist">
                    <a href="{{ route(
                        'pages.artists_details',
                        strtolower(str_replace(' ', '-', $artist->ArtistsName))
                    ) }}"  style='text-decoration: none'>
                        {{ $artist->ArtistsName }}
                    </a>
                </h2>
                
                <div class="ts-song-title-row">

                    <h3 class="ts-song-title">
                        {{ $artist->TrackTitle }}
                    </h3>
                
                    @if(!empty($artist->Featuring))
                
                        <div class="ts-song-featuring">
                
                            <span>feat.</span>
                
                            @foreach($featuring as $row)
                
                                <a href="{{ route(
                                    'pages.artists_details',
                                    strtolower(
                                        str_replace(
                                            ')',
                                            '',
                                            str_replace(
                                                '(',
                                                '',
                                                str_replace(
                                                    ' ',
                                                    '-',
                                                    trim($row)
                                                )
                                            )
                                        )
                                    )
                                ) }}">
                                    {{ trim($row) }}
                                </a>
                
                                @if(!$loop->last)
                                    ,
                                @endif
                
                            @endforeach
                
                        </div>
                
                    @endif
                
                </div>


                {{-- =================================================
                     CUSTOM AUDIO PLAYER
                ================================================== --}}
                @if(!empty($artist->TrackUrl))

                    <div class="ts-audio-box">

                        {{-- REAL AUDIO ELEMENT --}}
                        <audio
                            id="myaudio"
                            class="ts-audio-element"
                            preload="metadata"
                        >
                            <source
                                src="{{ 'https://cdn.Trendysongz.com/audio/' . $artist->TrackUrl }}"
                                type="audio/mpeg"
                            >
                        </audio>


                        {{-- =================================================
                             PLAY BUTTON + LABEL
                        ================================================== --}}
                        <div class="ts-audio-heading">

                            <button
                                type="button"
                                class="ts-main-play"
                                id="tsPlayButton"
                                aria-label="Play song"
                                aria-pressed="false"
                            >
                                <i
                                    class="fa fa-play"
                                    id="tsPlayIcon"
                                    aria-hidden="true"
                                ></i>
                            </button>

                            <span id="tsPlayLabel">
                                Listen to this song
                            </span>

                        </div>


                        {{-- =================================================
                             PROGRESS
                        ================================================== --}}
                        <div class="ts-audio-progress-row">

                            <span
                                class="ts-audio-time"
                                id="tsCurrentTime"
                            >
                                0:00
                            </span>

                            <input
                                type="range"
                                id="tsAudioProgress"
                                class="ts-audio-progress"
                                min="0"
                                max="100"
                                value="0"
                                step="0.1"
                                aria-label="Song progress"
                            >

                            <span
                                class="ts-audio-time"
                                id="tsDuration"
                            >
                                0:00
                            </span>

                        </div>


                        {{-- =================================================
                             DOWNLOAD
                        ================================================== --}}
                        <a
                            id="a1"
                            href="{{ 'https://cdn.Trendysongz.com/audio/' . $artist->TrackUrl }}"
                            class="ts-download-button"
                            download
                        >
                            <i class="fa fa-download"></i>
                            Download MP3
                        </a>

                    </div>

                @elseif(!empty($artist->track_url_2))

                    <a
                        href="{{ $artist->track_url_2 }}"
                        class="ts-download-button"
                        target="_blank"
                        rel="noopener"
                    >
                        <i class="fa fa-download"></i>
                        Download MP3
                    </a>

                @endif

            </div>

        </div>

    </section>


    {{-- =====================================================
         ABOUT THE SONG
    ====================================================== --}}
    @if(
        !empty($artist->introduction) ||
        !empty($artist->TrackInfo) ||
        !empty($artist->trackinfo1)
    )

        <section class="ts-about-song">

            <div class="ts-about-song-inner">

                <h2 class="ts-about-heading">
                    About the Song
                </h2>

                <div class="ts-about-text">

                    @if(!empty($artist->introduction))
                        {!! $artist->introduction !!}
                    @endif

                    @if(!empty($artist->TrackInfo))
                        {!! $artist->TrackInfo !!}
                    @endif

                    @if(!empty($artist->trackinfo1))
                        {!! $artist->trackinfo1 !!}
                    @endif

                </div>

            </div>

        </section>

    @endif




</div>
 


<!-- =========================================================
     SHARE SONG
========================================================= -->

<div class="ts-share-section">

    <div class="ts-share-heading">
        <span class="ts-share-heading-line"></span>

        <span class="ts-share-heading-text">
            SHARE
        </span>

        <span class="ts-share-heading-line"></span>
    </div>


    <p class="ts-share-description">
        Share “{{ $title }}” with your friends
    </p>


    <div class="ts-share-actions">

        {{-- COPY LINK --}}
        <button
            type="button"
            class="ts-share-copy"
            id="tsCopyShareButton"
            aria-label="Copy song link"
        >
            <span class="ts-copy-icon">
                <i class="fa fa-link" aria-hidden="true"></i>
            </span>

            <span
                class="ts-copy-label"
                id="tsCopyLabel"
            >
                Copy link
            </span>
        </button>


        {{-- ADDTOANY --}}
        <div class="a2a_kit ts-share-socials">

            <a class="a2a_button_whatsapp" title="Share on WhatsApp" aria-label="Share on WhatsApp" ></a>

            <a class="a2a_button_facebook"  title="Share on Facebook" aria-label="Share on Facebook" ></a>

            <a class="a2a_button_x" title="Share on X" aria-label="Share on X" ></a>

            <a class="a2a_button_email" title="Share by Email" aria-label="Share by Email" ></a>

        </div>

    </div>

</div>
<script async src="https://static.addtoany.com/menu/page.js"></script>

<!-- End of ADD TO Any-->

{{-- =========================================================
     ARTIST OTHER SONGS
========================================================= --}}

<section class="ts-related-section">

    <div class="ts-related-heading">
        <h2>
            Download {{ $artist->ArtistsName }} Other Songs
        </h2>
    </div>


    <div class="ts-music-list">

        @foreach($audio as $row)

            @php

                $artistSlug = strtolower(
                    str_replace(
                        ')',
                        '',
                        str_replace(
                            '(',
                            '',
                            str_replace(
                                ' ',
                                '-',
                                $row->ArtistsName
                            )
                        )
                    )
                );

                $titleSlug = strtolower(
                    str_replace(
                        ')',
                        '',
                        str_replace(
                            '(',
                            '',
                            str_replace(
                                ' ',
                                '-',
                                $row->Title
                            )
                        )
                    )
                );

            @endphp


            <article class="ts-music-list-item">

                <a
                    href="{{ route('pages.musics_detail', [
                        $row->id,
                        $artistSlug,
                        $titleSlug
                    ]) }}"
                    class="ts-music-list-link"
                >

                    <div class="ts-music-list-cover">

                        <img
                            src="{{ asset('/images/' . $row->CoverUrl) }}"
                            alt="{{ $row->ArtistsName }} - {{ $row->Title }}"
                            loading="lazy"
                        >

                    </div>


                    <div class="ts-music-list-info">

                        <h3 class="ts-music-list-artist">
                            {{ $row->ArtistsName }}
                        </h3>

                        <div class="ts-collaboration-title-row">

                            <p class="ts-music-list-title">
                                {{ $row->TrackTitle }}
                            </p>
                        
                            @if(!empty($row->Featuring))
                        
                                <span class="ts-music-list-featuring">
                                    <span>feat.</span>
                                    {{ $row->Featuring }}
                                </span>
                        
                            @endif
                        
                        </div>

                        @if(!empty($row->producedby))

                            <p class="ts-music-list-producer">
                                <span>Produced by</span>
                                {{ $row->producedby }}
                            </p>

                        @endif

                    </div>


                    <div class="ts-music-list-actions">

                        <span
                            class="ts-music-list-action"
                            title="Play"
                            aria-label="Play"
                        >
                            <i class="fa fa-play"></i>
                        </span>

                        <span
                            class="ts-music-list-action"
                            title="Download"
                            aria-label="Download"
                        >
                            <i class="fa fa-download"></i>
                        </span>

                    </div>

                </a>

            </article>

        @endforeach

    </div>


    {{-- VIEW ALL BUTTON --}}

    <div class="ts-section-button">

        <a href="{{ route(
            'pages.artists_details',
            strtolower(str_replace(' ', '-', $artist->ArtistsName))
        ) }}">

            View All {{ $artist->ArtistsName }} Songs

            <i class="fa fa-arrow-right"></i>

        </a>

    </div>

</section>

{{-- =========================================================
DOWNLOAD ARTIST VIDEOS
========================================================== --}}

@if(count($recent_video) != 0)


<section class="ts-artist-videos">


    {{-- =================================================
         PAGE HEADING
    ================================================== --}}

    <div class="ts-section-heading ts-video-page-heading">

        <h2>
            Download {{ $artist->ArtistsName }} Videos
        </h2>

    </div>


    {{-- =================================================
         VIDEO LIST
    ================================================== --}}

    <div class="ts-video-list">

        @foreach($recent_video as $row)

            @php

                /*
                |--------------------------------------------------------------------------
                | Artist slug
                |--------------------------------------------------------------------------
                */

                $artistSlug = strtolower(
                    str_replace(
                        ')',
                        '',
                        str_replace(
                            '(',
                            '',
                            str_replace(
                                ' ',
                                '-',
                                $row->ArtistsName
                            )
                        )
                    )
                );


                /*
                |--------------------------------------------------------------------------
                | Video title slug
                |--------------------------------------------------------------------------
                */

                $videoSlug = strtolower(
                    str_replace(
                        ')',
                        '',
                        str_replace(
                            '(',
                            '',
                            str_replace(
                                ' ',
                                '-',
                                $row->Title
                            )
                        )
                    )
                );

            @endphp


            {{-- =================================================
                 VIDEO ITEM
            ================================================== --}}

            <a
                href="{{ route('pages.videos_detail', [
                    $row->id,
                    $artistSlug,
                    $videoSlug
                ]) }}"
                class="ts-video-list-item"
            >


                {{-- =================================================
                     COVER
                ================================================== --}}

                <div class="ts-video-list-cover">

                    @if(!empty($row->CoverUrl))

                        <img
                            src="{{ asset('/images/' . ltrim($row->CoverUrl, '/')) }}"
                            alt="{{ $row->ArtistsName }} - {{ $row->Title }}"
                            loading="lazy"
                        >

                    @endif


                    {{-- PLAY BUTTON --}}

                    <span
                        class="ts-video-list-play"
                        aria-hidden="true"
                    >

                        <svg
                            viewBox="0 0 24 24"
                            fill="currentColor"
                        >
                            <path d="M8 5v14l11-7z"/>
                        </svg>

                    </span>

                </div>


                {{-- =================================================
                     VIDEO INFORMATION
                ================================================== --}}

                <div class="ts-video-list-info">


                    {{-- ARTIST --}}

                    <h2 class="ts-video-list-artist">
                        {{ $row->ArtistsName }}
                    </h2>


                    {{-- VIDEO TITLE --}}

                    <p class="ts-video-list-title">
                        {{ $row->TrackTitle ?? $row->Title }}
                    </p>


                    {{-- DIRECTOR --}}

                    @if(!empty($row->directedby))

                        <p class="ts-video-list-directed">

                            <span>Directed by</span>

                            {{ $row->directedby }}

                        </p>

                    @endif


                </div>


            </a>

        @endforeach

    </div>


    {{-- =================================================
         VIEW ALL ARTIST SONGS
    ================================================== --}}

    <div class="ts-section-button">

        <a href="{{ route(
            'pages.artists_details',
            strtolower(str_replace(' ', '-', $artist->ArtistsName))
        ) }}">

            View All {{ $artist->ArtistsName }} Songs

            <i class="fa fa-arrow-right"></i>

        </a>

    </div>


</section>


@endif
<br>

{{-- =========================================================
     ARTIST ALBUMS
========================================================= --}}

@if($recent_album && $recent_album->count())

    <section class="ts-artists-albums">

        {{-- =====================================================
             SECTION HEADING
        ====================================================== --}}

        <div class="ts-section-heading ts-artists-albums-heading">

            <h2>
                 Download {{ $artist->ArtistsName }} albums and EPs
            </h2>


        </div>


        {{-- =====================================================
             ALBUM LIST
        ====================================================== --}}

        <div class="ts-artists-albums-list">

            @foreach($recent_album as $row)

                @php

                    /*
                    |--------------------------------------------------------------------------
                    | ARTIST NAME
                    |--------------------------------------------------------------------------
                    */

                    $artistName = $artist->Stage_Name
                        ?: $artist->ArtistsName;


                    /*
                    |--------------------------------------------------------------------------
                    | ARTIST SLUG
                    |--------------------------------------------------------------------------
                    */

                    $artistSlug = strtolower(
                        str_replace(
                            ')',
                            '',
                            str_replace(
                                '(',
                                '',
                                str_replace(
                                    ' ',
                                    '-',
                                    $artistName
                                )
                            )
                        )
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | ALBUM SLUG
                    |--------------------------------------------------------------------------
                    */

                    $albumSlug = strtolower(
                        str_replace(
                            ')',
                            '',
                            str_replace(
                                '(',
                                '',
                                str_replace(
                                    ' ',
                                    '-',
                                    $row->title
                                )
                            )
                        )
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | ALBUM URL
                    |--------------------------------------------------------------------------
                    */

                    $albumUrl = route('pages.album_details', [
                        $row->id,
                        $artistSlug,
                        $albumSlug
                    ]);


                    /*
                    |--------------------------------------------------------------------------
                    | TRACK COUNT
                    |--------------------------------------------------------------------------
                    */

                    $trackCount = (int) ($row->track_count ?? 0);


                    /*
                    |--------------------------------------------------------------------------
                    | RELEASE DATE
                    |--------------------------------------------------------------------------
                    */

                    $releaseDate = !empty($row->released_date)
                        ? \Carbon\Carbon::parse($row->released_date)->format('M d, Y')
                        : null;

                @endphp


                {{-- =================================================
                     ALBUM CARD
                ================================================== --}}

                <a
                    href="{{ $albumUrl }}"
                    class="ts-artist-album-card"
                    aria-label="View {{ $row->title }} by {{ $artistName }}"
                >


                    {{-- =================================================
                         ALBUM COVER
                    ================================================== --}}

                    <div class="ts-artist-album-cover">

                        <img
                            src="{{ asset('/images/' . ltrim($row->cover_url, '/')) }}"
                            alt="{{ $row->title }} by {{ $artistName }}"
                            loading="lazy"
                        >


                        {{-- PLAY / VIEW OVERLAY --}}

                        <span
                            class="ts-artist-album-cover-overlay"
                            aria-hidden="true"
                        >

                            <svg
                                viewBox="0 0 24 24"
                                fill="currentColor"
                            >

                                <path d="M8 5v14l11-7z"/>

                            </svg>

                        </span>

                    </div>


                    {{-- =================================================
                         ALBUM INFORMATION
                    ================================================== --}}

                    <div class="ts-artist-album-details">


                        {{-- ARTIST --}}

                        <span class="ts-artist-album-artist">

                            {{ $artistName }}

                        </span>


                        {{-- ALBUM TITLE --}}

                        <span class="ts-artist-album-title">

                            {{ $row->title }}

                            @if(!empty($row->featuring))

                                <span class="ts-artist-album-featuring">

                                    ft {{ $row->featuring }}

                                </span>

                            @endif

                        </span>


                        {{-- =================================================
                             ALBUM META
                        ================================================== --}}

                        <div class="ts-artist-album-meta">


                            {{-- TRACK COUNT --}}

                            <div class="ts-artist-album-meta-row">

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    aria-hidden="true"
                                >

                                    <path d="M9 18V5l11-2v13"/>

                                    <circle
                                        cx="6"
                                        cy="18"
                                        r="3"
                                    />

                                    <circle
                                        cx="17"
                                        cy="16"
                                        r="3"
                                    />

                                </svg>

                                <span>

                                    {{ $trackCount }}

                                    {{ $trackCount == 1 ? 'Track' : 'Tracks' }}

                                </span>

                            </div>


                          


                            {{-- RELEASE DATE --}}

                            @if($releaseDate)

                                <div class="ts-artist-album-meta-row">

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        aria-hidden="true"
                                    >
    
                                        <rect
                                            x="3"
                                            y="4"
                                            width="18"
                                            height="18"
                                            rx="2"
                                        />
    
                                        <path d="M16 2v4M8 2v4M3 10h18"/>
    
                                    </svg>

                                    <span>

                                        {{ $releaseDate }}

                                    </span>

                                </div>

                            @endif


                        </div>

                    </div>

                </a>

            @endforeach

        </div>


    </section>

@endif

<br>

{{-- =========================================================
     ARTIST VARIOUS COLLABORATIONS
========================================================= --}}

@if($recent_featuring && $recent_featuring->count())

<section class="ts-related-section ts-collaborations-section">

    {{-- =====================================================
         SECTION HEADING
    ====================================================== --}}

    <div class="ts-related-heading">

        <h2>
            Download {{ $artist->ArtistsName }} Featurings
        </h2>

    </div>


    {{-- =====================================================
         COLLABORATION LIST
    ====================================================== --}}

    <div class="ts-music-list">

        @foreach($recent_featuring as $row)

            @php

                /*
                |--------------------------------------------------------------------------
                | ARTIST SLUG
                |--------------------------------------------------------------------------
                */

                $artistSlug = strtolower(
                    str_replace(
                        ')',
                        '',
                        str_replace(
                            '(',
                            '',
                            str_replace(
                                ' ',
                                '-',
                                $row->ArtistsName
                            )
                        )
                    )
                );


                /*
                |--------------------------------------------------------------------------
                | TITLE SLUG
                |--------------------------------------------------------------------------
                */

                $titleSlug = strtolower(
                    str_replace(
                        ')',
                        '',
                        str_replace(
                            '(',
                            '',
                            str_replace(
                                ' ',
                                '-',
                                $row->Title
                            )
                        )
                    )
                );


            @endphp


            {{-- =================================================
                 COLLABORATION ITEM
            ================================================== --}}

            <article class="ts-music-list-item">

                <a
                    href="{{ route('pages.musics_detail', [
                        $row->id,
                        $artistSlug,
                        $titleSlug
                    ]) }}"
                    class="ts-music-list-link"
                >


                    {{-- =================================================
                         COVER
                    ================================================== --}}

                    <div class="ts-music-list-cover">

                        <img
                            src="{{ asset('/images/' . ltrim($row->CoverUrl, '/')) }}"
                            alt="{{ $row->ArtistsName }} - {{ $row->TrackTitle }}"
                            loading="lazy"
                        >

                    </div>


                    {{-- =================================================
                         COLLABORATION INFORMATION
                    ================================================== --}}

                    <div class="ts-music-list-info">


                        {{-- COLLABORATING ARTIST --}}

                        <h3 class="ts-music-list-artist">

                            {{ $row->ArtistsName }}

                        </h3>


                        {{-- =================================================
                             TITLE + FEATURING
                        ================================================== --}}

                        <div class="ts-collaboration-title-row">

                            <p class="ts-music-list-title">

                                {{ $row->TrackTitle }}

                            </p>


                            <span class="ts-music-list-featuring">

                                <span>feat.</span>

                                {{ $artist->ArtistsName }}

                            </span>

                        </div>


                        {{-- =================================================
                             PRODUCER
                        ================================================== --}}

                        @if(!empty($row->producedby))

                            <p class="ts-music-list-producer">

                                <span>Produced by</span>

                                {{ $row->producedby }}

                            </p>

                        @endif


                    </div>


                    {{-- =================================================
                         ACTIONS
                    ================================================== --}}

                    <div class="ts-music-list-actions">

                        <span
                            class="ts-music-list-action"
                            title="Play"
                            aria-label="Play"
                        >

                            <i class="fa fa-play"></i>

                        </span>


                        <span
                            class="ts-music-list-action"
                            title="Download"
                            aria-label="Download"
                        >

                            <i class="fa fa-download"></i>

                        </span>

                    </div>


                </a>

            </article>

        @endforeach

    </div>


</section>

@endif

<br>
    
{{-- =========================================================
     LATEST MUSIC MP3 & VIDEOS
========================================================= --}}

@if($music && $music->count())

<section class="ts-related-section ts-latest-music-section">

    {{-- =====================================================
         SECTION HEADING
    ====================================================== --}}

    <div class="ts-related-heading">

        <h2>
            Download Latest Music MP3 & Videos
        </h2>

    </div>


    {{-- =====================================================
         LATEST MUSIC LIST
    ====================================================== --}}

    <div class="ts-music-list">

        @foreach($music as $row)

            @php

                /*
                |--------------------------------------------------------------------------
                | ARTIST SLUG
                |--------------------------------------------------------------------------
                */

                $artistSlug = strtolower(
                    str_replace(
                        ')',
                        '',
                        str_replace(
                            '(',
                            '',
                            str_replace(
                                ' ',
                                '-',
                                $row->ArtistsName
                            )
                        )
                    )
                );


                /*
                |--------------------------------------------------------------------------
                | TITLE SLUG
                |--------------------------------------------------------------------------
                */

                $titleSlug = strtolower(
                    str_replace(
                        ')',
                        '',
                        str_replace(
                            '(',
                            '',
                            str_replace(
                                ' ',
                                '-',
                                $row->Title
                            )
                        )
                    )
                );


                /*
                |--------------------------------------------------------------------------
                | LISTING TYPE
                |--------------------------------------------------------------------------
                */

                $isAudio = strtolower($row->ListingType) === 'audio';

            @endphp


            {{-- =================================================
                 AUDIO
            ================================================== --}}

            @if($isAudio)

                <article class="ts-music-list-item">

                    <a
                        href="{{ route('pages.musics_detail', [
                            $row->id,
                            $artistSlug,
                            $titleSlug
                        ]) }}"
                        class="ts-music-list-link"
                    >


                        {{-- COVER --}}

                        <div class="ts-music-list-cover">

                            <img
                                src="{{ asset('/images/' . ltrim($row->CoverUrl, '/')) }}"
                                alt="{{ $row->ArtistsName }} - {{ $row->TrackTitle }}"
                                loading="lazy"
                            >

                        </div>


                        {{-- INFORMATION --}}

                        <div class="ts-music-list-info">


                            {{-- ARTIST --}}

                            <h3 class="ts-music-list-artist">

                                {{ $row->ArtistsName }}

                            </h3>


                            {{-- TITLE + FEATURING --}}

                            <div class="ts-collaboration-title-row">

                                <p class="ts-music-list-title">

                                    {{ $row->TrackTitle }}

                                </p>


                                @if(!empty($row->Featuring))

                                    <span class="ts-music-list-featuring">

                                        <span>feat.</span>

                                        {{ $row->Featuring }}

                                    </span>

                                @endif

                            </div>


                            {{-- PRODUCER --}}

                            @if(!empty($row->producedby))

                                <p class="ts-music-list-producer">

                                    <span>Produced by</span>

                                    {{ $row->producedby }}

                                </p>

                            @endif


                        </div>


                        {{-- ACTIONS --}}

                        <div class="ts-music-list-actions">

                            <span
                                class="ts-music-list-action"
                                title="Play"
                                aria-label="Play"
                            >
                                <i class="fa fa-play"></i>
                            </span>


                            <span
                                class="ts-music-list-action"
                                title="Download"
                                aria-label="Download"
                            >
                                <i class="fa fa-download"></i>
                            </span>

                        </div>

                    </a>

                </article>


            {{-- =================================================
                 VIDEO
            ================================================== --}}

            @else

                <a
                    href="{{ route('pages.videos_detail', [
                        $row->id,
                        $artistSlug,
                        $titleSlug
                    ]) }}"
                    class="ts-video-list-item"
                >


                    {{-- =================================================
                         VIDEO COVER
                    ================================================== --}}

                    <div class="ts-video-list-cover">

                        @if(!empty($row->CoverUrl))

                            <img
                                src="{{ asset('/images/' . ltrim($row->CoverUrl, '/')) }}"
                                alt="{{ $row->ArtistsName }} - {{ $row->TrackTitle }}"
                                loading="lazy"
                            >

                        @endif


                        {{-- PLAY BUTTON --}}

                        <span
                            class="ts-video-list-play"
                            aria-hidden="true"
                        >

                            <svg
                                viewBox="0 0 24 24"
                                fill="currentColor"
                            >
                                <path d="M8 5v14l11-7z"/>
                            </svg>

                        </span>

                    </div>


                    {{-- =================================================
                         VIDEO INFORMATION
                    ================================================== --}}

                    <div class="ts-video-list-info">


                        {{-- ARTIST --}}

                        <h2 class="ts-video-list-artist">

                            {{ $row->ArtistsName }}

                        </h2>


                        {{-- VIDEO TITLE --}}

                        <p class="ts-video-list-title">

                            {{ $row->TrackTitle ?? $row->Title }}

                        </p>


                        {{-- DIRECTOR --}}

                        @if(!empty($row->directedby))

                            <p class="ts-video-list-directed">

                                <span>Directed by</span>

                                {{ $row->directedby }}

                            </p>

                        @endif


                    </div>


                </a>

            @endif

        @endforeach

    </div>


    {{-- =====================================================
         VIEW ALL BUTTON
    ====================================================== --}}

    <div class="ts-section-button">

        <a href="{{ route('pages.musics', [
            'country' => $artist->country_id
        ]) }}">
    
            View All Latest Music
    
            <i class="fa fa-arrow-right"></i>
    
        </a>
    
    </div>

</section>

@endif


{{-- =========================================================
     COMMENTS SECTION
========================================================= --}}

<section class="ts-comments-section" id="comments">

    {{-- =====================================================
         COMMENTS HEADER
    ====================================================== --}}

    <div class="ts-comments-heading">

        <div class="ts-comments-heading-left">

            <span class="ts-comments-icon">
                <i class="fa fa-comments"></i>
            </span>

            <div>
                <h3>Comments</h3>

                <span>
                    {{ $comment_count }}
                    {{ $comment_count == 1 ? 'Comment' : 'Comments' }}
                </span>
            </div>

        </div>

    </div>


    {{-- =====================================================
         EXISTING COMMENTS
    ====================================================== --}}

    <div class="ts-comments-list">

        @forelse($comment as $comments)

            <div class="ts-comment-item">

                <div class="ts-comment-avatar">
                    <i class="fa fa-user"></i>
                </div>

                <div class="ts-comment-content">

                    <div class="ts-comment-top">
                        <strong>
                            {{ $comments->name }}
                        </strong>
                    </div>

                    <div class="ts-comment-text">
                        {{ $comments->coments }}
                    </div>

                </div>

            </div>

        @empty

            <div class="ts-no-comments">

                <div class="ts-no-comments-icon">
                    <i class="fa fa-comment-o"></i>
                </div>

                <strong>No comments yet</strong>

                <span>
                    Be the first to share your thoughts.
                </span>

            </div>

        @endforelse

    </div>


    {{-- =====================================================
         REUSABLE COMMENT FORM
    ====================================================== --}}

    @include('comments.form', [
        'contentId'   => $artist->id,
        'categoryId'  => 3,
        'contentType' => 'music'
    ])

</section>


{{-- =========================================================
     MUSIC AUDIO PLAYER SCRIPT
========================================================= --}}
@if(!empty($artist->TrackUrl))

<script>
document.addEventListener('DOMContentLoaded', function () {

    const audio = document.getElementById('myaudio');
    const playButton = document.getElementById('tsPlayButton');
    const playIcon = document.getElementById('tsPlayIcon');
    const playLabel = document.getElementById('tsPlayLabel');

    const progress = document.getElementById('tsAudioProgress');

    const currentTimeElement =
        document.getElementById('tsCurrentTime');

    const durationElement =
        document.getElementById('tsDuration');


    /*
    |--------------------------------------------------------------------------
    | SAFETY CHECK
    |--------------------------------------------------------------------------
    */

    if (
        !audio ||
        !playButton ||
        !playIcon ||
        !progress ||
        !currentTimeElement ||
        !durationElement
    ) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | FORMAT TIME
    |--------------------------------------------------------------------------
    */

    function formatTime(seconds) {

        if (!isFinite(seconds) || seconds < 0) {
            return '0:00';
        }

        const minutes = Math.floor(seconds / 60);

        const remainingSeconds =
            Math.floor(seconds % 60);

        return minutes + ':' +
            String(remainingSeconds).padStart(2, '0');
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE PLAY BUTTON
    |--------------------------------------------------------------------------
    */

    function setPlayingState(isPlaying) {

        if (isPlaying) {

            playIcon.classList.remove('fa-play');
            playIcon.classList.add('fa-pause');

            playButton.setAttribute(
                'aria-label',
                'Pause song'
            );

            playButton.setAttribute(
                'aria-pressed',
                'true'
            );

            if (playLabel) {
                playLabel.textContent = 'Pause this song';
            }

        } else {

            playIcon.classList.remove('fa-pause');
            playIcon.classList.add('fa-play');

            playButton.setAttribute(
                'aria-label',
                'Play song'
            );

            playButton.setAttribute(
                'aria-pressed',
                'false'
            );

            if (playLabel) {
                playLabel.textContent = 'Listen to this song';
            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | PLAY / PAUSE
    |--------------------------------------------------------------------------
    */

    playButton.addEventListener('click', function () {

        if (audio.paused) {

            const playPromise = audio.play();

            if (playPromise !== undefined) {

                playPromise
                    .then(function () {

                        setPlayingState(true);

                    })
                    .catch(function (error) {

                        console.error(
                            'Unable to play audio:',
                            error
                        );

                        setPlayingState(false);

                    });
            }

        } else {

            audio.pause();

            setPlayingState(false);
        }

    });


    /*
    |--------------------------------------------------------------------------
    | AUDIO PLAY EVENT
    |--------------------------------------------------------------------------
    */

    audio.addEventListener('play', function () {

        setPlayingState(true);

    });


    /*
    |--------------------------------------------------------------------------
    | AUDIO PAUSE EVENT
    |--------------------------------------------------------------------------
    */

    audio.addEventListener('pause', function () {

        setPlayingState(false);

    });


    /*
    |--------------------------------------------------------------------------
    | AUDIO METADATA LOADED
    |--------------------------------------------------------------------------
    */

    audio.addEventListener('loadedmetadata', function () {

        if (isFinite(audio.duration)) {

            durationElement.textContent =
                formatTime(audio.duration);

            progress.max = audio.duration;

        }

    });


    /*
    |--------------------------------------------------------------------------
    | UPDATE PROGRESS WHILE PLAYING
    |--------------------------------------------------------------------------
    */

    audio.addEventListener('timeupdate', function () {

        if (!isFinite(audio.duration)) {
            return;
        }

        currentTimeElement.textContent =
            formatTime(audio.currentTime);

        durationElement.textContent =
            formatTime(audio.duration);

        progress.max = audio.duration;

        progress.value = audio.currentTime;

    });


    /*
    |--------------------------------------------------------------------------
    | SEEK
    |--------------------------------------------------------------------------
    */

    progress.addEventListener('input', function () {

        if (!isFinite(audio.duration)) {
            return;
        }

        audio.currentTime = Number(progress.value);

    });


    /*
    |--------------------------------------------------------------------------
    | SONG ENDED
    |--------------------------------------------------------------------------
    */

    audio.addEventListener('ended', function () {

        audio.currentTime = 0;

        progress.value = 0;

        currentTimeElement.textContent = '0:00';

        setPlayingState(false);
        
        setTimeout(function () {
            audio.play();
        }, 2000);

    });


    /*
    |--------------------------------------------------------------------------
    | AUDIO ERROR
    |--------------------------------------------------------------------------
    */

    audio.addEventListener('error', function () {

        setPlayingState(false);

        console.error(
            'The audio file could not be loaded.'
        );

    });


    /*
    |--------------------------------------------------------------------------
    | INITIAL STATE
    |--------------------------------------------------------------------------
    */

    setPlayingState(false);

});
</script>

@endif





@endsection
