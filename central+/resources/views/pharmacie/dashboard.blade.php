@extends('layouts.app')

@section('title', 'Dashboard - Pharmacie')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard Pharmacie</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Bienvenue dans votre espace pharmacie</h5>
                    <p class="card-text">Gérez vos stocks et vos commandes depuis ce dashboard.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
