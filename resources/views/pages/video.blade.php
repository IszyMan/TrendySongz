

@extends('layouts.app')

@section('title', $title . ' | Download Video MP4 » Trendysongz')


@section('meta')
    <meta name="description" content="{{ $metaDescription }}">

    <meta name="keywords" content="{{ $artist->ArtistsName }} {{ $artist->TrackTitle }}, {{ $title }}, {{ $title }} video, download {{ $title }}, download {{ $title }} mp4, download {{ $artist->ArtistsName }} {{ $artist->Featuring }} {{ $artist->TrackTitle }} Video, download {{ $title }} mp4 download, {{ $artist->ArtistsName }} {{ $artist->Featuring }} {{ $artist->TrackTitle }} download video, {{ $artist->ArtistsName }} {{ $artist->Featuring }} {{ $artist->TrackTitle }} video download">
@endsection

@section('facebook_meta')
    <meta property="og:image" content="{{ asset('/images/' . $artist->CoverUrl) }}">

    <meta property="og:description" content="{{ $metaDescription }}">
@endsection

@section('canonical')
    <link rel="canonical"
        href="{{ route('pages.videos_detail', [
            $artist->id,
            strtolower(str_replace(')', '', str_replace('(', '', str_replace(' ', '-', $artist->ArtistsName)))),
            strtolower(str_replace(')', '', str_replace('(', '', str_replace(' ', '-', $artist->Title))))
        ]) }}"
    />
@endsection

@section('twitter_meta')
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="@Trendysongz">
    <meta name="twitter:title" content="{{ $artist->ArtistsName }} - {{ $artist->TrackTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ asset('/images/' . $artist->CoverUrl) }}">
@endsection


@section('content')

<div class="ts-video-detail">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <header class="ts-video-detail-header">

        <h1 class="ts-video-detail-title">
            {{ $title }}

            @if(!empty($artist->Featuring))
                <span class="ts-video-detail-featuring">
                    feat. {{ $artist->Featuring }}
                </span>
            @endif
        </h1>

        <div class="ts-video-detail-post-meta">

            <a
                href="{{ route(
                    'pages.videos_posted_by',
                    [strtolower(str_replace(' ', '-', $artist->postedby))]
                ) }}"
                class="ts-video-detail-posted-by"
            >
                Posted by:
                <strong>{{ $artist->postedby }}</strong>

                <i class="fa fa-check-circle" aria-hidden="true"></i>
            </a>

            <span class="ts-video-detail-meta-separator">•</span>

            <time
                class="ts-video-detail-date"
                datetime="{{ Carbon\Carbon::now()->format('Y-m-d') }}"
            >
                {{ Carbon\Carbon::now()->format('M d, Y') }}
            </time>

        </div>

    </header>


    {{-- =========================================================
         HIDDEN VIDEO ID
    ========================================================== --}}

    <input
        type="hidden"
        value="{{ $artist->id }}"
        id="music_id"
    />


    {{-- =========================================================
         VIDEO INFORMATION BAR
    ========================================================== --}}

    <div class="ts-video-detail-info-bar">

        {{-- DIRECTED --}}
        <div class="ts-video-detail-info-item">

            <span class="ts-video-detail-info-label">
                Director
            </span>

            <span class="ts-video-detail-info-value">

                @if(!empty($artist->directedby))

                    {{ $artist->directedby }}

                @else

                    —

                @endif

            </span>

        </div>


        {{-- RELEASED --}}
        <div class="ts-video-detail-info-item">

            <span class="ts-video-detail-info-label">
                Released
            </span>

            <span class="ts-video-detail-info-value">

                @if(!empty($artist->YearOfRelease))

                    <a
                        href="{{ route(
                            'pages.videos_released_year',
                            $artist->YearOfRelease
                        ) }}"
                    >
                        {{ $artist->YearOfRelease }}
                    </a>

                @else

                    —

                @endif

            </span>

        </div>


        {{-- COUNTRY --}}
        <div class="ts-video-detail-info-item">

            <span class="ts-video-detail-info-label">
                Country
            </span>

            <span class="ts-video-detail-info-value">

                @if(!empty($artist->country_id))

                    <a
                        href="{{ route(
                            'pages.videos_country',
                            [$artist->country_id]
                        ) }}"
                    >
                        {{ ucwords($artist->country_id) }}
                    </a>

                @else

                    —

                @endif

            </span>

        </div>


        {{-- CATEGORY --}}
        <div class="ts-video-detail-info-item">

            <span class="ts-video-detail-info-label">
                Category
            </span>

            <span class="ts-video-detail-info-value">

                @if($artist->is_video_comedy == 0)

                    <a href="{{ route('pages.videos') }}">
                        Music Video
                    </a>

                @else

                    <a href="{{ url('download-latest-comedy-videos') }}">
                        Comedy Video
                    </a>

                @endif

            </span>

        </div>

    </div>


    {{-- =========================================================
         VIDEO PLAYER
    ========================================================== --}}

    @if(!empty($artist->TrackUrl))

        <section class="ts-video-detail-player-section">

            <div class="ts-video-detail-player">

                <video
                    playsinline
                    controls
                    preload="metadata"
                    id="myvideo"
                    poster="{{ asset('/images/' . $artist->CoverUrl) }}"
                >

                    <source
                        src="{{ 'https://cdn.trendysongz.com/video/' . $artist->TrackUrl }}"
                        type="video/mp4"
                    >

                    Your browser does not support HTML5 video.

                </video>

            </div>

        </section>

    @endif


    {{-- =========================================================
         DOWNLOAD VIDEO
    ========================================================== --}}

    @if(!empty($artist->TrackUrl))

        <div class="ts-video-detail-download-wrap">

            <a
                id="a1"
                href="{{ 'https://cdn.trendysongz.com/video/' . $artist->TrackUrl }}"
                class="ts-video-detail-download"
                download
            >

                <i class="fa fa-download" aria-hidden="true"></i>

                <span>
                    Download Video
                </span>

            </a>

        </div>

    @endif


    {{-- =========================================================
         ADDITIONAL DOWNLOAD LINK
    ========================================================== --}}

    @if(!empty($artist->track_url_2))

        <div class="ts-video-detail-extra-download">

            <a
                href="{{ $artist->track_url_2 }}"
                target="_blank"
                rel="noopener noreferrer"
            >
                <i class="fa fa-download" aria-hidden="true"></i>
                Download {{ $title }}
            </a>

        </div>

    @endif


    {{-- =========================================================
         ABOUT / INTRODUCTION
    ========================================================== --}}

    @if(!empty($artist->introduction))

        <section class="ts-video-detail-section ts-video-detail-about">

            <h2 class="ts-video-detail-section-heading">
                About the Video
            </h2>

            <div class="ts-video-detail-about-text">
                {!! $artist->introduction !!}
            </div>

        </section>

    @endif


    {{-- =========================================================
         TRACK INFORMATION
    ========================================================== --}}

    @if(!empty($artist->TrackInfo) || !empty($artist->trackinfo1))

        <section class="ts-video-detail-section ts-video-detail-information">

           

            @if(!empty($artist->TrackInfo))

                <div class="ts-video-detail-info-text">
                    {!! $artist->TrackInfo !!}
                </div>

            @endif

            @if(!empty($artist->trackinfo1))

                <div class="ts-video-detail-info-text">
                    {!! $artist->trackinfo1 !!}
                </div>

            @endif

        </section>

    @endif




    {{-- =========================================================
         VIDEO NOT AVAILABLE
    ========================================================== --}}

    @if(empty($artist->TrackUrl) && empty($additional_link))

        <div class="ts-video-detail-notice">

            This video will be released tonight.
            Please check back in a few hours.

        </div>

    @endif


    {{-- =========================================================
         ADDITIONAL AUDIO LINK
    ========================================================== --}}

    @if(!empty($additional_link))

        <section class="ts-video-detail-audio-link">

            <span>
                Also available:
            </span>

            <a
                href="{{ route(
                    'pages.musics_detail',
                    [
                        $additional_link->id,
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
                                        $additional_link->ArtistsName
                                    )
                                )
                            )
                        ),
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
                                        $additional_link->Title
                                    )
                                )
                            )
                        )
                    ]
                ) }}"
            >
                Download Audio:
                {{ $additional_link->ArtistsName }}
                {{ $additional_link->Title }}
                {{ $type }}
            </a>

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
            Share “{{ $title }}” with your friends now
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
         MORE VIDEOS BY ARTIST
    ========================================================== --}}

    @if(count($video) > 0)

        <section class="ts-video-detail-section ts-video-detail-related">

            <h2 class="ts-video-detail-section-heading">
                More {{ $artist->ArtistsName }} Videos
            </h2>


            <div class="ts-video-detail-related-list">

                @foreach($video as $row)

                    <a
                        href="{{ route(
                            'pages.videos_detail',
                            [
                                $row->id,
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
                                                $row->ArtistsName
                                            )
                                        )
                                    )
                                ),
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
                                                $row->Title
                                            )
                                        )
                                    )
                                )
                            ]
                        ) }}"
                        class="ts-video-detail-related-item"
                    >

                        <div class="ts-video-detail-related-cover">

                            <img
                                src="{{ asset('/images/' . $row->CoverUrl) }}"
                                alt="{{ $row->ArtistsName }} - {{ $row->TrackTitle }}"
                                loading="lazy"
                            >

                            <span class="ts-video-detail-related-play">
                                <i class="fa fa-play"></i>
                            </span>

                        </div>


                        <div class="ts-video-detail-related-info">

                            <h3>
                                {{ $row->ArtistsName }}
                            </h3>

                            <p>
                                {{ $row->TrackTitle }}
                            </p>

                            @if(!empty($row->directedby))

                                <span>
                                    Directed by {{ $row->directedby }}
                                </span>

                            @endif

                        </div>

                    </a>

                @endforeach

            </div>

        </section>

    @endif
    
    
    {{-- =========================================================
         ARTIST OTHER SONGS
    ========================================================= --}}
    
    @if($audio && $audio->count())
    
    <section class="ts-related-section ts-video-detail-related">
    
        <div>
            <h2 class="ts-video-detail-section-heading">
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
                                str_replace(' ', '-', $row->ArtistsName)
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
                                str_replace(' ', '-', $row->Title)
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
                                src="{{ asset('/images/' . ltrim($row->CoverUrl, '/')) }}"
                                alt="{{ $row->ArtistsName }} - {{ $row->TrackTitle }}"
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
    
                            <span class="ts-music-list-action">
                                <i class="fa fa-play"></i>
                            </span>
    
                            <span class="ts-music-list-action">
                                <i class="fa fa-download"></i>
                            </span>
    
                        </div>
    
                    </a>
    
                </article>
    
            @endforeach
    
        </div>
    
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


    {{-- =========================================================
         YOU MAY ALSO LIKE
    ========================================================= --}}
    
    @if($music && $music->count())
    
    <section class="ts-related-section ts-video-detail-related">
    
        <div>
            <h2 class="ts-video-detail-section-heading">
                Download Latest Music MP3 & Videos
            </h2>
        </div>
    
    
        <div class="ts-music-list">
    
            @foreach($music as $row)
    
                @php
    
                    $artistSlug = strtolower(
                        str_replace(
                            ')',
                            '',
                            str_replace(
                                '(',
                                '',
                                str_replace(' ', '-', $row->ArtistsName)
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
                                str_replace(' ', '-', $row->Title)
                            )
                        )
                    );
    
                @endphp
    
    
                {{-- =================================================
                     AUDIO
                ================================================== --}}
    
                @if(strtolower($row->ListingType) == 'audio')
    
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
                                    src="{{ asset('/images/' . ltrim($row->CoverUrl, '/')) }}"
                                    alt="{{ $row->ArtistsName }} - {{ $row->TrackTitle }}"
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
    
    
                                {{-- PRODUCED BY --}}
    
                                @if(!empty($row->producedby))
    
                                    <p class="ts-music-list-producer">
    
                                        <span>Produced by</span>
    
                                        {{ $row->producedby }}
    
                                    </p>
    
                                @endif
    
                            </div>
    
    
                            <div class="ts-music-list-actions">
    
                                <span class="ts-music-list-action">
                                    <i class="fa fa-play"></i>
                                </span>
    
                                <span class="ts-music-list-action">
                                    <i class="fa fa-download"></i>
                                </span>
    
                            </div>
    
                        </a>
    
                    </article>
    
    
                {{-- =================================================
                     VIDEO
                ================================================== --}}
    
                @else
    
                    <article class="ts-music-list-item">
    
                        <a
                            href="{{ route('pages.videos_detail', [
                                $row->id,
                                $artistSlug,
                                $titleSlug
                            ]) }}"
                            class="ts-music-list-link"
                        >
    
                            <div class="ts-music-list-cover">
    
                                <img
                                    src="{{ asset('/images/' . ltrim($row->CoverUrl, '/')) }}"
                                    alt="{{ $row->ArtistsName }} - {{ $row->TrackTitle }}"
                                    loading="lazy"
                                >
    
                                <span class="ts-music-list-video-play">
                                    <i class="fa fa-play"></i>
                                </span>
    
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
    
    
                                {{-- PRODUCED BY --}}
    
                                @if(!empty($row->producedby))
    
                                    <p class="ts-music-list-producer">
    
                                        <span>Produced by</span>
    
                                        {{ $row->producedby }}
    
                                    </p>
    
                                @endif
    
    
                                {{-- DIRECTED BY --}}
    
                                @if(!empty($row->directedby))
    
                                    <p class="ts-music-list-producer">
    
                                        <span>Directed by</span>
    
                                        {{ $row->directedby }}
    
                                    </p>
    
                                @endif
    
                            </div>
    
    
                            <div class="ts-music-list-actions">
    
                                <span class="ts-music-list-action">
                                    <i class="fa fa-play"></i>
                                </span>
    
                                <span class="ts-music-list-action">
                                    <i class="fa fa-download"></i>
                                </span>
    
                            </div>
    
                        </a>
    
                    </article>
    
                @endif
    
            @endforeach
    
        </div>
        
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
            'categoryId'  => 4,
            'contentType' => 'video'
        ])
    
    </section>

</div>





@endsection
