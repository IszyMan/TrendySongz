@extends('admin.layout')

@section('title', 'Edit album')

@section('content')
    <h1>Edit {{ $album->title }}</h1>
    
    
    <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px;">
        <a class="admin-button" href="{{ route('admin.albums.create') }}">
            Add album
        </a>
    
        <a class="admin-button" href="{{ route('admin.albums.index') }}">
            All albums
        </a>
    </div>

    <p>
        Leave the cover image field empty to keep the current image.
    </p>

    @include('admin.albums.form')
@endsection