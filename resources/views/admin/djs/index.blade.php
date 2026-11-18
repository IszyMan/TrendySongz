@extends('admin.layout')

@section('title', 'DJs')

@section('content')
    <h1>DJs</h1>

    <div class="admin-panel">
        <form
            class="admin-toolbar"
            method="GET"
            action="{{ route('admin.djs.index') }}"
        >
            <input
                type="search"
                name="q"
                value="{{ $search }}"
                placeholder="Search DJs"
                aria-label="Search DJs"
            >

            <button class="admin-button" type="submit">
                Search
            </button>

            <a
                class="admin-button"
                href="{{ route('admin.djs.create') }}"
            >
                Add DJ
            </a>
        </form>

        <div class="admin-table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>DJ name</th>
                        <th>Full name</th>
                        <th>Country</th>
                        <th>Published</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($djs as $dj)
                        <tr>
                            <td>{{ $dj->id }}</td>
                            <td>{{ $dj->dj_name }}</td>
                            <td>{{ $dj->fullname }}</td>
                            <td>{{ $dj->country_id }}</td>
                            <td>{{ $dj->IsPublished }}</td>

                            <td>
                                @if (
                                    (int) auth()->user()->roleid === 1
                                    || (int) $dj->user_id
                                        === (int) auth()->id()
                                )
                                    <div class="admin-actions">
                                        <a
                                            class="admin-button admin-button-secondary"
                                            href="{{ route(
                                                'admin.djs.edit',
                                                $dj->id
                                            ) }}"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.djs.destroy',
                                                $dj->id
                                            ) }}"
                                            onsubmit="return confirm('Delete this DJ?')"
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
                                No DJs found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="ts-list-pagination">
            {{ $djs->links('vendor.pagination.trendysongz') }}
        </div>
    </div>
@endsection