@extends('layouts.app')

@section('title', 'Dashboard - Patient')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard Patient</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Bienvenue dans votre espace patient</h5>
                    <p class="card-text">Consultez vos rendez-vous et votre dossier médical depuis ce dashboard.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
