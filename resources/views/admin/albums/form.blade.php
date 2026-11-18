@php
    $editing = isset($album);
@endphp

<form
    class="admin-panel admin-grid"
    method="POST"
    action="{{ $editing
        ? route('admin.albums.update', $album->id)
        : route('admin.albums.store') }}"
    enctype="multipart/form-data"
>
    @csrf

    @if ($editing)
        @method('PUT')
    @endif

    <label>
        Artist

        <select name="artist_id" required>
            <option value="">Choose artist</option>

            @foreach ($artists as $artist)
                <option
                    value="{{ $artist->Artists_Id }}"
                    @selected(
                        old(
                            'artist_id',
                            $album->artist_id ?? ''
                        ) === $artist->Artists_Id
                    )
                >
                    {{ $artist->ArtistsName
                        ?: $artist->Stage_Name }}
                </option>
            @endforeach
        </select>
    </label>

    <label class="admin-wide">
        Album title

        <input
            name="title"
            maxlength="5000"
            value="{{ old(
                'title',
                $album->title ?? ''
            ) }}"
            required
        >
    </label>

    <label>
        Featuring

        <input
            name="featuring"
            maxlength="100"
            value="{{ old(
                'featuring',
                $album->featuring ?? ''
            ) }}"
        >
    </label>

    <label>
        Release year

        <input
            name="released_year"
            maxlength="36"
            value="{{ old(
                'released_year',
                $album->released_year ?? ''
            ) }}"
            required
        >
    </label>

    <label>
        Release date

        <input
            type="date"
            name="released_date"
            required
            value="{{ old(
                'released_date',
                isset($album) && $album->released_date
                    ? \Illuminate\Support\Carbon::parse(
                        $album->released_date
                    )->toDateString()
                    : now()->toDateString()
            ) }}"
        >
    </label>

    <label>
        Album cover

        <input
            type="file"
            name="image"
            accept="image/jpeg,image/png,image/webp"
            @required(! $editing)
        >

        @if ($editing && $album->cover_url)
            <span class="admin-file-note">
                Current: {{ $album->cover_url }}.
                Leave empty to keep it.
            </span>
        @endif
    </label>

    <label class="admin-wide">
        Description

        <textarea name="description">{{ old(
            'description',
            $album->description ?? ''
        ) }}</textarea>
    </label>

    <input type="hidden" name="show_ads" value="0">

    <input type="hidden" name="publish" value="0">

    <label class="admin-check">
        <input
            type="checkbox"
            name="publish"
            value="1"
            @checked(
                old(
                    'publish',
                    $editing
                        && $album->IsPublished === 'YES'
                )
            )
        >
        Published
    </label>

    

    <div class="admin-wide">
        <button
            class="admin-button"
            type="submit"
        >
            {{ $editing
                ? 'Save changes'
                : 'Save album' }}
        </button>
    </div>
</form>