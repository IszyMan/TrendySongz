@extends('admin.layout')

@section('title', 'Add listing')

@section('content')
    <h1>Add audio or video</h1>

    <p>
        Add and publish the artist first. Albums are optional and must belong
        to the selected artist.
    </p>

    @include('admin.listings.form')
@endsection