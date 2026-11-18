<div class="ts-music-list">

    @forelse ($rows as $row)

        <article class="ts-music-list-item">

            <a class="ts-music-list-link"
               href="{{ \App\Support\ListingUrl::musicDetail($row) }}">

                {{-- Cover --}}
                <span class="ts-music-list-cover">

                    @if ($row->CoverUrl)
                        <img
                            loading="lazy"
                            src="{{ asset('images/' . ltrim($row->CoverUrl, '/')) }}"
                            alt="{{ $row->TrackTitle ?? 'Music cover' }}"
                            onerror="this.remove()"
                        >
                    @endif

                    <span class="ts-cover-placeholder" aria-hidden="true">
                        TS
                    </span>

                </span>

                {{-- Music information --}}
                <span class="ts-music-list-info">

                    {{-- Artist --}}
                    <span class="ts-music-list-artist">
                        {{ $row->ArtistsName ?: 'Unknown Artist' }}
                    </span>

                    {{-- Track title and featuring --}}
                    <span class="ts-collaboration-title-row">

                        <span class="ts-music-list-title">
                            {{ $row->TrackTitle }}
                        </span>

                        @if ($row->Featuring)
                            <span class="ts-music-list-featuring">
                                <span>feat.</span> {{ $row->Featuring }}
                            </span>
                        @endif

                    </span>

                    {{-- Producer --}}
                    @if ($row->producedby)
                        <span class="ts-music-list-producer">
                            <span>Produced by:</span> {{ $row->producedby }}
                        </span>
                    @endif

                </span>

                {{-- Action --}}
                <span class="ts-music-list-action">›</span>

            </a>

        </article>

    @empty

        <p>{{ $emptyMessage ?? 'No published songs found.' }}</p>

    @endforelse

</div>

@if ($rows->hasPages())
    <div class="ts-list-pagination">
        {{ $rows->links('vendor.pagination.trendysongz') }}
    </div>
@endif