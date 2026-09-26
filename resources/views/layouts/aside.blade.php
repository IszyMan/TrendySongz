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
        'Skales' => 'skales',
        'Niniola' => 'niniola',
        'T Classic' => 't-classic',
        'Ladipoe' => 'ladipoe',
        'Vector' => 'vector',
        'Asa' => 'asa',
        'Demmie Vee' => 'demmie-vee',
        'King Perryy' => 'king-perryy',
        'Skiibii' => 'skiibii',
        'Timaya' => 'timaya',
        'Slimcase' => 'slimcase',
        'Ice Prince' => 'ice-prince',
        'Peruzzi' => 'peruzzi',
        'Yung6ix' => 'yung6ix',
        'Diamond Platnumz' => 'diamond-platnumz',
    ];
@endphp

<div class="ts-sidebar-content">
    <section class="ts-aside-section">
        <h2 class="ts-sidebar-heading">Explore TrendySongz</h2>

        <ul>
            <li>
                <a href="{{ route('pages.musics', 'naija') }}">
                    Naija Music
                </a>
            </li>

            <li>
                <a href="{{ route('pages.musics', 'ghana') }}">
                    Ghana Music
                </a>
            </li>

            <li>
                <a href="{{ route('pages.videos_all') }}">
                    Latest Videos
                </a>
            </li>

            <li>
                <a href="{{ route('pages.album') }}">
                    Albums &amp; EPs
                </a>
            </li>

            <li>
                <a href="{{ route('pages.djmix') }}">
                    DJ Mixes
                </a>
            </li>

            <li>
                <a href="{{ route('pages.celebrity_news') }}">
                    Music News
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