@php
    $isTrack = isset($row->TrackTitle);
    $isMix = isset($row->mix_title);
    $isBlog = isset($row->photo) && isset($row->description);
    $isArtist = isset($row->ArtistsName) && !$isTrack;
    $title = $isTrack ? trim(($row->ArtistsName ?? '') . ' - ' . $row->TrackTitle) : ($row->mix_title ?? $row->title ?? $row->ArtistsName ?? 'Untitled');
    $image = $isTrack ? $row->CoverUrl : ($isArtist ? $row->ProfilePic : ($isBlog ? $row->photo : ($row->cover_url ?? null)));
    $folder = $isBlog ? 'blog/' : ($isMix ? 'dj/' : '');
    $url = $isTrack ? route($row->ListingType === 'video' ? 'pages.videos_detail' : 'pages.musics_detail', [$row->id, \Illuminate\Support\Str::slug($title)]) : ($isMix ? route('pages.djmix_detail', [$row->id, \Illuminate\Support\Str::slug($title)]) : ($isArtist ? route('pages.artists_details', [$row->id, \Illuminate\Support\Str::slug($title)]) : ($isBlog ? route('pages.celebritynews_details', [$row->id, \Illuminate\Support\Str::slug($title)]) : route('pages.album_details', [$row->id, \Illuminate\Support\Str::slug($title)]))));
@endphp
<article class="recovery-card"><a href="{{ $url }}"><div class="recovery-cover"><div class="recovery-cover-fallback" aria-hidden="true">TS</div>@if($image)<img loading="lazy" src="{{ asset('images/' . $folder . ltrim($image, '/')) }}" alt="" onerror="this.remove()">@endif</div><h3>{{ $title }}</h3></a></article>
