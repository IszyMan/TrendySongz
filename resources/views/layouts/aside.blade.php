@php
    $popularArtistes = [
        '2Baba' => '2baba',
        'Burna Boy' => 'burna-boy',
        'JoeBoy' => 'joeboy',
        'Davido' => 'davido',
        'Zlatan' => 'zlatan',
        'Falz' => 'falz',
        'Fire Boy' => 'fireboy-dml',
        'Kizz Daniel' => 'kizz-daniel',
        'Rema' => 'rema',
        'Mayorkun' => 'mayorkun',
        'Naira Marley' => 'naira-marley',
        'Simi' => 'simi',
        'Wizkid' => 'wizkid',
        'Phyno' => 'phyno',
        'Olamide' => 'olamide',
        'Omah Lay' => 'omah-lay',
        'Wande Coal' => 'wande-coal',
        'Tiwa Savage' => 'tiwa-savage',
        'Patoranking' => 'patoranking',
        'Mr Eazi' => 'mr-eazi',
        'Bella Shmurda' => 'bella-shmurda',
        'Zinoleesky' => 'zinoleesky',
    ];

    $starArtistes = [
        'Adekunle Gold' => 'adekunle-gold',
        'Shallipopi' => 'shallipopi',
        'Niniola' => 'niniola',
        'Mavo' => 'mavo',
        'Ladipoe' => 'ladipoe',
        'Vector' => 'vector',
        'Asa' => 'asa',
        'Famous Pluto' => 'famous-pluto',
        'Shoday' => 'shoday',
        'Rybeena' => 'rybeena',
        'Timaya' => 'timaya',
        'Ayra Starr' => 'ayra-starr',
        'Ice Prince' => 'ice-prince',
        'Peruzzi' => 'peruzzi',
        'Zlatan' => 'zlatan',
        'Diamond Platnumz' => 'diamond-platnumz',
    ];
@endphp

<div class="ts-sidebar-content">
    <section class="ts-aside-section ts-aside-explore">
        <h2 class="ts-sidebar-heading">Explore TrendySongz</h2>
    
        <ul class="ts-explore-links">
            <li>
                <a href="{{ route('pages.musics', 'naija') }}">
                    <i class="fa fa-music" aria-hidden="true"></i>
                    <span>Naija Music</span>
                </a>
            </li>
    
            <li>
                <a href="{{ route('pages.musics', 'ghana') }}">
                    <i class="fa fa-globe" aria-hidden="true"></i>
                    <span>Ghana Music</span>
                </a>
            </li>
            
            <li>
                <a href="{{ route('pages.musics', 'african') }}">
                    <i class="fa fa-globe" aria-hidden="true"></i>
                    <span>African Music</span>
                </a>
            </li>
    
            <li>
                <a href="{{ route('pages.videos_all') }}">
                    <i class="fa fa-film" aria-hidden="true"></i>
                    <span>Latest Videos</span>
                </a>
            </li>
    
            <li>
                <a href="{{ route('pages.album') }}">
                    <i class="fa fa-th-large" aria-hidden="true"></i>
                    <span>Albums &amp; EPs</span>
                </a>
            </li>
    
            <li>
                <a href="{{ route('pages.djmix') }}">
                    <i class="fa fa-headphones" aria-hidden="true"></i>
                    <span>DJ Mixes</span>
                </a>
            </li>
    
            <li>
                <a href="{{ route('pages.celebrity_news') }}">
                    <i class="fa fa-newspaper-o" aria-hidden="true"></i>
                    <span>Music News</span>
                </a>
            </li>
        </ul>
    </section>

    <section class="ts-aside-section">
        <h2 class="ts-sidebar-heading">Popular Artistes</h2>

        <ul class="ts-aside-artist-list">
            @foreach ($popularArtistes as $name => $slug)
                <li>
                    <a href="{{ route('pages.artists_details', $slug) }}">
                        {{ $name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </section>

    <section class="ts-aside-section">
        <h2 class="ts-sidebar-heading">Star Artistes</h2>

        <ul class="ts-aside-artist-list">
            @foreach ($starArtistes as $name => $slug)
                <li>
                    <a href="{{ route('pages.artists_details', $slug) }}">
                        {{ $name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </section>
</div>