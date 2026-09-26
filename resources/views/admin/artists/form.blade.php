@php
    $editing = isset($artist);
@endphp

<form
    class="admin-panel admin-grid"
    method="POST"
    action="{{ $editing
        ? route('admin.artists.update', $artist->id)
        : route('admin.artists.store') }}"
    enctype="multipart/form-data"
>
    @csrf

    @if ($editing)
        @method('PUT')
    @endif

    <label>
        Artist name
        <input
            name="ArtistsName"
            maxlength="100"
            value="{{ old('ArtistsName', $artist->ArtistsName ?? '') }}"
            required
        >
    </label>

    <label>
        Stage name (unique)
        <input
            name="Stage_Name"
            maxlength="100"
            value="{{ old('Stage_Name', $artist->Stage_Name ?? '') }}"
            required
        >
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
                    @selected(old('country_id', $artist->country_id ?? 'naija') === $value)
                >
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </label>

    <label>
        Full name
        <input
            name="Fullname"
            maxlength="100"
            value="{{ old('Fullname', $artist->Fullname ?? '') }}"
        >
    </label>

    <label>
        Record label
        <input
            name="RecordLabel"
            maxlength="100"
            value="{{ old('RecordLabel', $artist->RecordLabel ?? '') }}"
        >
    </label>

    <label>
        Genre
        <input
            name="Genres"
            maxlength="100"
            value="{{ old('Genres', $artist->Genres ?? '') }}"
        >
    </label>

    <label>
        Artist image
        <input
            type="file"
            name="image"
            accept="image/jpeg,image/png,image/webp"
        >

        @if ($editing && $artist->ProfilePic)
            <span class="admin-file-note">
                Current: {{ $artist->ProfilePic }}. Leave empty to keep it.
            </span>
        @endif
    </label>

    <label class="admin-wide">
        Short profile
        <textarea
            name="ArtistsProfile"
            maxlength="100"
        >{{ old('ArtistsProfile', $artist->ArtistsProfile ?? '') }}</textarea>
    </label>

    <input type="hidden" name="publish" value="0">

    <label class="admin-check">
        <input
            type="checkbox"
            name="publish"
            value="1"
            @checked(old('publish', isset($artist) && $artist->IsPublished === 'YES'))
        >
        Published
    </label>

    <div class="admin-wide">
        <button class="admin-button" type="submit">
            {{ $editing ? 'Save changes' : 'Save artiste' }}
        </button>
    </div>
</form>