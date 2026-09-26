@extends('admin.layout')

@section('title', 'Edit listing')

@section('content')
    <h1>Edit {{ $row->TrackTitle }}</h1>

    <p>Leave the image or media field empty to keep its current file.</p>

    @include('admin.listings.form')
@endsection