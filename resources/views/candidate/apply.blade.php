@extends('layouts.app')

@section('content')
<div class="container">
  <h1>Postuler: {{ $job->title }}</h1>
  <p class="text-muted">{{ $job->company }} — {{ $job->location }}</p>

  <form method="POST" action="{{ route('applications.apply.store',$job->id) }}" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
      <label class="form-label">Message</label>
      <textarea name="message" class="form-control" rows="4">{{ old('message') }}</textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">Lettre de motivation</label>
      <textarea name="cover_letter" class="form-control" rows="6">{{ old('cover_letter') }}</textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">CV (PDF, DOC, DOCX)</label>
      <input type="file" name="cv" class="form-control">
    </div>

    @if($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <button type="submit" class="btn btn-primary">Envoyer la candidature</button>
  </form>
</div>
@endsection
