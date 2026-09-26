@extends('admin.layout')
@section('title', 'Dashboard')
@section('content')
    <h1>Dashboard</h1>
    <div class="admin-grid">
        <div class="admin-panel"><strong>{{ $artistsCount }}</strong><p>Artistes</p></div>
        <div class="admin-panel"><strong>{{ $audioCount }}</strong><p>Audio listings</p></div>
        <div class="admin-panel"><strong>{{ $videoCount }}</strong><p>Video listings</p></div>
        <div class="admin-panel"><strong>{{ $draftCount }}</strong><p>Unpublished listings</p></div>
    </div>
@endsection
