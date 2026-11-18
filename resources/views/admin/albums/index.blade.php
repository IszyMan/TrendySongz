@extends('admin.layout')

@section('title', 'Albums')

@section('content')
    <h1>Albums</h1>

    <div class="admin-panel">
        <form
            class="admin-toolbar"
            method="GET"
            action="{{ route('admin.albums.index') }}"
        >
            <input
                type="search"
                name="q"
                value="{{ $search }}"
                placeholder="Search albums"
                aria-label="Search albums"
            >

            <button
                class="admin-button"
                type="submit"
            >
                Search
            </button>

            <a
                class="admin-button"
                href="{{ route('admin.albums.create') }}"
            >
                Add album
            </a>
        </form>

        <div class="admin-table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Album</th>
                        <th>Artist</th>
                        <th>Released</th>
                        <th>Published</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($albums as $album)
                        <tr>
                            <td>{{ $album->id }}</td>
                            <td>{{ $album->title }}</td>
                            <td>
                                {{ $album->ArtistsName
                                    ?: $album->Stage_Name }}
                            </td>
                            <td>
                                {{ $album->released_year }}
                            </td>
                            <td>
                                {{ $album->IsPublished }}
                            </td>

                            <td>
                                @if (
                                    (int) auth()->user()->roleid === 1
                                    || (int) $album->user_id
                                        === (int) auth()->id()
                                )
                                    <div class="admin-actions">
                                        <a
                                            class="admin-button admin-button-secondary"
                                            href="{{ route(
                                                'admin.albums.edit',
                                                $album->id
                                            ) }}"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.albums.destroy',
                                                $album->id
                                            ) }}"
                                            onsubmit="return confirm('Delete this album?')"
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
                                No albums found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="ts-list-pagination">
            {{ $albums->links(
                'vendor.pagination.trendysongz'
            ) }}
        </div>
    </div>
@endsection