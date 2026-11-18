@extends('admin.layout')

@section('title', 'Add album')

@section('content')
    <h1>Add album</h1>

    @include('admin.albums.form')
@endsection