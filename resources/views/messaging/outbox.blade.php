@extends('layouts.app')

@section('content')
<div class="container">
  <h1>Messages envoyés</h1>
  <a class="btn btn-primary mb-3" href="{{ route('messages.compose') }}">Nouveau message</a>
  <table class="table table-striped">
    <thead><tr><th>À</th><th>Sujet</th><th>Date</th></tr></thead>
    <tbody>
      @foreach($messages as $m)
        <tr>
          <td>{{ optional(\App\Models\User::find($m->recipient_id))->email }}</td>
          <td>{{ $m->subject }}</td>
          <td>{{ $m->created_at }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
  {{ $messages->links() }}
</div>
@endsection
