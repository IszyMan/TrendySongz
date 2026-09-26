@extends('admin.layout')

@section('title', 'Listings')

@section('content')
    <h1>Audio and video listings</h1>

    <div class="admin-panel">
        <form
            class="admin-toolbar"
            method="GET"
            action="{{ route('admin.listings.index') }}"
        >
            <input
                type="search"
                name="q"
                value="{{ $search }}"
                placeholder="Title or artist"
                aria-label="Search listings"
            >

            <select name="type" aria-label="Listing type">
                <option value="">All types</option>
                <option value="audio" @selected($type === 'audio')>Audio</option>
                <option value="video" @selected($type === 'video')>Video</option>
            </select>

            <button class="admin-button" type="submit">Filter</button>

            <a class="admin-button" href="{{ route('admin.listings.create') }}">
                Add listing
            </a>
        </form>

        <div class="admin-table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Artist</th>
                        <th>Type</th>
                        <th>Country</th>
                        <th>Published</th>
                        <th>Posted</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($rows as $row)
                        <tr>
                            <td>{{ $row->id }}</td>
                            <td>{{ $row->TrackTitle }}</td>
                            <td>{{ $row->ArtistsName }}</td>
                            <td>{{ $row->ListingType }}</td>
                            <td>{{ $row->country_id }}</td>
                            <td>{{ $row->IsPublished }}</td>
                            <td>{{ $row->created_at }}</td>

                            <td>
                                @if (
                                    (int) auth()->user()->roleid === 1
                                    || (int) $row->user_id === (int) auth()->id()
                                )
                                    <div class="admin-actions">
                                        <a
                                            class="admin-button admin-button-secondary"
                                            href="{{ route('admin.listings.edit', $row->id) }}"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route('admin.listings.destroy', $row->id) }}"
                                            onsubmit="return confirm('Delete this listing and its locally uploaded files?')"
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
                            <td colspan="8">No listings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
    </div>
        <div class="ts-list-pagination">
            {{ $rows->links('vendor.pagination.trendysongz') }}
        </div>
@endsection