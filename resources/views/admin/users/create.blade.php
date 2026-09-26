@extends('admin.layout')
@section('title', 'Add user')
@section('content')
    <h1>Add user</h1>
    <form class="admin-panel admin-grid" method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <label>Name <input name="name" maxlength="50" value="{{ old('name') }}" required></label>
        <label>Email <input type="email" name="email" value="{{ old('email') }}" required></label>
        <label>Role <select name="roleid" required>
            <option value="3" @selected(old('roleid') === '3')>Standard User — uploads</option>
            <option value="2" @selected(old('roleid') === '2')>Editor — blogs</option>
        </select></label>
        <label>Password <input type="password" name="password" minlength="12" autocomplete="new-password" required></label>
        <label>Confirm password <input type="password" name="password_confirmation" minlength="12" autocomplete="new-password" required></label>
        <div class="admin-wide"><button class="admin-button" type="submit">Create user</button></div>
    </form>
@endsection
