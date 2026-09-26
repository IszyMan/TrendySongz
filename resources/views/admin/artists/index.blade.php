@extends('admin.layout')

@section('title', 'Artistes')

@section('content')
    <h1>Artistes</h1>

    <div class="admin-panel">
        <form
            class="admin-toolbar"
            method="GET"
            action="{{ route('admin.artists.index') }}"
        >
            <input
                type="search"
                name="q"
                value="{{ $search }}"
                placeholder="Search artistes"
                aria-label="Search artistes"
            >

            <button class="admin-button" type="submit">Search</button>

            <a class="admin-button" href="{{ route('admin.artists.create') }}">
                Add artiste
            </a>
        </form>

        <div class="admin-table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Artist</th>
                        <th>Stage name</th>
                        <th>Country</th>
                        <th>Published</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($artists as $artist)
                        <tr>
                            <td>{{ $artist->id }}</td>
                            <td>{{ $artist->ArtistsName }}</td>
                            <td>{{ $artist->Stage_Name }}</td>
                            <td>{{ $artist->country_id }}</td>
                            <td>{{ $artist->IsPublished }}</td>

                            <td>
                                @if (
                                    (int) auth()->user()->roleid === 1
                                    || (int) $artist->user_id === (int) auth()->id()
                                )
                                    <div class="admin-actions">
                                        <a
                                            class="admin-button admin-button-secondary"
                                            href="{{ route('admin.artists.edit', $artist->id) }}"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route('admin.artists.destroy', $artist->id) }}"
                                            onsubmit="return confirm('Delete this artiste?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button class="admin-danger" type="submit">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No artistes found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="ts-list-pagination">
            {{ $artists->links('vendor.pagination.trendysongz') }}
        </div>
    </div>
@endsection