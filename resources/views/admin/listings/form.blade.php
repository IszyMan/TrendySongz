@php
    $editing = isset($row);
@endphp

<form
    class="admin-panel admin-grid"
    method="POST"
    action="{{ $editing
        ? route('admin.listings.update', $row->id)
        : route('admin.listings.store') }}"
    enctype="multipart/form-data"
>
    @csrf

    @if ($editing)
        @method('PUT')
    @endif

    <label>
        Artist

        <select name="Artists_Id" id="listing-artist" required>
            <option value="">Choose artist</option>

            @foreach ($artists as $artist)
                <option
                    value="{{ $artist->Artists_Id }}"
                    @selected(old('Artists_Id', $row->Artists_Id ?? '') === $artist->Artists_Id)
                >
                    {{ $artist->ArtistsName ?: $artist->Stage_Name }}
                </option>
            @endforeach
        </select>
    </label>

    <label>
        Album (optional)

        <select name="album_id" id="listing-album">
            <option value="">No album / single</option>

            @foreach ($albums as $album)
                <option
                    value="{{ $album->id }}"
                    data-artist="{{ $album->artist_id }}"
                    @selected((string) old('album_id', $row->album_id ?? '') === (string) $album->id)
                >
                    {{ $album->title }}
                </option>
            @endforeach
        </select>
    </label>

    <label>
        Country

        <select name="country_id" required>
            @foreach ([
                'naija' => 'Nigeria',
                'ghana' => 'Ghana',
                'african' => 'Other African country',
            ] as $value => $label)
                <option
                    value="{{ $value }}"
                    @selected(old('country_id', $row->country_id ?? 'naija') === $value)
                >
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </label>

    <label>
        Type

        <select name="ListingType" id="listing-type" required>
            <option
                value="audio"
                @selected(old('ListingType', $row->ListingType ?? 'audio') === 'audio')
            >
                Audio
            </option>

            <option
                value="video"
                @selected(old('ListingType', $row->ListingType ?? 'audio') === 'video')
            >
                Video
            </option>
        </select>
    </label>

    <label class="admin-wide">
        Track / video title

        <input
            name="TrackTitle"
            maxlength="1000"
            value="{{ old('TrackTitle', $row->TrackTitle ?? '') }}"
            required
        >
    </label>

    <label>
        Featuring

        <input
            name="Featuring"
            maxlength="500"
            value="{{ old('Featuring', $row->Featuring ?? '') }}"
        >
    </label>

    <label>
        Year of release

        <input
            name="YearOfRelease"
            maxlength="100"
            value="{{ old('YearOfRelease', $row->YearOfRelease ?? '') }}"
        >
    </label>

    <label>
        Produced by

        <input
            name="producedby"
            maxlength="500"
            value="{{ old('producedby', $row->producedby ?? '') }}"
        >
    </label>

    <label>
        Directed by (videos)

        <input
            name="directedby"
            maxlength="36"
            value="{{ old('directedby', $row->directedby ?? '') }}"
        >
    </label>

    <label>
        Album track number

        <input
            type="number"
            min="1"
            name="track_number"
            value="{{ old('track_number', $row->track_number ?? 1) }}"
        >
    </label>

    <label>
        Cover image

        <input
            type="file"
            name="image"
            accept="image/jpeg,image/png,image/webp"
        >

        @if ($editing && $row->CoverUrl)
            <span class="admin-file-note">
                Current: {{ $row->CoverUrl }}. Leave empty to keep it.
            </span>
        @endif
    </label>

    <label class="admin-wide">
        Media file (200 MB maximum)

        <input
            type="file"
            name="media"
            id="listing-media"
            @required(! $editing)
        >

        @if ($editing && $row->TrackUrl)
            <span class="admin-file-note">
                Current: {{ $row->TrackUrl }}. Leave empty to keep it.
            </span>
        @endif
    </label>

    <label class="admin-wide">
        Introduction

        <textarea name="introduction">{{ old('introduction', $row->introduction ?? '') }}</textarea>
    </label>

    <label class="admin-wide">
        Track information

        <textarea
            name="TrackInfo"
            maxlength="5000"
        >{{ old('TrackInfo', $row->TrackInfo ?? '') }}</textarea>
    </label>

    <label class="admin-wide">
        Additional track information 1

        <textarea
            name="trackinfo1"
            maxlength="5000"
        >{{ old('trackinfo1', $row->trackinfo1 ?? '') }}</textarea>
    </label>

    <label class="admin-wide">
        Additional track information 2

        <textarea
            name="trackinfo2"
            maxlength="5000"
        >{{ old('trackinfo2', $row->trackinfo2 ?? '') }}</textarea>
    </label>

    <input type="hidden" name="isgospel" value="0">

    <label class="admin-check">
        <input
            type="checkbox"
            name="isgospel"
            value="1"
            @checked(old('isgospel', $row->isgospel ?? 0))
        >
        Gospel
    </label>

    <input type="hidden" name="publish" value="0">

    <label class="admin-check">
        <input
            type="checkbox"
            name="publish"
            value="1"
            @checked(old('publish', isset($row) && $row->IsPublished === 'YES'))
        >
        Published
    </label>

    <div class="admin-wide">
        <button class="admin-button" type="submit">
            {{ $editing ? 'Save changes' : 'Save listing' }}
        </button>
    </div>
</form>

<script src="{{ asset('js/admin-listing.js') }}" defer></script>