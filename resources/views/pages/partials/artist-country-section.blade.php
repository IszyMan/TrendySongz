<section
    class="ts-artists-section"
    data-artist-section="{{ $section }}"
    id="artists-{{ $section }}"
>
    <header class="ts-section-heading">
        <h2>{{ $heading }}</h2>

        <span class="ts-artist-total">
            {{ $rows->total() }} artists
        </span>
    </header>

    <div class="ts-artist-grid">
        @forelse ($rows as $row)
            @php
                $artistName = $row->ArtistsName
                    ?: $row->Stage_Name
                    ?: 'Unknown Artist';
            @endphp

            <a
                class="ts-artist-card"
                href="{{ route(
                    'pages.artists_details',
                    \Illuminate\Support\Str::slug($artistName)
                ) }}"
            >
                <span class="ts-artist-image">
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

                <span class="ts-artist-content">
                    <span class="ts-artist-name">
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
            <p>No artists found in this section.</p>
        @endforelse
    </div>

    @if ($rows->hasPages())
        <div class="ts-artist-pagination">
            {{ $rows
                ->appends(request()->except($rows->getPageName()))
                ->appends(['section' => $section])
                ->links('vendor.pagination.trendysongz') }}
        </div>
    @endif
</section>