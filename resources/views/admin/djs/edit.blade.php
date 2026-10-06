@extends('admin.layout')

@section('title', 'Edit DJ')

@section('content')
    <h1>Edit {{ $dj->dj_name }}</h1>

    <p>Leave the image field empty to keep the current image.</p>

    @include('admin.djs.form')
@endsection