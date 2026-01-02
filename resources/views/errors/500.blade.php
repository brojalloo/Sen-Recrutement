@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
            <h1 class="display-1 text-danger">500</h1>
            <h2 class="mb-4">Erreur serveur</h2>
            <p class="lead">Une erreur est survenue. Veuillez réessayer plus tard.</p>
            <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                <i class="fas fa-home"></i> Retour à l'accueil
            </a>
        </div>
    </div>
</div>
@endsection
