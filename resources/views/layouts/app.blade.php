<!DOCTYPE html>
<html lang="en">
<head>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-54KMLTT');</script>
<!-- End Google Tag Manager -->

<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5191608255268104"
     crossorigin="anonymous"></script>



<!-- Default Statcounter code for TrendySongz
http://trendysongz.com -->
<script type="text/javascript">
var sc_project=13357412; 
var sc_invisible=1; 
var sc_security="1b074ead"; 
var sc_remove_link=1; 
</script>
<script type="text/javascript"
src="https://www.statcounter.com/counter/counter.js"
async></script>
<noscript><div class="statcounter"><img class="statcounter"
src="https://c.statcounter.com/13357412/0/1b074ead/1/"
alt="Web Analytics"
referrerPolicy="no-referrer-when-downgrade"></div></noscript>
<!-- End of Statcounter Code -->



<!-- This is pinterest verification code -->
<meta name="p:domain_verify" content="0570c249b6cc3a56722efdceb87dee5e"/>

  <meta http-equiv="Content-Type" content="text/html" charset="utf-8"/>
  @yield('meta')
  <meta name="author" content="Wilfred Moses"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title','Download Latest Nigerian Music, Nigerian Albums, Nigerian Music Videos, News and DJ Mix. ')</title>
  <link rel="shortcut icon" href="{{ asset('/images/Trendysongz-favicon.ico') }}" type="image/x-icon"/>
  <link rel="apple-touch-icon" href="https://trendysongz.com/images/Trendysongz-Favicon-Apple-Logo.png" />
  
  @yield('head')
  
  @stack('styles')
  
   <link rel="stylesheet" href="{{ asset('css/trendysongz.css') }}?v=waveform-5">
  <script src="{{ asset('js/comments.js') }}?v=3" defer></script>
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  
  
  
  @yield('canonical')
  <meta name='robots' content='index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1' />
 
  @yield('facebook_meta')
  @yield('twitter_meta')
  
  
<style>


</style>


<script data-cfasync="false" type="text/javascript" id="clever-core">
    /* <![CDATA[ */
    (function (document, window) {
        var a, c = document.createElement("script"), f = window.frameElement;

        c.id= "CleverCoreLoader103071";
        c.src = "https://scripts.cleverwebserver.com/b5e54b8379a1cec1ea981e47bfb5ada1.js";
        c.async = !0;
        c.type = "text/javascript";
        c.setAttribute("data-target",window.name|| (f && f.getAttribute("id")));
        c.setAttribute("data-callback", "put-your-callback-function-here");
        c.setAttribute("data-callback-url-click", "put-your-click-macro-here");
        c.setAttribute("data-callback-url-view", "put-your-view-macro-here");
        try {
            a = parent.document.getElementsByTagName("script")[0] || document.getElementsByTagName("script")[0];
        } catch (e) {
            a = !1;
        }
        a || (a = document.getElementsByTagName("head")[0] || document.getElementsByTagName("body")[0]);
        a.parentNode.insertBefore(c, a);
    })(document, window);
    /* ]]> */
</script>

</head>




<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-54KMLTT"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->    
    



<div id="fb-root"></div>

    {{-- That's how you write a comment in blade --}}
       <!--Start Banner-->		
	
		
        <!--End of Banner-->
        
        <!--Start Header-->
	{{-- =========================================================
     TRENDYSONGZ HEADER
========================================================= --}}

<header class="ts-header">

    {{-- BRAND --}}
    <div class="ts-brand">

        <a href="{{ url('/') }}" class="ts-logo">
            TRENDYSONGZ
        </a>

        <span class="ts-tagline">
            Nigerian Music Platform
        </span>

    </div>


   

</header>

 {{-- =====================================================
         NAVIGATION
    ====================================================== --}}
    
    @php
        /*
        |--------------------------------------------------------------------------
        | MUSIC ACTIVE STATES
        |--------------------------------------------------------------------------
        | Detect the actual music category from the current URL.
        |--------------------------------------------------------------------------
        */
    
        $currentPath = trim(request()->path(), '/');
    
        $isNaijaMusic = $currentPath === 'musics/naija';
    
        $isGhanaMusic = $currentPath === 'musics/ghana';
    
        $isAfricanMusic = $currentPath === 'musics/african';
    
        /*
        |--------------------------------------------------------------------------
        | PRIMARY MUSIC
        |--------------------------------------------------------------------------
        | Music should NOT be active when Naija, Ghana or African Music
        | is currently selected.
        |--------------------------------------------------------------------------
        */
    
        $isPrimaryMusicActive =
            strpos(Route::currentRouteName(), 'pages.musics') === 0
            && !$isNaijaMusic
            && !$isGhanaMusic
            && !$isAfricanMusic;
    @endphp
    
    
    <nav class="ts-navigation">
    
    
        {{-- =================================================
             FIRST NAVIGATION ROW
        ================================================== --}}
    
        <div class="ts-nav-row ts-nav-row-primary">
    
            {{-- HOME --}}
            <a href="{{ url('/') }}"
               class="{{ request()->is('/') ? 'active' : '' }}">
                <span>Home</span>
            </a>
    
    
            {{-- MUSIC --}}
            <a href="{{ route('pages.music_all') }}"
                class="{{ $isPrimaryMusicActive ? 'active' : '' }}">
                <span>Music</span>
            </a>
    
    
            {{-- VIDEOS --}}
            <a href="{{ route('pages.videos_all') }}"
               class="{{ strpos(Route::currentRouteName(), 'pages.videos') === 0 ? 'active' : '' }}">
                <span>Videos</span>
            </a>
    
    
            {{-- ALBUM / EP --}}
            <a
                href="{{ route('pages.album') }}"
                class="{{ request()->routeIs('pages.album', 'pages.album_details') ? 'active' : '' }}"
            >
                <span>Album<br>/ EP</span>
            </a>
    
    
            {{-- DJ MIX --}}
            <a href="{{ route('pages.djmix') }}"
               class="{{ strpos(Route::currentRouteName(), 'pages.djmix') === 0 ? 'active' : '' }}">
                <span>DJ<br>Mix</span>
            </a>
    
    
            {{-- ARTISTS --}}
            <a href="{{ route('pages.artists') }}"
               class="{{ strpos(Route::currentRouteName(), 'pages.artists') === 0 ? 'active' : '' }}">
                <span>Artists</span>
            </a>
    
    
            {{-- MUSIC NEWS --}}
            <a href="{{ route('pages.celebrity_news') }}"
               class="{{ strpos(Route::currentRouteName(), 'pages.celebrity_news') === 0 ? 'active' : '' }}">
                <span>Music<br>News</span>
            </a>
    
        </div>
    
    
        {{-- =================================================
             SECOND NAVIGATION ROW
        ================================================== --}}
    
        <div class="ts-nav-row ts-nav-row-secondary">
    
            <div class="ts-nav-secondary-inner">
    
    
                {{-- =================================================
                     NAIJA MUSIC
                ================================================== --}}
    
                <a href="{{ route('pages.musics', ['naija']) }}"
                   class="{{ $isNaijaMusic ? 'active' : '' }}">
                    Naija Music
                </a>
    
    
                {{-- =================================================
                     GHANA MUSIC
                ================================================== --}}
    
                <a href="{{ url('/musics/ghana') }}"
                   class="{{ $isGhanaMusic ? 'active' : '' }}">
                    Ghana Music
                </a>
    
    
                {{-- =================================================
                     AFRICAN MUSIC
                ================================================== --}}
    
                <a href="{{ url('/musics/african') }}"
                   class="{{ $isAfricanMusic ? 'active' : '' }}">
                    African Music
                </a>
    
    
                {{-- =================================================
                     SEARCH
                ================================================== --}}
    
                <form method="GET"
                      action="{{ route('pages.search') }}"
                      class="ts-nav-search">
    
                    <input
                        type="text"
                        name="search"
                        placeholder="Search TrendySongz..."
                        autocomplete="off"
                    >
    
                    <button
                        type="button"
                        class="ts-search-toggle"
                        aria-label="Open search"
                        title="Search">
    
                        <span
                            class="ts-search-icon"
                            aria-hidden="true">
                        </span>
    
                        <span class="ts-search-label">
                            Search
                        </span>
    
                    </button>
    
                </form>
    
            </div>
    
        </div>
    
    </nav>
     
    <!--End of Nav-->
    
    
    
   
 
        <div class="ts-layout">

            <main class="ts-main">
                @yield('content')
            </main>
        
            <aside class="ts-aside">
                @include('layouts.aside')
            </aside>
        
        </div>

     
          @include('layouts.footer')
                
   
    
<script>
document.addEventListener('DOMContentLoaded', function () {

    const searchForm = document.querySelector('.ts-nav-search');

    if (!searchForm) {
        return;
    }

    const searchButton = searchForm.querySelector('.ts-search-toggle');
    const searchInput = searchForm.querySelector('input');

    if (!searchButton || !searchInput) {
        return;
    }


    searchButton.addEventListener('click', function (event) {

        const isMobile =
            window.matchMedia('(max-width: 767px)').matches;


        /*
        |--------------------------------------------------------------------------
        | MOBILE
        |--------------------------------------------------------------------------
        */

        if (isMobile && !searchForm.classList.contains('is-open')) {

            event.preventDefault();

            searchForm.classList.add('is-open');

            searchButton.setAttribute(
                'aria-label',
                'Submit search'
            );

            searchButton.setAttribute(
                'title',
                'Submit search'
            );

            searchInput.focus();

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | MOBILE — SEARCH IS ALREADY OPEN
        |--------------------------------------------------------------------------
        */

        if (isMobile && searchForm.classList.contains('is-open')) {

            if (searchInput.value.trim() === '') {

                event.preventDefault();

                searchInput.focus();

                return;
            }

            searchForm.submit();

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | DESKTOP
        |--------------------------------------------------------------------------
        */

        searchForm.submit();

    });


    /*
    |--------------------------------------------------------------------------
    | ENTER KEY ON MOBILE
    |--------------------------------------------------------------------------
    */

    searchInput.addEventListener('keydown', function (event) {

        if (event.key === 'Enter') {

            event.preventDefault();

            if (searchInput.value.trim() !== '') {
                searchForm.submit();
            }

        }

    });

});
</script>  

@yield('page_scripts')
    
<div class="clever-core-ads"></div>


</body>


</html>