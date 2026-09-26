<footer class="ts-footer">
    <div class="ts-footer-inner">
        <div class="ts-footer-grid">
            <div class="ts-footer-brand">
                <strong>TRENDYSONGZ</strong>
                <p>Nigerian music, videos, albums, DJ mixes and music news.</p>
            </div>

            <div class="ts-footer-column">
                <h3>Music</h3>

                <div class="ts-footer-links">
                    <a href="{{ route('pages.musics', 'naija') }}">Naija Music</a>
                    <a href="{{ route('pages.musics', 'ghana') }}">Ghana Music</a>
                    <a href="{{ route('pages.musics', 'african') }}">African Music</a>
                </div>
            </div>

            <div class="ts-footer-column">
                <h3>Discover</h3>

                <div class="ts-footer-links">
                    <a href="{{ route('pages.videos_all') }}">Videos</a>
                    <a href="{{ route('pages.album') }}">Albums</a>
                    <a href="{{ route('pages.djmix') }}">DJ Mixes</a>
                </div>
            </div>

            <div class="ts-footer-column">
                <h3>More</h3>

                <div class="ts-footer-links">
                    <a href="{{ route('pages.artists') }}">Artists</a>
                    <a href="{{ route('pages.celebrity_news') }}">Music News</a>
                    <a href="{{ route('pages.search') }}">Search</a>
                </div>
            </div>
        </div>

        <div class="ts-footer-bottom">
            <span class="ts-footer-copyright">
                &copy; {{ date('Y') }} TrendySongz
            </span>
        </div>
    </div>
</footer>