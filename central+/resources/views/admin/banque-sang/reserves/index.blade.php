@extends('layouts.admin')

@section('title', 'Réserves de Sang')
@section('page-title', 'Réserves de Sang')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4"><i class="fas fa-tint me-2"></i>Réserves de Sang par Groupe</h2>

    <div class="row">
        @foreach($reserves as $reserve)
        <div class="col-md-3 mb-4">
            <div class="card text-center {{ $reserve->isCritique() ? 'border-danger' : ($reserve->isFaible() ? 'border-warning' : '') }}" style="border-width: 3px;">
                <div class="card-body">
                    <i class="fas fa-tint fa-3x text-danger mb-3"></i>
                    <h2 class="mb-3">{{ $reserve->groupe_sanguin }}</h2>
                    <h3 class="text-primary mb-2">{{ $reserve->quantite_disponible }}L</h3>
                    <p class="mb-2">
                        <small class="text-muted">{{ $reserve->nombre_poches }} poche(s)</small>
                    </p>
                    @if($reserve->isCritique())
                        <span class="badge bg-danger">CRITIQUE</span>
                    @elseif($reserve->isFaible())
                        <span class="badge bg-warning">FAIBLE</span>
                    @else
                        <span class="badge bg-success">DISPONIBLE</span>
                    @endif
                    <hr>
                    <small class="text-muted">
                        Min: {{ $reserve->quantite_minimum }}L<br>
                        Critique: {{ $reserve->quantite_critique }}L
                    </small>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
