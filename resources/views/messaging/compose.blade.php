@extends('layouts.app')

@section('content')
<div class="container">
  <h1>Nouveau message</h1>
  <form method="POST" action="{{ route('messages.send') }}">
    @csrf
    <div class="mb-3">
      <label class="form-label">Destinataire</label>
      <select name="recipient_id" class="form-select" required>
        @foreach($users as $u)
          <option value="{{ $u->id }}">{{ $u->email }}</option>
        @endforeach
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Sujet</label>
      <input type="text" name="subject" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Message</label>
      <textarea name="message" class="form-control" rows="6" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Envoyer</button>
  </form>
</div>
@endsection
