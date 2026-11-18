@extends('admin.layout')

@section('title', 'Edit listing')

@section('content')
   <h1>
        Edit {{ $row->ArtistsName ?: ($row->Stage_Name ?: 'Unknown Artist') }}
        - {{ $row->TrackTitle }}
    </h1>

    <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 20px;">
        <a class="admin-button" href="{{ route('admin.listings.create') }}">
            Add listing
        </a>

        <a class="admin-button" href="{{ route('admin.listings.index') }}">
            All listings
        </a>
    </div>

    <p>Leave the image or media field empty to keep its current file.</p>

    @include('admin.listings.form')
@endsection