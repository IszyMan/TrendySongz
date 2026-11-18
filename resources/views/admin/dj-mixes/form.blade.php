@php
    $editing = isset($mix);
@endphp

<form
    class="admin-panel admin-grid"
    method="POST"
    action="{{ $editing
        ? route('admin.dj-mixes.update', $mix->id)
        : route('admin.dj-mixes.store') }}"
    enctype="multipart/form-data"
>
    @csrf

    @if ($editing)
        @method('PUT')
    @endif

    <label>
        DJ name

        <select name="dj_id" required>
            <option value="">Choose DJ</option>

            @foreach ($djs as $dj)
                <option
                    value="{{ $dj->id }}"
                    @selected(
                        (string) old(
                            'dj_id',
                            $mix->dj_id ?? ''
                        ) === (string) $dj->id
                    )
                >
                    {{ $dj->dj_name }}
                </option>
            @endforeach
        </select>
    </label>

    <label>
        Mixtape title

        <input
            name="mix_title"
            maxlength="50"
            value="{{ old('mix_title', $mix->mix_title ?? '') }}"
            required
        >
    </label>

    <label>
        Release year

        <input
            name="released_year"
            maxlength="36"
            value="{{ old(
                'released_year',
                $mix->released_year ?? ''
            ) }}"
        >
    </label>

    <label>
        Cover image

        <input
            type="file"
            name="image"
            accept="image/jpeg,image/png,image/webp"
        >

        @if ($editing && $mix->cover_url)
            <span class="admin-file-note">
                Current: {{ $mix->cover_url }}.
                Leave empty to keep it.
            </span>
        @endif
    </label>

    <label>
        Back cover (optional)

        <input
            type="file"
            name="back_image"
            accept="image/jpeg,image/png,image/webp"
        >

        @if ($editing && $mix->back_cover)
            <span class="admin-file-note">
                Current: {{ $mix->back_cover }}.
                Leave empty to keep it.
            </span>
        @endif
    </label>

    <label class="admin-wide">
        DJ mix audio (200 MB maximum)

        <input
            type="file"
            name="media"
            accept=".mp3,.m4a,.wav,.aac"
            @required(! $editing)
        >

        @if ($editing && $mix->track_url)
            <span class="admin-file-note">
                Current: {{ $mix->track_url }}.
                Leave empty to keep it.
            </span>
        @endif
    </label>

    

    <label class="admin-wide">
        Description

        <textarea
            name="description1"
        >{{ old(
            'description1',
            $mix->description1 ?? ''
        ) }}</textarea>
    </label>

    <label class="admin-wide">
        Description 2

        <textarea
            name="description2"
        >{{ old(
            'description2',
            $mix->description2 ?? ''
        ) }}</textarea>
    </label>

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
                        && $mix->IsPublished === 'YES'
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
                : 'Save DJ mix' }}
        </button>
    </div>
</form>