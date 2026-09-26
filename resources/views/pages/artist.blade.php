@extends('layouts.app')

@section('title', 'Download ' . 'Latest ' . $artist->ArtistsName . ' Songs, Music, Albums, Biography, Profile, All Music, Videos - TrendySongz')

@section('meta')

<meta  name="description" content="Download {{ $artist->ArtistsName }} Songs, Download Latest {{ $artist->ArtistsName }} Songs, Download Latest {{ $artist->ArtistsName }} Album, Download Latest {{ $artist->ArtistsName }} Music, Read {{ $artist->ArtistsName }} Biography and view his full Discography on TrendySongz"/>
<meta name="keywords" content="{{ $artist->ArtistsName }} songs, download {{ $artist->ArtistsName }} songs, download Latest {{ $artist->ArtistsName }} songs. download {{ $artist->ArtistsName }} Albums, download {{ $artist->ArtistsName }} music,{{ $artist->ArtistsName }} songs download , {{ $artist->ArtistsName }} biography,"/>

@endsection


@section('facebook_meta')
<meta property="og:title" content="{{ $artist->ArtistsName }} - Songs, Albums, Videos & Biography | TrendySongz">
<meta property="og:description" content="Download {{ $artist->ArtistsName }} songs, albums and videos. Read {{ $artist->ArtistsName }} biography and explore the complete discography on TrendySongz.">
<meta property="og:image" content="{{ asset('/images/' . $artist->ProfilePic) }}">
<meta property="og:type" content="profile"><meta property="og:url" content="{{ route('pages.artists_details', strtolower(str_replace(' ', '-', $artist->ArtistsName))) }}">
@endsection

@section('canonical')
<link rel="canonical" href="{{ route('pages.artists_details', strtolower(str_replace(' ', '-', $artist->ArtistsName))) }}"/>
@endsection


@section('twitter_meta')
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:description" content="Download {{ $artist->ArtistsName }} Songs, Download Latest {{ $artist->ArtistsName }} Songs, Download Latest {{ $artist->ArtistsName }} Album, Download {{ $artist->ArtistsName }} Music, Read {{ $artist->ArtistsName }} Biography and view his full Discography on TrendySongz"/>
<meta name="twitter:title" content="{{ $artist->ArtistsName }} - Songs, Albums, Videos & Biography | TrendySongz"/>
<meta name="twitter:image" content="{{ asset('/images/' . $artist->ProfilePic) }}">
@endsection


@section('content')

<div class="ts-artist-detail">


    {{-- =========================================================
         PAGE TITLE
    ========================================================== --}}
    <div class="ts-section-heading ts-video-page-heading">
        <h2 >
            Download Latest {!! $artist->ArtistsName !!} Songs, Albums, Biography, All Music, and Videos on TrendySongz
        </h2>
    
        <time
            class="post-date" datetime="{{ Carbon\Carbon::now()->format('Y-m-d') }}">
            {{ Carbon\Carbon::now()->format('M d, Y') }}
        </time>
    </div>    
        





    {{-- =========================================================
         ARTIST PROFILE
    ========================================================== --}}

    <section class="ts-artist-profile-section">

        <div class="ts-artist-profile-card">


            {{-- =================================================
                 PROFILE IMAGE
            ================================================== --}}

            <div class="ts-artist-profile-image">

                <img
                    src="{{ asset('/images/' . $artist->ProfilePic) }}"
                    alt="{{ $artist->ArtistsName }}"
                    loading="eager"
                >

            </div>



            {{-- =================================================
                 ARTIST INFORMATION
            ================================================== --}}

            <div class="ts-artist-profile-info">

                {{-- Artist Name --}}
            
                <div class="ts-artist-profile-row">
            
                    <span class="ts-artist-profile-label">
                        Real Name
                    </span>
            
                    <span class="ts-artist-profile-value ts-artist-profile-name">
                        {{ $artist->Fullname }}
                    </span>
            
                </div>
            
            
                {{-- Stage Name --}}
            
                @if(!empty($artist->Stage_Name))
            
                    <div class="ts-artist-profile-row">
            
                        <span class="ts-artist-profile-label">
                            Stage Name
                        </span>
            
                        <span class="ts-artist-profile-value">
                            {{ $artist->Stage_Name }}
                        </span>
            
                    </div>
            
                @endif
            
            
                {{-- Genre --}}
            
                @if(!empty($artist->Genres))
            
                    <div class="ts-artist-profile-row">
            
                        <span class="ts-artist-profile-label">
                            Genre
                        </span>
            
                        <span class="ts-artist-profile-value">
                            {{ $artist->Genres }}
                        </span>
            
                    </div>
            
                @endif
            
            
                {{-- Record Label --}}
            
                @if(!empty($artist->RecordLabel))
            
                    <div class="ts-artist-profile-row">
            
                        <span class="ts-artist-profile-label">
                            Record Label
                        </span>
            
                        <span class="ts-artist-profile-value">
                            {{ $artist->RecordLabel }}
                        </span>
            
                    </div>
            
                @endif
            
            </div>

        </div>

    </section>



    {{-- =========================================================
         ARTIST BIOGRAPHY
    ========================================================== --}}

    @if(!empty($artist->ArtistsProfile))
    
        <section class="ts-artist-section ts-artist-about">
    
            <div class="ts-artist-section-heading">
    
                <h2>
                    About {{ $artist->ArtistsName }}
                </h2>
    
            </div>
    
            <div class="ts-artist-about-text">
    
                {!! $artist->Place_Birth !!}
    
            </div>
    
        </section>
    
    @endif


    {{-- =========================================================
         NET WORTH
    ========================================================== --}}

    @if(!empty($networth))

        <section class="ts-artist-section ts-artist-networth">

            <div class="ts-artist-section-heading">

                <h2>
                    {{ $networth->title ?? 'Net Worth' }}
                </h2>

            </div>


            <div class="ts-artist-info-text">

                @if(isset($networth->intro) && !empty($networth->intro))

                    {!! $networth->intro !!}

                @elseif(isset($networth->description) && !empty($networth->description))

                    {!! $networth->description !!}

                @endif

            </div>

        </section>

    @endif



    {{-- =========================================================
         ALBUMS
    ========================================================== --}}

    @if(!$album->isEmpty())

        <section class="ts-artist-section ts-artist-albums-section">

            <div class="ts-artist-section-heading">

                <h2>
                    {{ $artist->ArtistsName }} Albums
                </h2>

            </div>


            <div class="ts-artist-albums">


                @foreach($album as $albumIndex => $albums)


                    @php

                        /*
                        |--------------------------------------------------------------------------
                        | Album tracks
                        |--------------------------------------------------------------------------
                        */

                        $albumTracks = $titles->where(
                            'album_id',
                            $albums->id
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | Album cover
                        |--------------------------------------------------------------------------
                        */

                        $albumCover = null;

                        if (isset($albums->cover_url) && !empty($albums->cover_url)) {

                            $albumCover = $albums->cover_url;

                        } elseif (isset($albums->CoverUrl) && !empty($albums->CoverUrl)) {

                            $albumCover = $albums->CoverUrl;

                        }


                    @endphp


                    <div class="ts-artist-album {{ $albumIndex === 0 ? 'is-open' : '' }}">


                        {{-- =================================================
                             ALBUM HEADER
                        ================================================== --}}

                        <button
                            type="button"
                            class="ts-artist-album-header"
                            aria-expanded="{{ $albumIndex === 0 ? 'true' : 'false' }}"
                        >


                            {{-- Album Cover --}}

                            <div class="ts-artist-album-cover">

                                @if($albumCover)

                                    <img
                                        src="{{ asset('/images/' . $albumCover) }}"
                                        alt="{{ $albums->title }}"
                                        loading="lazy"
                                    >

                                @else

                                    <div class="ts-artist-album-cover-placeholder">

                                        <i class="fa fa-music"></i>

                                    </div>

                                @endif

                            </div>



                            {{-- Album Information --}}

                            <div class="ts-artist-album-header-info">


                                <h3 class="ts-artist-album-title">

                                    {{ $albums->title }}

                                </h3>



                                <div class="ts-artist-album-meta">


                                    <span class="ts-artist-album-meta-item">

                                        <span class="ts-artist-album-meta-label">
                                            Artist
                                        </span>

                                        <span class="ts-artist-album-meta-value">
                                            {{ $artist->ArtistsName }}
                                        </span>

                                    </span>



                                    @if(isset($albums->released_year) && !empty($albums->released_year))

                                        <span class="ts-artist-album-meta-item">

                                            <span class="ts-artist-album-meta-label">
                                                Year
                                            </span>

                                            <span class="ts-artist-album-meta-value">
                                                {{ $albums->released_year }}
                                            </span>

                                        </span>

                                    @endif



                                    <span class="ts-artist-album-meta-item">

                                        <span class="ts-artist-album-meta-label">
                                            Tracks
                                        </span>

                                        <span class="ts-artist-album-meta-value">

                                            {{ $albums->track_count }}

                                        </span>

                                    </span>


                                </div>


                            </div>



                            {{-- Toggle --}}

                            <span
                                class="ts-artist-album-toggle"
                                aria-hidden="true"
                            >

                                <i class="fa fa-chevron-down"></i>

                            </span>


                        </button>



                        {{-- =================================================
                             ALBUM TRACKS
                        ================================================== --}}

                        <div class="ts-artist-album-tracks">


                            @forelse($albumTracks as $title)


                                @php

                                    /*
                                    |--------------------------------------------------------------------------
                                    | Artist slug
                                    |--------------------------------------------------------------------------
                                    */

                                    $trackArtistSlug = strtolower(
                                        str_replace(
                                            ')',
                                            '',
                                            str_replace(
                                                '(',
                                                '',
                                                str_replace(
                                                    ' ',
                                                    '-',
                                                    $title->ArtistsName
                                                )
                                            )
                                        )
                                    );


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Track slug
                                    |--------------------------------------------------------------------------
                                    */

                                    $trackTitleSlug = strtolower(
                                        str_replace(
                                            ')',
                                            '',
                                            str_replace(
                                                '(',
                                                '',
                                                str_replace(
                                                    ' ',
                                                    '-',
                                                    $title->TrackTitle
                                                )
                                            )
                                        )
                                    );


                                    /*
                                    |--------------------------------------------------------------------------
                                    | Audio / Video
                                    |--------------------------------------------------------------------------
                                    */

                                    $isAudio = strtolower(
                                        $title->ListingType
                                    ) === 'audio';


                                    /*
                                    |--------------------------------------------------------------------------
                                    | URL
                                    |--------------------------------------------------------------------------
                                    */

                                    if ($isAudio) {

                                        $trackUrl = route(
                                            'pages.musics_detail',
                                            [
                                                $title->id,
                                                $trackArtistSlug,
                                                $trackTitleSlug
                                            ]
                                        );

                                    } else {

                                        $trackUrl = route(
                                            'pages.videos_detail',
                                            [
                                                $title->id,
                                                $trackArtistSlug,
                                                $trackTitleSlug
                                            ]
                                        );

                                    }

                                @endphp



                                <a
                                    href="{{ $trackUrl }}"
                                    class="ts-artist-track"
                                >


                                    {{-- Track Number --}}

                                    <div class="ts-artist-track-number">

                                        {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}

                                    </div>



                                    {{-- Track Information --}}

                                    <div class="ts-artist-track-info">


                                        <div class="ts-artist-track-title-row">


                                            {{-- Song title --}}

                                            <span class="ts-artist-track-title">

                                                {{ $title->TrackTitle }}

                                            </span>



                                            {{-- Featuring --}}

                                            @if(isset($title->Featuring) && !empty($title->Featuring))

                                                <span class="ts-artist-track-featuring">

                                                    feat. {{ $title->Featuring }}

                                                </span>

                                            @endif


                                        </div>



                                        {{-- Producer / Director --}}

                                        @if(
                                            $isAudio &&
                                            isset($title->producedby) &&
                                            !empty($title->producedby)
                                        )

                                            <div class="ts-artist-track-credit">

                                                Produced by
                                                {{ $title->producedby }}

                                            </div>

                                        @elseif(
                                            !$isAudio &&
                                            isset($title->directedby) &&
                                            !empty($title->directedby)
                                        )

                                            <div class="ts-artist-track-credit">

                                                Directed by
                                                {{ $title->directedby }}

                                            </div>

                                        @endif


                                    </div>



                                    {{-- Arrow --}}

                                    <div class="ts-artist-track-arrow">

                                        <i class="fa fa-angle-right"></i>

                                    </div>


                                </a>


                            @empty


                                <div class="ts-artist-no-tracks">

                                    No tracks available for this album.

                                </div>


                            @endforelse


                        </div>


                    </div>


                @endforeach


            </div>

        </section>

    @endif



    {{-- =========================================================
         SINGLES
    ========================================================== --}}
    
    @if(!$single_songs->isEmpty())
    
        <section class="ts-artist-section ts-artist-singles-section">
    
            <div class="ts-artist-section-heading">
    
                <h2>
                    {{ $artist->ArtistsName }} Singles & Videos
                </h2>
    
            </div>
    
    
            <div class="ts-artist-singles">
    
    
                @foreach($single_songs as $single)
    
    
                    @php
    
                        /*
                        |--------------------------------------------------------------------------
                        | Artist slug
                        |--------------------------------------------------------------------------
                        */
    
                        $singleArtistSlug = strtolower(
                            str_replace(
                                ')',
                                '',
                                str_replace(
                                    '(',
                                    '',
                                    str_replace(
                                        ' ',
                                        '-',
                                        $single->ArtistsName
                                    )
                                )
                            )
                        );
    
    
                        /*
                        |--------------------------------------------------------------------------
                        | Single title slug
                        |--------------------------------------------------------------------------
                        */
    
                        $singleTitleSlug = strtolower(
                            str_replace(
                                ')',
                                '',
                                str_replace(
                                    '(',
                                    '',
                                    str_replace(
                                        ' ',
                                        '-',
                                        $single->TrackTitle
                                    )
                                )
                            )
                        );
    
    
                        /*
                        |--------------------------------------------------------------------------
                        | Audio / Video
                        |--------------------------------------------------------------------------
                        */
    
                        $isAudio = strtolower(
                            $single->ListingType
                        ) === 'audio';
    
    
                        /*
                        |--------------------------------------------------------------------------
                        | URL
                        |--------------------------------------------------------------------------
                        */
    
                        if ($isAudio) {
    
                            $singleUrl = route(
                                'pages.musics_detail',
                                [
                                    $single->id,
                                    $singleArtistSlug,
                                    $singleTitleSlug
                                ]
                            );
    
                        } else {
    
                            $singleUrl = route(
                                'pages.videos_detail',
                                [
                                    $single->id,
                                    $singleArtistSlug,
                                    $singleTitleSlug
                                ]
                            );
    
                        }
    
                    @endphp
    
    
                    <a
                        href="{{ $singleUrl }}"
                        class="ts-artist-track ts-artist-single"
                    >
    
    
                        {{-- =================================================
                             SINGLE / VIDEO IMAGE
                        ================================================== --}}
    
                        <div class="ts-artist-single-image">
    
                            <img
                                src="{{ asset('/images/' . $single->CoverUrl) }}"
                                alt="{{ $artist->ArtistsName }} - {{ $single->TrackTitle }}"
                                loading="lazy"
                            >
    
                        </div>
    
    
                        {{-- =================================================
                             SINGLE INFORMATION
                        ================================================== --}}
    
                        <div class="ts-artist-track-info">

                            <div class="ts-artist-single-artist">
                        
                                {{ $single->ArtistsName }}
                        
                            </div>
                        
                            <div class="ts-artist-track-title-row">
                        
                                <span class="ts-artist-track-title">
                        
                                    {{ $single->TrackTitle }}
                        
                                </span>
    
    
                                @if(isset($single->Featuring) && !empty($single->Featuring))
    
                                    <span class="ts-artist-track-featuring">
    
                                        feat. {{ $single->Featuring }}
    
                                    </span>
    
                                @endif
    
                            </div>
    
    
                            @if(
                                $isAudio &&
                                isset($single->producedby) &&
                                !empty($single->producedby)
                            )
    
                                <div class="ts-artist-track-credit">
    
                                    Produced by
                                    {{ $single->producedby }}
    
                                </div>
    
    
                            @elseif(
                                !$isAudio &&
                                isset($single->directedby) &&
                                !empty($single->directedby)
                            )
    
                                <div class="ts-artist-track-credit">
    
                                    Directed by
                                    {{ $single->directedby }}
    
                                </div>
    
                            @endif
    
    
                        </div>
    
    
                        {{-- =================================================
                             ARROW
                        ================================================== --}}
    
                        <div class="ts-artist-track-arrow">
    
                            <i class="fa fa-angle-right"></i>
    
                        </div>
    
    
                    </a>
    
    
                @endforeach
    
    
            </div>
    
        </section>
    
    @endif


    {{-- =========================================================
         AWARDS
    ========================================================== --}}

    @if(!$award->isEmpty())

        <section class="ts-artist-section">

            <div class="ts-artist-section-heading">

                <h2>
                    {{ $artist->ArtistsName }} Awards
                </h2>

            </div>


            <div class="ts-artist-info-list">

                @foreach($award as $item)

                    <div class="ts-artist-info-item">

                        @if(isset($item->title) && !empty($item->title))

                            <div class="ts-artist-info-item-title">

                                {{ $item->title }}

                            </div>

                        @endif


                        @if(isset($item->description) && !empty($item->description))

                            <div class="ts-artist-info-item-text">

                                {!! $item->description !!}

                            </div>

                        @endif

                    </div>

                @endforeach

            </div>

        </section>

    @endif



    {{-- =========================================================
         ENDORSEMENTS
    ========================================================== --}}

    @if(!$endorsement->isEmpty())

        <section class="ts-artist-section">

            <div class="ts-artist-section-heading">

                <h2>
                    {{ $artist->ArtistsName }} Endorsements
                </h2>

            </div>


            <div class="ts-artist-info-list">

                @foreach($endorsement as $item)

                    <div class="ts-artist-info-item">

                        @if(isset($item->title) && !empty($item->title))

                            <div class="ts-artist-info-item-title">

                                {{ $item->title }}

                            </div>

                        @endif


                        @if(isset($item->description) && !empty($item->description))

                            <div class="ts-artist-info-item-text">

                                {!! $item->description !!}

                            </div>

                        @endif

                    </div>

                @endforeach

            </div>

        </section>

    @endif



    {{-- =========================================================
         LINKS
    ========================================================== --}}

    @if(!$links->isEmpty())

        <section class="ts-artist-section">

            <div class="ts-artist-section-heading">

                <h2>
                    {{ $artist->ArtistsName }} Links
                </h2>

            </div>


            <div class="ts-artist-info-list">

                @foreach($links as $item)

                    <div class="ts-artist-info-item">

                        @if(isset($item->title) && !empty($item->title))

                            <div class="ts-artist-info-item-title">

                                {{ $item->title }}

                            </div>

                        @endif


                        @if(isset($item->description) && !empty($item->description))

                            <div class="ts-artist-info-item-text">

                                {!! $item->description !!}

                            </div>

                        @endif

                    </div>

                @endforeach

            </div>

        </section>

    @endif


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
            Share “{!! $artist->ArtistsName !!}” profile with your friends
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


</div>




{{-- =============================================================
     ADDTOANY
============================================================= --}}






{{-- =============================================================
     ALBUM ACCORDION
============================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const albumHeaders = document.querySelectorAll(
        '.ts-artist-album-header'
    );


    albumHeaders.forEach(function (header) {

        header.addEventListener('click', function () {

            const album = header.closest(
                '.ts-artist-album'
            );

            if (!album) {
                return;
            }


            const isOpen = album.classList.contains(
                'is-open'
            );


            /*
            |--------------------------------------------------------------------------
            | Close every album
            |--------------------------------------------------------------------------
            */

            document
                .querySelectorAll('.ts-artist-album')
                .forEach(function (item) {

                    item.classList.remove(
                        'is-open'
                    );


                    const itemHeader =
                        item.querySelector(
                            '.ts-artist-album-header'
                        );


                    if (itemHeader) {

                        itemHeader.setAttribute(
                            'aria-expanded',
                            'false'
                        );

                    }

                });


            /*
            |--------------------------------------------------------------------------
            | Open selected album
            |--------------------------------------------------------------------------
            */

            if (!isOpen) {

                album.classList.add(
                    'is-open'
                );


                header.setAttribute(
                    'aria-expanded',
                    'true'
                );

            }

        });

    });

});

</script>


@endsection

