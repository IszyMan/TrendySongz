@extends('admin.layout')

@section('title', 'Edit DJ mix')

@section('content')
    <h1>Edit {{ $mix->mix_title }}</h1>

    <p>
        Leave file fields empty to keep the current files.
    </p>

    @include('admin.dj-mixes.form')
@endsection