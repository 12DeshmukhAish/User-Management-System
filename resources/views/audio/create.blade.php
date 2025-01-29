@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Upload Audio File</div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('audio.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="audio_file">Select Audio File</label>
                            <input type="file" class="form-control-file" id="audio_file" name="audio_file" accept="audio/*" required>
                            <small class="form-text text-muted">Supported formats: MP3, WAV, OGG (Max size: 10MB)</small>
                        </div>

                        <button type="submit" class="btn btn-primary">Upload</button>
                        <a href="{{ route('audio.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection