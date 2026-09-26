@extends('admin.layout')

@section('title', 'Edit artiste')

@section('content')
    <h1>Edit {{ $artist->ArtistsName ?: $artist->Stage_Name }}</h1>

    <p>Leave the image field empty to keep the current image.</p>

    @include('admin.artists.form')
@endsection