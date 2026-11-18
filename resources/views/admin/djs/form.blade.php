@php
    $editing = isset($dj);
@endphp

<form
    class="admin-panel admin-grid"
    method="POST"
    action="{{ $editing
        ? route('admin.djs.update', $dj->id)
        : route('admin.djs.store') }}"
    enctype="multipart/form-data"
>
    @csrf

    @if ($editing)
        @method('PUT')
    @endif

    <label>
        DJ name
        <input
            name="dj_name"
            maxlength="50"
            value="{{ old('dj_name', $dj->dj_name ?? '') }}"
            required
        >
    </label>

    <label>
        Full name
        <input
            name="fullname"
            maxlength="50"
            value="{{ old('fullname', $dj->fullname ?? '') }}"
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
                    @selected(
                        old(
                            'country_id',
                            $dj->country_id ?? 'naija'
                        ) === $value
                    )
                >
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </label>

    <label>
        Display order
        <input
            type="number"
            name="order_id"
            min="0"
            value="{{ old('order_id', $dj->order_id ?? 10000) }}"
        >
    </label>

    <label>
        DJ photo
        <input
            type="file"
            name="image"
            accept="image/jpeg,image/png,image/webp"
        >

        @if ($editing && $dj->photo)
            <span class="admin-file-note">
                Current: {{ $dj->photo }}.
                Leave empty to keep it.
            </span>
        @endif
    </label>

    <label class="admin-wide">
        Profile / biography
        <textarea
            name="place_of_birth"
            maxlength="10000"
        >{{ old('place_of_birth', $dj->place_of_birth ?? '') }}</textarea>
    </label>

    <label class="admin-wide">
        Awards
        <textarea name="award">{{ old('award', $dj->award ?? '') }}</textarea>
    </label>

    <label class="admin-wide">
        Endorsements
        <textarea name="endorsement">{{ old('endorsement', $dj->endorsement ?? '') }}</textarea>
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
                    $editing && $dj->IsPublished === 'YES'
                )
            )
        >
        Published
    </label>

    <div class="admin-wide">
        <button class="admin-button" type="submit">
            {{ $editing ? 'Save changes' : 'Save DJ' }}
        </button>
    </div>
</form>