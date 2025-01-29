@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit User</div>

                <div class="card-body">
                    <form method="POST" 
                          action="{{ route('users.update', $user) }}" 
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}" 
                                   required 
                                   pattern="[A-Za-z\s]+"
                                   title="Only alphabets are allowed">
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}" 
                                   required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Mobile Number</label>
                            <input type="text" 
                                   class="form-control @error('mobile_no') is-invalid @enderror" 
                                   name="mobile_no" 
                                   value="{{ old('mobile_no', $user->mobile_no) }}" 
                                   required 
                                   pattern="[0-9]{10}"
                                   title="Please enter exactly 10 digits">
                            @error('mobile_no')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Profile Picture</label>
                            @if ($user->profile_pic)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($user->profile_pic) }}" 
                                         alt="Current Profile Picture" 
                                         style="max-width: 100px;">
                                </div>
                            @endif
                            <input type="file" 
                                   class="form-control @error('profile_pic') is-invalid @enderror" 
                                   name="profile_pic" 
                                   accept="image/*">
                            @error('profile_pic')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Password (leave blank to keep current)</label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   name="password" 
                                   minlength="8">
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Update User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection