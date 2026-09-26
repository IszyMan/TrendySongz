@extends('layouts.app')
@section('title', $title . ' | TrendySongz')
@section('content')
@if($section === 'music' || $section === 'search')
<div class="ts-music-page"><header class="ts-music-page-heading"><h2>{{ $title }}</h2></header><div class="ts-music-list">@forelse($rows as $row)<article class="ts-music-list-item"><a class="ts-music-list-link" href="{{ route($row->ListingType === 'video' ? 'pages.videos_detail' : 'pages.musics_detail', [$row->id, \Illuminate\Support\Str::slug(($row->ArtistsName ?? '') . ' ' . $row->TrackTitle)]) }}"><span class="ts-music-list-cover">@include('pages.partials.cover', ['src'=>$row->CoverUrl,'folder'=>''])</span><span class="ts-music-list-info"><span class="ts-music-list-artist">{{ $row->ArtistsName ?: 'Unknown Artist' }}</span><span class="ts-music-list-title">{{ $row->TrackTitle }}</span>@if($row->Featuring)<span class="ts-music-list-featuring">feat. {{ $row->Featuring }}</span>@endif</span><span class="ts-music-list-action">›</span></a></article>@empty<p>No published music found.</p>@endforelse</div></div>
@elseif($section === 'videos')
<div class="ts-video-page"><header class="ts-video-page-heading"><h2>{{ $title }}</h2><p class="ts-video-page-subtitle">Latest music videos on TrendySongz</p></header><div class="ts-video-list">@forelse($rows as $row)<article class="ts-video-list-item"><a href="{{ route('pages.videos_detail', [$row->id, \Illuminate\Support\Str::slug($row->TrackTitle)]) }}" class="ts-video-list-link"><span class="ts-video-list-cover">@include('pages.partials.cover', ['src'=>$row->CoverUrl,'folder'=>''])<span class="ts-video-list-play">▶</span></span><span class="ts-video-list-info"><strong class="ts-video-list-artist">{{ $row->ArtistsName }}</strong><span class="ts-video-list-title">{{ $row->TrackTitle }}</span>@if($row->Featuring)<span class="ts-video-list-featuring">feat. {{ $row->Featuring }}</span>@endif</span></a></article>@empty<p>No videos found.</p>@endforelse</div></div>
@elseif($section === 'albums')
<div class="ts-latest-albums"><header class="ts-section-heading"><h2>{{ $title }}</h2></header><div class="ts-albums-grid">@forelse($rows as $row)@include('pages.partials.album-card')@empty<p>No albums found.</p>@endforelse</div></div>
@elseif($section === 'mixes')
<div class="ts-djmix-page"><header class="ts-section-heading"><h2>{{ $title }}</h2></header><div class="ts-djmix-list">@forelse($rows as $row)@include('pages.partials.mix-card')@empty<p>No DJ mixes found.</p>@endforelse</div></div>
@elseif($section === 'artists')
<div class="ts-artists-page"><header class="ts-artists-header"><div class="ts-section-heading"><h1>{{ $title }}</h1></div></header><div class="ts-artist-grid">@forelse($rows as $row)@include('pages.partials.artist-card')@empty<p>No artists found.</p>@endforelse</div></div>
@elseif($section === 'news')
<div class="ts-music-news-page"><header class="ts-section-heading"><h2>{{ $title }}</h2></header><div class="ts-music-news-list">@forelse($rows as $row)@include('pages.partials.news-card')@empty<p>No news found.</p>@endforelse</div></div>
@endif
@if($rows instanceof \Illuminate\Contracts\Pagination\Paginator) <div class="ts-list-pagination">{{ $rows->links() }}</div> @endif
@endsection