@extends('layouts.app')

@section('title', 'Dashboard - Hôpital')

@section('content')
    @include('dashboard.partials.welcome')
    @include('dashboard.partials.stats')
    @include('dashboard.partials.activities')
    @include('dashboard.partials.modals')
@endsection
