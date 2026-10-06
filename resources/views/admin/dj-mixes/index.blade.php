@extends('admin.layout')

@section('title', 'DJ mixes')

@section('content')
    <h1>DJ mixes</h1>

    <div class="admin-panel">
        <form
            class="admin-toolbar"
            method="GET"
            action="{{ route('admin.dj-mixes.index') }}"
        >
            <input
                type="search"
                name="q"
                value="{{ $search }}"
                placeholder="Search DJ mixes"
                aria-label="Search DJ mixes"
            >

            <button
                class="admin-button"
                type="submit"
            >
                Search
            </button>

            <a
                class="admin-button"
                href="{{ route('admin.dj-mixes.create') }}"
            >
                Add DJ mix
            </a>
        </form>

        <div class="admin-table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Mixtape</th>
                        <th>DJ</th>
                        <th>Audio file</th>
                        <th>Published</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($mixes as $mix)
                        <tr>
                            <td>{{ $mix->id }}</td>
                            <td>{{ $mix->mix_title }}</td>
                            <td>{{ $mix->dj_name }}</td>
                            <td>
                                {{ $mix->track_url ?: '—' }}
                            </td>
                            <td>{{ $mix->IsPublished }}</td>

                            <td>
                                @if (
                                    (int) auth()->user()->roleid === 1
                                    || (int) $mix->user_id
                                        === (int) auth()->id()
                                )
                                    <div class="admin-actions">
                                        <a
                                            class="admin-button admin-button-secondary"
                                            href="{{ route(
                                                'admin.dj-mixes.edit',
                                                $mix->id
                                            ) }}"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.dj-mixes.destroy',
                                                $mix->id
                                            ) }}"
                                            onsubmit="return confirm('Delete this DJ mix?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                class="admin-danger"
                                                type="submit"
                                            >
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                No DJ mixes found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="ts-list-pagination">
            {{ $mixes->links(
                'vendor.pagination.trendysongz'
            ) }}
        </div>
    </div>
@endsection