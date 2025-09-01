@extends('layouts.app')

@section('title', 'Dashboard - Hôpital')

@section('content')
                @include('layouts.partials.welcome') 
                @include('layouts.partials.stats') 
                @include('layouts.partials.activities')
                @include('layouts.partials.modal')
@endsection