@extends('admin.layout')

@section('title', 'Edit album')

@section('content')
    <h1>Edit {{ $album->title }}</h1>

    <p>
        Leave the cover image field empty to keep the current image.
    </p>

    @include('admin.albums.form')
@endsection