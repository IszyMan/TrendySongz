<a
    class="ts-album-card"
    href="{{ \App\Support\AlbumUrl::detail($row) }}"
    style="color: inherit; text-decoration: none;"
>
    <div class="ts-album-cover">
        @include('pages.partials.cover', [
            'src' => $row->cover_url,
            'folder' => '',
        ])
    </div>

    <div class="ts-album-details">
        <div class="ts-album-artist">
            {{ $row->ArtistsName ?: $row->Stage_Name }}
        </div>

        <span class="ts-album-title">
            {{ $row->title }}
        </span>

        <div class="ts-album-meta-row">
            {{ $row->released_year }}
        </div>
    </div>
</a>