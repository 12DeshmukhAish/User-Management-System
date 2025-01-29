@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Audio Files Management</h2>
                    <a href="{{ route('audio.create') }}" class="btn btn-primary float-right">Upload New Audio</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <table class="table">
                        <thead>
                            <tr>
                                <th>File Name</th>
                                <th>Duration</th>
                                <th>File Type</th>
                                <th>Uploaded At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($audioFiles as $audio)
                                <tr>
                                    <td>{{ $audio->original_name }}</td>
                                    <td>{{ $audio->duration ? gmdate("H:i:s", $audio->duration) : 'N/A' }}</td>
                                    <td>{{ $audio->file_type }}</td>
                                    <td>{{ $audio->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td>
                                        <form action="{{ route('audio.destroy', $audio) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this file?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection