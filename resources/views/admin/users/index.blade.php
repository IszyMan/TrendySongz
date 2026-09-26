@extends('admin.layout')
@section('title', 'Users')
@section('content')
    <h1>Users</h1>
    <div class="admin-panel">
        <p><a class="admin-button" href="{{ route('admin.users.create') }}">Add user</a></p>
        <div class="admin-table-wrap"><table>
            <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Actions</th></tr></thead>
            <tbody>
            @foreach ($users as $user)
                <tr><td>{{ $user->id }}</td><td>{{ $user->name }}</td><td>{{ $user->email }}</td><td>{{ $user->role_name }}</td><td>
                    @if ((int) $user->id !== (int) auth()->id() && (int) $user->roleid !== 1)
                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Delete this user account?')">
                            @csrf @method('DELETE')
                            <button class="admin-danger" type="submit">Delete</button>
                        </form>
                    @endif
                </td></tr>
            @endforeach
            </tbody>
        </table></div>
        <div class="admin-pagination">{{ $users->links() }}</div>
    </div>
@endsection
