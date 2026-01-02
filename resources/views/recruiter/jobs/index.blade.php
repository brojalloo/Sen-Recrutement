@extends('layouts.app')

@section('content')
<div class="container">
  <h1>Mes offres</h1>
  <a class="btn btn-primary mb-3" href="{{ route('recruiter.jobs.create') }}">Créer une offre</a>
  <table class="table table-striped">
    <thead><tr><th>Titre</th><th>Statut</th><th>Actions</th></tr></thead>
    <tbody>
      @foreach($jobs as $j)
        <tr>
          <td>{{ $j->title }}</td>
          <td>{{ $j->status }}</td>
          <td>
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('recruiter.jobs.edit',$j->id) }}">Modifier</a>
            <form method="POST" action="{{ route('recruiter.jobs.destroy',$j->id) }}" style="display:inline">@csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger" type="submit">Supprimer</button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
  {{ $jobs->links() }}
</div>
@endsection
