@extends('admin.layout')

@section('title', 'Edit DJ mix')

@section('content')
    <h1>Edit {{ $mix->mix_title }}</h1>

    <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px;">
        <a class="admin-button" href="{{ route('admin.dj-mixes.create') }}">
            Add mix
        </a>

        <a class="admin-button" href="{{ route('admin.dj-mixes.index') }}">
            All mixes
        </a>
    </div>

    <p>
        Leave file fields empty to keep the current files.
    </p>

    @include('admin.dj-mixes.form')
@endsection