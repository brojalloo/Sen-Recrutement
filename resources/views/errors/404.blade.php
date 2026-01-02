@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
            <h1 class="display-1 text-primary">404</h1>
            <h2 class="mb-4">Page non trouvée</h2>
            <p class="lead">La page que vous recherchez n'existe pas ou a été déplacée.</p>
            <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                <i class="fas fa-home"></i> Retour à l'accueil
            </a>
        </div>
    </div>
</div>
@endsection
